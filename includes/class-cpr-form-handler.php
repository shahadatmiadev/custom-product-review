<?php


if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Form_Handler {

    public function __construct() {
        add_action( 'woocommerce_after_single_product_summary', array( $this, 'render_review_form' ), 20 );
        add_action( 'init', array( $this, 'handle_form_submission' ) );
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

        $this->render_all_reviews( $product );
        ?>

        <div id="cpr-review-form-wrapper">
            <h3><?php _e( 'Write a Review', 'custom-product-reviews' ); ?></h3>
            <form method="post" enctype="multipart/form-data" id="cpr-review-form">
                <?php wp_nonce_field( 'cpr_submit_review', 'cpr_review_nonce' ); ?>

                <input type="hidden" name="cpr_product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">

                <p>
                    <input type="text" name="cpr_title" id="cpr_title" placeholder="Review Title" required>
                </p>

                <p>
                    <textarea name="cpr_content" id="cpr_content" rows="4" placeholder="Review Description" required></textarea>
                </p>

                <div class="drag-file-area">
                    <div class="drag-file-icon">
                        <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/download.svg' ); ?>" alt="">
                    </div>
                    <label class="label">
                        <span class="browse-files">
                            <input type="file" name="cpr_file" class="default-file-input" id="cpr_file_input">
                            Drag and drop, or <span class="browse-files-text">browse</span>
                            <span>your files</span>
                        </span>
                        <img src="" alt="" id="cpr_file_preview" style="display:none; max-width:70px; margin-left: auto; margin-right: auto;">
                    </label>
                    <div>
                        <span>Support JPG,PDF,PNG</span>
                    </div>
                </div>

                <p>
                    <label><?php _e( 'Star Rating', 'custom-product-reviews' ); ?></label><br>
                    <div class="cpr-star-rating">
                        <span data-value="1">&#9733;</span>
                        <span data-value="2">&#9733;</span>
                        <span data-value="3">&#9733;</span>
                        <span data-value="4">&#9733;</span>
                        <span data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" name="cpr_rating" id="cpr_rating" required>
                </p>

                <p>
                    <input type="text" name="cpr_name" id="cpr_name" placeholder="Name" required>
                </p>

                <p>
                    <input type="email" name="cpr_email" id="cpr_email" placeholder="Email Address" required>
                </p>

                <p>
                    <label class="cpr-age-range-label"><?php _e( 'Age Range', 'custom-product-reviews' ); ?></label><br>
                    <label><?php _e( 'Choose One', 'custom-product-reviews' ); ?></label><br>
                    <div class="cpr-age-range">
                        <button type="button" data-value="under-18">Under 18</button>
                        <button type="button" data-value="18-24">18 - 24</button>
                        <button type="button" data-value="25-34">25 - 34</button>
                        <button type="button" data-value="35-44">35 - 44</button>
                        <button type="button" data-value="45-54">45 - 54</button>
                        <button type="button" data-value="55-64">55 - 64</button>
                        <button type="button" data-value="65+">65+</button>
                    </div>
                    <input type="hidden" name="cpr_age_range" id="cpr_age_range" required>
                </p>

                <p class="cpr-terms">
                    <label>By continuing you agree to JOURIE'S Terms and Conditions </label>
                </p>

                <p class="submit-wrapper">
                    <input type="submit" name="cpr_submit_review" value="<?php _e( 'Submit Review', 'custom-product-reviews' ); ?>">
                </p>
            </form>
        </div>
        <?php
    }

    public function handle_form_submission() {
        if ( !isset( $_POST['cpr_submit_review'] ) ) {
            return;
        }

        $product_id = isset( $_POST['cpr_product_id'] ) ? intval( $_POST['cpr_product_id'] ) : 0;
        if ( !$product_id ) {
            return;
        }

        $title = isset( $_POST['cpr_title'] ) ? sanitize_text_field( $_POST['cpr_title'] ) : '';
        $content = isset( $_POST['cpr_content'] ) ? sanitize_text_field( $_POST['cpr_content'] ) : '';
        $file_url = isset( $_POST['cpr_file'] ) ? sanitize_url( $_POST['cpr_file'] ) : '';
        $rating = isset( $_POST['cpr_rating'] ) ? intval( $_POST['cpr_rating'] ) : 0;
        $name = isset( $_POST['cpr_name'] ) ? sanitize_text_field( $_POST['cpr_name'] ) : '';
        $email = isset( $_POST['cpr_email'] ) ? sanitize_email( $_POST['cpr_email'] ) : '';
        $age_range = isset( $_POST['cpr_age_range'] ) ? sanitize_text_field( $_POST['cpr_age_range'] ) : '';

        $post = array(
            'post_type'    => 'cpr_review',
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'pending',
            'post_author'  => 0,
        );
        $review_id = wp_insert_post( $post );

        if ( !$review_id ) {
            return;
        }

        update_post_meta( $review_id, '_cpr_rating', $rating );
        update_post_meta( $review_id, '_cpr_product_id', $product_id );
        update_post_meta( $review_id, '_cpr_name', $name );
        update_post_meta( $review_id, '_cpr_email', $email );
        update_post_meta( $review_id, '_cpr_age_range', $age_range );
        update_post_meta( $review_id, '_cpr_verified_buyer', true );

        if ( !empty( $_FILES['cpr_file']['name'] ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';

            $uploaded = wp_handle_upload( $_FILES['cpr_file'], [
                'test_form' => false,
            ] );

            if ( !isset( $uploaded['error'] ) ) {
                update_post_meta( $review_id, '_cpr_file_url', $uploaded['url'] );
            }
        }

        wp_safe_redirect( get_permalink( $product_id ) . '?review_submitted=1' );
        exit;
    }
    
    public function render_all_reviews( $product ) {
        $product_id = $product->get_id();
        ?>
        <div id="cpr-all-reviews-wrapper">
            <h3><?php _e( 'All Reviews', 'custom-product-reviews' ); ?></h3>
            
            <!-- Hidden input for product ID -->
            <input type="hidden" id="cpr_product_id" value="<?php echo esc_attr( $product_id ); ?>">
            
            <?php
            $filter = new CPR_Filter();
            $filter->render_filter_form();
            ?>
            
            <div id="cpr-reviews-container">
                <?php
                $arg = array(
                    'post_type'      => 'cpr_review',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
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
                
                $review_query = new WP_Query( $arg );
                
                if ( $review_query->have_posts() ) :
                    while ( $review_query->have_posts() ) : $review_query->the_post();
                        $this->render_single_review( get_the_ID() );
                    endwhile;
                else :
                    echo '<div class="cpr-no-reviews"><p>' . __( 'No reviews found.', 'custom-product-reviews' ) . '</p></div>';
                endif;
                
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    }

    private function render_single_review( $review_id ) {
        $product_id = get_post_meta( $review_id, '_cpr_product_id', true );
        $file_url = get_post_meta( $review_id, '_cpr_file_url', true );
        $rating = get_post_meta( $review_id, '_cpr_rating', true );
        $reviewer_name = get_post_meta( $review_id, '_cpr_name', true );
        $reviewer_age = get_post_meta( $review_id, '_cpr_age_range', true );
        $verified = get_post_meta( $review_id, '_cpr_verified_buyer', true );
        
        ?>
        <div class="cpt-review-full-box">
            <div class="cpt-review-box-one">
                <div class="cpt-name"><?php echo esc_html( $reviewer_name ); ?></div>
                <?php if ( $verified == '1' ) : ?>
                <div class="cpt-verify-buer">
                    <span><?php _e( 'Verified Buyer', 'custom-product-reviews' ); ?></span>
                    <img src="<?php echo CPR_ASSETS_URL . 'images/verify-buyer.svg'; ?>" alt="verify-buyer">
                </div>
                <?php endif; ?>
                <div class="cpt-age-range">
                    <span><?php _e( 'Age Range', 'custom-product-reviews' ); ?></span>
                    <span><?php echo esc_html( $reviewer_age ); ?></span>
                </div>
            </div>
            <div class="cpt-review-box-two">
                <div class="cpt-review-date">
                    <div class="cpt-review-count">
                        <?php echo str_repeat( '★', intval( $rating ) ); ?>
                        <?php echo str_repeat( '☆', 5 - intval( $rating ) ); ?>
                    </div>
                    <div class="cpt-date"><?php echo get_the_date( 'j/n/y' ); ?></div>
                </div>
                <div class="cpt-review-content">
                    <span><?php echo esc_html( get_the_content() ); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
}

// Initialize Form Handler
new CPR_Form_Handler();