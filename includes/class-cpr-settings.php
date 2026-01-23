<?php
/**
 * Settings Class
 * includes/class-cpr-settings.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Settings {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Register Settings
     */
    public function register_settings() {
        // General Settings
        register_setting( 'cpr_general_settings', 'cpr_auto_approve', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '0'
        ) );
        
        register_setting( 'cpr_general_settings', 'cpr_min_rating', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );
        
        register_setting( 'cpr_general_settings', 'cpr_form_position', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'after'
        ) );
        
        register_setting( 'cpr_general_settings', 'cpr_reviews_per_page', array(
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 10
        ) );

        // Form Settings
        register_setting( 'cpr_form_settings', 'cpr_enable_file_upload', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );
        
        register_setting( 'cpr_form_settings', 'cpr_enable_age_range', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );
        
        register_setting( 'cpr_form_settings', 'cpr_email_required', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );
        
        register_setting( 'cpr_form_settings', 'cpr_title_required', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );

        // Display Settings
        register_setting( 'cpr_display_settings', 'cpr_show_verified_badge', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );
        
        register_setting( 'cpr_display_settings', 'cpr_date_format', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'j/n/y'
        ) );
        
        register_setting( 'cpr_display_settings', 'cpr_show_filters', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );
        
        register_setting( 'cpr_display_settings', 'cpr_empty_star_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#dddddd'
        ) );
        
        register_setting( 'cpr_display_settings', 'cpr_filled_star_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffc107'
        ) );

        // Advanced Settings
        register_setting( 'cpr_advanced_settings', 'cpr_enable_moderation', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '0'
        ) );
        
        register_setting( 'cpr_advanced_settings', 'cpr_bad_words', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'default' => ''
        ) );
        
        register_setting( 'cpr_advanced_settings', 'cpr_enable_email_notification', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );
        
        register_setting( 'cpr_advanced_settings', 'cpr_admin_email', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_email',
            'default' => get_option( 'admin_email' )
        ) );
    }

    /**
     * Render Settings Page
     */
    public function render_settings_page() {
        // Check if settings saved
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error(
                'cpr_messages',
                'cpr_message',
                esc_html__( 'Settings saved successfully', 'amrrev-product-reviews-for-woocommerce' ),
                'updated'
            );
        }

        settings_errors( 'cpr_messages' );
        
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <h2 class="nav-tab-wrapper">
                <a href="?page=cpr-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'General', 'amrrev-product-reviews-for-woocommerce' ); ?>
                </a>
                <a href="?page=cpr-settings&tab=form" class="nav-tab <?php echo $active_tab == 'form' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Form Settings', 'amrrev-product-reviews-for-woocommerce' ); ?>
                </a>
                <a href="?page=cpr-settings&tab=display" class="nav-tab <?php echo $active_tab == 'display' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Display Settings', 'amrrev-product-reviews-for-woocommerce' ); ?>
                </a>
                <a href="?page=cpr-settings&tab=advanced" class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e( 'Advanced', 'amrrev-product-reviews-for-woocommerce' ); ?>
                </a>
            </h2>

            <form method="post" action="options.php">
                <?php
                if ( $active_tab == 'general' ) {
                    settings_fields( 'cpr_general_settings' );
                    $this->render_general_settings();
                } elseif ( $active_tab == 'form' ) {
                    settings_fields( 'cpr_form_settings' );
                    $this->render_form_settings();
                } elseif ( $active_tab == 'display' ) {
                    settings_fields( 'cpr_display_settings' );
                    $this->render_display_settings();
                } elseif ( $active_tab == 'advanced' ) {
                    settings_fields( 'cpr_advanced_settings' );
                    $this->render_advanced_settings();
                }
                
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * General Settings Tab
     */
    private function render_general_settings() {
        $auto_approve = get_option( 'cpr_auto_approve', '0' );
        $min_rating = get_option( 'cpr_min_rating', '1' );
        $form_position = get_option( 'cpr_form_position', 'after' );
        $reviews_per_page = get_option( 'cpr_reviews_per_page', '10' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Auto Approve Reviews', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_auto_approve" value="1" <?php checked( $auto_approve, '1' ); ?>>
                        <?php esc_html_e( 'Automatically approve reviews (No manual approval needed)', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'If disabled, reviews will be in pending status and require admin approval.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_min_rating"><?php esc_html_e( 'Minimum Star Rating', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <select name="cpr_min_rating" id="cpr_min_rating">
                        <option value="1" <?php selected( $min_rating, '1' ); ?>>1 Star</option>
                        <option value="2" <?php selected( $min_rating, '2' ); ?>>2 Stars</option>
                        <option value="3" <?php selected( $min_rating, '3' ); ?>>3 Stars</option>
                        <option value="4" <?php selected( $min_rating, '4' ); ?>>4 Stars</option>
                        <option value="5" <?php selected( $min_rating, '5' ); ?>>5 Stars</option>
                    </select>
                    <p class="description"><?php esc_html_e( 'Reviews below this rating will not be accepted.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_form_position"><?php esc_html_e( 'Review Form Position', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <select name="cpr_form_position" id="cpr_form_position">
                        <option value="before" <?php selected( $form_position, 'before' ); ?>><?php esc_html_e( 'Before Product Summary', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                        <option value="after" <?php selected( $form_position, 'after' ); ?>><?php esc_html_e( 'After Product Summary', 'amrrev-product-reviews-for-woocommerce' ); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e( 'Where to display the review form on product page.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_reviews_per_page"><?php esc_html_e( 'Reviews Per Page', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <input type="number" name="cpr_reviews_per_page" id="cpr_reviews_per_page" value="<?php echo esc_attr( $reviews_per_page ); ?>" min="2" max="100" class="small-text">
                    <p class="description"><?php esc_html_e( 'Number of reviews to display per page (pagination).', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Form Settings Tab
     */
    private function render_form_settings() {
        $enable_file = get_option( 'cpr_enable_file_upload', '1' );
        $enable_age = get_option( 'cpr_enable_age_range', '1' );
        $email_required = get_option( 'cpr_email_required', '1' );
        $title_required = get_option( 'cpr_title_required', '1' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'File Upload', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_enable_file_upload" value="1" <?php checked( $enable_file, '1' ); ?>>
                        <?php esc_html_e( 'Enable file upload (JPG, PNG, PDF)', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Allow customers to upload images or documents with their review.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Age Range Field', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_enable_age_range" value="1" <?php checked( $enable_age, '1' ); ?>>
                        <?php esc_html_e( 'Show age range selection field', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Ask customers to select their age range when submitting a review.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Email Field', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_email_required" value="1" <?php checked( $email_required, '1' ); ?>>
                        <?php esc_html_e( 'Email address is required', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Make email field mandatory for review submission.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Review Title', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_title_required" value="1" <?php checked( $title_required, '1' ); ?>>
                        <?php esc_html_e( 'Review title is required', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Make review title field mandatory.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Display Settings Tab
     */
    private function render_display_settings() {
        $show_badge = get_option( 'cpr_show_verified_badge', '1' );
        $date_format = get_option( 'cpr_date_format', 'j/n/y' );
        $show_filters = get_option( 'cpr_show_filters', '1' );
        $empty_star = get_option( 'cpr_empty_star_color', '#dddddd' );
        $filled_star = get_option( 'cpr_filled_star_color', '#ffc107' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Verified Buyer Badge', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_show_verified_badge" value="1" <?php checked( $show_badge, '1' ); ?>>
                        <?php esc_html_e( 'Show verified buyer badge on reviews', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Display a badge for verified purchasers.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_date_format"><?php esc_html_e( 'Date Format', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <select name="cpr_date_format" id="cpr_date_format">
                        <option value="j/n/y" <?php selected( $date_format, 'j/n/y' ); ?>>29/11/25</option>
                        <option value="d/m/Y" <?php selected( $date_format, 'd/m/Y' ); ?>>29/11/2025</option>
                        <option value="F j, Y" <?php selected( $date_format, 'F j, Y' ); ?>>November 29, 2025</option>
                        <option value="M j, Y" <?php selected( $date_format, 'M j, Y' ); ?>>Nov 29, 2025</option>
                        <option value="Y-m-d" <?php selected( $date_format, 'Y-m-d' ); ?>>2025-11-29</option>
                    </select>
                    <p class="description"><?php esc_html_e( 'How to display review submission date.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Review Filters', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_show_filters" value="1" <?php checked( $show_filters, '1' ); ?>>
                        <?php esc_html_e( 'Show filter options (Rating, Age Range, Verified)', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Allow customers to filter reviews by rating, age range, etc.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_empty_star_color"><?php esc_html_e( 'Empty Star Color', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <input type="color" name="cpr_empty_star_color" id="cpr_empty_star_color" value="<?php echo esc_attr( $empty_star ); ?>">
                    <p class="description"><?php esc_html_e( 'Color for empty/unfilled stars.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_filled_star_color"><?php esc_html_e( 'Filled Star Color', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <input type="color" name="cpr_filled_star_color" id="cpr_filled_star_color" value="<?php echo esc_attr( $filled_star ); ?>">
                    <p class="description"><?php esc_html_e( 'Color for filled/selected stars.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Advanced Settings Tab
     */
    private function render_advanced_settings() {
        $enable_moderation = get_option( 'cpr_enable_moderation', '0' );
        $bad_words = get_option( 'cpr_bad_words', '' );
        $enable_email = get_option( 'cpr_enable_email_notification', '1' );
        $admin_email = get_option( 'cpr_admin_email', get_option( 'admin_email' ) );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Review Moderation', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_enable_moderation" value="1" <?php checked( $enable_moderation, '1' ); ?>>
                        <?php esc_html_e( 'Enable bad words filter', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Automatically reject reviews containing inappropriate words.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_bad_words"><?php esc_html_e( 'Bad Words List', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <textarea name="cpr_bad_words" id="cpr_bad_words" rows="5" class="large-text"><?php echo esc_textarea( $bad_words ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Add words separated by commas. Reviews containing these words will be automatically rejected.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Email Notifications', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_enable_email_notification" value="1" <?php checked( $enable_email, '1' ); ?>>
                        <?php esc_html_e( 'Send email notification when a new review is submitted', 'amrrev-product-reviews-for-woocommerce' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Admin will receive an email alert for each new review.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_admin_email"><?php esc_html_e( 'Admin Email Address', 'amrrev-product-reviews-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <input type="email" name="cpr_admin_email" id="cpr_admin_email" value="<?php echo esc_attr( $admin_email ); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e( 'Email address to receive review notifications.', 'amrrev-product-reviews-for-woocommerce' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }
}

new CPR_Settings();