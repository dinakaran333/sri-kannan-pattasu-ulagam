<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

// Fetch products with category details
$stmt = $db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Inventory | Admin Panel</title>
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
                <h3 class="fw-bold text-dark mb-0">Products Inventory</h3>
                <small class="text-muted">Manage crackers stock, prices, and catalog status</small>
            </div>
            <a href="<?= BASE_URL; ?>admin/add-product.php" class="btn btn-danger rounded-pill fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Add New Product
            </a>
        </div>

        <?php display_flash(); ?>

        <div class="card glass-card border-0 shadow-sm p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>MRP</th>
                            <th>Offer Price</th>
                            <th>Stock</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td>
                                    <img src="<?= BASE_URL; ?>assets/images/uploads/<?= e($p['image']); ?>" class="rounded" style="width: 45px; height: 45px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?q=80&w=80&auto=format&fit=crop'">
                                </td>
                                <td class="fw-bold text-dark">
                                    <?= e($p['name']); ?><br>
                                    <small class="text-muted fw-normal">Unit: <?= e($p['unit']); ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= e($p['category_name']); ?></span></td>
                                <td class="text-muted text-decoration-line-through"><?= format_price($p['mrp']); ?></td>
                                <td class="fw-bold text-danger"><?= format_price($p['offer_price']); ?></td>
                                <td>
                                    <?php if ($p['stock_quantity'] > 10): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle"><?= $p['stock_quantity']; ?> Box</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><?= $p['stock_quantity']; ?> Low Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= ($p['is_featured']) ? '<span class="badge bg-warning text-dark">YES</span>' : '<span class="badge bg-secondary">NO</span>'; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL; ?>admin/edit-product.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="<?= BASE_URL; ?>admin/delete-product.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle btn-confirm-delete"><i class="bi bi-trash-fill"></i></a>
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
