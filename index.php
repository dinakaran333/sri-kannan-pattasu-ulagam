<?php
$page_title = "SparkleFest Crackers | Premium Festive Fireworks & Sparklers Online";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch Active Categories
$stmt_cat = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY id ASC");
$categories = $stmt_cat->fetchAll();

// Fetch Featured Products
$stmt_feat = $db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 AND p.status = 'active' ORDER BY p.id DESC LIMIT 8");
$featured_products = $stmt_feat->fetchAll();
?>

<!-- Hero Banner Section -->
<section class="hero-banner shadow-lg text-center text-lg-start mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-7 mb-3 fw-bold">
                    <i class="bi bi-stars me-1"></i> FESTIVE OFFERS UP TO 60% OFF
                </span>
                <h1 class="display-3 fw-extrabold text-white mb-3">
                    Bring Magic to Your <span class="text-warning">Celebrations!</span>
                </h1>
                <p class="lead text-light mb-4">
                    Explore India's finest range of green certified fireworks, sparkling fountains, colorful sky shots, and festive combo gift boxes with safe home delivery.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="<?= BASE_URL; ?>products.php" class="btn btn-warning btn-lg rounded-pill fw-bold text-dark px-4 py-3 shadow">
                        <i class="bi bi-bag-fill me-2"></i> Shop Fireworks Now
                    </a>
                    <a href="<?= BASE_URL; ?>products.php?category=fancy-gift-boxes" class="btn btn-outline-light btn-lg rounded-pill fw-bold px-4 py-3">
                        <i class="bi bi-gift-fill me-2"></i> View Gift Combos
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center position-relative">
                <div class="glass-dark-card p-4 text-white rounded-4 border border-warning">
                    <i class="bi bi-box-seam text-warning display-1 d-block mb-2 spark-icon"></i>
                    <h4 class="fw-bold text-warning">Diwali Super Combo Pack</h4>
                    <p class="fs-7 text-white-50">25 Variety Fireworks Box for Whole Family</p>
                    <div class="d-flex justify-content-center align-items-center gap-3 my-3">
                        <span class="price-mrp fs-5 text-white-50">₹ 3,500.00</span>
                        <span class="price-offer text-warning fs-3">₹ 1,799.00</span>
                    </div>
                    <a href="<?= BASE_URL; ?>product-details.php?slug=family-festival-dhamaka-pack" class="btn btn-warning w-100 rounded-pill fw-bold text-dark">
                        Claim Deal Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges Section -->
<div class="container mb-5">
    <div class="row g-3">
        <div class="col-md-3 col-6">
            <div class="card glass-card border-0 p-3 text-center h-100 shadow-sm">
                <i class="bi bi-shield-check text-danger display-6 mb-2"></i>
                <h6 class="fw-bold mb-1">100% Green Crackers</h6>
                <small class="text-muted">Eco-friendly certified quality</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card glass-card border-0 p-3 text-center h-100 shadow-sm">
                <i class="bi bi-truck text-warning display-6 mb-2"></i>
                <h6 class="fw-bold mb-1">Express Delivery</h6>
                <small class="text-muted">Safe doorstep transport</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card glass-card border-0 p-3 text-center h-100 shadow-sm">
                <i class="bi bi-tags text-danger display-6 mb-2"></i>
                <h6 class="fw-bold mb-1">Best Wholesale Rates</h6>
                <small class="text-muted">Direct factory prices</small>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card glass-card border-0 p-3 text-center h-100 shadow-sm">
                <i class="bi bi-cash-coin text-warning display-6 mb-2"></i>
                <h6 class="fw-bold mb-1">Cash On Delivery</h6>
                <small class="text-muted">Pay upon doorstep receipt</small>
            </div>
        </div>
    </div>
</div>

<!-- Shop By Category Section -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-danger fw-bold text-uppercase fs-7 letter-spacing-1">Explore Categories</span>
            <h2 class="fw-bold text-dark mb-0">Shop Fireworks By Type</h2>
        </div>
        <a href="<?= BASE_URL; ?>products.php" class="btn btn-outline-danger btn-sm rounded-pill fw-semibold">View All <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-4">
        <?php foreach ($categories as $cat): ?>
            <div class="col-lg-2 col-md-4 col-6">
                <a href="<?= BASE_URL; ?>products.php?category=<?= e($cat['slug']); ?>" class="text-decoration-none text-dark">
                    <div class="card category-card text-center shadow-sm h-100">
                        <div class="p-3 bg-light d-flex align-items-center justify-content-center" style="height:120px;">
                            <i class="bi bi-fire text-danger display-4 spark-icon"></i>
                        </div>
                        <div class="card-body p-2">
                            <h6 class="fw-bold mb-0 text-truncate"><?= e($cat['name']); ?></h6>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Featured Products Section -->
<section class="container mb-5">
    <div class="text-center mb-4">
        <span class="badge bg-danger text-white px-3 py-2 rounded-pill fs-7 fw-bold mb-2">HANDPICKED DEALS</span>
        <h2 class="fw-bold text-dark">Featured Festive Fireworks</h2>
        <p class="text-muted">Top selling crackers loved by thousands of families</p>
    </div>

    <div class="row g-4">
        <?php foreach ($featured_products as $p): 
            $discount = calculate_discount($p['mrp'], $p['offer_price']);
        ?>
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <?php if ($discount > 0): ?>
                        <span class="discount-badge"><?= $discount; ?>% OFF</span>
                    <?php endif; ?>
                    <div class="card-img-wrapper">
                        <img src="<?= BASE_URL; ?>assets/images/uploads/<?= e($p['image']); ?>" class="card-img-top" alt="<?= e($p['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?q=80&w=400&auto=format&fit=crop'">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-light text-secondary border mb-2"><?= e($p['category_name']); ?></span>
                            <h6 class="fw-bold text-dark mb-2 text-truncate-2">
                                <a href="<?= BASE_URL; ?>product-details.php?slug=<?= e($p['slug']); ?>" class="text-dark text-decoration-none">
                                    <?= e($p['name']); ?>
                                </a>
                            </h6>
                            <small class="text-muted d-block mb-2"><?= e($p['unit']); ?></small>
                        </div>
                        
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="price-offer"><?= format_price($p['offer_price']); ?></span>
                                <span class="price-mrp"><?= format_price($p['mrp']); ?></span>
                            </div>

                            <button class="btn btn-danger btn-add-cart w-100 rounded-pill fw-semibold shadow-sm" data-id="<?= $p['id']; ?>">
                                <i class="bi bi-cart-plus me-1"></i> Add To Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Promotional Banner -->
<section class="container mb-5">
    <div class="card glass-dark-card border-0 text-white p-5 text-center shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #212529 0%, #d32f2f 100%);">
        <div class="position-relative z-1">
            <h2 class="display-5 fw-extrabold text-warning mb-3">Planning a Grand Celebration?</h2>
            <p class="lead text-light mb-4 max-w-600 mx-auto">Get exclusive bulk order discount quotes for marriage functions, community Diwali events, and corporate gifting!</p>
            <a href="<?= BASE_URL; ?>contact.php" class="btn btn-warning btn-lg rounded-pill fw-bold text-dark px-5">Contact Wholesale Desk</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
