<?php
/**
 * Customer Logout Script
 * Online Cracker Shop
 */

require_once __DIR__ . '/includes/config.php';

unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

session_start();
$_SESSION['flash'] = [
    'type' => 'success',
    'message' => 'You have been logged out safely.'
];

header('Location: ' . BASE_URL . 'login.php');
exit;
