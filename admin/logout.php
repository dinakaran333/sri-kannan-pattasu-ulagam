<?php
/**
 * Admin Logout Handler
 * Online Cracker Shop
 */

require_once __DIR__ . '/../includes/config.php';

unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

set_flash('info', 'Admin logged out.');
header('Location: ' . BASE_URL . 'admin/index.php');
exit;
