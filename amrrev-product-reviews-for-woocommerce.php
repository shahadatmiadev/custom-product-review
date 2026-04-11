<?php
/**
 * Plugin Name: AmrRev – Product Reviews for WooCommerce
 * Plugin URI:  https://github.com/shahadatmiadev/amrrev-product-reviews-for-woocommerce
 * Description: A powerful AmrRev system for WooCommerce with advanced filtering, admin approval, and complete style customization.
 * Version:     1.0.0
 * Author:      Xohan Niloy
 * Author URI:  https://github.com/shahadatmiadev
 * Text Domain: amrrev-product-reviews-for-woocommerce
 * Domain Path: /languages
 * Requires at least: 5.8
 * Tested up to: 6.9
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Disable direct access
}

/**
 * Main Plugin Class
 */
final class AmrRev_Product_Reviews {

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

        define( 'AMRREV_VERSION', self::VERSION );
        define( 'AMRREV_PLUGIN_FILE', __FILE__ );
        define( 'AMRREV_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        define( 'AMRREV_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        define( 'AMRREV_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

        define( 'AMRREV_INCLUDES_DIR', AMRREV_PLUGIN_DIR . 'includes/' );
        define( 'AMRREV_ADMIN_DIR', AMRREV_PLUGIN_DIR . 'admin/' );
        define( 'AMRREV_ADMIN_URL', AMRREV_PLUGIN_URL . 'admin/' );
        define( 'AMRREV_PUBLIC_DIR', AMRREV_PLUGIN_DIR . 'public/' );
        define( 'AMRREV_PUBLIC_URL', AMRREV_PLUGIN_URL . 'public/' );
        define( 'AMRREV_ASSETS_URL', AMRREV_PLUGIN_URL . 'assets/' );
    }

    /**
     * Hooks
     */
    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

        // Activation hook
        register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
    }

    /**
     * Plugin Activation
     */
    public function plugin_activation() {
        // Set default options
        add_option( 'amrrev_auto_approve', '0' );
        add_option( 'amrrev_min_rating', '1' );
        add_option( 'amrrev_form_position', 'after' );
        add_option( 'amrrev_reviews_per_page', '10' );
        add_option( 'amrrev_enable_file_upload', '1' );
        add_option( 'amrrev_enable_age_range', '1' );
        add_option( 'amrrev_email_required', '1' );
        add_option( 'amrrev_title_required', '1' );
        add_option( 'amrrev_show_verified_badge', '1' );
        add_option( 'amrrev_date_format', 'j/n/y' );
        add_option( 'amrrev_show_filters', '1' );
        add_option( 'amrrev_empty_star_color', '#dddddd' );
        add_option( 'amrrev_filled_star_color', '#ffc107' );
        add_option( 'amrrev_enable_moderation', '0' );
        add_option( 'amrrev_bad_words', '' );
        add_option( 'amrrev_enable_email_notification', '1' );
        add_option( 'amrrev_admin_email', get_option( 'admin_email' ) );
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

        $this->includes();

        // Initialize main loader class
        new AMRREV_Ajax();
        new AMRREV_Filter();
        new AMRREV_Form_Handler();
        new AMRREV_Meta_Boxes();
        new AMRREV_Post_Type();
        new AMRREV_Settings();
    }

    /**
     * Include plugin files
     */
    private function includes() {

        require_once AMRREV_INCLUDES_DIR . 'class-amrrev-post-type.php';
        require_once AMRREV_INCLUDES_DIR . 'class-amrrev-meta-boxes.php';
        require_once AMRREV_INCLUDES_DIR . 'class-amrrev-form-handler.php';
        require_once AMRREV_INCLUDES_DIR . 'class-amrrev-filter.php';
        require_once AMRREV_INCLUDES_DIR . 'class-amrrev-ajax.php';
        require_once AMRREV_INCLUDES_DIR . 'class-amrrev-settings.php';
        require_once AMRREV_INCLUDES_DIR . 'class-amrrev-style-settings.php';
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {

        wp_enqueue_style( 'amrrev-public-review-form', AMRREV_PUBLIC_URL . 'css/review-form.css', array(), AMRREV_VERSION );
        wp_enqueue_style( 'amrrev-public-review-display', AMRREV_PUBLIC_URL . 'css/review-display.css', array(), AMRREV_VERSION );
        wp_enqueue_style( 'amrrev-public-review-filter', AMRREV_PUBLIC_URL . 'css/review-filters.css', array(), AMRREV_VERSION );

        wp_enqueue_script( 'amrrev-public-review-rating', AMRREV_PUBLIC_URL . 'js/review-rating.js', array( 'jquery' ), AMRREV_VERSION, true );
        wp_enqueue_script( 'amrrev-public-review-filter', AMRREV_PUBLIC_URL . 'js/review-filter.js', array( 'jquery' ), AMRREV_VERSION, true );

        wp_enqueue_script( 'amrrev-load-more', AMRREV_PUBLIC_URL . 'js/load-more.js', array( 'jquery' ), AMRREV_VERSION, true );
        wp_enqueue_script( 'amrrev-pagination', AMRREV_PUBLIC_URL . 'js/pagination.js', array( 'jquery' ), AMRREV_VERSION, true );

        // Custom CSS
        wp_add_inline_style( 'amrrev-public-review-display', AMRREV_Style_Settings::get_custom_css() );

        /**
         * 🔥 AJAX Data for FILTER
         */
        wp_localize_script( 'amrrev-public-review-filter', 'amrrev_filter_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'amrrev_filter_nonce' ),
        ) );

        /**
         * 🔥 AJAX Data for LOAD MORE + PAGINATION
         */
        $ajax_data = array(
            'ajax_url'         => admin_url( 'admin-ajax.php' ),
            'load_more_nonce'  => wp_create_nonce( 'amrrev_load_more_nonce' ),
            'pagination_nonce' => wp_create_nonce( 'amrrev_pagination_nonce' ),
        );

        wp_localize_script( 'amrrev-load-more', 'amrrev_ajax', $ajax_data );
        wp_localize_script( 'amrrev-pagination', 'amrrev_ajax', $ajax_data );

        /**
         * Star rating settings
         */
        wp_localize_script( 'amrrev-public-review-rating', 'amrrev_settings', array(
            'empty_star_color'  => get_option( 'amrrev_empty_star_color', '#dddddd' ),
            'filled_star_color' => get_option( 'amrrev_filled_star_color', '#ffc107' ),
            'min_rating'        => get_option( 'amrrev_min_rating', '1' ),
        ) );

        $this->add_dynamic_star_css();
    }

