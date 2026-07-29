<?php
/**
 * AJAX Delete Cart API
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

if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

$summary = get_cart_summary();

echo json_encode([
    'status' => 'success',
    'message' => 'Item removed from cart.',
    'subtotal' => format_price($summary['subtotal']),
    'shipping' => format_price($summary['shipping']),
    'grand_total' => format_price($summary['grand_total']),
    'cart_count' => $summary['count']
]);
exit;
