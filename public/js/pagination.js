jQuery(document).ready(function ($) {
    $(document).on('click', '.amrrev-pagination a.page-numbers', function (e) {
        e.preventDefault();

        var page = $(this).data('page');
        var product_id = $('#amrrev_product_id').val();

        var ratings = [];
        $('input[name="rating[]"]:checked').each(function () {
            ratings.push($(this).val());
        });

        var age_range = $('select[name="age_range"]').val();
        var verified_only = $('input[name="verified_only"]').is(':checked') ? '1' : '0';

        $.ajax({
            url: amrrev_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'amrrev_paginate_reviews',
                nonce: amrrev_ajax.pagination_nonce,
                product_id: product_id,
                page: page,
                rating: ratings,
                age_range: age_range,
                verified_only: verified_only
            },
            beforeSend: function () {
                $('#amrrev-reviews-container').html('<div class="amrrev-loading">Loading...</div>');
            },
            success: function (response) {
                if (response.success) {
                    $('#amrrev-reviews-container').html(response.data.reviews);
                    $('.amrrev-pagination').html(response.data.pagination);
                } else {
                    $('#amrrev-reviews-container').html('Error loading reviews');
                }
            }
        });
    });
});
