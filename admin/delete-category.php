<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_login();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    if ($stmt->execute([$id])) {
        set_flash('warning', 'Category deleted.');
    } else {
        set_flash('danger', 'Failed to delete category.');
    }
}

header('Location: ' . BASE_URL . 'admin/categories.php');
exit;
