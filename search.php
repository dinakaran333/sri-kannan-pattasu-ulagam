<?php
$page_title = "Search Results | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$query = trim($_GET['q'] ?? '');

$products = [];
if (!empty($query)) {
    $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?) AND p.status = 'active' ORDER BY p.id DESC");
    $term = "%{$query}%";
    $stmt->execute([$term, $term, $term]);
    $products = $stmt->fetchAll();
}
?>

<div class="py-4 bg-dark text-white text-center mb-4">
    <div class="container">
        <h2 class="fw-bold text-warning mb-0"><i class="bi bi-search me-2"></i> Search Results</h2>
        <small class="text-light">Showing matching fireworks for "<?= e($query); ?>"</small>
    </div>
</div>

<div class="container mb-5">
    <?php if (empty($query)): ?>
        <div class="alert alert-warning text-center">Please type a keyword in the search bar above.</div>
    <?php elseif (empty($products)): ?>
        <div class="text-center py-5 glass-card shadow-sm">
            <i class="bi bi-search-heart display-1 text-muted"></i>
            <h3 class="fw-bold mt-3">No Fireworks Found Matching "<?= e($query); ?>"</h3>
            <p class="text-muted">Try searching with broader terms such as "sparklers", "rockets", "pots", or "gift box".</p>
            <a href="<?= BASE_URL; ?>products.php" class="btn btn-warning rounded-pill px-4">Browse All Fireworks</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($products as $p): 
                $discount = calculate_discount($p['mrp'], $p['offer_price']);
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
