<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Post_Type {

    public function __construct() {
        add_action('init', array($this, 'register_review_post_type'));
    }

    public function register_review_post_type() {

        $labels = array(
            'name'               => __( 'Reviews', 'custom-product-reviews' ),
            'singular_name'      => __( 'Review', 'custom-product-reviews' ),
            'menu_name'          => __( 'Product Reviews', 'custom-product-reviews' ),
            'add_new'            => __( 'Add New Review', 'custom-product-reviews' ),
            'add_new_item'       => __( 'Add New Review', 'custom-product-reviews' ),
            'edit_item'          => __( 'Edit Review', 'custom-product-reviews' ),
            'new_item'           => __( 'New Review', 'custom-product-reviews' ),
            'view_item'          => __( 'View Review', 'custom-product-reviews' ),
            'search_items'       => __( 'Search Reviews', 'custom-product-reviews' ),
            'not_found'          => __( 'No Reviews found', 'custom-product-reviews' ),
            'not_found_in_trash' => __( 'No Reviews found in Trash', 'custom-product-reviews' ),
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
