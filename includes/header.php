<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';

$page_title = $page_title ?? SITE_NAME . ' | ' . SITE_SLOGAN;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title); ?></title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS Styles -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/responsive.css">
</head>
<body>

<!-- Top Announcement Banner -->
<div class="top-bar py-2 text-white text-center bg-danger shadow-sm">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        <span class="fs-6"><i class="bi bi-fire text-warning me-2"></i><strong>Festive Season Mega Sale!</strong> Flat 50% OFF on all Sparklers & Combo Gift Boxes!</span>
        <div class="top-contacts d-none d-md-block fs-6">
            <span class="me-3"><i class="bi bi-telephone-fill me-1"></i> +91 98765 43210</span>
            <span><i class="bi bi-envelope-fill me-1"></i> support@crackershop.com</span>
        </div>
    </div>
</div>
