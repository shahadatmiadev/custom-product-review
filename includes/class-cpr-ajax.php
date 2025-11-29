<?php
/**
 * Ajax Class
 * includes/class-cpr-ajax.php
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Ajax {
    public function __construct() {
        add_action( 'wp_ajax_cpr_approve_review', array( $this, 'approve_review' ) );
        add_action( 'wp_ajax_cpr_reject_review', array( $this, 'reject_review' ) );
        add_action( 'wp_ajax_cpr_delete_review', array( $this, 'delete_review' ) );

        add_action( 'wp_ajax_cpr_load_more_reviews', array( $this, 'load_more_reviews' ) );
        add_action( 'wp_ajax_nopriv_cpr_load_more_reviews', array( $this, 'load_more_reviews' ) );
    }

    public function approve_review() {
        check_ajax_referer( 'cpr_admin_ajax', 'nonce' );
       
        $review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;

        if ( !$review_id ) {
            wp_send_json_error( array( 'message' => 'Invalid review ID.' ) );
        }
        
        $update = array(
            'ID' => $review_id,
            'post_status' => 'publish',
        );
        wp_update_post( $update );

        if($update && !is_wp_error($update)) {
            wp_send_json_success( array( 'message' => 'Review approved successfully.' ) );
        }else {
            wp_send_json_error( array( 'message' => 'Failed to approve review.' ) );
        }
    }

    public function reject_review() {
        check_ajax_referer( 'cpr_admin_ajax', 'nonce' );
       
        $review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;

        if ( !$review_id ) {
            wp_send_json_error( array( 'message' => 'Invalid review ID.' ) );
        }
        
        $update = array(
            'ID' => $review_id,
            'post_status' => 'draft',
        );
        wp_update_post( $update );

        if($update && !is_wp_error($update)) {
            wp_send_json_success( array( 'message' => 'Review rejected successfully.' ) );
        }else {
            wp_send_json_error( array( 'message' => 'Failed to reject review.' ) );
        }
    }

    public function delete_review() {
        check_ajax_referer( 'cpr_admin_ajax', 'nonce' );
       
        $review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;

        if ( !$review_id ) {
            wp_send_json_error( array( 'message' => 'Invalid review ID.' ) );
        }
        
        
       $deleted = wp_delete_post( $review_id, true );

        if ( $deleted ) {
            wp_send_json_success( array( 'message' => 'Review deleted permanently!' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Failed to delete review.' ) );
        }
    }

    /**
     * Load More Reviews
     */
    public function load_more_reviews() {
        check_ajax_referer( 'cpr_load_more_nonce', 'nonce' );
        
        $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
        $offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : 0;
        $count = isset( $_POST['count'] ) ? intval( $_POST['count'] ) : 3;
        $ratings = isset( $_POST['rating'] ) && is_array( $_POST['rating'] ) ? array_map( 'intval', $_POST['rating'] ) : array();
        $age_range = isset( $_POST['age_range'] ) ? sanitize_text_field( $_POST['age_range'] ) : '';
        $verified_only = isset( $_POST['verified_only'] ) && $_POST['verified_only'] == '1' ? true : false;
        
        if ( !$product_id ) {
            wp_send_json_error( array( 'message' => 'Product ID missing' ) );
        }
        
        $meta_query = array(
            'relation' => 'AND',
            array(
                'key'     => '_cpr_product_id',
                'value'   => $product_id,
                'compare' => '=',
            ),
        );
        
        if ( ! empty( $ratings ) ) {
            $meta_query[] = array(
                'key'     => '_cpr_rating',
                'value'   => $ratings,
                'compare' => 'IN',
                'type'    => 'NUMERIC',
            );
        }
        
        if ( ! empty( $age_range ) ) {
            $meta_query[] = array(
                'key'     => '_cpr_age_range',
                'value'   => $age_range,
                'compare' => '=',
            );
        }
        
        if ( $verified_only ) {
            $meta_query[] = array(
                'key'     => '_cpr_verified_buyer',
                'value'   => '1',
                'compare' => '=',
            );
        }
        
        $args = array(
            'post_type'      => 'cpr_review',
            'post_status'    => 'publish',
            'posts_per_page' => $count,
            'offset'         => $offset,
            'meta_query'     => $meta_query,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        
        $review_query = new WP_Query( $args );
        
        ob_start();
        if ( $review_query->have_posts() ) {
            while ( $review_query->have_posts() ) {
                $review_query->the_post();
                $this->render_single_review( get_the_ID() );
            }
        }
        wp_reset_postdata();
        $reviews_html = ob_get_clean();
        
        wp_send_json_success( array(
            'reviews' => $reviews_html,
            'loaded_count' => $review_query->post_count
        ) );
    }
    
    /**
     * Render Single Review for AJAX
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
                
                <?php if ( $show_verified_badge == '1' && $verified == '1' ) : ?>
                <div class="cpt-verify-buyer">
                    <span><?php _e( 'Verified Buyer', 'custom-product-reviews' ); ?></span>
                    <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/verify-buyer.svg' ); ?>" alt="verify-buyer">
                </div>
                <?php endif; ?>
                
                <?php if ( $enable_age_range == '1' && !empty( $reviewer_age ) ) : ?>
                <div class="cpt-age-range">
                    <span><?php _e( 'Age Range:', 'custom-product-reviews' ); ?></span>
                    <span><?php echo esc_html( $reviewer_age ); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="cpt-review-box-two">
                <div class="cpt-review-date">
                    <div class="cpt-review-count">
                        <?php 
                        // Display stars with custom colors
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

                    <?php if ( !empty( $file_url ) ) : ?>
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