<?php
$page_title = "Shopping Cart | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$summary = get_cart_summary();
?>

<div class="py-4 bg-dark text-white text-center mb-4">
    <div class="container">
        <h2 class="fw-bold text-warning mb-0"><i class="bi bi-cart3 me-2"></i> Your Shopping Cart</h2>
        <small class="text-light">Review your festive items before checkout</small>
    </div>
</div>

<div class="container mb-5">
    <?php if (empty($summary['items'])): ?>
        <div class="text-center py-5 glass-card shadow-sm">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="fw-bold mt-3">Your Cart is Empty!</h3>
            <p class="text-muted">Looks like you haven't added any fireworks to your cart yet.</p>
            <a href="<?= BASE_URL; ?>products.php" class="btn btn-warning btn-lg rounded-pill fw-bold text-dark px-4 mt-2">
                <i class="bi bi-bag-plus me-2"></i> Explore Fireworks Catalog
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Cart Items Table -->
            <div class="col-lg-8">
                <div class="card glass-card border-0 p-3 shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['items'] as $item): ?>
                                    <tr id="cart_row_<?= $item['id']; ?>" class="cart-item-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= BASE_URL; ?>assets/images/uploads/<?= e($item['image']); ?>" alt="<?= e($item['name']); ?>" class="rounded me-3" style="width: 55px; height: 55px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?q=80&w=100&auto=format&fit=crop'">
                                                <div>
                                                    <h6 class="fw-bold mb-0 fs-7">
                                                        <a href="<?= BASE_URL; ?>product-details.php?slug=<?= slugify($item['name']); ?>" class="text-dark text-decoration-none"><?= e($item['name']); ?></a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-muted fs-7"><?= format_price($item['price']); ?></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center cart-qty-input fw-bold" style="width: 75px;" value="<?= $item['quantity']; ?>" min="1" max="<?= $item['stock']; ?>" data-id="<?= $item['id']; ?>">
                                        </td>
                                        <td id="line_total_<?= $item['id']; ?>" class="fw-bold text-danger fs-7"><?= format_price($item['line_total']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger btn-remove-cart rounded-circle" data-id="<?= $item['id']; ?>">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="<?= BASE_URL; ?>products.php" class="btn btn-outline-secondary rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="col-lg-4">
                <div class="card glass-card border-0 p-4 shadow-sm">
                    <h5 class="fw-bold text-dark border-bottom pb-3 mb-3"><i class="bi bi-receipt me-2 text-danger"></i> Order Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span id="cartSubtotal" class="fw-bold"><?= format_price($summary['subtotal']); ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Delivery Charges</span>
                        <span id="cartShipping" class="fw-bold text-success">
                            <?= ($summary['shipping'] > 0) ? format_price($summary['shipping']) : 'FREE'; ?>
                        </span>
                    </div>

                    <?php if ($summary['subtotal'] < FREE_SHIPPING_THRESHOLD): ?>
                        <div class="alert alert-info py-2 fs-8 mb-3">
                            <i class="bi bi-info-circle me-1"></i> Add <strong><?= format_price(FREE_SHIPPING_THRESHOLD - $summary['subtotal']); ?></strong> more for FREE Shipping!
                        </div>
                    <?php endif; ?>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4 fs-5">
                        <strong class="text-dark">Grand Total</strong>
                        <strong id="cartGrandTotal" class="text-danger"><?= format_price($summary['grand_total']); ?></strong>
                    </div>

                    <a href="<?= BASE_URL; ?>checkout.php" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold shadow">
                        Proceed To Checkout <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
