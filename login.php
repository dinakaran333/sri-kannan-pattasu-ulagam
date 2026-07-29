<?php
$page_title = "Customer Login | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

if (is_user_logged_in()) {
    header('Location: ' . BASE_URL . 'profile.php');
    exit;
}

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error_msg = "Please fill in both email and password.";
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];

            set_flash('success', "Welcome back, " . $user['full_name'] . "!");

            $redirect = $_SESSION['redirect_url'] ?? BASE_URL . 'index.php';
            unset($_SESSION['redirect_url']);
            header('Location: ' . $redirect);
            exit;
        } else {
            $error_msg = "Invalid email address or password.";
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card glass-card p-4 border-0 shadow-lg">
                <div class="text-center mb-4">
                    <i class="bi bi-person-circle display-3 text-danger spark-icon"></i>
                    <h3 class="fw-bold text-dark mt-2">Welcome Back</h3>
                    <p class="text-muted fs-7">Log in to track your fireworks orders & checkout faster</p>
                </div>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger fs-7 py-2"><?= e($error_msg); ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL; ?>login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold mb-3 shadow">
                        Log In Now <i class="bi bi-box-arrow-in-right ms-1"></i>
                    </button>
                </form>

                <div class="text-center border-top pt-3 fs-7">
                    <span class="text-muted">Don't have an account yet?</span>
                    <a href="<?= BASE_URL; ?>register.php" class="text-danger fw-bold text-decoration-none ms-1">Register Here</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
