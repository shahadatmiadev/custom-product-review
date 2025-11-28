
jQuery(document).ready(function ($) {
  'use strict';

  $('.cpr-approve-btn').on('click', function (e) {
    e.preventDefault();
    var button = $(this);
    const reviewId = button.data('review-id');
    
    if (!confirm('Are you sure you want to approve this review?')) {
      return;
    }
    var originalHTML = button.html();
    button.html('Approving...');

    $.ajax({
       url: cpr_admin_ajax.ajax_url,
       type: 'POST',
       data: {
           action: 'cpr_approve_review',
           review_id: reviewId,
           nonce: cpr_admin_ajax.nonce
       },
       success: function(response) {
            if (response.success) {
                let popup = $(`
                    <div class="cpr-popup-message">
                        ${response.data.message}
                    </div>
                `);

                $('body').append(popup);

                setTimeout(function () {
                    popup.fadeOut(400, function () {
                        popup.remove();
                        location.reload(); 
                    });
                }, 1500);

            } else {
                alert('Error: ' + response.data.message);
                button.prop('disabled', false).html(originalHTML);
            }
        },
    });
    
  });

  $('.cpr-reject-btn').on('click', function (e) {
    e.preventDefault();
    var button = $(this);
    const reviewId = button.data('review-id');
    
    if (!confirm('Are you sure you want to reject this review?')) {
      return;
    }
    var originalHTML = button.html();
    button.html('Rejecting...');

    $.ajax({
       url: cpr_admin_ajax.ajax_url,
       type: 'POST',
       data: {
           action: 'cpr_reject_review',
           review_id: reviewId,
           nonce: cpr_admin_ajax.nonce
       },
       success: function(response) {
            if (response.success) {
                let popup = $(`
                    <div class="cpr-popup-message">
                        ${response.data.message}
                    </div>
                `);

                $('body').append(popup);

                setTimeout(function () {
                    popup.fadeOut(400, function () {
                        popup.remove();
                        location.reload(); 
                    });
                }, 1500);

            } else {
                alert('Error: ' + response.data.message);
                button.prop('disabled', false).html(originalHTML);
            }
        },
    });
    
  });

  $('.cpr-delete-btn').on('click', function (e) {
    e.preventDefault();
    var button = $(this);
    const reviewId = button.data('review-id');
    
    if (!confirm('Are you sure you want to delete this review?')) {
      return;
    }
    var originalHTML = button.html();
    button.html('Deleting...');

    $.ajax({
       url: cpr_admin_ajax.ajax_url,
       type: 'POST',
       data: {
           action: 'cpr_delete_review',
           review_id: reviewId,
           nonce: cpr_admin_ajax.nonce
       },
       success: function(response) {
            if (response.success) {
                let popup = $(`
                    <div class="cpr-popup-message">
                        ${response.data.message}
                    </div>
                `);

                $('body').append(popup);

                setTimeout(function () {
                    popup.fadeOut(400, function () {
                        popup.remove();
                        location.reload(); 
                    });
                }, 1500);

            } else {
                alert('Error: ' + response.data.message);
                button.prop('disabled', false).html(originalHTML);
            }
        },
    });
    
  });

});
