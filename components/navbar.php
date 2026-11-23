<?php
$userName = $_SESSION['user_name'] ?? 'Guest';
$cartCount = $cartCount ?? 0;
?>
<header class="glass-nav">
    <div class="nav-inner">
        <a href="home.php" class="brand-logo">◈ AETHER</a>
        <div class="nav-actions">
            <span class="user-pill">Hi, <strong><?= htmlspecialchars($userName) ?></strong></span>
            <button type="button" class="btn-glass" id="openOrdersBtn">My Orders</button>
            <button type="button" class="btn-glass cart-trigger" id="openCartBtn">
                Cart <span class="cart-badge" id="cartBadge"><?= (int)$cartCount ?></span>
            </button>
            <a href="logout.php" class="btn-ghost">Logout</a>
        </div>
    </div>
</header>