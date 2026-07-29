/**
 * AJAX Shopping Cart Module
 * Online Cracker Shop
 */

$(document).ready(function() {
    
    // Add to Cart Event Handler
    $(document).on('click', '.btn-add-cart', function(e) {
        e.preventDefault();
        const btn = $(this);
        const productId = btn.data('id');
        const quantity = $('#qty_' + productId).val() || 1;

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Adding...');

        $.ajax({
            url: BASE_URL + 'ajax/add_cart.php',
            type: 'POST',
            dataType: 'json',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                btn.prop('disabled', false).html('<i class="bi bi-cart-plus me-1"></i> Add To Cart');
                
                if (response.status === 'success') {
                    $('#navCartBadge').text(response.cart_count);
                    showToast(response.message, 'success');
                } else {
                    showToast(response.message, 'danger');
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="bi bi-cart-plus me-1"></i> Add To Cart');
                showToast('Error connecting to server. Please try again.', 'danger');
            }
        });
    });

    // Update Quantity in Cart Page
    $(document).on('change', '.cart-qty-input', function() {
        const productId = $(this).data('id');
        const newQty = $(this).val();

        if (newQty < 1) return;

        $.ajax({
            url: BASE_URL + 'ajax/update_cart.php',
            type: 'POST',
            dataType: 'json',
            data: {
                product_id: productId,
                quantity: newQty
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#navCartBadge').text(response.cart_count);
                    $('#line_total_' + productId).text(response.line_total);
                    $('#cartSubtotal').text(response.subtotal);
                    $('#cartShipping').text(response.shipping);
                    $('#cartGrandTotal').text(response.grand_total);
                    showToast(response.message, 'info');
                } else {
                    showToast(response.message, 'danger');
                }
            }
        });
    });

    // Remove Item from Cart
    $(document).on('click', '.btn-remove-cart', function(e) {
        e.preventDefault();
        const productId = $(this).data('id');

        if (!confirm("Are you sure you want to remove this item from your cart?")) return;

        $.ajax({
            url: BASE_URL + 'ajax/delete_cart.php',
            type: 'POST',
            dataType: 'json',
            data: { product_id: productId },
            success: function(response) {
                if (response.status === 'success') {
                    $('#cart_row_' + productId).fadeOut(300, function() {
                        $(this).remove();
                        if ($('.cart-item-row').length === 0) {
                            location.reload();
                        }
                    });
                    $('#navCartBadge').text(response.cart_count);
                    $('#cartSubtotal').text(response.subtotal);
                    $('#cartShipping').text(response.shipping);
                    $('#cartGrandTotal').text(response.grand_total);
                    showToast(response.message, 'warning');
                }
            }
        });
    });

});
