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
        register_setting( 'cpr_general_settings', 'cpr_auto_approve' );
        register_setting( 'cpr_general_settings', 'cpr_min_rating' );
        register_setting( 'cpr_general_settings', 'cpr_form_position' );
        register_setting( 'cpr_general_settings', 'cpr_reviews_per_page' );

        // Form Settings
        register_setting( 'cpr_form_settings', 'cpr_enable_file_upload' );
        register_setting( 'cpr_form_settings', 'cpr_enable_age_range' );
        register_setting( 'cpr_form_settings', 'cpr_email_required' );
        register_setting( 'cpr_form_settings', 'cpr_title_required' );

        // Display Settings
        register_setting( 'cpr_display_settings', 'cpr_show_verified_badge' );
        register_setting( 'cpr_display_settings', 'cpr_date_format' );
        register_setting( 'cpr_display_settings', 'cpr_show_filters' );
        register_setting( 'cpr_display_settings', 'cpr_empty_star_color' );
        register_setting( 'cpr_display_settings', 'cpr_filled_star_color' );

        // Advanced Settings
        register_setting( 'cpr_advanced_settings', 'cpr_enable_moderation' );
        register_setting( 'cpr_advanced_settings', 'cpr_bad_words' );
        register_setting( 'cpr_advanced_settings', 'cpr_enable_email_notification' );
        register_setting( 'cpr_advanced_settings', 'cpr_admin_email' );
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
                __( 'Settings saved successfully', 'custom-product-reviews' ),
                'updated'
            );
        }

        settings_errors( 'cpr_messages' );
        
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <h2 class="nav-tab-wrapper">
                <a href="?page=cpr-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'General', 'custom-product-reviews' ); ?>
                </a>
                <a href="?page=cpr-settings&tab=form" class="nav-tab <?php echo $active_tab == 'form' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'Form Settings', 'custom-product-reviews' ); ?>
                </a>
                <a href="?page=cpr-settings&tab=display" class="nav-tab <?php echo $active_tab == 'display' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'Display Settings', 'custom-product-reviews' ); ?>
                </a>
                <a href="?page=cpr-settings&tab=advanced" class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'Advanced', 'custom-product-reviews' ); ?>
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
                    <label><?php _e( 'Auto Approve Reviews', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_auto_approve" value="1" <?php checked( $auto_approve, '1' ); ?>>
                        <?php _e( 'Automatically approve reviews (No manual approval needed)', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'If disabled, reviews will be in pending status and require admin approval.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_min_rating"><?php _e( 'Minimum Star Rating', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <select name="cpr_min_rating" id="cpr_min_rating">
                        <option value="1" <?php selected( $min_rating, '1' ); ?>>1 Star</option>
                        <option value="2" <?php selected( $min_rating, '2' ); ?>>2 Stars</option>
                        <option value="3" <?php selected( $min_rating, '3' ); ?>>3 Stars</option>
                        <option value="4" <?php selected( $min_rating, '4' ); ?>>4 Stars</option>
                        <option value="5" <?php selected( $min_rating, '5' ); ?>>5 Stars</option>
                    </select>
                    <p class="description"><?php _e( 'Reviews below this rating will not be accepted.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_form_position"><?php _e( 'Review Form Position', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <select name="cpr_form_position" id="cpr_form_position">
                        <option value="before" <?php selected( $form_position, 'before' ); ?>><?php _e( 'Before Product Summary', 'custom-product-reviews' ); ?></option>
                        <option value="after" <?php selected( $form_position, 'after' ); ?>><?php _e( 'After Product Summary', 'custom-product-reviews' ); ?></option>
                    </select>
                    <p class="description"><?php _e( 'Where to display the review form on product page.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_reviews_per_page"><?php _e( 'Reviews Per Page', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <input type="number" name="cpr_reviews_per_page" id="cpr_reviews_per_page" value="<?php echo esc_attr( $reviews_per_page ); ?>" min="2" max="100" class="small-text">
                    <p class="description"><?php _e( 'Number of reviews to display per page (pagination).', 'custom-product-reviews' ); ?></p>
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
                    <label><?php _e( 'File Upload', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_enable_file_upload" value="1" <?php checked( $enable_file, '1' ); ?>>
                        <?php _e( 'Enable file upload (JPG, PNG, PDF)', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'Allow customers to upload images or documents with their review.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php _e( 'Age Range Field', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_enable_age_range" value="1" <?php checked( $enable_age, '1' ); ?>>
                        <?php _e( 'Show age range selection field', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'Ask customers to select their age range when submitting a review.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php _e( 'Email Field', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_email_required" value="1" <?php checked( $email_required, '1' ); ?>>
                        <?php _e( 'Email address is required', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'Make email field mandatory for review submission.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php _e( 'Review Title', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_title_required" value="1" <?php checked( $title_required, '1' ); ?>>
                        <?php _e( 'Review title is required', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'Make review title field mandatory.', 'custom-product-reviews' ); ?></p>
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
                    <label><?php _e( 'Verified Buyer Badge', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_show_verified_badge" value="1" <?php checked( $show_badge, '1' ); ?>>
                        <?php _e( 'Show verified buyer badge on reviews', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'Display a badge for verified purchasers.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_date_format"><?php _e( 'Date Format', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <select name="cpr_date_format" id="cpr_date_format">
                        <option value="j/n/y" <?php selected( $date_format, 'j/n/y' ); ?>>29/11/25</option>
                        <option value="d/m/Y" <?php selected( $date_format, 'd/m/Y' ); ?>>29/11/2025</option>
                        <option value="F j, Y" <?php selected( $date_format, 'F j, Y' ); ?>>November 29, 2025</option>
                        <option value="M j, Y" <?php selected( $date_format, 'M j, Y' ); ?>>Nov 29, 2025</option>
                        <option value="Y-m-d" <?php selected( $date_format, 'Y-m-d' ); ?>>2025-11-29</option>
                    </select>
                    <p class="description"><?php _e( 'How to display review submission date.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php _e( 'Review Filters', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_show_filters" value="1" <?php checked( $show_filters, '1' ); ?>>
                        <?php _e( 'Show filter options (Rating, Age Range, Verified)', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'Allow customers to filter reviews by rating, age range, etc.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_empty_star_color"><?php _e( 'Empty Star Color', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <input type="color" name="cpr_empty_star_color" id="cpr_empty_star_color" value="<?php echo esc_attr( $empty_star ); ?>">
                    <p class="description"><?php _e( 'Color for empty/unfilled stars.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_filled_star_color"><?php _e( 'Filled Star Color', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <input type="color" name="cpr_filled_star_color" id="cpr_filled_star_color" value="<?php echo esc_attr( $filled_star ); ?>">
                    <p class="description"><?php _e( 'Color for filled/selected stars.', 'custom-product-reviews' ); ?></p>
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
                    <label><?php _e( 'Review Moderation', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_enable_moderation" value="1" <?php checked( $enable_moderation, '1' ); ?>>
                        <?php _e( 'Enable bad words filter', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'Automatically reject reviews containing inappropriate words.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_bad_words"><?php _e( 'Bad Words List', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <textarea name="cpr_bad_words" id="cpr_bad_words" rows="5" class="large-text"><?php echo esc_textarea( $bad_words ); ?></textarea>
                    <p class="description"><?php _e( 'Add words separated by commas. Reviews containing these words will be automatically rejected.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php _e( 'Email Notifications', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cpr_enable_email_notification" value="1" <?php checked( $enable_email, '1' ); ?>>
                        <?php _e( 'Send email notification when a new review is submitted', 'custom-product-reviews' ); ?>
                    </label>
                    <p class="description"><?php _e( 'Admin will receive an email alert for each new review.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="cpr_admin_email"><?php _e( 'Admin Email Address', 'custom-product-reviews' ); ?></label>
                </th>
                <td>
                    <input type="email" name="cpr_admin_email" id="cpr_admin_email" value="<?php echo esc_attr( $admin_email ); ?>" class="regular-text">
                    <p class="description"><?php _e( 'Email address to receive review notifications.', 'custom-product-reviews' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }
}

new CPR_Settings();