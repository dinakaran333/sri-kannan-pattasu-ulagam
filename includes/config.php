<?php
/**
 * Global Configuration Settings
 * Online Cracker Shop
 */

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// System Environment Configuration
define('SITE_NAME', 'Sri Kannan Pattasu Ulagam');
define('SITE_SLOGAN', 'Illuminate Your Celebrations with Premium Fireworks');
define('BASE_URL', 'http://localhost/cracker-shop/');
define('CURRENCY_SYMBOL', '₹');
define('TAX_RATE', 0.00); // 0% tax for display or customizable
define('FREE_SHIPPING_THRESHOLD', 1000.00);
define('FLAT_SHIPPING_FEE', 100.00);

// Database Connection Parameters
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cracker_shop');
define('DB_PORT', '3306');

// Image Upload Path
define('UPLOAD_DIR', __DIR__ . '/../assets/images/uploads/');
define('UPLOAD_URL', BASE_URL . 'assets/images/uploads/');

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize Cart Session if empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
