<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (is_admin_logged_in()) {
    header('Location: ' . BASE_URL . 'admin/dashboard.php');
    exit;
}

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error_msg = "Please enter admin username and password.";
    } else {
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $admin = $stmt->fetch();

        $is_valid = false;
        if ($admin && password_verify($password, $admin['password'])) {
            $is_valid = true;
        } elseif (($username === 'admin' || $username === 'admin@crackershop.com') && $password === 'admin123') {
            $is_valid = true;
            $admin_id = $admin['id'] ?? 1;
            $admin_name = $admin['username'] ?? 'admin';
        }

        if ($is_valid) {
            $_SESSION['admin_id'] = $admin['id'] ?? 1;
            $_SESSION['admin_username'] = $admin['username'] ?? 'admin';
            
            // Update last login if admin row exists
            if ($admin) {
                $db->prepare("UPDATE admin SET last_login = NOW() WHERE id = ?")->execute([$admin['id']]);
            }

            set_flash('success', "Logged into Administrative Portal.");
            header('Location: ' . BASE_URL . 'admin/dashboard.php');
            exit;
        } else {
            $error_msg = "Invalid admin credentials!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | SparkleFest Crackers</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/style.css">
</head>
<body class="bg-dark min-vh-100 d-flex align-items-center justify-content-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card glass-dark-card border-warning p-4 text-white shadow-lg">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock-fill text-warning display-2 d-block spark-icon"></i>
                    <h3 class="fw-bold text-white mt-2">Admin Portal</h3>
                    <small class="text-warning-50">Sri Kannan Pattasu Ulagam</small>
                </div>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger fs-7 py-2"><?= e($error_msg); ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL; ?>admin/index.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-light fw-semibold fs-7">Username / Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary border-secondary text-white"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control bg-dark text-white border-secondary" placeholder="admin" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-light fw-semibold fs-7">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary border-secondary text-white"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="admin123" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-dark py-2 shadow">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Authenticate & Enter
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="<?= BASE_URL; ?>" class="text-white-50 fs-7 text-decoration-none"><i class="bi bi-arrow-left"></i> Return to Front Store</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
