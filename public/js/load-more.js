jQuery(document).ready(function ($) {
    $('#amrrev-load-more-btn').on('click', function (e) {
        e.preventDefault();

        var button = $(this);
        var spinner = $('.amrrev-loading-spinner');
        var container = $('#amrrev-reviews-container');

        var product_id = $('#amrrev_product_id').val();
        var load_more_count = parseInt($('#amrrev_load_more_count').val());
        var total_reviews = parseInt($('#amrrev_total_reviews').val());
        var loaded_reviews = parseInt($('#amrrev_loaded_reviews').val());

        var ratings = [];
        $('input[name="rating[]"]:checked').each(function () {
            ratings.push($(this).val());
        });

        var age_range = $('select[name="age_range"]').val();
        var verified_only = $('input[name="verified_only"]').is(':checked') ? '1' : '0';

        button.hide();
        spinner.show();

        $.ajax({
            url: amrrev_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'amrrev_load_more_reviews',
                nonce: amrrev_ajax.load_more_nonce,
                product_id: product_id,
                offset: loaded_reviews,
                count: load_more_count,
                rating: ratings,
                age_range: age_range,
                verified_only: verified_only
            },
            success: function (response) {
                if (response.success) {
                    container.append(response.data.reviews);

                    var new_loaded_count = loaded_reviews + response.data.loaded_count;
                    $('#amrrev_loaded_reviews').val(new_loaded_count);

                    if (new_loaded_count >= total_reviews) {
                        $('.amrrev-load-more-container').hide();
                    }
                } else {
                    alert('Error loading more reviews.');
                }

                button.show();
                spinner.hide();
            },
            error: function () {
                alert('AJAX error.');
                button.show();
                spinner.hide();
            }
        });
    });
});