    /**
     * Add Dynamic Star Color CSS
    */
    private function add_dynamic_star_css() {
        $empty_star  = sanitize_hex_color( get_option( 'amrrev_empty_star_color', '#dddddd' ) );
        $filled_star = sanitize_hex_color( get_option( 'amrrev_filled_star_color', '#ffc107' ) );

        // Fallback to safe defaults if sanitize_hex_color returns empty
        if ( empty( $empty_star ) ) {
            $empty_star = '#dddddd';
        }
        if ( empty( $filled_star ) ) {
            $filled_star = '#ffc107';
        }

        $dynamic_css = '
            .amrrev-star-rating span {
                color: ' . $empty_star . ' !important;
            }
            .amrrev-star-rating span.selected {
                color: ' . $filled_star . ' !important;
            }
            .cpt-review-count {
                color: ' . $filled_star . ' !important;
            }
        ';

        wp_add_inline_style( 'amrrev-public-review-display', $dynamic_css );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets( $hook ) {
        if ( $hook == 'toplevel_page_amrrev-reviews' || $hook == 'product-reviews_page_amrrev-styles' ) {
            wp_enqueue_style( 'amrrev-admin-style', AMRREV_ADMIN_URL . 'css/admin-style.css', array(), AMRREV_VERSION );
            wp_enqueue_script( 'amrrev-admin-reviews', AMRREV_ADMIN_URL . 'js/admin-reviews.js', array( 'jquery' ), AMRREV_VERSION, true );
            wp_localize_script( 'amrrev-admin-reviews', 'amrrev_admin_ajax', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'amrrev_admin_ajax' ),
            ) );
        }

        // Enqueue style settings script
        if ( $hook == 'product-reviews_page_amrrev-styles' ) {
            wp_enqueue_script( 'amrrev-admin-style-settings', AMRREV_ADMIN_URL . 'js/admin-style-settings.js', array( 'jquery' ), AMRREV_VERSION, true );
        }
    }

    /**
     * WooCommerce missing notice
     */
    public function woocommerce_missing_notice() {
        echo '<div class="error"><p><strong>' . esc_html__( 'AmrRev requires WooCommerce to be installed and active.', 'amrrev-product-reviews-for-woocommerce' ) . '</strong></p></div>';
    }

    /**
     * Add Admin Menu
     */
    public function add_admin_menu() {
        add_menu_page(
            esc_html__( 'Product Reviews', 'amrrev-product-reviews-for-woocommerce' ),
            esc_html__( 'Product Reviews', 'amrrev-product-reviews-for-woocommerce' ),
            'manage_options',
            'amrrev-reviews',
            array( $this, 'render_manage_reviews_page' ),
            'dashicons-star-half',
            56
        );

        // Rename first submenu
        add_submenu_page(
            'amrrev-reviews',
            esc_html__( 'All Reviews', 'amrrev-product-reviews-for-woocommerce' ),
            esc_html__( 'All Reviews', 'amrrev-product-reviews-for-woocommerce' ),
            'manage_options',
            'amrrev-reviews',
            array( $this, 'render_manage_reviews_page' )
        );

        // Add Settings submenu
        add_submenu_page(
            'amrrev-reviews',
            esc_html__( 'Settings', 'amrrev-product-reviews-for-woocommerce' ),
            esc_html__( 'Settings', 'amrrev-product-reviews-for-woocommerce' ),
            'manage_options',
            'amrrev-settings',
            array( $this, 'render_settings_page' )
        );

        // Add Customize Styles submenu
        add_submenu_page(
            'amrrev-reviews',
            esc_html__( 'Customize Styles', 'amrrev-product-reviews-for-woocommerce' ),
            esc_html__( 'Customize Styles', 'amrrev-product-reviews-for-woocommerce' ),
            'manage_options',
            'amrrev-styles',
            array( $this, 'render_styles_page' )
        );

    }

    /**
     * Render Admin Page
     */
    public function render_manage_reviews_page() {
        require_once AMRREV_ADMIN_DIR . 'views/admin-reviews-page.php';
    }

    /**
     * Render Settings Page
     */
    public function render_settings_page() {
        $settings = new AMRREV_Settings();
        $settings->render_settings_page();
    }

    /**
     * Render Styles Page
     */
    public function render_styles_page() {
        require_once AMRREV_ADMIN_DIR . 'views/admin-styles-page.php';
    }

}

/**
 * Helper function
 */
function AmrRev() {
    return AmrRev_Product_Reviews::instance();
}

// Start plugin
AmrRev();