<?php
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function getCartPayload($conn) {
    $items = [];
    $total = 0;
    $count = 0;

    if (!empty($_SESSION['cart'])) {
        $ids = array_map('intval', array_keys($_SESSION['cart']));
        $idList = implode(',', $ids);
        $res = $conn->query("SELECT * FROM products WHERE id IN ($idList)");
        while ($row = $res->fetch_assoc()) {
            $pid = (int)$row['id'];
            $qty = (int)$_SESSION['cart'][$pid];
            $subtotal = $row['price'] * $qty;
            $total += $subtotal;
            $count += $qty;
            $items[] = [
                'id' => $pid,
                'name' => $row['name'],
                'price' => (float)$row['price'],
                'image_url' => $row['image_url'],
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
        }
    }

    return [
        'status' => 'ok',
        'items' => $items,
        'total' => $total,
        'count' => $count
    ];
}

if ($action === 'get_cart') {
    echo json_encode(getCartPayload($conn));
    exit();
}

if ($action === 'add') {
    $pid = (int)($_POST['product_id'] ?? 0);
    if ($pid > 0) {
        $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
    }
    echo json_encode(getCartPayload($conn));
    exit();
}

if ($action === 'update') {
    $pid = (int)($_POST['product_id'] ?? 0);
    $delta = (int)($_POST['delta'] ?? 0);
    if ($pid > 0 && isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] += $delta;
        if ($_SESSION['cart'][$pid] <= 0) {
            unset($_SESSION['cart'][$pid]);
        }
    }
    echo json_encode(getCartPayload($conn));
    exit();
}

if ($action === 'order') {
    $address = trim($_POST['address'] ?? '');
    $cartData = getCartPayload($conn);

    if (empty($cartData['items'])) {
        echo json_encode(['status' => 'error', 'message' => 'Your cart is empty.']);
        exit();
    }
    if ($address === '') {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a shipping address.']);
        exit();
    }

    $userId = (int)$_SESSION['user_id'];
    $total = $cartData['total'];

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $userId, $total, $address);

    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;
        $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

        foreach ($cartData['items'] as $item) {
            $itemStmt->bind_param("iiid", $orderId, $item['id'], $item['qty'], $item['price']);
            $itemStmt->execute();
        }

        $_SESSION['cart'] = [];
        echo json_encode([
            'status' => 'ok',
            'order_id' => $orderId,
            'message' => "Order #$orderId confirmed!"
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create order.']);
    }
    exit();
}

if ($action === 'get_orders') {
    $userId = (int)$_SESSION['user_id'];
    $orders = [];
    $res = $conn->query("SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC");

    while ($order = $res->fetch_assoc()) {
        $oid = (int)$order['id'];
        $itemsRes = $conn->query("
            SELECT oi.quantity, oi.price, p.name 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = $oid
        ");
        $orderItems = [];
        while ($ir = $itemsRes->fetch_assoc()) {
            $orderItems[] = $ir;
        }
        $order['items'] = $orderItems;
        $orders[] = $order;
    }

    echo json_encode(['status' => 'ok', 'orders' => $orders]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>