<?php
/**
 * Helper Functions
 * Online Cracker Shop
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * Escape HTML output to prevent XSS attacks
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Format currency with symbol
 */
function format_price($price) {
    return CURRENCY_SYMBOL . ' ' . number_format((float)$price, 2);
}

/**
 * Calculate discount percentage
 */
function calculate_discount($mrp, $offer_price) {
    if ($mrp <= 0 || $offer_price >= $mrp) return 0;
    $discount = (($mrp - $offer_price) / $mrp) * 100;
    return round($discount);
}

/**
 * Generate URL slug from text string
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Set Session Flash Message
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

/**
 * Display Session Flash Message
 */
function display_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        echo '<div class="alert alert-' . e($flash['type']) . ' alert-dismissible fade show my-3" role="alert">
                ' . e($flash['message']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['flash']);
    }
}

/**
 * CSRF Token Generator & Validator
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get total items count in cart
 */
function get_cart_count() {
    $count = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += (int)($item['quantity'] ?? 0);
        }
    }
    return $count;
}

/**
 * Get cart total price calculation
 */
function get_cart_summary() {
    global $db;
    $subtotal = 0.00;
    $items = [];

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $product_ids = array_keys($_SESSION['cart']);
        if (!empty($product_ids)) {
            $in_clause = implode(',', array_fill(0, count($product_ids), '?'));
            $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($in_clause) AND status = 'active'");
            $stmt->execute(array_values($product_ids));
            $products = $stmt->fetchAll();

            foreach ($products as $product) {
                $pid = $product['id'];
                $qty = (int)$_SESSION['cart'][$pid]['quantity'];
                $line_total = $product['offer_price'] * $qty;
                $subtotal += $line_total;

                $items[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'image' => $product['image'],
                    'mrp' => $product['mrp'],
                    'price' => $product['offer_price'],
                    'stock' => $product['stock_quantity'],
                    'quantity' => $qty,
                    'line_total' => $line_total
                ];
            }
        }
    }

    $shipping = ($subtotal > 0 && $subtotal < FREE_SHIPPING_THRESHOLD) ? FLAT_SHIPPING_FEE : 0.00;
    $grand_total = $subtotal + $shipping;

    return [
        'items' => $items,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'grand_total' => $grand_total,
        'count' => get_cart_count()
    ];
}

/**
 * Image Upload Helper
 */
function upload_image($file, $target_dir = UPLOAD_DIR) {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_extensions)) {
        return false;
    }

    $filename = uniqid('img_', true) . '.' . $file_ext;
    $destination = $target_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }

    return false;
}
