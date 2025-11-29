<?php
/**
 * Post Type Class
 * includes/class-cpr-post-type.php
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Post_Type {

    public function __construct() {
        add_action('init', array($this, 'register_review_post_type'));
    }

    public function register_review_post_type() {

        $labels = array(
            'name'               => esc_html__( 'Reviews', 'custom-product-reviews' ),
            'singular_name'      => esc_html__( 'Review', 'custom-product-reviews' ),
            'menu_name'          => esc_html__( 'Product Reviews', 'custom-product-reviews' ),
            'add_new'            => esc_html__( 'Add New Review', 'custom-product-reviews' ),
            'add_new_item'       => esc_html__( 'Add New Review', 'custom-product-reviews' ),
            'edit_item'          => esc_html__( 'Edit Review', 'custom-product-reviews' ),
            'new_item'           => esc_html__( 'New Review', 'custom-product-reviews' ),
            'view_item'          => esc_html__( 'View Review', 'custom-product-reviews' ),
            'search_items'       => esc_html__( 'Search Reviews', 'custom-product-reviews' ),
            'not_found'          => esc_html__( 'No Reviews found', 'custom-product-reviews' ),
            'not_found_in_trash' => esc_html__( 'No Reviews found in Trash', 'custom-product-reviews' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => 'cpr-reviews', // ⭐ MAIN MENU এর অধীনে দেখাবে
            'menu_icon'          => 'dashicons-star-half',
            'supports'           => array( 'title', 'editor', 'author' ),
            'capability_type'    => 'post',
            'rewrite'            => false,
            'show_in_rest'       => false,
        );

        register_post_type( 'cpr_review', $args );
    }
}
