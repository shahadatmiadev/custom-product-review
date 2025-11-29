/**
 * public/js/review-filter.js
 */
jQuery(document).ready(function($) {
    'use strict';
    
    // Function to apply filters
    function applyFilters() {
        var product_id = $('#cpr_product_id').val();
        var ratings = [];
        var age_range = $('select[name="age_range"]').val();
        var verified_only = $('input[name="verified_only"]').is(':checked') ? '1' : '0';
        
        // Get selected ratings
        $('input[name="rating[]"]:checked').each(function() {
            ratings.push($(this).val());
        });
                
        $.ajax({
            url: cpr_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'cpr_filter_reviews',
                nonce: cpr_ajax.nonce,
                product_id: product_id,
                rating: ratings,
                age_range: age_range,
                verified_only: verified_only
            },
            beforeSend: function() {
                $('#cpr-reviews-container').html('<div class="cpr-loading">Loading...</div>');
            },
            success: function(response) {
                if (response.success) {
                    $('#cpr-reviews-container').html(response.data);
                    $('.cpr-load-more-container').hide();
                } else {
                    $('#cpr-reviews-container').html('<div class="cpr-error">Error loading reviews</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#cpr-reviews-container').html('<div class="cpr-error">Error loading reviews. Please try again.</div>');
            }
        });
    }
    
    // Apply filters when any filter option changes
    $('input[name="rating[]"]').on('change', function() {
        applyFilters();
    });
    
    $('select[name="age_range"]').on('change', function() {
        applyFilters();
    });
    
    $('input[name="verified_only"]').on('change', function() {
        applyFilters();
    });
});