<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

$stmt = $db->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management | Admin Panel</title>
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
                <h3 class="fw-bold text-dark mb-0">Fireworks Categories</h3>
                <small class="text-muted">Manage product category classifications</small>
            </div>
            <a href="<?= BASE_URL; ?>admin/add-category.php" class="btn btn-warning rounded-pill fw-bold text-dark">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </a>
        </div>

        <?php display_flash(); ?>

        <div class="card glass-card border-0 shadow-sm p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td>
                                    <img src="<?= BASE_URL; ?>assets/images/uploads/<?= e($cat['image']); ?>" class="rounded" style="width: 45px; height: 45px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=80&auto=format&fit=crop'">
                                </td>
                                <td class="fw-bold text-dark"><?= e($cat['name']); ?></td>
                                <td><code><?= e($cat['slug']); ?></code></td>
                                <td class="text-muted fs-7"><?= e($cat['description']); ?></td>
                                <td>
                                    <span class="badge bg-<?= ($cat['status'] == 'active') ? 'success' : 'secondary'; ?>"><?= e($cat['status']); ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL; ?>admin/edit-category.php?id=<?= $cat['id']; ?>" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="<?= BASE_URL; ?>admin/delete-category.php?id=<?= $cat['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle btn-confirm-delete"><i class="bi bi-trash-fill"></i></a>
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
