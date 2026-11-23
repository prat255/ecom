<?php
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = "";
$activeTab = "login";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'signup') {
        $activeTab = "signup";
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($name && $email && $password) {
            $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "An account with this email already exists.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $password);
                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $stmt->insert_id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['cart'] = [];
                    header("Location: home.php");
                    exit();
                } else {
                    $error = "Sign-up failed. Please try again.";
                }
            }
        } else {
            $error = "Please fill in all fields.";
        }
    } elseif ($action === 'login') {
        $activeTab = "login";
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email && $password) {
            $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ? AND password = ?");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($userRow = $result->fetch_assoc()) {
                $_SESSION['user_id'] = $userRow['id'];
                $_SESSION['user_name'] = $userRow['name'];
                $_SESSION['cart'] = $_SESSION['cart'] ?? [];
                header("Location: home.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Please enter both email and password.";
        }
    }
}

$pageTitle = 'AETHER // Sign In';
$bodyClass = 'auth-body';
require 'components/header.php';
?>

<main class="auth-wrapper">
    <div class="glass-card auth-card">
        <div class="brand-header">
            <div class="brand-logo">◈ AETHER</div>
            <p class="brand-subtitle">Curated Future-Tech & Lifestyle</p>
        </div>

        <div class="auth-tabs" data-active="<?= htmlspecialchars($activeTab) ?>">
            <div class="tab-indicator"></div>
            <button type="button" class="tab-btn <?= $activeTab === 'login' ? 'active' : '' ?>" data-target="login">Log In</button>
            <button type="button" class="tab-btn <?= $activeTab === 'signup' ? 'active' : '' ?>" data-target="signup">Sign Up</button>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="forms-container">
            <form method="POST" action="index.php" class="auth-form <?= $activeTab === 'login' ? 'active' : '' ?>" id="login-form">
                <input type="hidden" name="action" value="login">
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="you@example.com" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-primary full-width">Enter Storefront →</button>
            </form>

            <form method="POST" action="index.php" class="auth-form <?= $activeTab === 'signup' ? 'active' : '' ?>" id="signup-form">
                <input type="hidden" name="action" value="signup">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="Alex Mercer" required>
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="alex@example.com" required>
                </div>
                <div class="input-group">
                    <label>Create Password</label>
                    <input type="password" name="password" placeholder="Choose a password" required>
                </div>
                <button type="submit" class="btn-primary full-width">Create Account →</button>
            </form>
        </div>
    </div>
</main>

<?php
$showSiteFooter = false;
require 'components/footer.php';
?>