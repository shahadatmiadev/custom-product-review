<?php
/**
 * Post Type Class
 * includes/class-amrrev-post-type.php
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AMRREV_Post_Type {

    public function __construct() {
        add_action('init', array($this, 'register_review_post_type'));
    }

    public function register_review_post_type() {

        $labels = array(
            'name'               => esc_html__( 'Reviews', 'amrrev-product-reviews-for-woocommerce' ),
            'singular_name'      => esc_html__( 'Review', 'amrrev-product-reviews-for-woocommerce' ),
            'menu_name'          => esc_html__( 'Product Reviews', 'amrrev-product-reviews-for-woocommerce' ),
            'add_new'            => esc_html__( 'Add New Review', 'amrrev-product-reviews-for-woocommerce' ),
            'add_new_item'       => esc_html__( 'Add New Review', 'amrrev-product-reviews-for-woocommerce' ),
            'edit_item'          => esc_html__( 'Edit Review', 'amrrev-product-reviews-for-woocommerce' ),
            'new_item'           => esc_html__( 'New Review', 'amrrev-product-reviews-for-woocommerce' ),
            'view_item'          => esc_html__( 'View Review', 'amrrev-product-reviews-for-woocommerce' ),
            'search_items'       => esc_html__( 'Search Reviews', 'amrrev-product-reviews-for-woocommerce' ),
            'not_found'          => esc_html__( 'No Reviews found', 'amrrev-product-reviews-for-woocommerce' ),
            'not_found_in_trash' => esc_html__( 'No Reviews found in Trash', 'amrrev-product-reviews-for-woocommerce' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => 'amrrev-reviews', // ⭐ MAIN MENU এর অধীনে দেখাবে
            'menu_icon'          => 'dashicons-star-half',
            'supports'           => array( 'title', 'editor', 'author' ),
            'capability_type'    => 'post',
            'rewrite'            => false,
            'show_in_rest'       => false,
        );

        register_post_type( 'amrrev_review', $args );
    }
}
