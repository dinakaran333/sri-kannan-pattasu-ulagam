<?php
$page_title = "404 Page Not Found | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="container my-5 text-center">
    <div class="glass-card p-5 border-0 shadow-lg mx-auto max-w-600">
        <h1 class="display-1 fw-extrabold text-danger mb-0">404</h1>
        <i class="bi bi-fire text-warning display-2 d-block my-3 spark-icon"></i>
        <h3 class="fw-bold text-dark mb-2">Oops! Page Fizzled Out</h3>
        <p class="text-muted mb-4">The page or cracker item you are looking for doesn't exist or has been moved.</p>
        <a href="<?= BASE_URL; ?>" class="btn btn-warning btn-lg rounded-pill fw-bold text-dark px-4 shadow">
            <i class="bi bi-house-door-fill me-2"></i> Return To Home
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
