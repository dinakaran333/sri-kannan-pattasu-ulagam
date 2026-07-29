<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    set_flash('danger', 'Category not found.');
    header('Location: ' . BASE_URL . 'admin/categories.php');
    exit;
}

$err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = trim($_POST['status'] ?? 'active');

    if (empty($name)) {
        $err = "Category name is required.";
    } else {
        $slug = slugify($name);
        $image = $category['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = upload_image($_FILES['image']);
            if ($uploaded) {
                $image = $uploaded;
            }
        }

        $stmt_up = $db->prepare("UPDATE categories SET name = ?, slug = ?, description = ?, image = ?, status = ? WHERE id = ?");
        if ($stmt_up->execute([$name, $slug, $description, $image, $status, $id])) {
            set_flash('success', "Category '{$name}' updated.");
            header('Location: ' . BASE_URL . 'admin/categories.php');
            exit;
        } else {
            $err = "Failed to update category.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category | Admin Panel</title>
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
                <h3 class="fw-bold text-dark mb-0">Edit Category</h3>
                <small class="text-muted">Modify <?= e($category['name']); ?></small>
            </div>
            <a href="<?= BASE_URL; ?>admin/categories.php" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Back To List
            </a>
        </div>

        <?php if ($err): ?>
            <div class="alert alert-danger"><?= e($err); ?></div>
        <?php endif; ?>

        <div class="card glass-card border-0 shadow-sm p-4 max-w-600">
            <form action="<?= BASE_URL; ?>admin/edit-category.php?id=<?= $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= e($category['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" rows="3" class="form-control"><?= e($category['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= ($category['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?= ($category['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Replace Thumbnail Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-warning rounded-pill fw-bold text-dark px-4">
                    <i class="bi bi-save-fill me-1"></i> Update Category
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
</body>
</html>
