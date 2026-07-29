<?php
$page_title = "My Orders | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

require_user_login();
$user = get_logged_user();

// Fetch orders placed by this customer
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();
?>

<div class="py-4 bg-dark text-white text-center mb-4">
    <div class="container">
        <h2 class="fw-bold text-warning mb-0"><i class="bi bi-bag-check me-2"></i> My Order History</h2>
        <small class="text-light">Track order status and review past purchases</small>
    </div>
</div>

<div class="container mb-5">
    <?php if (empty($orders)): ?>
        <div class="text-center py-5 glass-card shadow-sm">
            <i class="bi bi-bag-x display-1 text-muted"></i>
            <h3 class="fw-bold mt-3">No Orders Found</h3>
            <p class="text-muted">You haven't placed any fireworks orders yet.</p>
            <a href="<?= BASE_URL; ?>products.php" class="btn btn-warning rounded-pill px-4 fw-bold">Shop Fireworks Now</a>
        </div>
    <?php else: ?>
        <div class="accordion" id="ordersAccordion">
            <?php foreach ($orders as $index => $order): 
                // Fetch line items for this order
                $stmt_items = $db->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $stmt_items->execute([$order['id']]);
                $items = $stmt_items->fetchAll();

                $badge_class = 'bg-warning text-dark';
                if ($order['order_status'] == 'Delivered') $badge_class = 'bg-success text-white';
                if ($order['order_status'] == 'Shipped') $badge_class = 'bg-info text-dark';
                if ($order['order_status'] == 'Cancelled') $badge_class = 'bg-danger text-white';
            ?>
                <div class="accordion-item mb-3 border rounded-3 shadow-sm overflow-hidden">
                    <h2 class="accordion-header" id="heading<?= $order['id']; ?>">
                        <button class="accordion-button <?= ($index !== 0) ? 'collapsed' : ''; ?> bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $order['id']; ?>">
                            <div class="d-flex justify-content-between align-items-center w-100 me-3 flex-wrap gap-2">
                                <div>
                                    <strong class="text-dark me-2"><?= e($order['order_number']); ?></strong>
                                    <small class="text-muted"><i class="bi bi-calendar me-1"></i> <?= date('d M Y, h:i A', strtotime($order['created_at'])); ?></small>
                                </div>
                                <div>
                                    <span class="badge <?= $badge_class; ?> px-3 py-2 me-3 fs-7"><?= e($order['order_status']); ?></span>
                                    <strong class="text-danger fs-6"><?= format_price($order['total_amount']); ?></strong>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse<?= $order['id']; ?>" class="accordion-collapse collapse <?= ($index === 0) ? 'show' : ''; ?>" data-bs-parent="#ordersAccordion">
                        <div class="accordion-body bg-white">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-dark">Shipping Address:</h6>
                                    <p class="text-muted fs-7 mb-0">
                                        <strong><?= e($order['shipping_name']); ?></strong><br>
                                        <?= e($order['shipping_address']); ?><br>
                                        <?= e($order['city']); ?>, <?= e($order['state']); ?> - <?= e($order['pincode']); ?><br>
                                        Phone: <?= e($order['shipping_phone']); ?>
                                    </p>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    <h6 class="fw-bold text-dark">Payment Details:</h6>
                                    <span class="badge bg-secondary mb-1"><?= e($order['payment_method']); ?></span><br>
                                    <small class="text-muted">Payment Status: <strong><?= e($order['payment_status']); ?></strong></small>
                                </div>
                            </div>

                            <h6 class="fw-bold border-bottom pb-2 mb-2">Order Items:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td class="fw-semibold text-dark fs-7"><?= e($item['product_name']); ?></td>
                                                <td class="fs-7"><?= format_price($item['price']); ?></td>
                                                <td class="fs-7"><?= $item['quantity']; ?></td>
                                                <td class="fw-bold text-danger fs-7"><?= format_price($item['total_price']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
