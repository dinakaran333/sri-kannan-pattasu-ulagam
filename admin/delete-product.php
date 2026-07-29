<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    if ($stmt->execute([$id])) {
        set_flash('warning', 'Product deleted from inventory.');
    } else {
        set_flash('danger', 'Failed to delete product.');
    }
}

header('Location: ' . BASE_URL . 'admin/products.php');
exit;
