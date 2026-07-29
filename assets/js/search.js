/**
 * AJAX Live Search Module
 * Online Cracker Shop
 */

$(document).ready(function() {
    let searchTimeout = null;

    $('#liveSearchInput').on('keyup input', function() {
        const query = $(this).val().trim();
        const dropdown = $('#searchResultsDropdown');

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            dropdown.addClass('d-none').empty();
            return;
        }

        searchTimeout = setTimeout(function() {
            $.ajax({
                url: BASE_URL + 'ajax/search.php',
                type: 'GET',
                dataType: 'json',
                data: { q: query },
                success: function(response) {
                    dropdown.empty();

                    if (response.length > 0) {
                        response.forEach(function(item) {
                            const html = `
                                <a href="${BASE_URL}product-details.php?slug=${item.slug}" class="dropdown-item d-flex align-items-center py-2 px-3 border-bottom border-secondary text-white">
                                    <img src="${BASE_URL}assets/images/uploads/${item.image}" alt="${item.name}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-white fs-7">${item.name}</div>
                                        <small class="text-warning">${item.offer_price}</small>
                                    </div>
                                </a>
                            `;
                            dropdown.append(html);
                        });
                        dropdown.removeClass('d-none');
                    } else {
                        dropdown.html('<div class="p-3 text-muted text-center fs-7">No fireworks found matching "' + query + '"</div>').removeClass('d-none');
                    }
                }
            });
        }, 250);
    });

    // Close dropdown on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-box-container').length) {
            $('#searchResultsDropdown').addClass('d-none');
        }
    });
});
