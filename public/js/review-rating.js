/**
 * public/js/review-rating.js
 */
jQuery(document).ready(function($) {
    'use strict';
    
    // Star Rating Functionality
    $('.cpr-star-rating span').on('click', function() {
        var rating = $(this).data('value');
        var minRating = $('.cpr-star-rating').data('min-rating') || 1;
        
        // Check minimum rating requirement
        if (rating < minRating) {
            alert('Minimum ' + minRating + ' stars required!');
            return;
        }
        
        $('#cpr_rating').val(rating);
        
        // Remove selected class from all stars
        $('.cpr-star-rating span').removeClass('selected');
        
        // Add selected class to clicked star and all previous stars
        $(this).addClass('selected').prevAll().addClass('selected');
    });
    
    // Star hover effect
    $('.cpr-star-rating span').hover(
        function() {
            var rating = $(this).data('value');
            $(this).addClass('hover').prevAll().addClass('hover');
        },
        function() {
            $('.cpr-star-rating span').removeClass('hover');
        }
    );
    
    // Age Range Button Functionality
    $('.cpr-age-range .age-btn').on('click', function() {
        var ageValue = $(this).data('value');
        
        // Remove selected class from all buttons
        $('.cpr-age-range .age-btn').removeClass('selected');
        
        // Add selected class to clicked button
        $(this).addClass('selected');
        
        // Set hidden input value
        $('#cpr_age_range').val(ageValue);
    });
    
    // File Upload Preview
    $('#cpr_file_input').on('change', function(e) {
        var file = e.target.files[0];
        
        if (file) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                if (file.type.match('image.*')) {
                    $('#cpr_file_preview').attr('src', e.target.result).show();
                }
            };
            
            reader.readAsDataURL(file);
        }
    });
    
    // Form Validation
    $('#cpr-review-form').on('submit', function(e) {
        var rating = $('#cpr_rating').val();
        var minRating = $('.cpr-star-rating').data('min-rating') || 1;
        
        // Check if rating is selected
        if (!rating) {
            e.preventDefault();
            alert('Please select a star rating!');
            return false;
        }
        
        // Check minimum rating
        if (parseInt(rating) < parseInt(minRating)) {
            e.preventDefault();
            alert('Minimum ' + minRating + ' stars required!');
            return false;
        }
        
        // Check age range if field exists
        if ($('#cpr_age_range').length && !$('#cpr_age_range').val()) {
            e.preventDefault();
            alert('Please select your age range!');
            return false;
        }
        
        return true;
    });
    
    // Apply star colors from settings
    if (typeof cpr_settings !== 'undefined') {
        var emptyColor = cpr_settings.empty_star_color || '#dddddd';
        var filledColor = cpr_settings.filled_star_color || '#ffc107';
        
        // Apply to rating form stars
        $('.cpr-star-rating span').css('color', emptyColor);
        $('.cpr-star-rating span.selected').css('color', filledColor);
        
        // Apply to display stars
        $('.cpt-review-count').each(function() {
            $(this).find('span').each(function() {
                var starText = $(this).text();
                if (starText === '★') {
                    $(this).css('color', filledColor);
                } else if (starText === '☆') {
                    $(this).css('color', emptyColor);
                }
            });
        });
    }
});