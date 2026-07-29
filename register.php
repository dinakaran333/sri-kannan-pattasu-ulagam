<?php
$page_title = "Customer Registration | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

if (is_user_logged_in()) {
    header('Location: ' . BASE_URL . 'profile.php');
    exit;
}

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');

    if (empty($full_name) || empty($email) || empty($password) || empty($phone)) {
        $error_msg = "Please fill in all required fields marked with *.";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error_msg = "Password must be at least 6 characters long.";
    } else {
        // Check if email already exists
        $stmt_chk = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_chk->execute([$email]);
        if ($stmt_chk->fetch()) {
            $error_msg = "An account with this email address already exists. Please log in.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt_ins = $db->prepare("INSERT INTO users (full_name, email, password, phone, address, city, state, pincode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt_ins->execute([$full_name, $email, $hashed_password, $phone, $address, $city, $state, $pincode])) {
                $new_id = $db->lastInsertId();
                $_SESSION['user_id'] = $new_id;
                $_SESSION['user_name'] = $full_name;
                $_SESSION['user_email'] = $email;

                set_flash('success', "Registration successful! Welcome to SparkleFest.");
                header('Location: ' . BASE_URL . 'index.php');
                exit;
            } else {
                $error_msg = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card glass-card p-4 border-0 shadow-lg">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus display-3 text-warning spark-icon"></i>
                    <h3 class="fw-bold text-dark mt-2">Create Customer Account</h3>
                    <p class="text-muted fs-7">Join SparkleFest to unlock festive discounts & order tracking</p>
                </div>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger fs-7 py-2"><?= e($error_msg); ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL; ?>register.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" placeholder="e.g. Priya Patel" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm Password *</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. 9876543210" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" class="form-control" placeholder="e.g. 400001">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Delivery Address</label>
                            <textarea name="address" rows="2" class="form-control" placeholder="Street name, landmark"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. Mumbai">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control" placeholder="e.g. Maharashtra">
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold text-dark shadow">
                                Create Account <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="text-center border-top pt-3 mt-3 fs-7">
                    <span class="text-muted">Already registered?</span>
                    <a href="<?= BASE_URL; ?>login.php" class="text-danger fw-bold text-decoration-none ms-1">Log In Here</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
