<?php
/**
 * Form Handler Class
 * includes/class-amrrev-form-handler.php
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class AMRREV_Form_Handler {

    public function __construct() {
        // Form position এর উপর depend করে hook add করা
        add_action( 'init', array( $this, 'setup_form_position' ) );
        add_action( 'init', array( $this, 'handle_form_submission' ) );
    }

    /**
     * Setup Form Position Based on Settings
     */
    public function setup_form_position() {
        $form_position = get_option( 'amrrev_form_position', 'after' );

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

        if ( did_action( 'amrrev_render_form_once' ) ) {
            return;
        }
        do_action( 'amrrev_render_form_once' );

        // Get all settings
        $enable_file_upload = get_option( 'amrrev_enable_file_upload', '1' );
        $enable_age_range = get_option( 'amrrev_enable_age_range', '1' );
        $email_required = get_option( 'amrrev_email_required', '1' );
        $title_required = get_option( 'amrrev_title_required', '1' );
        $min_rating = get_option( 'amrrev_min_rating', '1' );
        
        // Get product description - try long description first, then short
        $product_description = $product->get_description();
        if ( empty( $product_description ) ) {
            $product_description = $product->get_short_description();
        }
        // Apply content filters to process shortcodes and formatting
        $product_description = apply_filters( 'the_content', $product_description );

        ?>

        <div class="amrrev-tab">
            <div class="amrrev-tab-desc " data-tab="desc"><?php esc_html_e( 'Description', 'amrrev-product-reviews-for-woocommerce' ); ?></div>
            <div class="amrrev-tab-rev amrrev-tab-active" data-tab="rev"><?php esc_html_e( 'Review', 'amrrev-product-reviews-for-woocommerce' ); ?></div>
        </div>

        <div class="amrrev-tab-desc-area" style="display: none">
            <div class="amrrev-product-description">
                <?php
                if ( ! empty( $product_description ) ) {
                    echo wp_kses_post( $product_description );
                } else {
                    echo '<p>' . esc_html__( 'No product description available.', 'amrrev-product-reviews-for-woocommerce' ) . '</p>';
                }
                ?>
            </div>
        </div>

        <div class="amrrev-tab-review-area">
            <?php
            // Show all reviews first
            $this->render_all_reviews( $product );
            ?>

            <div id="amrrev-review-form-wrapper" class="amrrev-review-form-section">
                <h3><?php esc_html_e( 'Write a Review', 'amrrev-product-reviews-for-woocommerce' ); ?></h3>

                <?php if ( isset( $_GET['review_submitted'] ) && $_GET['review_submitted'] == '1' ): ?>
                    <div class="amrrev-success-message">
                        <?php
                        $auto_approve = get_option( 'amrrev_auto_approve', '0' );
                        if ( $auto_approve == '1' ) {
                            esc_html_e( 'Thank you! Your review has been published.', 'amrrev-product-reviews-for-woocommerce' );
                        } else {
                            esc_html_e( 'Thank you! Your review has been submitted and is pending approval.', 'amrrev-product-reviews-for-woocommerce' );
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" id="amrrev-review-form">
                    <?php wp_nonce_field( 'amrrev_submit_review', 'amrrev_review_nonce' ); ?>

                    <input type="hidden" name="amrrev_product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">

                    <!-- Review Title Field -->
                    <p class="amrrev-form-field">
                        <label for="amrrev_title">
                            <?php esc_html_e( 'Review Title', 'amrrev-product-reviews-for-woocommerce' ); ?>
                            <?php if ( $title_required == '1' ): ?>
                                <span class="required">*</span>
                            <?php else: ?>
                                <span class="optional"><?php esc_html_e( '(Optional)', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
                            <?php endif; ?>
                        </label>
                        <input type="text"
                            name="amrrev_title"
                            id="amrrev_title"
                            placeholder="<?php esc_attr_e( 'Enter review title', 'amrrev-product-reviews-for-woocommerce' ); ?>"
                            <?php echo $title_required == '1' ? 'required' : ''; ?>>
                    </p>

                    <!-- Review Description Field -->
                    <p class="amrrev-form-field">
                        <label for="amrrev_content">
                            <?php esc_html_e( 'Review Description', 'amrrev-product-reviews-for-woocommerce' ); ?>
                            <span class="required">*</span>
                        </label>
                        <textarea name="amrrev_content"
                                id="amrrev_content"
                                rows="4"
                                placeholder="<?php esc_attr_e( 'Share your experience with this product', 'amrrev-product-reviews-for-woocommerce' ); ?>"
                                required></textarea>
                    </p>

                    <!-- File Upload Field (Conditional) -->
                    <?php if ( $enable_file_upload == '1' ): ?>
                    <div class="amrrev-form-field drag-file-area">
                        <div class="drag-file-icon">
                            <img src="<?php echo esc_url( AMRREV_ASSETS_URL . 'images/download.svg' ); ?>" alt="">
                        </div>
                        <label class="label">
                            <span class="browse-files">
                                <input type="file" name="amrrev_file" class="default-file-input" id="amrrev_file_input" accept=".jpg,.jpeg,.png,.pdf">
                                <?php esc_html_e( 'Drag and drop, or', 'amrrev-product-reviews-for-woocommerce' ); ?>
                                <span class="browse-files-text"><?php esc_html_e( 'browse', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
                                <span><?php esc_html_e( 'your files', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
                            </span>
                            <img src="" alt="" id="amrrev_file_preview" style="display:none; max-width:70px; margin-left: auto; margin-right: auto;">
                        </label>
                        <div class="amrrev-file-format-note">
                            <span><?php esc_html_e( 'Support JPG, PDF, PNG', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Star Rating Field -->
                    <p class="amrrev-form-field">
                        <label>
                            <?php esc_html_e( 'Star Rating', 'amrrev-product-reviews-for-woocommerce' ); ?>
                            <span class="required">*</span>
                        </label>
                        <?php if ( $min_rating > 1 ): ?>
                            <span class="amrrev-min-rating-note">
                                <?php printf(
                                    /* translators: %d: minimum number of stars required for a review */
                                    esc_html__( '(Minimum %d stars required)', 'amrrev-product-reviews-for-woocommerce' ),
                                    intval( $min_rating )
                                ); ?>
                            </span>

                        <?php endif; ?>
                        <div class="amrrev-star-rating" data-min-rating="<?php echo esc_attr( $min_rating ); ?>">
                            <span data-value="1">&#9733;</span>
                            <span data-value="2">&#9733;</span>
                            <span data-value="3">&#9733;</span>
                            <span data-value="4">&#9733;</span>
                            <span data-value="5">&#9733;</span>
                        </div>
                        <input type="hidden" name="amrrev_rating" id="amrrev_rating" required>
                    </p>

                    <!-- Name Field -->
                    <p class="amrrev-form-field">
                        <label for="amrrev_name">
                            <?php esc_html_e( 'Name', 'amrrev-product-reviews-for-woocommerce' ); ?>
                            <span class="required">*</span>
                        </label>
                        <input type="text"
                            name="amrrev_name"
                            id="amrrev_name"
                            placeholder="<?php esc_attr_e( 'Enter your name', 'amrrev-product-reviews-for-woocommerce' ); ?>"
                            required>
                    </p>

                    <!-- Email Field -->
                    <p class="amrrev-form-field">
                        <label for="amrrev_email">
                            <?php esc_html_e( 'Email Address', 'amrrev-product-reviews-for-woocommerce' ); ?>
                            <?php if ( $email_required == '1' ): ?>
                                <span class="required">*</span>
                            <?php else: ?>
                                <span class="optional"><?php esc_html_e( '(Optional)', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
                            <?php endif; ?>
                        </label>
                        <input type="email"
                            name="amrrev_email"
                            id="amrrev_email"
                            placeholder="<?php esc_attr_e( 'Enter your email', 'amrrev-product-reviews-for-woocommerce' ); ?>"
                            <?php echo $email_required == '1' ? 'required' : ''; ?>>
                    </p>

                    <!-- Age Range Field (Conditional) -->
                    <?php if ( $enable_age_range == '1' ): ?>
                    <p class="amrrev-form-field">
                        <label class="amrrev-age-range-label">
                            <?php esc_html_e( 'Age Range', 'amrrev-product-reviews-for-woocommerce' ); ?>
                            <span class="required">*</span>
                        </label>
                        <div class="amrrev-age-range">
                            <button type="button" class="age-btn" data-value="under-18"><?php esc_html_e( 'Under 18', 'amrrev-product-reviews-for-woocommerce' ); ?></button>
                            <button type="button" class="age-btn" data-value="18-24">18 - 24</button>
                            <button type="button" class="age-btn" data-value="25-34">25 - 34</button>
                            <button type="button" class="age-btn" data-value="35-44">35 - 44</button>
                            <button type="button" class="age-btn" data-value="45-54">45 - 54</button>
                            <button type="button" class="age-btn" data-value="55-64">55 - 64</button>
                            <button type="button" class="age-btn" data-value="65+">65+</button>
                        </div>
                        <input type="hidden" name="amrrev_age_range" id="amrrev_age_range" required>
                    </p>
                    <?php endif; ?>

                    <!-- Terms Notice -->
                    <p class="amrrev-terms">
                        <label><?php esc_html_e( "By continuing you agree to JOURIE'S Terms and Conditions", 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                    </p>

                    <!-- Submit Button -->
                    <p class="submit-wrapper">
                        <input type="submit" name="amrrev_submit_review" value="<?php esc_attr_e( 'Submit Review', 'amrrev-product-reviews-for-woocommerce' ); ?>" class="amrrev-submit-btn">
                    </p>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Handle Form Submission with All Settings Check
     */
    public function handle_form_submission() {
        if ( !isset( $_POST['amrrev_submit_review'] ) ) {
            return;
        }

        // Verify nonce
        if ( !isset( $_POST['amrrev_review_nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['amrrev_review_nonce'] ) ), 'amrrev_submit_review' ) ) {
            wp_die( esc_html__( 'Security check failed', 'amrrev-product-reviews-for-woocommerce' ) );
        }

        $product_id = isset( $_POST['amrrev_product_id'] ) ? intval( $_POST['amrrev_product_id'] ) : 0;
        if ( !$product_id ) {
            wp_die( esc_html__( 'Invalid product', 'amrrev-product-reviews-for-woocommerce' ) );
        }

        // Get form data
        $title = isset( $_POST['amrrev_title'] ) ? sanitize_text_field( wp_unslash( $_POST['amrrev_title'] ) ) : '';
        $content = isset( $_POST['amrrev_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['amrrev_content'] ) ) : '';
        $rating = isset( $_POST['amrrev_rating'] ) ? intval( $_POST['amrrev_rating'] ) : 0;
        $name = isset( $_POST['amrrev_name'] ) ? sanitize_text_field( wp_unslash( $_POST['amrrev_name'] ) ) : '';
        $email = isset( $_POST['amrrev_email'] ) ? sanitize_email( wp_unslash( $_POST['amrrev_email'] ) ) : '';
        $age_range = isset( $_POST['amrrev_age_range'] ) ? sanitize_text_field( wp_unslash( $_POST['amrrev_age_range'] ) ) : '';

        // Get all settings
        $auto_approve = get_option( 'amrrev_auto_approve', '0' );
        $min_rating = get_option( 'amrrev_min_rating', '1' );
        $enable_moderation = get_option( 'amrrev_enable_moderation', '0' );
        $bad_words = get_option( 'amrrev_bad_words', '' );
        $enable_email = get_option( 'amrrev_enable_email_notification', '1' );
        $title_required = get_option( 'amrrev_title_required', '1' );
        $email_required = get_option( 'amrrev_email_required', '1' );

        // Validate required fields based on settings
        if ( $title_required == '1' && empty( $title ) ) {
            wp_die( esc_html__( 'Review title is required.', 'amrrev-product-reviews-for-woocommerce' ) );
        }

        if ( $email_required == '1' && empty( $email ) ) {
            wp_die( esc_html__( 'Email address is required.', 'amrrev-product-reviews-for-woocommerce' ) );
        }

        if ( empty( $content ) ) {
            wp_die( esc_html__( 'Review description is required.', 'amrrev-product-reviews-for-woocommerce' ) );
        }

        // Validate minimum rating
        if ( $rating < $min_rating ) {
            wp_die( 
                sprintf( 
                    /* translators: %d: minimum rating number */
                    esc_html__( 'Minimum rating of %d stars is required.', 'amrrev-product-reviews-for-woocommerce' ), 
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
                    wp_die( esc_html__( 'Your review contains inappropriate content and cannot be submitted.', 'amrrev-product-reviews-for-woocommerce' ) );
                }
            }
        }

        // Determine post status based on auto_approve setting
        $post_status = ( $auto_approve == '1' ) ? 'publish' : 'pending';

        // Use title or generate from content if title not provided
        $post_title = !empty( $title ) ? $title : wp_trim_words( $content, 5, '...' );

        // Insert review post
        $post = array(
            'post_type'    => 'amrrev_review',
            'post_title'   => $post_title,
            'post_content' => $content,
            'post_status'  => $post_status,
            'post_author'  => 0,
        );
        $review_id = wp_insert_post( $post );

        if ( !$review_id ) {
            wp_die( esc_html__( 'Failed to submit review. Please try again.', 'amrrev-product-reviews-for-woocommerce' ) );
        }

        // Save meta data
        update_post_meta( $review_id, '_amrrev_rating', $rating );
        update_post_meta( $review_id, '_amrrev_product_id', $product_id );
        update_post_meta( $review_id, '_amrrev_name', $name );
        update_post_meta( $review_id, '_amrrev_email', $email );
        update_post_meta( $review_id, '_amrrev_age_range', $age_range );
        update_post_meta( $review_id, '_amrrev_verified_buyer', '1' );

        // Handle file upload if enabled
        if ( get_option( 'amrrev_enable_file_upload', '1' ) == '1' && !empty( $_FILES['amrrev_file']['name'] ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';

            $allowed_mimes = array(
                'jpg|jpeg' => 'image/jpeg',
                'png'      => 'image/png',
                'pdf'      => 'application/pdf',
            );

            $file_info = wp_check_filetype( basename( $_FILES['amrrev_file']['name'] ), $allowed_mimes );
            if ( ! in_array( $file_info['ext'], array_keys( $allowed_mimes ) ) ) {
                wp_die( esc_html__( 'Invalid file type. Only JPG, PNG, and PDF files are allowed.', 'amrrev-product-reviews-for-woocommerce' ) );
            }

            $uploaded = wp_handle_upload( $_FILES['amrrev_file'], array(
                'test_form' => false,
                'mimes'     => $allowed_mimes,
            ) );

            if ( !isset( $uploaded['error'] ) ) {
                update_post_meta( $review_id, '_amrrev_file_url', $uploaded['url'] );
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
        $admin_email = get_option( 'amrrev_admin_email', get_option( 'admin_email' ) );

        if ( empty( $admin_email ) ) {
            return;
        }

        $product = wc_get_product( $product_id );
        if ( !$product ) {
            return;
        }

        $review_title = get_the_title( $review_id );
        $review_content = get_post_field( 'post_content', $review_id );
        $reviewer_name = get_post_meta( $review_id, '_amrrev_name', true );
        $reviewer_email = get_post_meta( $review_id, '_amrrev_email', true );
        $rating = get_post_meta( $review_id, '_amrrev_rating', true );
        $auto_approve = get_option( 'amrrev_auto_approve', '0' );

        /* translators: %s: product name */
        $subject = sprintf( __( 'New Review Submitted: %s', 'amrrev-product-reviews-for-woocommerce' ), $product->get_name() );

        /* translators: %s: product name */
        $message = sprintf(
            __( "A new review has been submitted for: %s\n\n", 'amrrev-product-reviews-for-woocommerce' ),
            $product->get_name()
        );


        /* translators: %s: reviewer name */
        $message .= sprintf( __( "Reviewer: %s\n", 'amrrev-product-reviews-for-woocommerce' ), $reviewer_name );


        if ( !empty( $reviewer_email ) ) {
            /* translators: %s: reviewer email address */
            $message .= sprintf( __( "Email: %s\n", 'amrrev-product-reviews-for-woocommerce' ), $reviewer_email );
        }

        $message .= sprintf( 
            /* translators: %s: star rating (1-5) */
            esc_html__( "Rating: %s stars\n", 'amrrev-product-reviews-for-woocommerce' ), 
            intval( $rating ) 
        );

        $message .= sprintf( 
            /* translators: %s: review title */
            esc_html__( "Review Title: %s\n", 'amrrev-product-reviews-for-woocommerce' ), 
            sanitize_text_field( $review_title ) 
        );

        $message .= sprintf( 
            /* translators: %s: review content */
            esc_html__( "Review: %s\n\n", 'amrrev-product-reviews-for-woocommerce' ), 
            sanitize_textarea_field( $review_content ) 
        );

        if ( $auto_approve == '1' ) {
            $message .= esc_html__( "Status: Published (Auto-approved)\n\n", 'amrrev-product-reviews-for-woocommerce' );
        } else {
            $message .= esc_html__( "Status: Pending Approval\n\n", 'amrrev-product-reviews-for-woocommerce' );
        }
        $message .= sprintf(
            /* translators: %s: URL to review management page */
            __( "View and manage this review:\n%s", 'amrrev-product-reviews-for-woocommerce' ),
            esc_url( admin_url( 'admin.php?page=amrrev-reviews' ) )
        );

        wp_mail( $admin_email, $subject, $message );
    }

    /**
     * Render All Reviews Section
     */
    public function render_all_reviews( $product ) {
        $product_id = $product->get_id();
        $show_filters = get_option( 'amrrev_show_filters', '1' );
        $initial_reviews = get_option( 'amrrev_reviews_per_page', '10' );
        $load_more_count = 3;
        $auto_approve = get_option( 'amrrev_auto_approve', '0' );
        $post_status = ( $auto_approve == '1' ) ? 'publish' : 'publish';
         // Get product description
       
        ?>
        <div id="amrrev-all-reviews-wrapper" class="amrrev-reviews-section">
                <h3><?php esc_html_e( 'Customer Reviews', 'amrrev-product-reviews-for-woocommerce' ); ?></h3>
            
                <!-- Hidden input for product ID -->
                <input type="hidden" id="amrrev_product_id" value="<?php echo esc_attr( $product_id ); ?>">
                <input type="hidden" id="amrrev_initial_reviews" value="<?php echo esc_attr( $initial_reviews ); ?>">
                <input type="hidden" id="amrrev_load_more_count" value="<?php echo esc_attr( $load_more_count ); ?>">
                
                <!-- Filters (Conditional) -->
                <?php if ( $show_filters == '1' ) : ?>
                <?php
                $filter = new AMRREV_Filter();
                $filter->render_filter_form();
                ?>
                <?php endif; ?>
                
                <!-- Reviews Container -->
                <div id="amrrev-reviews-container">
                    <?php
                    // Get total reviews count
                    $total_args = array(
                        'post_type'      => 'amrrev_review',
                        'post_status'    => $post_status,
                        'meta_query'     => array(
                            array(
                                'key'     => '_amrrev_product_id',
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
                        'post_type'      => 'amrrev_review',
                        'post_status'    => 'publish',
                        'posts_per_page' => $initial_reviews,
                        'meta_query'     => array(
                            array(
                                'key'     => '_amrrev_product_id',
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
                        echo '<div class="amrrev-no-reviews"><p>' . esc_html__( 'No reviews yet. Be the first to review this product!', 'amrrev-product-reviews-for-woocommerce' ) . '</p></div>';
                    endif;
                    
                    wp_reset_postdata();
                    ?>
                </div>
                
                <!-- Load More Button -->
                <?php if ( $total_reviews > $initial_reviews ) : ?>
                <div class="amrrev-load-more-container">
                    <button id="amrrev-load-more-btn" class="amrrev-load-more-btn">
                        <?php esc_html_e( 'More Reviews', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </button>
                    <div class="amrrev-loading-spinner" style="display: none;">
                        <div class="spinner"></div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Hidden field for total reviews -->
                <input type="hidden" id="amrrev_total_reviews" value="<?php echo esc_attr( $total_reviews ); ?>">
                <input type="hidden" id="amrrev_loaded_reviews" value="<?php echo esc_attr( min( $initial_reviews, $total_reviews ) ); ?>">
            
            
        </div>
    
        <?php
    }

    /**
     * Render Custom Pagination
     */
    private function render_custom_pagination( $total_pages, $current_page, $product_id ) {
        echo '<div class="amrrev-pagination">';
        
        // Previous button
        if ( $current_page > 1 ) {
            echo '<a class="prev page-numbers" href="#" data-page="' . esc_attr( $current_page - 1 ) . '">&laquo; ' . esc_html__( 'Previous', 'amrrev-product-reviews-for-woocommerce' ) . '</a>';
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
            echo '<a class="next page-numbers" href="#" data-page="' . esc_attr( $current_page + 1 ) . '">' . esc_html__( 'Next', 'amrrev-product-reviews-for-woocommerce' ) . ' &raquo;</a>';
        }
        
        echo '</div>';
    }

    /**
     * Render Single Review with Display Settings
     */
    private function render_single_review( $review_id ) {
        $product_id = get_post_meta( $review_id, '_amrrev_product_id', true );
        $file_url = get_post_meta( $review_id, '_amrrev_file_url', true );
        $rating = get_post_meta( $review_id, '_amrrev_rating', true );
        $reviewer_name = get_post_meta( $review_id, '_amrrev_name', true );
        $reviewer_age = get_post_meta( $review_id, '_amrrev_age_range', true );
        $verified = get_post_meta( $review_id, '_amrrev_verified_buyer', true );

        // Get display settings
        $show_verified_badge = get_option( 'amrrev_show_verified_badge', '1' );
        $date_format = get_option( 'amrrev_date_format', 'j/n/y' );
        $enable_age_range = get_option( 'amrrev_enable_age_range', '1' );
        $filled_star_color = get_option( 'amrrev_filled_star_color', '#ffc107' );
        $empty_star_color = get_option( 'amrrev_empty_star_color', '#dddddd' );

        ?>
        <div class="cpt-review-full-box">
            <div class="cpt-review-box-one">
                <div class="cpt-name"><?php echo esc_html( $reviewer_name ); ?></div>

                <?php if ( $show_verified_badge == '1' && $verified == '1' ): ?>
                <div class="cpt-verify-buyer">
                    <span><?php esc_html_e( 'Verified Buyer', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
                    <img src="<?php echo esc_url( AMRREV_ASSETS_URL . 'images/verify-buyer.svg' ); ?>" alt="verify-buyer">

                </div>
                <?php endif; ?>

                <?php if ( $enable_age_range == '1' && !empty( $reviewer_age ) ): ?>
                <div class="cpt-age-range">
                    <span><?php esc_html_e( 'Age Range:', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
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
new AMRREV_Form_Handler();