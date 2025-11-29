<?php
/**
 * Form Handler Class
 * includes/class-cpr-form-handler.php
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Form_Handler {

    public function __construct() {
        // Form position এর উপর depend করে hook add করা
        add_action( 'init', array( $this, 'setup_form_position' ) );
        add_action( 'init', array( $this, 'handle_form_submission' ) );
    }

    /**
     * Setup Form Position Based on Settings
     */
    public function setup_form_position() {
        $form_position = get_option( 'cpr_form_position', 'after' );

        if ( $form_position == 'before' ) {
            add_action( 'woocommerce_before_single_product_summary', array( $this, 'render_review_form' ), 25 );
        } else {
            add_action( 'woocommerce_after_single_product_summary', array( $this, 'render_review_form' ), 20 );
        }
    }

    /**
     * Render Review Form on WooCommerce Single Product Page
     */
    public function render_review_form() {
        if ( !is_product() ) {
            return;
        }

        global $product;

        if ( did_action( 'cpr_render_form_once' ) ) {
            return;
        }
        do_action( 'cpr_render_form_once' );

        // Get all settings
        $enable_file_upload = get_option( 'cpr_enable_file_upload', '1' );
        $enable_age_range = get_option( 'cpr_enable_age_range', '1' );
        $email_required = get_option( 'cpr_email_required', '1' );
        $title_required = get_option( 'cpr_title_required', '1' );
        $min_rating = get_option( 'cpr_min_rating', '1' );

        // Show all reviews first
        $this->render_all_reviews( $product );
        ?>

        <div id="cpr-review-form-wrapper" class="cpr-review-form-section">
            <h3><?php esc_html_e( 'Write a Review', 'custom-product-reviews' ); ?></h3>

            <?php if ( isset( $_GET['review_submitted'] ) && $_GET['review_submitted'] == '1' ): ?>
                <div class="cpr-success-message">
                    <?php
$auto_approve = get_option( 'cpr_auto_approve', '0' );
        if ( $auto_approve == '1' ) {
            esc_html_e( 'Thank you! Your review has been published.', 'custom-product-reviews' );
        } else {
            esc_html_e( 'Thank you! Your review has been submitted and is pending approval.', 'custom-product-reviews' );
        }
        ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" id="cpr-review-form">
                <?php wp_nonce_field( 'cpr_submit_review', 'cpr_review_nonce' ); ?>

                <input type="hidden" name="cpr_product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">

                <!-- Review Title Field -->
                <p class="cpr-form-field">
                    <label for="cpr_title">
                        <?php esc_html_e( 'Review Title', 'custom-product-reviews' ); ?>
                        <?php if ( $title_required == '1' ): ?>
                            <span class="required">*</span>
                        <?php else: ?>
                            <span class="optional"><?php esc_html_e( '(Optional)', 'custom-product-reviews' ); ?></span>
                        <?php endif; ?>
                    </label>
                    <input type="text"
                           name="cpr_title"
                           id="cpr_title"
                           placeholder="<?php esc_attresc_html_e( 'Enter review title', 'custom-product-reviews' ); ?>"
                           <?php echo $title_required == '1' ? 'required' : ''; ?>>
                </p>

                <!-- Review Description Field -->
                <p class="cpr-form-field">
                    <label for="cpr_content">
                        <?php esc_html_e( 'Review Description', 'custom-product-reviews' ); ?>
                        <span class="required">*</span>
                    </label>
                    <textarea name="cpr_content"
                              id="cpr_content"
                              rows="4"
                              placeholder="<?php esc_attresc_html_e( 'Share your experience with this product', 'custom-product-reviews' ); ?>"
                              required></textarea>
                </p>

                <!-- File Upload Field (Conditional) -->
                <?php if ( $enable_file_upload == '1' ): ?>
                <div class="cpr-form-field drag-file-area">
                    <div class="drag-file-icon">
                        <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/download.svg' ); ?>" alt="">
                    </div>
                    <label class="label">
                        <span class="browse-files">
                            <input type="file" name="cpr_file" class="default-file-input" id="cpr_file_input" accept=".jpg,.jpeg,.png,.pdf">
                            <?php esc_html_e( 'Drag and drop, or', 'custom-product-reviews' ); ?>
                            <span class="browse-files-text"><?php esc_html_e( 'browse', 'custom-product-reviews' ); ?></span>
                            <span><?php esc_html_e( 'your files', 'custom-product-reviews' ); ?></span>
                        </span>
                        <img src="" alt="" id="cpr_file_preview" style="display:none; max-width:70px; margin-left: auto; margin-right: auto;">
                    </label>
                    <div class="cpr-file-format-note">
                        <span><?php esc_html_e( 'Support JPG, PDF, PNG', 'custom-product-reviews' ); ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Star Rating Field -->
                <p class="cpr-form-field">
                    <label>
                        <?php esc_html_e( 'Star Rating', 'custom-product-reviews' ); ?>
                        <span class="required">*</span>
                    </label>
                    <?php if ( $min_rating > 1 ): ?>
                        <span class="cpr-min-rating-note">
                            <?php printf(
                                /* translators: %d: minimum number of stars required for a review */
                                esc_html__( '(Minimum %d stars required)', 'custom-product-reviews' ),
                                intval( $min_rating )
                            ); ?>
                        </span>

                    <?php endif; ?>
                    <div class="cpr-star-rating" data-min-rating="<?php echo esc_attr( $min_rating ); ?>">
                        <span data-value="1">&#9733;</span>
                        <span data-value="2">&#9733;</span>
                        <span data-value="3">&#9733;</span>
                        <span data-value="4">&#9733;</span>
                        <span data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" name="cpr_rating" id="cpr_rating" required>
                </p>

                <!-- Name Field -->
                <p class="cpr-form-field">
                    <label for="cpr_name">
                        <?php esc_html_e( 'Name', 'custom-product-reviews' ); ?>
                        <span class="required">*</span>
                    </label>
                    <input type="text"
                           name="cpr_name"
                           id="cpr_name"
                           placeholder="<?php esc_attresc_html_e( 'Enter your name', 'custom-product-reviews' ); ?>"
                           required>
                </p>

                <!-- Email Field -->
                <p class="cpr-form-field">
                    <label for="cpr_email">
                        <?php esc_html_e( 'Email Address', 'custom-product-reviews' ); ?>
                        <?php if ( $email_required == '1' ): ?>
                            <span class="required">*</span>
                        <?php else: ?>
                            <span class="optional"><?php esc_html_e( '(Optional)', 'custom-product-reviews' ); ?></span>
                        <?php endif; ?>
                    </label>
                    <input type="email"
                           name="cpr_email"
                           id="cpr_email"
                           placeholder="<?php esc_attresc_html_e( 'Enter your email', 'custom-product-reviews' ); ?>"
                           <?php echo $email_required == '1' ? 'required' : ''; ?>>
                </p>

                <!-- Age Range Field (Conditional) -->
                <?php if ( $enable_age_range == '1' ): ?>
                <p class="cpr-form-field">
                    <label class="cpr-age-range-label">
                        <?php esc_html_e( 'Age Range', 'custom-product-reviews' ); ?>
                        <span class="required">*</span>
                    </label>
                    <div class="cpr-age-range">
                        <button type="button" class="age-btn" data-value="under-18"><?php esc_html_e( 'Under 18', 'custom-product-reviews' ); ?></button>
                        <button type="button" class="age-btn" data-value="18-24">18 - 24</button>
                        <button type="button" class="age-btn" data-value="25-34">25 - 34</button>
                        <button type="button" class="age-btn" data-value="35-44">35 - 44</button>
                        <button type="button" class="age-btn" data-value="45-54">45 - 54</button>
                        <button type="button" class="age-btn" data-value="55-64">55 - 64</button>
                        <button type="button" class="age-btn" data-value="65+">65+</button>
                    </div>
                    <input type="hidden" name="cpr_age_range" id="cpr_age_range" required>
                </p>
                <?php endif; ?>

                <!-- Terms Notice -->
                <p class="cpr-terms">
                    <label><?php esc_html_e( "By continuing you agree to JOURIE'S Terms and Conditions", 'custom-product-reviews' ); ?></label>
                </p>

                <!-- Submit Button -->
                <p class="submit-wrapper">
                    <input type="submit" name="cpr_submit_review" value="<?php esc_attresc_html_e( 'Submit Review', 'custom-product-reviews' ); ?>" class="cpr-submit-btn">
                </p>
            </form>
        </div>
        <?php
}

    /**
     * Handle Form Submission with All Settings Check
     */
    public function handle_form_submission() {
        if ( !isset( $_POST['cpr_submit_review'] ) ) {
            return;
        }

        // Verify nonce
        if ( !isset( $_POST['cpr_review_nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpr_review_nonce'] ) ), 'cpr_submit_review' ) ) {
            wp_die( esc_html__( 'Security check failed', 'custom-product-reviews' ) );
        }

        $product_id = isset( $_POST['cpr_product_id'] ) ? intval( $_POST['cpr_product_id'] ) : 0;
        if ( !$product_id ) {
            wp_die( esc_html__( 'Invalid product', 'custom-product-reviews' ) );
        }

        // Get form data
        $title = isset( $_POST['cpr_title'] ) ? sanitize_text_field( wp_unslash( $_POST['cpr_title'] ) ) : '';
        $content = isset( $_POST['cpr_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cpr_content'] ) ) : '';
        $rating = isset( $_POST['cpr_rating'] ) ? intval( $_POST['cpr_rating'] ) : 0;
        $name = isset( $_POST['cpr_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cpr_name'] ) ) : '';
        $email = isset( $_POST['cpr_email'] ) ? sanitize_email( wp_unslash( $_POST['cpr_email'] ) ) : '';
        $age_range = isset( $_POST['cpr_age_range'] ) ? sanitize_text_field( wp_unslash( $_POST['cpr_age_range'] ) ) : '';

        // Get all settings
        $auto_approve = get_option( 'cpr_auto_approve', '0' );
        $min_rating = get_option( 'cpr_min_rating', '1' );
        $enable_moderation = get_option( 'cpr_enable_moderation', '0' );
        $bad_words = get_option( 'cpr_bad_words', '' );
        $enable_email = get_option( 'cpr_enable_email_notification', '1' );
        $title_required = get_option( 'cpr_title_required', '1' );
        $email_required = get_option( 'cpr_email_required', '1' );

        // Validate required fields based on settings
        if ( $title_required == '1' && empty( $title ) ) {
            wp_die( esc_html__( 'Review title is required.', 'custom-product-reviews' ) );
        }

        if ( $email_required == '1' && empty( $email ) ) {
            wp_die( esc_html__( 'Email address is required.', 'custom-product-reviews' ) );
        }

        if ( empty( $content ) ) {
            wp_die( esc_html__( 'Review description is required.', 'custom-product-reviews' ) );
        }

        // Validate minimum rating
        if ( $rating < $min_rating ) {
            wp_die( 
                sprintf( 
                    /* translators: %d: minimum rating number */
                    esc_html__( 'Minimum rating of %d stars is required.', 'custom-product-reviews' ), 
                    intval( $min_rating ) 
                ) 
            );
        }

        // Check bad words if moderation is enabled
        if ( $enable_moderation == '1' && !empty( $bad_words ) ) {
            $bad_words_array = array_map( 'trim', explode( ',', strtolower( $bad_words ) ) );
            $review_text = strtolower( $title . ' ' . $content );

            foreach ( $bad_words_array as $bad_word ) {
                if ( !empty( $bad_word ) && strpos( $review_text, $bad_word ) !== false ) {
                    wp_die( esc_html__( 'Your review contains inappropriate content and cannot be submitted.', 'custom-product-reviews' ) );
                }
            }
        }

        // Determine post status based on auto_approve setting
        $post_status = ( $auto_approve == '1' ) ? 'publish' : 'pending';

        // Use title or generate from content if title not provided
        $post_title = !empty( $title ) ? $title : wp_trim_words( $content, 5, '...' );

        // Insert review post
        $post = array(
            'post_type'    => 'cpr_review',
            'post_title'   => $post_title,
            'post_content' => $content,
            'post_status'  => $post_status,
            'post_author'  => 0,
        );
        $review_id = wp_insert_post( $post );

        if ( !$review_id ) {
            wp_die( esc_html__( 'Failed to submit review. Please try again.', 'custom-product-reviews' ) );
        }

        // Save meta data
        update_post_meta( $review_id, '_cpr_rating', $rating );
        update_post_meta( $review_id, '_cpr_product_id', $product_id );
        update_post_meta( $review_id, '_cpr_name', $name );
        update_post_meta( $review_id, '_cpr_email', $email );
        update_post_meta( $review_id, '_cpr_age_range', $age_range );
        update_post_meta( $review_id, '_cpr_verified_buyer', '1' );

        // Handle file upload if enabled
        if ( get_option( 'cpr_enable_file_upload', '1' ) == '1' && !empty( $_FILES['cpr_file']['name'] ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';

            $uploaded = wp_handle_upload( $_FILES['cpr_file'], array(
                'test_form' => false,
            ) );

            if ( !isset( $uploaded['error'] ) ) {
                update_post_meta( $review_id, '_cpr_file_url', $uploaded['url'] );
            }
        }

        // Send email notification if enabled
        if ( $enable_email == '1' ) {
            $this->send_review_notification( $review_id, $product_id );
        }

        // Redirect back to product page with success message
        wp_safe_redirect( add_query_arg( 'review_submitted', '1', get_permalink( $product_id ) ) );
        exit;
    }

    /**
     * Send Email Notification to Admin
     */
    private function send_review_notification( $review_id, $product_id ) {
        $admin_email = get_option( 'cpr_admin_email', get_option( 'admin_email' ) );

        if ( empty( $admin_email ) ) {
            return;
        }

        $product = wc_get_product( $product_id );
        if ( !$product ) {
            return;
        }

        $review_title = get_the_title( $review_id );
        $review_content = get_post_field( 'post_content', $review_id );
        $reviewer_name = get_post_meta( $review_id, '_cpr_name', true );
        $reviewer_email = get_post_meta( $review_id, '_cpr_email', true );
        $rating = get_post_meta( $review_id, '_cpr_rating', true );
        $auto_approve = get_option( 'cpr_auto_approve', '0' );

        /* translators: %s: product name */
        $subject = sprintf( __( 'New Review Submitted: %s', 'custom-product-reviews' ), $product->get_name() );

        /* translators: %s: product name */
        $message = sprintf(
            __( "A new review has been submitted for: %s\n\n", 'custom-product-reviews' ),
            $product->get_name()
        );


        /* translators: %s: reviewer name */
        $message .= sprintf( __( "Reviewer: %s\n", 'custom-product-reviews' ), $reviewer_name );


        if ( !empty( $reviewer_email ) ) {
            /* translators: %s: reviewer email address */
            $message .= sprintf( __( "Email: %s\n", 'custom-product-reviews' ), $reviewer_email );
        }

        $message .= sprintf( 
            /* translators: %s: star rating (1-5) */
            esc_html__( "Rating: %s stars\n", 'custom-product-reviews' ), 
            intval( $rating ) 
        );

        $message .= sprintf( 
            /* translators: %s: review title */
            esc_html__( "Review Title: %s\n", 'custom-product-reviews' ), 
            sanitize_text_field( $review_title ) 
        );

        $message .= sprintf( 
            /* translators: %s: review content */
            esc_html__( "Review: %s\n\n", 'custom-product-reviews' ), 
            sanitize_textarea_field( $review_content ) 
        );

        if ( $auto_approve == '1' ) {
            $message .= esc_html__( "Status: Published (Auto-approved)\n\n", 'custom-product-reviews' );
        } else {
            $message .= esc_html__( "Status: Pending Approval\n\n", 'custom-product-reviews' );
        }
        $message .= sprintf(
            /* translators: %s: URL to review management page */
            __( "View and manage this review:\n%s", 'custom-product-reviews' ),
            esc_url( admin_url( 'admin.php?page=cpr-reviews' ) )
        );

        wp_mail( $admin_email, $subject, $message );
    }

    /**
     * Render All Reviews Section
     */
    public function render_all_reviews( $product ) {
        $product_id = $product->get_id();
        $show_filters = get_option( 'cpr_show_filters', '1' );
        $initial_reviews = get_option( 'cpr_reviews_per_page', '10' );
        $load_more_count = 3;
        ?>
         <div id="cpr-all-reviews-wrapper" class="cpr-reviews-section">
            <h3><?php esc_html_e( 'Customer Reviews', 'custom-product-reviews' ); ?></h3>
            
            <!-- Hidden input for product ID -->
            <input type="hidden" id="cpr_product_id" value="<?php echo esc_attr( $product_id ); ?>">
            <input type="hidden" id="cpr_initial_reviews" value="<?php echo esc_attr( $initial_reviews ); ?>">
            <input type="hidden" id="cpr_load_more_count" value="<?php echo esc_attr( $load_more_count ); ?>">
            
            <!-- Filters (Conditional) -->
            <?php if ( $show_filters == '1' ) : ?>
            <?php
            $filter = new CPR_Filter();
            $filter->render_filter_form();
            ?>
            <?php endif; ?>
            
            <!-- Reviews Container -->
            <div id="cpr-reviews-container">
                <?php
                // Get total reviews count
                $total_args = array(
                    'post_type'      => 'cpr_review',
                    'post_status'    => 'publish',
                    'meta_query'     => array(
                        array(
                            'key'     => '_cpr_product_id',
                            'value'   => $product_id,
                            'compare' => '=',
                        ),
                    ),
                    'posts_per_page' => -1,
                );
                
                $total_query = new WP_Query( $total_args );
                $total_reviews = $total_query->found_posts;
                
                // Get initial reviews
                $args = array(
                    'post_type'      => 'cpr_review',
                    'post_status'    => 'publish',
                    'posts_per_page' => $initial_reviews,
                    'meta_query'     => array(
                        array(
                            'key'     => '_cpr_product_id',
                            'value'   => $product_id,
                            'compare' => '=',
                        ),
                    ),
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                
                $review_query = new WP_Query( $args );
                
                if ( $review_query->have_posts() ) :
                    while ( $review_query->have_posts() ) : $review_query->the_post();
                        $this->render_single_review( get_the_ID() );
                    endwhile;
                else :
                    echo '<div class="cpr-no-reviews"><p>' . esc_html__( 'No reviews yet. Be the first to review this product!', 'custom-product-reviews' ) . '</p></div>';
                endif;
                
                wp_reset_postdata();
                ?>
            </div>
            
            <!-- Load More Button -->
            <?php if ( $total_reviews > $initial_reviews ) : ?>
            <div class="cpr-load-more-container">
                <button id="cpr-load-more-btn" class="cpr-load-more-btn">
                    <?php esc_html_e( 'More Reviews', 'custom-product-reviews' ); ?>
                </button>
                <div class="cpr-loading-spinner" style="display: none;">
                    <div class="spinner"></div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Hidden field for total reviews -->
            <input type="hidden" id="cpr_total_reviews" value="<?php echo esc_attr( $total_reviews ); ?>">
            <input type="hidden" id="cpr_loaded_reviews" value="<?php echo esc_attr( min( $initial_reviews, $total_reviews ) ); ?>">
        </div>
    
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#cpr-load-more-btn').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var spinner = $('.cpr-loading-spinner');
                    var container = $('#cpr-reviews-container');
                    var product_id = $('#cpr_product_id').val();
                    var initial_reviews = parseInt($('#cpr_initial_reviews').val());
                    var load_more_count = parseInt($('#cpr_load_more_count').val());
                    var total_reviews = parseInt($('#cpr_total_reviews').val());
                    var loaded_reviews = parseInt($('#cpr_loaded_reviews').val());
                    
                    // Get filter values
                    var ratings = [];
                    $('input[name="rating[]"]:checked').each(function() {
                        ratings.push($(this).val());
                    });
                    
                    var age_range = $('select[name="age_range"]').val();
                    var verified_only = $('input[name="verified_only"]').is(':checked') ? '1' : '0';
                    
                    // Show loading spinner
                    button.hide();
                    spinner.show();
                    
                    $.ajax({
                        url: '<?php echo esc_url( admin_url('admin-ajax.php' ) ); ?>',
                        type: 'POST',
                        data: {
                            action: 'cpr_load_more_reviews',
                            nonce: '<?php echo esc_js( wp_create_nonce( 'cpr_load_more_nonce' ) ); ?>',
                            product_id: product_id,
                            offset: loaded_reviews,
                            count: load_more_count,
                            rating: ratings,
                            age_range: age_range,
                            verified_only: verified_only
                        },
                        success: function(response) {
                            if (response.success) {
                                // Append new reviews
                                container.append(response.data.reviews);
                                
                                // Update loaded reviews count
                                var new_loaded_count = loaded_reviews + response.data.loaded_count;
                                $('#cpr_loaded_reviews').val(new_loaded_count);
                                
                                // Hide button if all reviews are loaded
                                if (new_loaded_count >= total_reviews) {
                                    $('.cpr-load-more-container').hide();
                                }
                            } else {
                                alert('Error loading more reviews. Please try again.');
                            }
                            
                            // Show button and hide spinner
                            button.show();
                            spinner.hide();
                        },
                        error: function() {
                            alert('Error loading more reviews. Please try again.');
                            button.show();
                            spinner.hide();
                        }
                    });
                });
            });
        </script>
        <?php
    }

    /**
     * Render Custom Pagination
     */
    private function render_custom_pagination( $total_pages, $current_page, $product_id ) {
        echo '<div class="cpr-pagination">';
        
        // Previous button
        if ( $current_page > 1 ) {
            echo '<a class="prev page-numbers" href="#" data-page="' . esc_attr( $current_page - 1 ) . '">&laquo; ' . esc_html__( 'Previous', 'custom-product-reviews' ) . '</a>';
        }
        
        // Page numbers
        for ( $i = 1; $i <= $total_pages; $i++ ) {
            if ( $i == $current_page ) {
                echo '<span class="page-numbers current">' . esc_html( $i ) . '</span>';
            } else {
                echo '<a class="page-numbers" href="#" data-page="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</a>';
            }
        }
        
        // Next button
        if ( $current_page < $total_pages ) {
            echo '<a class="next page-numbers" href="#" data-page="' . esc_attr( $current_page + 1 ) . '">' . esc_html__( 'Next', 'custom-product-reviews' ) . ' &raquo;</a>';
        }
        
        echo '</div>';
        
        // Add JavaScript for AJAX pagination
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.cpr-pagination a.page-numbers').on('click', function(e) {
                e.preventDefault();
                
                var page = $(this).data('page');
                var product_id = $('#cpr_product_id').val();
                
                // Get filter values
                var ratings = [];
                $('input[name="rating[]"]:checked').each(function() {
                    ratings.push($(this).val());
                });
                
                var age_range = $('select[name="age_range"]').val();
                var verified_only = $('input[name="verified_only"]').is(':checked') ? '1' : '0';
                
                $.ajax({
                    url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                    type: 'POST',
                    data: {
                        action: 'cpr_paginate_reviews',
                        nonce: '<?php echo esc_js( wp_create_nonce( 'cpr_pagination_nonce' ) ); ?>',
                        product_id: product_id,
                        page: page,
                        rating: ratings,
                        age_range: age_range,
                        verified_only: verified_only
                    },
                    beforeSend: function() {
                        $('#cpr-reviews-container').html('<div class="cpr-loading">Loading...</div>');
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#cpr-reviews-container').html(response.data.reviews);
                            $('.cpr-pagination').html(response.data.pagination);
                            
                            // Update URL without page reload
                            var url = new URL(window.location);
                            url.searchParams.set('cpr_page', page);
                            window.history.pushState({}, '', url);
                        } else {
                            $('#cpr-reviews-container').html('<div class="cpr-error">Error loading reviews</div>');
                        }
                    },
                    error: function() {
                        $('#cpr-reviews-container').html('<div class="cpr-error">Error loading reviews. Please try again.</div>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Render Single Review with Display Settings
     */
    private function render_single_review( $review_id ) {
        $product_id = get_post_meta( $review_id, '_cpr_product_id', true );
        $file_url = get_post_meta( $review_id, '_cpr_file_url', true );
        $rating = get_post_meta( $review_id, '_cpr_rating', true );
        $reviewer_name = get_post_meta( $review_id, '_cpr_name', true );
        $reviewer_age = get_post_meta( $review_id, '_cpr_age_range', true );
        $verified = get_post_meta( $review_id, '_cpr_verified_buyer', true );

        // Get display settings
        $show_verified_badge = get_option( 'cpr_show_verified_badge', '1' );
        $date_format = get_option( 'cpr_date_format', 'j/n/y' );
        $enable_age_range = get_option( 'cpr_enable_age_range', '1' );
        $filled_star_color = get_option( 'cpr_filled_star_color', '#ffc107' );
        $empty_star_color = get_option( 'cpr_empty_star_color', '#dddddd' );

        ?>
        <div class="cpt-review-full-box">
            <div class="cpt-review-box-one">
                <div class="cpt-name"><?php echo esc_html( $reviewer_name ); ?></div>

                <?php if ( $show_verified_badge == '1' && $verified == '1' ): ?>
                <div class="cpt-verify-buyer">
                    <span><?php esc_html_e( 'Verified Buyer', 'custom-product-reviews' ); ?></span>
                    <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/verify-buyer.svg' ); ?>" alt="verify-buyer">

                </div>
                <?php endif; ?>

                <?php if ( $enable_age_range == '1' && !empty( $reviewer_age ) ): ?>
                <div class="cpt-age-range">
                    <span><?php esc_html_e( 'Age Range:', 'custom-product-reviews' ); ?></span>
                    <span><?php echo esc_html( $reviewer_age ); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="cpt-review-box-two">
                <div class="cpt-review-date">
                    <div class="cpt-review-count" style="color: <?php echo esc_attr( $filled_star_color ); ?>;">
                        <?php
// Display filled stars
        for ( $i = 1; $i <= 5; $i++ ) {
            if ( $i <= intval( $rating ) ) {
                echo '<span style="color: ' . esc_attr( $filled_star_color ) . ';">★</span>';
            } else {
                echo '<span style="color: ' . esc_attr( $empty_star_color ) . ';">☆</span>';
            }
        }
        ?>
                    </div>
                    <div class="cpt-date"><?php echo get_the_date( $date_format ); ?></div>
                </div>

                <div class="cpt-review-box-content-image">
                    <div class="cpt-review-content-td">
                        <div class="cpt-review-title">
                        <strong><?php echo esc_html( get_the_title() ); ?></strong>
                        </div>

                        <div class="cpt-review-content">
                            <span><?php echo esc_html( get_the_content() ); ?></span>
                        </div>
                    </div>

                    <?php if ( !empty( $file_url ) ): ?>
                    <div class="cpt-review-image">
                        <img src="<?php echo esc_url( $file_url ); ?>" alt="Review attachment" style="max-width: 50px; height: auto;">
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
}
}

// Initialize Form Handler
new CPR_Form_Handler();