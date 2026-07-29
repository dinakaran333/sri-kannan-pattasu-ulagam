<?php
/**
 * AJAX Add to Cart API
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

// Fetch product details from DB to check existence & stock
$stmt = $db->prepare("SELECT id, name, stock_quantity, status FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product || $product['status'] !== 'active') {
    echo json_encode(['status' => 'error', 'message' => 'Product is currently unavailable.']);
    exit;
}

$current_qty_in_cart = isset($_SESSION['cart'][$product_id]['quantity']) ? (int)$_SESSION['cart'][$product_id]['quantity'] : 0;
$new_total_qty = $current_qty_in_cart + $quantity;

if ($new_total_qty > $product['stock_quantity']) {
    echo json_encode([
        'status' => 'error',
        'message' => "Only {$product['stock_quantity']} units available in stock."
    ]);
    exit;
}

// Add/Update in session cart
$_SESSION['cart'][$product_id] = [
    'quantity' => $new_total_qty
];

echo json_encode([
    'status' => 'success',
    'message' => "{$product['name']} added to your cart!",
    'cart_count' => get_cart_count()
]);
exit;
