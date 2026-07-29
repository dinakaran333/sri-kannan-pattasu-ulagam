<?php
/**
 * Authentication and Authorization Guard Handlers
 * Online Cracker Shop
 */

require_once __DIR__ . '/config.php';

/**
 * Check if a customer user is logged in
 */
function is_user_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Require User Login Guard
 */
function require_user_login() {
    if (!is_user_logged_in()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        set_flash('warning', 'Please log in to your account to continue.');
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

/**
 * Get current logged in user details
 */
function get_logged_user() {
    if (!is_user_logged_in()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? 'Customer',
        'email' => $_SESSION['user_email'] ?? ''
    ];
}

/**
 * Check if Admin is logged in
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Require Admin Login Guard
 */
function require_admin_login() {
    if (!is_admin_logged_in()) {
        set_flash('danger', 'Unauthorized access! Admin authentication required.');
        header('Location: ' . BASE_URL . 'admin/index.php');
        exit;
    }
}
