<?php
/**
 * includes/class-amrrev-filter.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AMRREV_Filter {

    public function __construct() {
        add_action( 'wp_ajax_amrrev_filter_reviews', array( $this, 'filter_reviews' ) );
        add_action( 'wp_ajax_nopriv_amrrev_filter_reviews', array( $this, 'filter_reviews' ) );
    }

    public function render_filter_form() {
        ?>
        <div class="amrrev-review-filters">
            <h4><?php esc_html_e( 'Filter Reviews', 'amrrev-product-reviews-for-woocommerce' ); ?></h4>
            
            <div class="amrrev-filter-group">
                <label><?php esc_html_e( 'Rating', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                <div class="amrrev-rating-filter">
                    <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                        <label>
                            <input type="checkbox" name="rating[]" value="<?php echo esc_attr( $i ); ?>">
                            <?php echo esc_html( str_repeat( '★', $i ) ); ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="amrrev-filter-group">
                <label><?php esc_html_e( 'Age Range', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                <div class="amrrev-age-filter">
                    <select name="age_range">
                        <option value=""><?php esc_html_e( 'All Ages', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                        <option value="under-18"><?php esc_html_e( 'Under 18', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                        <option value="18-24"><?php esc_html_e( '18 - 24', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                        <option value="25-34"><?php esc_html_e( '25 - 34', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                        <option value="35-44"><?php esc_html_e( '35 - 44', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                        <option value="45-54"><?php esc_html_e( '45 - 54', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                        <option value="55-64"><?php esc_html_e( '55 - 64', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                        <option value="65+"><?php esc_html_e( '65+', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="amrrev-filter-group amrrev-verified-filter">
                <label>
                    <input type="checkbox" name="verified_only" value="1">
                    <?php esc_html_e( 'Verified Buyers Only', 'amrrev-product-reviews-for-woocommerce' ); ?>
                </label>
            </div>
        </div>
        <?php
    }

    public function filter_reviews() {
        check_ajax_referer( 'amrrev_filter_nonce', 'nonce' );
        
        $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
        $ratings = isset( $_POST['rating'] ) && is_array( $_POST['rating'] ) ? array_map( 'intval', $_POST['rating'] ) : array();
        $age_range = isset( $_POST['age_range'] ) ? sanitize_text_field( wp_unslash( $_POST['age_range'] ) ) : '';
        $verified_only = isset( $_POST['verified_only'] ) && $_POST['verified_only'] == '1' ? true : false;
        
        if ( !$product_id ) {
            wp_send_json_error( array( 'message' => 'Product ID missing' ) );
            return;
        }
        
        $meta_query = array(
            'relation' => 'AND',
            array(
                'key'     => '_amrrev_product_id',
                'value'   => $product_id,
                'compare' => '=',
            ),
        );
        
        if ( ! empty( $ratings ) ) {
            $meta_query[] = array(
                'key'     => '_amrrev_rating',
                'value'   => $ratings,
                'compare' => 'IN',
                'type'    => 'NUMERIC',
            );
        }
        
        if ( ! empty( $age_range ) ) {
            $meta_query[] = array(
                'key'     => '_amrrev_age_range',
                'value'   => $age_range,
                'compare' => '=',
            );
        }
        
        if ( $verified_only ) {
            $meta_query[] = array(
                'key'     => '_amrrev_verified_buyer',
                'value'   => '1',
                'compare' => '=',
            );
        }
        $auto_approve = get_option( 'amrrev_auto_approve', '0' );
        $post_status = ( $auto_approve == '1' ) ? 'publish' : 'publish';

        $args = array(
            'post_type'      => 'amrrev_review',
            'post_status'    => $post_status,
            'posts_per_page' => -1,
            'meta_query'     => $meta_query,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        
        $reviews = new WP_Query( $args );
        
        ob_start();
        if ( $reviews->have_posts() ) {
            while ( $reviews->have_posts() ) {
                $reviews->the_post();
                $this->render_single_review( get_the_ID() );
            }
        } else {
            echo '<div class="amrrev-no-reviews"><p>' . esc_html__( 'No reviews found with these filters.', 'amrrev-product-reviews-for-woocommerce' ) . '</p></div>';
        }
        wp_reset_postdata();
        
        $output = ob_get_clean();
        
        wp_send_json_success( $output );
    }
    
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
                
                <?php if ( $show_verified_badge == '1' && $verified == '1' ) : ?>
                <div class="cpt-verify-buyer">
                    <span><?php esc_html_e( 'Verified Buyer', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
                    <img src="<?php echo esc_url( AMRREV_ASSETS_URL . 'images/verify-buyer.svg' ); ?>" alt="verify-buyer">
                </div>
                <?php endif; ?>
                
                <?php if ( $enable_age_range == '1' && !empty( $reviewer_age ) ) : ?>
                <div class="cpt-age-range">
                    <span><?php esc_html_e( 'Age Range:', 'amrrev-product-reviews-for-woocommerce' ); ?></span>
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

new AMRREV_Filter();