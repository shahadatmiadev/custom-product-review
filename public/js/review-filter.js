/**
 * public/js/review-filter.js
 */
jQuery(document).ready(function($) {
    'use strict';
    
    // Function to apply filters
    function applyFilters() {
        var product_id = $('#amrrev_product_id').val();
        var ratings = [];
        var age_range = $('select[name="age_range"]').val();
        var verified_only = $('input[name="verified_only"]').is(':checked') ? '1' : '0';
        
        // Get selected ratings
        $('input[name="rating[]"]:checked').each(function() {
            ratings.push($(this).val());
        });
                
        $.ajax({
            url: amrrev_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'amrrev_filter_reviews',
                nonce: amrrev_ajax.nonce,
                product_id: product_id,
                rating: ratings,
                age_range: age_range,
                verified_only: verified_only
            },
            beforeSend: function() {
                $('#amrrev-reviews-container').html('<div class="amrrev-loading">Loading...</div>');
            },
            success: function(response) {
                if (response.success) {
                    $('#amrrev-reviews-container').html(response.data);
                    $('.amrrev-load-more-container').hide();
                } else {
                    $('#amrrev-reviews-container').html('<div class="amrrev-error">Error loading reviews</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#amrrev-reviews-container').html('<div class="amrrev-error">Error loading reviews. Please try again.</div>');
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

     // Tab switching functionality
    $('.amrrev-tab > div').on('click', function() {
        var tab = $(this).data('tab');
        
        // Update active tab
        $('.amrrev-tab > div').removeClass('amrrev-tab-active');
        $(this).addClass('amrrev-tab-active');
        
        // Show/hide content
        if (tab === 'desc') {
            $('.amrrev-tab-desc-area').show();
            $('.amrrev-tab-review-area').hide();
        } else {
            $('.amrrev-tab-desc-area').hide();
            $('.amrrev-tab-review-area').show();
        }
    });
});