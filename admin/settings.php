<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = "Store settings updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Settings | Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/admin.css">
</head>
<body>

<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="admin-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-0">Website Configuration</h3>
                <small class="text-muted">Manage general store details, currency & shipping thresholds</small>
            </div>
        </div>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?= e($msg); ?></div>
        <?php endif; ?>

        <div class="card glass-card border-0 shadow-sm p-4 max-w-600">
            <form action="<?= BASE_URL; ?>admin/settings.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Store Brand Name</label>
                    <input type="text" class="form-control" value="<?= SITE_NAME; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Support Contact Phone</label>
                    <input type="tel" class="form-control" value="+91 98765 43210" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Support Email Address</label>
                    <input type="email" class="form-control" value="support@crackershop.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Free Shipping Order Threshold (₹)</label>
                    <input type="number" class="form-control" value="<?= FREE_SHIPPING_THRESHOLD; ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Flat Delivery Fee (₹)</label>
                    <input type="number" class="form-control" value="<?= FLAT_SHIPPING_FEE; ?>" required>
                </div>

                <button type="submit" class="btn btn-warning rounded-pill fw-bold text-dark px-4">
                    <i class="bi bi-save-fill me-1"></i> Save Configuration
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
</body>
</html>
