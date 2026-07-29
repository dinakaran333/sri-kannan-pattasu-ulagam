<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

$start_date = trim($_GET['start_date'] ?? date('Y-m-01'));
$end_date = trim($_GET['end_date'] ?? date('Y-m-d'));

$stmt = $db->prepare("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id WHERE DATE(o.created_at) BETWEEN ? AND ? AND o.order_status != 'Cancelled' ORDER BY o.id DESC");
$stmt->execute([$start_date, $end_date]);
$report_orders = $stmt->fetchAll();

$report_revenue = 0.00;
foreach ($report_orders as $ro) {
    $report_revenue += $ro['total_amount'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Reports | Admin Panel</title>
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
                <h3 class="fw-bold text-dark mb-0">Sales & Revenue Reports</h3>
                <small class="text-muted">Generate analytics by date ranges</small>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card glass-card border-0 shadow-sm p-3 mb-4">
            <form action="<?= BASE_URL; ?>admin/reports.php" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold fs-7">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= e($start_date); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold fs-7">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= e($end_date); ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-warning rounded-pill fw-bold text-dark w-100">
                        <i class="bi bi-filter me-1"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Report Metrics Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card bg-white p-4 border-start border-danger border-4 shadow-sm">
                    <small class="text-muted fw-bold">TOTAL COMPLETED ORDERS</small>
                    <h2 class="fw-bold mb-0 text-dark"><?= count($report_orders); ?> Orders</h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-white p-4 border-start border-success border-4 shadow-sm">
                    <small class="text-muted fw-bold">TOTAL GENERATED REVENUE</small>
                    <h2 class="fw-bold mb-0 text-success"><?= format_price($report_revenue); ?></h2>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <div class="card glass-card border-0 shadow-sm p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_orders as $ro): ?>
                            <tr>
                                <td class="fw-bold"><?= e($ro['order_number']); ?></td>
                                <td><?= date('d M Y', strtotime($ro['created_at'])); ?></td>
                                <td><?= e($ro['full_name']); ?></td>
                                <td><span class="badge bg-secondary"><?= e($ro['payment_method']); ?></span></td>
                                <td class="fw-bold text-danger"><?= format_price($ro['total_amount']); ?></td>
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
