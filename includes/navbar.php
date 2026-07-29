<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';

$cart_count = get_cart_count();
$user = get_logged_user();
?>
<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg sticky-top navbar-dark custom-navbar shadow">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL; ?>">
            <i class="bi bi-rocket-takeoff-fill text-warning me-2 fs-2 spark-icon"></i>
            <div>
                <span class="fw-bold fs-5 text-white letter-spacing-1">SRI KANNAN <span class="text-warning">PATTASU ULAGAM</span></span>
                <small class="d-block text-warning-50 fs-8">SIVAKASI DIRECT FIREWORKS</small>
            </div>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links & Search -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-medium ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="<?= BASE_URL; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'active' : ''; ?>" href="<?= BASE_URL; ?>products.php">Shop Crackers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>" href="<?= BASE_URL; ?>about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>" href="<?= BASE_URL; ?>contact.php">Contact</a>
                </li>
            </ul>

            <!-- AJAX Live Search Form -->
            <div class="search-box-container position-relative me-lg-3 my-2 my-lg-0 flex-grow-1 max-w-350">
                <form action="<?= BASE_URL; ?>search.php" method="GET" class="d-flex position-relative">
                    <input type="text" id="liveSearchInput" name="q" class="form-control rounded-pill pe-5 bg-dark text-white border-warning-subtle" placeholder="Search sparklers, rockets..." autocomplete="off" value="<?= e($_GET['q'] ?? ''); ?>">
                    <button class="btn text-warning position-absolute end-0 top-0 h-100 px-3 border-0 bg-transparent" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                <div id="searchResultsDropdown" class="dropdown-menu w-100 shadow-lg border-0 bg-dark text-white rounded-3 mt-1 position-absolute d-none z-1050"></div>
            </div>

            <!-- Action Buttons: Cart & User Account -->
            <div class="d-flex align-items-center gap-2">
                <!-- Cart Button -->
                <a href="<?= BASE_URL; ?>cart.php" class="btn btn-outline-warning rounded-pill position-relative px-3 py-2">
                    <i class="bi bi-cart3 me-1"></i> Cart
                    <span id="navCartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow">
                        <?= $cart_count; ?>
                    </span>
                </a>

                <!-- User Profile / Auth Links -->
                <?php if (is_user_logged_in()): ?>
                    <div class="dropdown">
                        <button class="btn btn-warning rounded-pill dropdown-toggle fw-semibold text-dark px-3 py-2" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?= e(explode(' ', $user['name'])[0]); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2">
                            <li><a class="dropdown-item" href="<?= BASE_URL; ?>profile.php"><i class="bi bi-person me-2"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL; ?>orders.php"><i class="bi bi-bag-check me-2"></i> My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL; ?>logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL; ?>login.php" class="btn btn-warning rounded-pill fw-semibold text-dark px-3 py-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Alerts Container -->
<div class="container">
    <?php display_flash(); ?>
</div>
