<?php
$page_title = "Checkout Order | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

require_user_login();

$summary = get_cart_summary();
if (empty($summary['items'])) {
    header('Location: ' . BASE_URL . 'cart.php');
    exit;
}

$user = get_logged_user();

// Pre-fill user data if available
$stmt_user = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$user['id']]);
$user_info = $stmt_user->fetch();

$error_msg = "";
$order_success = false;
$placed_order_number = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_name = trim($_POST['shipping_name'] ?? '');
    $shipping_phone = trim($_POST['shipping_phone'] ?? '');
    $shipping_email = trim($_POST['shipping_email'] ?? '');
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? 'COD');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($shipping_name) || empty($shipping_phone) || empty($shipping_email) || empty($shipping_address) || empty($city) || empty($state) || empty($pincode)) {
        $error_msg = "Please fill in all mandatory shipping address fields.";
    } else {
        try {
            $db->beginTransaction();

            $order_number = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
            $total_amount = $summary['grand_total'];

            // Insert into orders table
            $stmt_ord = $db->prepare("INSERT INTO orders (order_number, user_id, total_amount, payment_method, payment_status, order_status, shipping_name, shipping_phone, shipping_email, shipping_address, city, state, pincode, notes) VALUES (?, ?, ?, ?, 'Pending', 'Pending', ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_ord->execute([
                $order_number,
                $user['id'],
                $total_amount,
                $payment_method,
                $shipping_name,
                $shipping_phone,
                $shipping_email,
                $shipping_address,
                $city,
                $state,
                $pincode,
                $notes
            ]);

            $order_id = $db->lastInsertId();

            // Insert order line items and update stock
            $stmt_item = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_stock = $db->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");

            foreach ($summary['items'] as $item) {
                $stmt_item->execute([
                    $order_id,
                    $item['id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    $item['line_total']
                ]);

                // Decrement Stock
                $stmt_stock->execute([$item['quantity'], $item['id']]);
            }

            $db->commit();

            // Clear session cart
            $_SESSION['cart'] = [];
            $order_success = true;
            $placed_order_number = $order_number;

        } catch (Exception $e) {
            $db->rollBack();
            $error_msg = "Order placement failed: " . $e->getMessage();
        }
    }
}
?>

<div class="py-4 bg-dark text-white text-center mb-4">
    <div class="container">
        <h2 class="fw-bold text-warning mb-0"><i class="bi bi-shield-check me-2"></i> Secure Checkout</h2>
        <small class="text-light">Enter shipping details to confirm your order</small>
    </div>
</div>

