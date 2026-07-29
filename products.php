<?php
$page_title = "Shop Fireworks & Festive Crackers | SparkleFest";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Get Active Categories
$categories = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();

// Get filter inputs
$selected_category = trim($_GET['category'] ?? '');
$sort = trim($_GET['sort'] ?? 'newest');

// Build query
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p JOIN categories c ON p.category_id = c.id WHERE p.status = 'active'";
$params = [];

if (!empty($selected_category)) {
    $sql .= " AND c.slug = ?";
    $params[] = $selected_category;
}

switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY p.offer_price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY p.offer_price DESC";
        break;
    case 'popular':
        $sql .= " ORDER BY p.is_featured DESC, p.id DESC";
        break;
    default:
        $sql .= " ORDER BY p.id DESC";
        break;
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="py-4 bg-dark text-white mb-4">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h2 class="fw-bold text-warning mb-0">Fireworks & Crackers Catalog</h2>
            <small class="text-light">Browse our certified collection of Diwali crackers</small>
        </div>
        <div class="mt-2 mt-md-0">
            <span class="badge bg-danger fs-6"><?= count($products); ?> Products Available</span>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card glass-card p-3 border-0 shadow-sm sticky-top" style="top: 90px; z-index: 10;">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel-fill text-danger me-2"></i> Categories</h5>
                <div class="list-group list-group-flush mb-4">
                    <a href="<?= BASE_URL; ?>products.php" class="list-group-item list-group-item-action <?= empty($selected_category) ? 'active bg-danger border-danger fw-bold' : ''; ?>">
                        All Categories
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= BASE_URL; ?>products.php?category=<?= e($cat['slug']); ?>&sort=<?= e($sort); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($selected_category == $cat['slug']) ? 'active bg-danger border-danger fw-bold' : ''; ?>">
                            <?= e($cat['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <h5 class="fw-bold mb-3"><i class="bi bi-sort-down text-warning me-2"></i> Sort By</h5>
                <form action="<?= BASE_URL; ?>products.php" method="GET">
                    <?php if (!empty($selected_category)): ?>
                        <input type="hidden" name="category" value="<?= e($selected_category); ?>">
                    <?php endif; ?>
                    <select name="sort" class="form-select mb-3" onchange="this.form.submit()">
                        <option value="newest" <?= ($sort == 'newest') ? 'selected' : ''; ?>>Newest Arrivals</option>
                        <option value="popular" <?= ($sort == 'popular') ? 'selected' : ''; ?>>Featured / Popular</option>
                        <option value="price_low" <?= ($sort == 'price_low') ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?= ($sort == 'price_high') ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Product Cards Grid -->
        <div class="col-lg-9">
            <?php if (empty($products)): ?>
                <div class="text-center py-5 glass-card">
                    <i class="bi bi-emoji-frown display-1 text-muted"></i>
                    <h4 class="mt-3 fw-bold">No Fireworks Found</h4>
                    <p class="text-muted">Try selecting a different category or search term.</p>
                    <a href="<?= BASE_URL; ?>products.php" class="btn btn-danger rounded-pill">Reset Filters</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($products as $p): 
                        $discount = calculate_discount($p['mrp'], $p['offer_price']);
                    ?>
                        <div class="col-md-4 col-sm-6">
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
