<?php
/**
 * AJAX Update Cart API
 * Online Cracker Shop
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product or quantity specified.']);
    exit;
}

$stmt = $db->prepare("SELECT id, name, offer_price, stock_quantity FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
    exit;
}

if ($quantity > $product['stock_quantity']) {
    echo json_encode([
        'status' => 'error',
        'message' => "Stock limit reached. Maximum {$product['stock_quantity']} allowed."
    ]);
    exit;
}

$_SESSION['cart'][$product_id]['quantity'] = $quantity;
$summary = get_cart_summary();

$line_total = format_price($product['offer_price'] * $quantity);

echo json_encode([
    'status' => 'success',
    'message' => 'Cart updated.',
    'line_total' => $line_total,
    'subtotal' => format_price($summary['subtotal']),
    'shipping' => format_price($summary['shipping']),
    'grand_total' => format_price($summary['grand_total']),
    'cart_count' => $summary['count']
]);
exit;
