<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    set_flash('danger', 'Product not found.');
    header('Location: ' . BASE_URL . 'admin/products.php');
    exit;
}

$categories = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();
$err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int)($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $mrp = (float)($_POST['mrp'] ?? 0);
    $offer_price = (float)($_POST['offer_price'] ?? 0);
    $stock_quantity = (int)($_POST['stock_quantity'] ?? 0);
    $unit = trim($_POST['unit'] ?? 'Box');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $status = trim($_POST['status'] ?? 'active');

    if ($category_id <= 0 || empty($name) || $mrp <= 0 || $offer_price <= 0) {
        $err = "Please fill in all required fields.";
    } else {
        $slug = slugify($name);
        $image = $product['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = upload_image($_FILES['image']);
            if ($uploaded) {
                $image = $uploaded;
            }
        }

        $stmt_up = $db->prepare("UPDATE products SET category_id = ?, name = ?, slug = ?, description = ?, image = ?, mrp = ?, offer_price = ?, stock_quantity = ?, unit = ?, is_featured = ?, status = ? WHERE id = ?");
        if ($stmt_up->execute([$category_id, $name, $slug, $description, $image, $mrp, $offer_price, $stock_quantity, $unit, $is_featured, $status, $id])) {
            set_flash('success', "Product '{$name}' updated successfully.");
            header('Location: ' . BASE_URL . 'admin/products.php');
            exit;
        } else {
            $err = "Failed to update product details.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Admin Panel</title>
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
                <h3 class="fw-bold text-dark mb-0">Edit Product</h3>
                <small class="text-muted">Modify details for <?= e($product['name']); ?></small>
            </div>
            <a href="<?= BASE_URL; ?>admin/products.php" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Back To List
            </a>
        </div>

        <?php if ($err): ?>
            <div class="alert alert-danger"><?= e($err); ?></div>
        <?php endif; ?>

        <div class="card glass-card border-0 shadow-sm p-4">
            <form action="<?= BASE_URL; ?>admin/edit-product.php?id=<?= $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Name *</label>
                        <input type="text" name="name" class="form-control" value="<?= e($product['name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id']; ?>" <?= ($product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?= e($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">MRP (₹) *</label>
                        <input type="number" step="0.01" name="mrp" class="form-control" value="<?= $product['mrp']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Offer Price (₹) *</label>
                        <input type="number" step="0.01" name="offer_price" class="form-control" value="<?= $product['offer_price']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" class="form-control" value="<?= $product['stock_quantity']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Unit Type</label>
                        <input type="text" name="unit" class="form-control" value="<?= e($product['unit']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Replace Product Image</label>
                        <input type="file" name="image" class="form-control image-input-preview" data-target="#imgPreview" accept="image/*">
                    </div>
                    <div class="col-12 text-center my-2">
                        <img id="imgPreview" src="<?= BASE_URL; ?>assets/images/uploads/<?= e($product['image']); ?>" class="img-fluid rounded border" style="max-height: 150px;">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="4" class="form-control"><?= e($product['description']); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="chkFeat" <?= ($product['is_featured']) ? 'checked' : ''; ?>>
                            <label class="form-check-label fw-semibold" for="chkFeat">
                                Featured Product
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= ($product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?= ($product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-warning btn-lg rounded-pill fw-bold text-dark px-5">
                            <i class="bi bi-save-fill me-1"></i> Update Fireworks Item
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL; ?>assets/js/admin.js"></script>
</body>
</html>
