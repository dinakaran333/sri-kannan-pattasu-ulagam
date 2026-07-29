<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

// Handle Order Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $order_id = (int)$_POST['order_id'];
    $new_status = trim($_POST['order_status']);
    $payment_status = trim($_POST['payment_status']);

    $stmt_up = $db->prepare("UPDATE orders SET order_status = ?, payment_status = ? WHERE id = ?");
    if ($stmt_up->execute([$new_status, $payment_status, $order_id])) {
        set_flash('success', "Order #{$order_id} status updated to {$new_status}.");
    } else {
        set_flash('danger', "Failed to update order status.");
    }
    header('Location: ' . BASE_URL . 'admin/orders.php');
    exit;
}

// Fetch Orders
$stmt = $db->query("SELECT o.*, u.full_name as customer_name, u.email as customer_email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management | Admin Panel</title>
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
                <h3 class="fw-bold text-dark mb-0">Customer Orders Workflow</h3>
                <small class="text-muted">Review, process, ship, or cancel customer fireworks orders</small>
            </div>
        </div>

        <?php display_flash(); ?>

        <div class="card glass-card border-0 shadow-sm p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order No</th>
                            <th>Customer Info</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?= e($o['order_number']); ?></td>
                                <td>
                                    <strong><?= e($o['shipping_name']); ?></strong><br>
                                    <small class="text-muted"><?= e($o['shipping_phone']); ?> | <?= e($o['city']); ?></small>
                                </td>
                                <td class="fw-bold text-danger"><?= format_price($o['total_amount']); ?></td>
                                <td>
                                    <span class="badge bg-secondary mb-1"><?= e($o['payment_method']); ?></span><br>
                                    <small class="text-muted"><?= e($o['payment_status']); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark px-3 py-2"><?= e($o['order_status']); ?></span>
                                </td>
                                <td class="fs-7 text-muted"><?= date('d M Y, h:i A', strtotime($o['created_at'])); ?></td>
                                <td>
                                    <form action="<?= BASE_URL; ?>admin/orders.php" method="POST" class="d-flex gap-1 align-items-center">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="order_id" value="<?= $o['id']; ?>">
                                        <select name="order_status" class="form-select form-select-sm">
                                            <option value="Pending" <?= ($o['order_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Processing" <?= ($o['order_status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                            <option value="Shipped" <?= ($o['order_status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="Delivered" <?= ($o['order_status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="Cancelled" <?= ($o['order_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                        <select name="payment_status" class="form-select form-select-sm" style="display:none;">
                                            <option value="Pending" <?= ($o['payment_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Paid" <?= ($o['payment_status'] == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
</body>
</html>
