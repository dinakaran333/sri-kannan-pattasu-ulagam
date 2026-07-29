<?php
$page_title = "My Profile | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

require_user_login();
$user_session = get_logged_user();

$msg = $err = "";

// Fetch current user details
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_session['id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');

    if (empty($full_name) || empty($phone)) {
        $err = "Full Name and Phone Number are required.";
    } else {
        $stmt_up = $db->prepare("UPDATE users SET full_name = ?, phone = ?, address = ?, city = ?, state = ?, pincode = ? WHERE id = ?");
        if ($stmt_up->execute([$full_name, $phone, $address, $city, $state, $pincode, $user_session['id']])) {
            $_SESSION['user_name'] = $full_name;
            $msg = "Profile details updated successfully!";
            // Refresh local variable
            $user['full_name'] = $full_name;
            $user['phone'] = $phone;
            $user['address'] = $address;
            $user['city'] = $city;
            $user['state'] = $state;
            $user['pincode'] = $pincode;
        } else {
            $err = "Failed to update profile.";
        }
    }
}
?>

<div class="py-4 bg-dark text-white text-center mb-4">
    <div class="container">
        <h2 class="fw-bold text-warning mb-0"><i class="bi bi-person-lines-fill me-2"></i> Account Profile</h2>
        <small class="text-light">Manage your default shipping address and contact details</small>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card glass-card p-4 border-0 shadow-sm">
                <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">Edit Customer Information</h5>

                <?php if ($msg): ?>
                    <div class="alert alert-success"><?= e($msg); ?></div>
                <?php endif; ?>
                <?php if ($err): ?>
                    <div class="alert alert-danger"><?= e($err); ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL; ?>profile.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" value="<?= e($user['full_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address (Read Only)</label>
                            <input type="email" class="form-control bg-light" value="<?= e($user['email']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" value="<?= e($user['phone']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pincode</label>
                            <input type="text" name="pincode" class="form-control" value="<?= e($user['pincode']); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Default Street Address</label>
                            <textarea name="address" rows="3" class="form-control"><?= e($user['address']); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" value="<?= e($user['city']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control" value="<?= e($user['state']); ?>">
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg rounded-pill fw-bold text-dark px-5">
                                <i class="bi bi-save-fill me-1"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
