<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

// Metrics calculations
$total_products = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_categories = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_customers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_orders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue = $db->query("SELECT SUM(total_amount) FROM orders WHERE order_status != 'Cancelled'")->fetchColumn() ?: 0.00;
$today_orders = $db->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();

// Recent 5 Orders
$recent_orders = $db->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SparkleFest Crackers</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/admin.css">
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="admin-content flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-0">Executive Dashboard</h3>
                <small class="text-muted">Overview of store sales, product inventory, and customer activities</small>
            </div>
            <span class="badge bg-warning text-dark px-3 py-2 fs-7 fw-semibold"><i class="bi bi-clock me-1"></i> <?= date('d M Y'); ?></span>
        </div>

        <?php display_flash(); ?>

        <!-- Stat Cards Grid -->
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-white p-3 border-start border-danger border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-semibold">PRODUCTS</small>
                            <h3 class="fw-bold mb-0 text-dark"><?= $total_products; ?></h3>
                        </div>
                        <div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-box-seam"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-white p-3 border-start border-warning border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-semibold">CATEGORIES</small>
                            <h3 class="fw-bold mb-0 text-dark"><?= $total_categories; ?></h3>
                        </div>
                        <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-grid"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-white p-3 border-start border-primary border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-semibold">CUSTOMERS</small>
                            <h3 class="fw-bold mb-0 text-dark"><?= $total_customers; ?></h3>
                        </div>
                        <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-people"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-white p-3 border-start border-info border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-semibold">TOTAL ORDERS</small>
                            <h3 class="fw-bold mb-0 text-dark"><?= $total_orders; ?></h3>
                        </div>
                        <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-cart-check"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-white p-3 border-start border-dark border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-semibold">TODAY ORDERS</small>
                            <h3 class="fw-bold mb-0 text-dark"><?= $today_orders; ?></h3>
                        </div>
                        <div class="stat-icon bg-secondary-subtle text-dark"><i class="bi bi-bag-plus"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-white p-3 border-start border-success border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-semibold">TOTAL REVENUE</small>
                            <h4 class="fw-bold mb-0 text-success"><?= format_price($total_revenue); ?></h4>
                        </div>
                        <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-cash-stack"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="card glass-card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-receipt me-2 text-danger"></i> Recent Customer Orders</h5>
                <a href="<?= BASE_URL; ?>admin/orders.php" class="btn btn-sm btn-outline-danger rounded-pill">View All Orders</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?= e($order['order_number']); ?></td>
                                <td><?= e($order['full_name']); ?></td>
                                <td class="fw-bold text-danger"><?= format_price($order['total_amount']); ?></td>
                                <td><span class="badge bg-secondary"><?= e($order['payment_method']); ?></span></td>
                                <td>
                                    <span class="badge bg-warning text-dark"><?= e($order['order_status']); ?></span>
                                </td>
                                <td class="fs-7 text-muted"><?= date('d M Y', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <a href="<?= BASE_URL; ?>admin/orders.php" class="btn btn-sm btn-outline-dark rounded-circle"><i class="bi bi-eye"></i></a>
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
<script src="<?= BASE_URL; ?>assets/js/admin.js"></script>
</body>
</html>
