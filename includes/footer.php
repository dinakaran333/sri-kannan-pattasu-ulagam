<!-- Footer Section -->
<footer class="footer bg-dark text-white pt-5 pb-3 border-top border-warning-subtle mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- Brand Info & Safety Warning -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-warning mb-3">
                    <i class="bi bi-rocket-takeoff-fill me-2"></i>Sri Kannan Pattasu Ulagam
                </h5>
                <p class="text-white-50 fs-7">
                    Your premier online destination for certified safe, green, and vibrant festive crackers. Celebrate Diwali, New Year, and special occasions with bright fireworks delivered directly to your doorstep.
                </p>
                <div class="alert alert-warning border-0 bg-warning-subtle text-dark p-2 rounded-3 fs-8 mb-3">
                    <i class="bi bi-shield-exclamation text-danger me-1"></i> <strong>Safety Advice:</strong> Always burst crackers in open areas under adult supervision. Keep water buckets nearby!
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-white mb-3">Quick Navigation</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?= BASE_URL; ?>" class="text-white-50 text-decoration-none">Home</a></li>
                    <li><a href="<?= BASE_URL; ?>products.php" class="text-white-50 text-decoration-none">All Fireworks</a></li>
                    <li><a href="<?= BASE_URL; ?>about.php" class="text-white-50 text-decoration-none">About Store</a></li>
                    <li><a href="<?= BASE_URL; ?>contact.php" class="text-white-50 text-decoration-none">Contact Us</a></li>
                    <li><a href="<?= BASE_URL; ?>cart.php" class="text-white-50 text-decoration-none">Shopping Cart</a></li>
                </ul>
            </div>

            <!-- Product Categories -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-white mb-3">Popular Categories</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?= BASE_URL; ?>products.php?category=sparklers" class="text-white-50 text-decoration-none">Electric Sparklers</a></li>
                    <li><a href="<?= BASE_URL; ?>products.php?category=flower-pots" class="text-white-50 text-decoration-none">Flower Pots & Fountains</a></li>
                    <li><a href="<?= BASE_URL; ?>products.php?category=ground-chakkars" class="text-white-50 text-decoration-none">Ground Chakkars</a></li>
                    <li><a href="<?= BASE_URL; ?>products.php?category=rockets-sky-shots" class="text-white-50 text-decoration-none">Sky Rockets & Repeaters</a></li>
                    <li><a href="<?= BASE_URL; ?>products.php?category=fancy-gift-boxes" class="text-white-50 text-decoration-none">Assorted Gift Boxes</a></li>
                </ul>
            </div>

            <!-- Newsletter & Social -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-white mb-3">Stay Updated</h6>
                <p class="text-white-50 fs-7">Subscribe to get secret discount coupons and early festive deal alerts!</p>
                <form id="newsletterForm" class="mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control bg-dark border-secondary text-white" placeholder="Enter your email" required>
                        <button class="btn btn-warning" type="submit"><i class="bi bi-send-fill"></i></button>
                    </div>
                </form>
                <div class="social-icons d-flex gap-2">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center fs-7 text-white-50">
            <p class="mb-2 mb-md-0">&copy; <?= date('Y'); ?> SparkleFest Crackers. All Rights Reserved. Designed for Festive Celebrations.</p>
            <div>
                <a href="<?= BASE_URL; ?>admin/index.php" class="text-warning text-decoration-none me-3"><i class="bi bi-lock-fill me-1"></i> Admin Portal</a>
                <span>Made with <i class="bi bi-heart-fill text-danger"></i> for Fireworks Lovers</span>
            </div>
        </div>
    </div>
</footer>

<!-- Toast Notification Element -->
<div class="toast-container position-fixed bottom-0 end-0 p-3 z-1050">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <span id="toastMessage">Item added to cart!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>

<!-- Custom JS Files -->
<script>
    const BASE_URL = "<?= BASE_URL; ?>";
</script>
<script src="<?= BASE_URL; ?>assets/js/script.js"></script>
<script src="<?= BASE_URL; ?>assets/js/cart.js"></script>
<script src="<?= BASE_URL; ?>assets/js/search.js"></script>
</body>
</html>
