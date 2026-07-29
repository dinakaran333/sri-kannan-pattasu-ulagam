<?php
/**
 * AJAX Live Search API
 * Online Cracker Shop
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $db->prepare("SELECT id, name, slug, image, offer_price FROM products WHERE (name LIKE ? OR description LIKE ?) AND status = 'active' LIMIT 6");
$searchTerm = "%{$query}%";
$stmt->execute([$searchTerm, $searchTerm]);
$results = $stmt->fetchAll();

$formatted = [];
foreach ($results as $row) {
    $formatted[] = [
        'id' => $row['id'],
        'name' => e($row['name']),
        'slug' => $row['slug'],
        'image' => $row['image'],
        'offer_price' => format_price($row['offer_price'])
    ];
}

echo json_encode($formatted);
exit;
