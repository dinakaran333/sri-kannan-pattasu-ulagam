/**
 * Admin Panel Helpers
 * Online Cracker Shop
 */

$(document).ready(function() {
    // Image Preview Helper for product/category upload forms
    $('.image-input-preview').on('change', function() {
        const file = this.files[0];
        const previewTarget = $($(this).data('target'));
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewTarget.attr('src', e.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Confirmation for Delete actions
    $('.btn-confirm-delete').on('click', function(e) {
        if (!confirm("WARNING: Are you sure you want to permanently delete this item?")) {
            e.preventDefault();
        }
    });
});
