/**
 * Global JavaScript Core
 * Online Cracker Shop
 */

$(document).ready(function() {
    console.log("SparkleFest Cracker Shop Loaded.");

    // Initialize Toast Notification Helper
    window.showToast = function(message, type = 'success') {
        const toastEl = $('#liveToast');
        const toastBody = $('#toastMessage');
        
        toastBody.text(message);
        toastEl.removeClass('bg-success bg-danger bg-warning bg-info').addClass('bg-' + type);
        
        const toast = new bootstrap.Toast(toastEl[0]);
        toast.show();
    };

    // Newsletter Form Handler
    $('#newsletterForm').on('submit', function(e) {
        e.preventDefault();
        const email = $(this).find('input[type="email"]').val();
        
        if (email) {
            showToast("Thank you! You've successfully subscribed to Diwali special deals.", "success");
            $(this)[0].reset();
        }
    });
});
