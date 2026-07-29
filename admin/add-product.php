<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

// Fetch categories for dropdown
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

    if ($category_id <= 0 || empty($name) || $mrp <= 0 || $offer_price <= 0) {
        $err = "Please fill in all mandatory fields correctly.";
    } else {
        $slug = slugify($name);
        
        // Handle image upload
        $image = 'default-product.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = upload_image($_FILES['image']);
            if ($uploaded) {
                $image = $uploaded;
            }
        }

        $stmt = $db->prepare("INSERT INTO products (category_id, name, slug, description, image, mrp, offer_price, stock_quantity, unit, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$category_id, $name, $slug, $description, $image, $mrp, $offer_price, $stock_quantity, $unit, $is_featured])) {
            set_flash('success', "Cracker product '{$name}' added to inventory!");
            header('Location: ' . BASE_URL . 'admin/products.php');
            exit;
        } else {
            $err = "Failed to insert product record.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Admin Panel</title>
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
                <h3 class="fw-bold text-dark mb-0">Add New Fireworks Item</h3>
                <small class="text-muted">Fill product details to make it available in customer catalog</small>
            </div>
            <a href="<?= BASE_URL; ?>admin/products.php" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Back To List
            </a>
        </div>

        <?php if ($err): ?>
            <div class="alert alert-danger"><?= e($err); ?></div>
        <?php endif; ?>

        <div class="card glass-card border-0 shadow-sm p-4">
            <form action="<?= BASE_URL; ?>admin/add-product.php" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. 10cm Electric Sparklers" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id']; ?>"><?= e($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Original MRP (₹) *</label>
                        <input type="number" step="0.01" name="mrp" class="form-control" placeholder="150.00" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Offer Discount Price (₹) *</label>
                        <input type="number" step="0.01" name="offer_price" class="form-control" placeholder="75.00" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" class="form-control" value="100" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Unit Packing Type</label>
                        <input type="text" name="unit" class="form-control" placeholder="e.g. Box of 10 Pcs">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Main Image</label>
                        <input type="file" name="image" class="form-control image-input-preview" data-target="#imgPreview" accept="image/*">
                    </div>
                    <div class="col-12 text-center my-2">
                        <img id="imgPreview" src="" class="img-fluid rounded border d-none" style="max-height: 150px;">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Full Description & Safety Instructions</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Enter fireworks description..."></textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="chkFeat">
                            <label class="form-check-label fw-semibold" for="chkFeat">
                                Display in Featured / Top Sellers Carousel
                            </label>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-danger btn-lg rounded-pill fw-bold px-5">
                            <i class="bi bi-plus-circle-fill me-1"></i> Save Fireworks Item
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
