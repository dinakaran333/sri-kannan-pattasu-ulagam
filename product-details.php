<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$slug = trim($_GET['slug'] ?? '');

if (empty($slug)) {
    header('Location: ' . BASE_URL . 'products.php');
    exit;
}

$stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ? AND p.status = 'active'");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: ' . BASE_URL . '404.php');
    exit;
}

$page_title = e($product['name']) . " | SparkleFest Crackers";
$discount = calculate_discount($product['mrp'], $product['offer_price']);

// Fetch related products
$stmt_rel = $db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.id != ? AND p.status = 'active' LIMIT 4");
$stmt_rel->execute([$product['category_id'], $product['id']]);
$related_products = $stmt_rel->fetchAll();
?>

<!-- Breadcrumb -->
<div class="bg-light py-2 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 fs-7">
                <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>products.php" class="text-decoration-none text-muted">Products</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>products.php?category=<?= e($product['category_slug']); ?>" class="text-decoration-none text-muted"><?= e($product['category_name']); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= e($product['name']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-5 align-items-center mb-5">
        <!-- Image Preview -->
        <div class="col-lg-6">
            <div class="card glass-card border-0 p-3 shadow text-center position-relative">
                <?php if ($discount > 0): ?>
                    <span class="discount-badge fs-6 px-3 py-2"><?= $discount; ?>% OFF</span>
                <?php endif; ?>
                <img src="<?= BASE_URL; ?>assets/images/uploads/<?= e($product['image']); ?>" class="img-fluid rounded-3 mx-auto" alt="<?= e($product['name']); ?>" style="max-height: 420px; object-fit: contain;" onerror="this.src='https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?q=80&w=600&auto=format&fit=crop'">
            </div>
        </div>

        <!-- Product Information & Order Actions -->
        <div class="col-lg-6">
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-2 px-3 py-1 fs-7"><?= e($product['category_name']); ?></span>
            <h2 class="fw-extrabold text-dark mb-2"><?= e($product['name']); ?></h2>
            <small class="text-muted d-block mb-3">Packing: <strong><?= e($product['unit']); ?></strong></small>

            <!-- Pricing Box -->
            <div class="p-3 bg-white rounded-3 shadow-sm border mb-4 d-flex align-items-center gap-3">
                <div>
                    <small class="text-muted d-block fs-8">OFFER PRICE</small>
                    <span class="price-offer display-6 text-danger fw-bold"><?= format_price($product['offer_price']); ?></span>
                </div>
                <div class="border-start ps-3">
                    <small class="text-muted d-block fs-8">ORIGINAL MRP</small>
                    <span class="price-mrp fs-5"><?= format_price($product['mrp']); ?></span>
                </div>
                <div class="border-start ps-3 ms-auto">
                    <span class="badge bg-success px-3 py-2 text-uppercase fs-7">Save <?= format_price($product['mrp'] - $product['offer_price']); ?></span>
                </div>
            </div>

            <!-- Stock Status -->
            <div class="mb-4">
                <?php if ($product['stock_quantity'] > 0): ?>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fs-7">
                        <i class="bi bi-check-circle-fill me-1"></i> In Stock (<?= $product['stock_quantity']; ?> available)
                    </span>
                <?php else: ?>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fs-7">
                        <i class="bi bi-x-circle-fill me-1"></i> Out of Stock
                    </span>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <h6 class="fw-bold mb-2">Description & Details:</h6>
            <p class="text-muted mb-4 fs-7"><?= nl2br(e($product['description'])); ?></p>

            <!-- Quantity & Actions -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="input-group" style="width: 130px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="let input = $('#qty_<?= $product['id']; ?>'); if(input.val() > 1) input.val(parseInt(input.val()) - 1);">-</button>
                    <input type="number" id="qty_<?= $product['id']; ?>" class="form-control text-center fw-bold" value="1" min="1" max="<?= $product['stock_quantity']; ?>">
                    <button class="btn btn-outline-secondary" type="button" onclick="let input = $('#qty_<?= $product['id']; ?>'); if(parseInt(input.val()) < <?= $product['stock_quantity']; ?>) input.val(parseInt(input.val()) + 1);">+</button>
                </div>

                <button class="btn btn-danger btn-add-cart rounded-pill fw-bold px-4 py-2 flex-grow-1 shadow" data-id="<?= $product['id']; ?>" <?= ($product['stock_quantity'] <= 0) ? 'disabled' : ''; ?>>
                    <i class="bi bi-cart-plus me-1"></i> Add To Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related_products)): ?>
        <div class="mt-5">
            <h4 class="fw-bold mb-4">You May Also Like</h4>
            <div class="row g-4">
                <?php foreach ($related_products as $p): 
                    $disc = calculate_discount($p['mrp'], $p['offer_price']);
                ?>
                    <div class="col-md-3 col-6">
                        <div class="card product-card h-100">
                            <div class="card-img-wrapper">
                                <img src="<?= BASE_URL; ?>assets/images/uploads/<?= e($p['image']); ?>" class="card-img-top" alt="<?= e($p['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?q=80&w=400&auto=format&fit=crop'">
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold mb-2 text-truncate">
                                    <a href="<?= BASE_URL; ?>product-details.php?slug=<?= e($p['slug']); ?>" class="text-dark text-decoration-none">
                                        <?= e($p['name']); ?>
                                    </a>
                                </h6>
                                <span class="price-offer fs-6"><?= format_price($p['offer_price']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
