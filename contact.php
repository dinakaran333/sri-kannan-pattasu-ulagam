<?php
$page_title = "Contact Us | SparkleFest Crackers";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$success_msg = $error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_msg = "Please fill in all required fields.";
    } else {
        $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $subject, $message])) {
            $success_msg = "Thank you for reaching out! Our festive desk will respond within 24 hours.";
        } else {
            $error_msg = "Failed to submit message. Please try again.";
        }
    }
}
?>

<div class="py-4 bg-dark text-white text-center mb-5">
    <div class="container">
        <h1 class="fw-bold text-warning">Get In Touch</h1>
        <p class="text-light">Have questions about cracker delivery, bulk orders, or custom combo packs?</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card glass-card p-4 border-0 shadow">
                <h4 class="fw-bold text-dark mb-3"><i class="bi bi-envelope-heart text-danger me-2"></i> Send Us A Message</h4>

                <?php if ($success_msg): ?>
                    <div class="alert alert-success"><?= e($success_msg); ?></div>
                <?php endif; ?>
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger"><?= e($error_msg); ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL; ?>contact.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Your Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Amit Kumar" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. amit@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. 9876543210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subject *</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Wholesale Query" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Your Message *</label>
                            <textarea name="message" rows="5" class="form-control" placeholder="Describe your query or order requirement..." required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger btn-lg rounded-pill fw-bold w-100">
                                <i class="bi bi-send-fill me-2"></i> Submit Inquiry
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Information & Map -->
        <div class="col-lg-5">
            <div class="card glass-card p-4 border-0 shadow mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i> Store Headquarters</h5>
                <p class="text-muted mb-2"><i class="bi bi-building me-2"></i> <strong>Sri Kannan Pattasu Ulagam Retail Ltd.</strong></p>
                <p class="text-muted mb-2"><i class="bi bi-pin-map me-2"></i> 108 Fireworks Avenue, Sivakasi Road, Virudhunagar, Tamil Nadu - 626123</p>
                <p class="text-muted mb-2"><i class="bi bi-telephone me-2"></i> +91 98765 43210 / 04562-220000</p>
                <p class="text-muted mb-0"><i class="bi bi-clock me-2"></i> Mon - Sat: 9:00 AM - 8:00 PM</p>
            </div>

            <!-- Embedded Google Map -->
            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62963.02058319694!2d77.77196603507567!3d9.453303649669527!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b06cee15a9992c7%3A0x6b8f3fb40d12e9b0!2sSivakasi%2C%20Tamil%20Nadu!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" width="100%" height="260" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
