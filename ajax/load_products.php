<?php
/**
 * AJAX Load Products Catalog API
 * Online Cracker Shop
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$category_slug = isset($_GET['category']) ? trim($_GET['category']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';

$sql = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.status = 'active'";
$params = [];

if (!empty($category_slug)) {
    $sql .= " AND c.slug = ?";
    $params[] = $category_slug;
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

$data = [];
foreach ($products as $p) {
    $discount = calculate_discount($p['mrp'], $p['offer_price']);
    $data[] = [
        'id' => $p['id'],
        'name' => e($p['name']),
        'slug' => $p['slug'],
        'category_name' => e($p['category_name']),
        'image' => $p['image'],
        'mrp' => format_price($p['mrp']),
        'offer_price' => format_price($p['offer_price']),
        'discount' => $discount,
        'stock' => (int)$p['stock_quantity'],
        'unit' => e($p['unit'])
    ];
}

echo json_encode($data);
exit;
