<?php
/**
 * includes/class-cpr-filter.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Filter {

    public function __construct() {
        add_action( 'wp_ajax_cpr_filter_reviews', array( $this, 'filter_reviews' ) );
        add_action( 'wp_ajax_nopriv_cpr_filter_reviews', array( $this, 'filter_reviews' ) );
    }

    public function render_filter_form() {
        ?>
        <div class="cpr-review-filters">
            <h4><?php _e( 'Filter Reviews', 'custom-product-reviews' ); ?></h4>
            
            <div class="cpr-filter-group">
                <label><?php _e( 'Rating', 'custom-product-reviews' ); ?></label>
                <div class="cpr-rating-filter">
                    <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                        <label>
                            <input type="checkbox" name="rating[]" value="<?php echo $i; ?>">
                            <?php echo str_repeat( '★', $i ); ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="cpr-filter-group">
                <label><?php _e( 'Age Range', 'custom-product-reviews' ); ?></label>
                <div class="cpr-age-filter">
                    <select name="age_range">
                        <option value=""><?php _e( 'All Ages', 'custom-product-reviews' ); ?></option>
                        <option value="under-18"><?php _e( 'Under 18', 'custom-product-reviews' ); ?></option>
                        <option value="18-24"><?php _e( '18 - 24', 'custom-product-reviews' ); ?></option>
                        <option value="25-34"><?php _e( '25 - 34', 'custom-product-reviews' ); ?></option>
                        <option value="35-44"><?php _e( '35 - 44', 'custom-product-reviews' ); ?></option>
                        <option value="45-54"><?php _e( '45 - 54', 'custom-product-reviews' ); ?></option>
                        <option value="55-64"><?php _e( '55 - 64', 'custom-product-reviews' ); ?></option>
                        <option value="65+"><?php _e( '65+', 'custom-product-reviews' ); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="cpr-filter-group">
                <label>
                    <input type="checkbox" name="verified_only" value="1">
                    <?php _e( 'Verified Buyers Only', 'custom-product-reviews' ); ?>
                </label>
            </div>
        </div>
        <?php
    }

    public function filter_reviews() {
        check_ajax_referer( 'cpr_filter_nonce', 'nonce' );
        
        $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
        $ratings = isset( $_POST['rating'] ) && is_array( $_POST['rating'] ) ? array_map( 'intval', $_POST['rating'] ) : array();
        $age_range = isset( $_POST['age_range'] ) ? sanitize_text_field( $_POST['age_range'] ) : '';
        $verified_only = isset( $_POST['verified_only'] ) && $_POST['verified_only'] == '1' ? true : false;
        
        // Debug করার জন্য
        error_log( 'Product ID: ' . $product_id );
        error_log( 'Ratings: ' . print_r( $ratings, true ) );
        error_log( 'Age Range: ' . $age_range );
        error_log( 'Verified Only: ' . ( $verified_only ? 'true' : 'false' ) );
        
        if ( !$product_id ) {
            wp_send_json_error( array( 'message' => 'Product ID missing' ) );
            return;
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
            'posts_per_page' => -1,
            'meta_query'     => $meta_query,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        
        // Debug query
        error_log( 'Query Args: ' . print_r( $args, true ) );
        
        $reviews = new WP_Query( $args );
        
        error_log( 'Found Posts: ' . $reviews->found_posts );
        
        ob_start();
        if ( $reviews->have_posts() ) {
            while ( $reviews->have_posts() ) {
                $reviews->the_post();
                $this->render_single_review( get_the_ID() );
            }
        } else {
            echo '<div class="cpr-no-reviews"><p>' . __( 'No reviews found with these filters.', 'custom-product-reviews' ) . '</p></div>';
        }
        wp_reset_postdata();
        
        $output = ob_get_clean();
        
        wp_send_json_success( $output );
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

new CPR_Filter();