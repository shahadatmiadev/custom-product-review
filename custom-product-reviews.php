<?php
/**
 * Plugin Name: Custom Product Reviews
 * Plugin URI: https://softvencedelta.com
 * Description: A custom product review system for WooCommerce products with advanced filtering and admin approval system
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-product-reviews
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Disable direct access
}

/**
 * Main Plugin Class
 */
final class Custom_Product_Reviews {

    /**
     * Plugin version
     */
    const VERSION = '1.0.0';

    /**
     * Single instance
     */
    private static $_instance = null;

    /**
     * Access the single instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->define_constants();
        $this->init_hooks();
    }

    /**
     * Define plugin constants
     */
    private function define_constants() {

        define( 'CPR_VERSION', self::VERSION );
        define( 'CPR_PLUGIN_FILE', __FILE__ );
        define( 'CPR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        define( 'CPR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        define( 'CPR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

        define( 'CPR_INCLUDES_DIR', CPR_PLUGIN_DIR . 'includes/' );
        define( 'CPR_ADMIN_DIR', CPR_PLUGIN_DIR . 'admin/' );
        define( 'CPR_ADMIN_URL', CPR_PLUGIN_URL . 'admin/' );
        define( 'CPR_PUBLIC_DIR', CPR_PLUGIN_DIR . 'public/' );
        define( 'CPR_PUBLIC_URL', CPR_PLUGIN_URL . 'public/' );
        define( 'CPR_ASSETS_URL', CPR_PLUGIN_URL . 'assets/' );
    }

    /**
     * Hooks
     */
    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
    }

    /**
     * Initialize only after plugins are loaded
     */
    public function init_plugin() {

        // WooCommerce check
        if ( !class_exists( 'WooCommerce' ) ) {
            add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
            return;
        }

        load_plugin_textdomain( 'custom-product-reviews', false, dirname( CPR_PLUGIN_BASENAME ) . '/languages' );

        $this->includes();

        // Initialize main loader class
        new CPR_Ajax();
        new CPR_Filter();
        new CPR_Form_Handler();
        new CPR_Meta_Boxes();
        new CPR_Post_Type();
    }

    /**
     * Include plugin files
     */
    private function includes() {

        require_once CPR_INCLUDES_DIR . 'class-cpr-post-type.php';
        require_once CPR_INCLUDES_DIR . 'class-cpr-meta-boxes.php';
         require_once CPR_INCLUDES_DIR . 'class-cpr-form-handler.php';
        require_once CPR_INCLUDES_DIR . 'class-cpr-filter.php';
        require_once CPR_INCLUDES_DIR . 'class-cpr-ajax.php';
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'cpr-public-review-form', CPR_PUBLIC_URL . 'css/review-form.css', array(), CPR_VERSION );
        wp_enqueue_style( 'cpr-public-review-display', CPR_PUBLIC_URL . 'css/review-display.css', array(), CPR_VERSION );
        wp_enqueue_script( 'cpr-public-review-rating', CPR_PUBLIC_URL . 'js/review-rating.js', array( 'jquery' ), CPR_VERSION, true );
        wp_enqueue_style( 'cpr-public-review-filter', CPR_PUBLIC_URL . 'css/review-filters.css', array(), CPR_VERSION );
        wp_enqueue_script( 'cpr-public-review-filter', CPR_PUBLIC_URL . 'js/review-filter.js', array( 'jquery' ), CPR_VERSION, true );
        
        // AJAX ভেরিয়েবল যোগ করুন
        wp_localize_script( 'cpr-public-review-filter', 'cpr_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'cpr_filter_nonce' ),
        ) );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets( $hook ) {
        if ( $hook != 'toplevel_page_cpr-reviews' ) {
            return;
        }
        wp_enqueue_style( 'cpr-admin-style', CPR_ADMIN_URL . 'css/admin-style.css', array(), CPR_VERSION );
        wp_enqueue_script( 'cpr-admin-reviews', CPR_ADMIN_URL . 'js/admin-reviews.js', array( 'jquery' ), CPR_VERSION, true );
        wp_localize_script( 'cpr-admin-reviews', 'cpr_admin_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'cpr_admin_ajax' ),
        ) );
    }

    /**
     * WooCommerce missing notice
     */
    public function woocommerce_missing_notice() {
        echo '<div class="error"><p><strong>Custom Product Reviews requires WooCommerce to be installed and active.</strong></p></div>';
    }

    /**
     * Add Admin Menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Product Reviews', 'custom-product-reviews' ),
            __( 'Product Reviews', 'custom-product-reviews' ),
            'manage_options',
            'cpr-reviews',
            '',
            'dashicons-star-half',
            56
        );
        add_submenu_page(
            'cpr-reviews',
            __( 'Manage Reviews', 'custom-product-reviews' ),
            __( 'Manage Reviews', 'custom-product-reviews' ),
            'manage_options',
            'cpr-reviews',
            array( $this, 'render_manage_reviews_page' )
        );
    }

    /**
     * Render Admin Page
     */
    public function render_manage_reviews_page() {
        require_once CPR_ADMIN_DIR . 'views/admin-reviews-page.php';
    }

}

/**
 * Helper function
 */
function CPR() {
    return Custom_Product_Reviews::instance();
}

// Start plugin
CPR();