<div class="container mb-5">
    <?php if ($order_success): ?>
        <div class="card glass-card p-5 text-center border-0 shadow-lg mx-auto max-w-600">
            <i class="bi bi-check-circle-fill text-success display-1 mb-3"></i>
            <h2 class="fw-bold text-dark mb-2">Order Placed Successfully!</h2>
            <p class="text-muted">Thank you for your order. Your Order Tracking ID is:</p>
            <div class="bg-warning-subtle text-dark border border-warning rounded-3 p-3 fw-bold fs-4 mb-4">
                <?= e($placed_order_number); ?>
            </div>
            <p class="text-muted fs-7 mb-4">We are preparing your fireworks package for dispatch. You can track your order progress in your account dashboard.</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="<?= BASE_URL; ?>orders.php" class="btn btn-danger rounded-pill px-4">View Order History</a>
                <a href="<?= BASE_URL; ?>" class="btn btn-outline-secondary rounded-pill px-4">Back To Home</a>
            </div>
        </div>
    <?php else: ?>
        <?php if ($error_msg): ?>
            <div class="alert alert-danger"><?= e($error_msg); ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL; ?>checkout.php" method="POST">
            <div class="row g-4">
                <!-- Customer Shipping Address -->
                <div class="col-lg-7">
                    <div class="card glass-card border-0 p-4 shadow-sm mb-4">
                        <h5 class="fw-bold text-dark border-bottom pb-3 mb-3"><i class="bi bi-truck me-2 text-danger"></i> Shipping Address</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Recipient Full Name *</label>
                                <input type="text" name="shipping_name" class="form-control" value="<?= e($user_info['full_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number *</label>
                                <input type="tel" name="shipping_phone" class="form-control" value="<?= e($user_info['phone'] ?? ''); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Email Address *</label>
                                <input type="email" name="shipping_email" class="form-control" value="<?= e($user_info['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Street Address *</label>
                                <textarea name="shipping_address" rows="3" class="form-control" placeholder="House/Flat No, Landmark, Street name" required><?= e($user_info['address'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">City *</label>
                                <input type="text" name="city" class="form-control" value="<?= e($user_info['city'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">State *</label>
                                <input type="text" name="state" class="form-control" value="<?= e($user_info['state'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Pincode *</label>
                                <input type="text" name="pincode" class="form-control" value="<?= e($user_info['pincode'] ?? ''); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Special Delivery Notes (Optional)</label>
                                <input type="text" name="notes" class="form-control" placeholder="e.g. Leave with security guard">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="card glass-card border-0 p-4 shadow-sm">
                        <h5 class="fw-bold text-dark border-bottom pb-3 mb-3"><i class="bi bi-wallet2 me-2 text-warning"></i> Select Payment Method</h5>
                        
                        <div class="form-check p-3 border rounded-3 mb-3 bg-light">
                            <input class="form-check-input" type="radio" name="payment_method" id="payCOD" value="COD" checked>
                            <label class="form-check-label fw-bold d-block" for="payCOD">
                                <i class="bi bi-cash-stack text-success me-2 fs-5"></i> Cash On Delivery (COD)
                                <small class="d-block text-muted fw-normal mt-1">Pay cash directly to the delivery agent upon receiving your fireworks package.</small>
                            </label>
                        </div>

                        <div class="form-check p-3 border rounded-3 bg-light opacity-75">
                            <input class="form-check-input" type="radio" name="payment_method" id="payOnline" value="ONLINE" disabled>
                            <label class="form-check-label fw-bold d-block" for="payOnline">
                                <i class="bi bi-credit-card text-primary me-2 fs-5"></i> UPI / Credit / Debit Card (Simulated Online Payment)
                                <small class="d-block text-muted fw-normal mt-1">Online payment gateway integration placeholder.</small>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Breakdown -->
                <div class="col-lg-5">
                    <div class="card glass-card border-0 p-4 shadow-sm sticky-top" style="top:90px;">
                        <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">Order Items (<?= $summary['count']; ?>)</h5>

                        <div class="checkout-items-list mb-3 max-h-300 overflow-y-auto">
                            <?php foreach ($summary['items'] as $item): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= BASE_URL; ?>assets/images/uploads/<?= e($item['image']); ?>" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1531306728370-e2ebd9d7bb99?q=80&w=80&auto=format&fit=crop'">
                                        <div>
                                            <div class="fw-semibold fs-7 text-truncate" style="max-width: 180px;"><?= e($item['name']); ?></div>
                                            <small class="text-muted">Qty: <?= $item['quantity']; ?></small>
                                        </div>
                                    </div>
                                    <span class="fw-bold fs-7 text-danger"><?= format_price($item['line_total']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold"><?= format_price($summary['subtotal']); ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping Fee</span>
                            <span class="fw-bold text-success">
                                <?= ($summary['shipping'] > 0) ? format_price($summary['shipping']) : 'FREE'; ?>
                            </span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-4 fs-4">
                            <strong class="text-dark">Total Amount</strong>
                            <strong class="text-danger"><?= format_price($summary['grand_total']); ?></strong>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold text-dark shadow">
                            <i class="bi bi-bag-check-fill me-2"></i> Confirm & Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
