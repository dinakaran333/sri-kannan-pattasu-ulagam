<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="admin-sidebar bg-dark text-white p-3 min-vh-100 border-end border-secondary shadow">
    <div class="sidebar-header pb-3 mb-3 border-bottom border-secondary d-flex align-items-center">
        <i class="bi bi-shield-lock-fill text-warning fs-3 me-2"></i>
        <div>
            <h6 class="fw-bold mb-0 text-white">ADMIN PANEL</h6>
            <small class="text-warning-50 fs-8">Sri Kannan Pattasu Ulagam</small>
        </div>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="<?= BASE_URL; ?>admin/dashboard.php" class="nav-link text-white <?= ($current_page == 'dashboard.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= BASE_URL; ?>admin/categories.php" class="nav-link text-white <?= (in_array($current_page, ['categories.php', 'add-category.php', 'edit-category.php'])) ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="bi bi-grid-fill me-2"></i> Categories
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= BASE_URL; ?>admin/products.php" class="nav-link text-white <?= (in_array($current_page, ['products.php', 'add-product.php', 'edit-product.php'])) ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="bi bi-box-seam me-2"></i> Products Inventory
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= BASE_URL; ?>admin/orders.php" class="nav-link text-white <?= ($current_page == 'orders.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="bi bi-cart-check-fill me-2"></i> Orders Management
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= BASE_URL; ?>admin/customers.php" class="nav-link text-white <?= ($current_page == 'customers.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="bi bi-people-fill me-2"></i> Registered Customers
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= BASE_URL; ?>admin/reports.php" class="nav-link text-white <?= ($current_page == 'reports.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="bi bi-graph-up-arrow me-2"></i> Sales Reports
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="<?= BASE_URL; ?>admin/settings.php" class="nav-link text-white <?= ($current_page == 'settings.php') ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="bi bi-gear-fill me-2"></i> Store Settings
            </a>
        </li>
    </ul>

    <hr class="border-secondary my-3">

    <div class="d-flex flex-column gap-2">
        <a href="<?= BASE_URL; ?>" target="_blank" class="btn btn-sm btn-outline-warning w-100 text-start">
            <i class="bi bi-box-arrow-up-right me-2"></i> View Front Store
        </a>
        <a href="<?= BASE_URL; ?>admin/logout.php" class="btn btn-sm btn-outline-danger w-100 text-start">
            <i class="bi bi-power me-2"></i> Logout Admin
        </a>
    </div>
</div>
