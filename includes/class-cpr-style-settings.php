<?php
/**
 * Style Settings Class
 * includes/class-cpr-style-settings.php
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Style_Settings {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'register_style_settings' ) );
    }

    /**
     * Register Style Settings
     */
    public function register_style_settings() {
        // Form Styles
        register_setting( 'cpr_style_settings', 'cpr_form_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_border_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#e0e0e0'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_border_width', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1px'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_border_radius', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '8px'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_padding', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '20px'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_title_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#333333'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_label_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#555555'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_input_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_input_border_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#dddddd'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_input_text_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#333333'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_button_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#0073aa'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_button_text_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_button_hover_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#005a87'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_form_button_hover_text_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff'
        ) );

        // Review Styles
        register_setting( 'cpr_style_settings', 'cpr_review_box_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#f9f9f9'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_review_box_border_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#e0e0e0'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_review_box_border_width', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_review_box_border_radius', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '8px'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_review_box_padding', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '15px'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_review_name_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#333333'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_review_date_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#777777'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_review_content_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#555555'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_star_size', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '16px'
        ) );

        // Filter Styles
        register_setting( 'cpr_style_settings', 'cpr_filter_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#f9f9f9'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_border_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#f0e6d3'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_border_width', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_border_radius', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '8px'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_padding', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '15px'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_title_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#333333'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_label_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#555555'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_input_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_input_border_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#dddddd'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_input_text_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#333333'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_checkbox_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#0073aa'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_select_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#ffffff'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_select_border_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#dddddd'
        ) );
        
        register_setting( 'cpr_style_settings', 'cpr_filter_select_text_color', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#333333'
        ) );

        register_setting( 'cpr_style_settings', 'cpr_custom_css', array(
            'type' => 'string',
            'sanitize_callback' => array( $this, 'sanitize_css' ),
            'default' => ''
        ) );
    }

    /**
     * Sanitize CSS - Remove script tags and dangerous content
     */
    public function sanitize_css( $css ) {
        // Remove any script tags or dangerous content
        $css = wp_strip_all_tags( $css );
        
        // Remove potential XSS vectors
        $css = str_replace( array( '<script', '</script', 'javascript:', 'expression(' ), '', $css );
        
        return $css;
    }

    /**
     * Render Style Settings Page
     */
    public function render_style_settings_page() {
        
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading WordPress core settings-updated parameter
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error(
                'cpr_style_messages',
                'cpr_style_message',
                esc_html__( 'Styles saved successfully', 'revwoo-product-reviews' ),
                'updated'
            );
        }

        settings_errors( 'cpr_style_messages' );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'cpr_style_settings' ); ?>

                <div class="cpr-style-settings-tabs">
                    <h2 class="nav-tab-wrapper">
                        <a href="#form-styles" class="nav-tab nav-tab-active"><?php esc_html_e( 'Form Styles', 'revwoo-product-reviews' ); ?></a>
                        <a href="#review-styles" class="nav-tab"><?php esc_html_e( 'Review Styles', 'revwoo-product-reviews' ); ?></a>
                        <a href="#filter-styles" class="nav-tab"><?php esc_html_e( 'Filter Styles', 'revwoo-product-reviews' ); ?></a>
                        <a href="#custom-css" class="nav-tab"><?php esc_html_e( 'Custom CSS', 'revwoo-product-reviews' ); ?></a>
                    </h2>

                    <div id="form-styles" class="cpr-style-tab-content">
                        <?php $this->render_form_styles(); ?>
                    </div>

                    <div id="review-styles" class="cpr-style-tab-content" style="display:none;">
                        <?php $this->render_review_styles(); ?>
                    </div>

                    <div id="filter-styles" class="cpr-style-tab-content" style="display:none;">
                        <?php $this->render_filter_styles(); ?>
                    </div>

                    <div id="custom-css" class="cpr-style-tab-content" style="display:none;">
                        <?php $this->render_custom_css(); ?>
                    </div>
                </div>

                <?php submit_button(); ?>
            </form>

             
        </div>
        <?php
    }

    /**
     * Render Filter Styles Section
     */
    private function render_filter_styles() {
        $filter_bg = get_option( 'cpr_filter_bg_color', '#f9f9f9' );
        $filter_border = get_option( 'cpr_filter_border_color', '#f0e6d3' );
        $filter_border_width = get_option( 'cpr_filter_border_width', '1' );
        $filter_radius = get_option( 'cpr_filter_border_radius', '8px' );
        $filter_padding = get_option( 'cpr_filter_padding', '15px' );
        $title_color = get_option( 'cpr_filter_title_color', '#333333' );
        $label_color = get_option( 'cpr_filter_label_color', '#555555' );
        $input_bg = get_option( 'cpr_filter_input_bg_color', '#ffffff' );
        $input_border = get_option( 'cpr_filter_input_border_color', '#dddddd' );
        $input_text = get_option( 'cpr_filter_input_text_color', '#333333' );
        $checkbox_color = get_option( 'cpr_filter_checkbox_color', '#0073aa' );
        $select_bg = get_option( 'cpr_filter_select_bg_color', '#ffffff' );
        $select_border = get_option( 'cpr_filter_select_border_color', '#dddddd' );
        $select_text = get_option( 'cpr_filter_select_text_color', '#333333' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Section Background', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_bg_color" value="<?php echo esc_attr( $filter_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Section Border', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_border_color" value="<?php echo esc_attr( $filter_border ); ?>" />
                    <input type="text" name="cpr_filter_border_width" value="<?php echo esc_attr( $filter_border_width ); ?>" class="small-text" placeholder="1px" />
                    <input type="text" name="cpr_filter_border_radius" value="<?php echo esc_attr( $filter_radius ); ?>" class="small-text" placeholder="8px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Section Padding', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="text" name="cpr_filter_padding" value="<?php echo esc_attr( $filter_padding ); ?>" class="regular-text" placeholder="15px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Title Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_title_color" value="<?php echo esc_attr( $title_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Label Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_label_color" value="<?php echo esc_attr( $label_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Input Background', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_input_bg_color" value="<?php echo esc_attr( $input_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Input Border', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_input_border_color" value="<?php echo esc_attr( $input_border ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Input Text Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_input_text_color" value="<?php echo esc_attr( $input_text ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Checkbox Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_checkbox_color" value="<?php echo esc_attr( $checkbox_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Select Background', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_select_bg_color" value="<?php echo esc_attr( $select_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Select Border', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_select_border_color" value="<?php echo esc_attr( $select_border ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Select Text Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_filter_select_text_color" value="<?php echo esc_attr( $select_text ); ?>" />
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render Form Styles Section
     */
    private function render_form_styles() {
        $form_bg = get_option( 'cpr_form_bg_color', '#ffffff' );
        $form_border = get_option( 'cpr_form_border_color', '#e0e0e0' );
        $form_border_width = get_option( 'cpr_form_border_width', '1px' );
        $form_radius = get_option( 'cpr_form_border_radius', '8px' );
        $form_padding = get_option( 'cpr_form_padding', '20px' );
        $title_color = get_option( 'cpr_form_title_color', '#333333' );
        $label_color = get_option( 'cpr_form_label_color', '#555555' );
        $input_bg = get_option( 'cpr_form_input_bg_color', '#ffffff' );
        $input_border = get_option( 'cpr_form_input_border_color', '#dddddd' );
        $input_text = get_option( 'cpr_form_input_text_color', '#333333' );
        $button_bg = get_option( 'cpr_form_button_bg_color', '#0073aa' );
        $button_text = get_option( 'cpr_form_button_text_color', '#ffffff' );
        $button_hover_bg = get_option( 'cpr_form_button_hover_bg_color', '#005a87' );
        $button_hover_text = get_option( 'cpr_form_button_hover_text_color', '#ffffff' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Form Background', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_bg_color" value="<?php echo esc_attr( $form_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Form Border', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_border_color" value="<?php echo esc_attr( $form_border ); ?>" />
                    <input type="text" name="cpr_form_border_width" value="<?php echo esc_attr( $form_border_width ); ?>" class="small-text" placeholder="1px" />
                    <input type="text" name="cpr_form_border_radius" value="<?php echo esc_attr( $form_radius ); ?>" class="small-text" placeholder="8px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Form Padding', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="text" name="cpr_form_padding" value="<?php echo esc_attr( $form_padding ); ?>" class="regular-text" placeholder="20px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Form Title Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_title_color" value="<?php echo esc_attr( $title_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Form Label Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_label_color" value="<?php echo esc_attr( $label_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Input Background', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_input_bg_color" value="<?php echo esc_attr( $input_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Input Border', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_input_border_color" value="<?php echo esc_attr( $input_border ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Input Text Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_input_text_color" value="<?php echo esc_attr( $input_text ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Button Background', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_button_bg_color" value="<?php echo esc_attr( $button_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Button Text Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_button_text_color" value="<?php echo esc_attr( $button_text ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Button Hover Background', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_button_hover_bg_color" value="<?php echo esc_attr( $button_hover_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Button Hover Text', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_form_button_hover_text_color" value="<?php echo esc_attr( $button_hover_text ); ?>" />
                </td>
            </tr>
        </table>
        <?php
}

    /**
     * Render Review Styles Section
     */
    private function render_review_styles() {
        $review_bg = get_option( 'cpr_review_box_bg_color', '#f9f9f9' );
        $review_border = get_option( 'cpr_review_box_border_color', '#e0e0e0' );
        $review_border_width = get_option( 'cpr_review_box_border_width', '' );
        $review_radius = get_option( 'cpr_review_box_border_radius', '8px' );
        $review_padding = get_option( 'cpr_review_box_padding', '15px' );
        $name_color = get_option( 'cpr_review_name_color', '#333333' );
        $date_color = get_option( 'cpr_review_date_color', '#777777' );
        $content_color = get_option( 'cpr_review_content_color', '#555555' );
        $star_size = get_option( 'cpr_star_size', '16px' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Review Box Background', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_review_box_bg_color" value="<?php echo esc_attr( $review_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Review Box Border', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_review_box_border_color" value="<?php echo esc_attr( $review_border ); ?>" />
                    <input type="text" name="cpr_review_box_border_width" value="<?php echo esc_attr( $review_border_width ); ?>" class="small-text" placeholder="1px" />
                    <input type="text" name="cpr_review_box_border_radius" value="<?php echo esc_attr( $review_radius ); ?>" class="small-text" placeholder="8px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Review Box Padding', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="text" name="cpr_review_box_padding" value="<?php echo esc_attr( $review_padding ); ?>" class="regular-text" placeholder="15px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Reviewer Name Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_review_name_color" value="<?php echo esc_attr( $name_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Review Date Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_review_date_color" value="<?php echo esc_attr( $date_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Review Content Color', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="color" name="cpr_review_content_color" value="<?php echo esc_attr( $content_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Star Size', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <input type="text" name="cpr_star_size" value="<?php echo esc_attr( $star_size ); ?>" class="small-text" placeholder="16px" />
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render Custom CSS Section
     */
    private function render_custom_css() {
        $custom_css = get_option( 'cpr_custom_css', '' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Custom CSS', 'revwoo-product-reviews' ); ?></th>
                <td>
                    <textarea name="cpr_custom_css" rows="20" class="large-text"><?php echo esc_textarea( $custom_css ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Add your custom CSS code here. It will be applied to all review forms and displays.', 'revwoo-product-reviews' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Generate Custom CSS
     */
    public static function get_custom_css() {
        // Get all style options
        $form_bg = get_option( 'cpr_form_bg_color', '' );
        $form_border = get_option( 'cpr_form_border_color', '' );
        $form_border_width = get_option( 'cpr_form_border_width', '' );
        $form_radius = get_option( 'cpr_form_border_radius', '' );
        $form_padding = get_option( 'cpr_form_padding', '' );
        $title_color = get_option( 'cpr_form_title_color', '' );
        $label_color = get_option( 'cpr_form_label_color', '' );
        $input_bg = get_option( 'cpr_form_input_bg_color', '' );
        $input_border = get_option( 'cpr_form_input_border_color', '' );
        $input_text = get_option( 'cpr_form_input_text_color', '' );
        $button_bg = get_option( 'cpr_form_button_bg_color', '' );
        $button_text = get_option( 'cpr_form_button_text_color', '' );
        $button_hover_bg = get_option( 'cpr_form_button_hover_bg_color', '' );
        $button_hover_text = get_option( 'cpr_form_button_hover_text_color', '' );
        $review_bg = get_option( 'cpr_review_box_bg_color', '' );
        $review_border = get_option( 'cpr_review_box_border_color', '' );
        $review_border_width = get_option( 'cpr_review_box_border_width', '' );
        $review_radius = get_option( 'cpr_review_box_border_radius', '8px' );
        $review_padding = get_option( 'cpr_review_box_padding', '15px' );
        $name_color = get_option( 'cpr_review_name_color', '' );
        $date_color = get_option( 'cpr_review_date_color', '' );
        $content_color = get_option( 'cpr_review_content_color', '' );
        $star_size = get_option( 'cpr_star_size', '16px' );

        // Filter styles
        $filter_bg = get_option( 'cpr_filter_bg_color', '' );
        $filter_border = get_option( 'cpr_filter_border_color', '#f0e6d3' );
        $filter_border_width = get_option( 'cpr_filter_border_width', '1' );
        $filter_radius = get_option( 'cpr_filter_border_radius', '' );
        $filter_padding = get_option( 'cpr_filter_padding', '' );
        $filter_title_color = get_option( 'cpr_filter_title_color', '' );
        $filter_label_color = get_option( 'cpr_filter_label_color', '' );
        $filter_input_bg = get_option( 'cpr_filter_input_bg_color', '' );
        $filter_input_border = get_option( 'cpr_filter_input_border_color', '' );
        $filter_input_text = get_option( 'cpr_filter_input_text_color', '' );
        $filter_checkbox_color = get_option( 'cpr_filter_checkbox_color', '' );
        $filter_select_bg = get_option( 'cpr_filter_select_bg_color', '' );
        $filter_select_border = get_option( 'cpr_filter_select_border_color', '' );
        $filter_select_text = get_option( 'cpr_filter_select_text_color', '' );

        $custom_css = get_option( 'cpr_custom_css', '' );

        // Generate CSS
        $css = "
        /* RevWoo - Form Styles */
        .cpr-review-form-section {
            background-color: {$form_bg}!important;
            border: {$form_border_width}px solid {$form_border}!important;
            border-radius: {$form_radius}!important;
            padding: {$form_padding}!important;
        }

        .cpr-review-form-section h3 {
            color: {$title_color}!important;
        }

        .cpr-form-field label {
            color: {$label_color}!important;
        }

        .cpr-form-field input[type=\"text\"],
        .cpr-form-field input[type=\"email\"],
        .cpr-form-field textarea {
            background-color: {$input_bg}!important;
            border-color: {$input_border}!important;
            color: {$input_text}!important;
        }

        .cpr-submit-btn {
            background-color: {$button_bg}!important;
            color: {$button_text}!important;
        }

        .cpr-submit-btn:hover {
            background-color: {$button_hover_bg}!important;
            color: {$button_hover_text}!important;
        }

        /* RevWoo - Review Display Styles */
        .cpt-review-full-box {
            background-color: {$review_bg}!important;
            border: {$review_border_width}px solid {$review_border}!important;
            border-radius: {$review_radius}!important;
            padding: {$review_padding}!important;
        }

        .cpt-name {
            color: {$name_color}!important;
        }

        .cpt-date {
            color: {$date_color}!important;
        }

        .cpt-review-content {
            color: {$content_color}!important;
        }

        .cpt-review-count span {
            font-size: {$star_size}!important;
        }

        /* RevWoo - Filter Styles */
        .cpr-review-filters {
            background-color: {$filter_bg}!important;
            border: {$filter_border_width}px solid {$filter_border}!important;
            border-radius: {$filter_radius}!important;
            padding: {$filter_padding}!important;
        }

        .cpr-review-filters h4 {
            color: {$filter_title_color}!important;
        }

        .cpr-filter-group label {
            color: {$filter_label_color}!important;
        }

        .cpr-rating-filter input[type=\"checkbox\"]:checked,
        .cpr-filter-group input[type=\"checkbox\"]:checked {
            accent-color: {$filter_checkbox_color}!important;
        }

        .cpr-age-filter select {
            background-color: {$filter_select_bg}!important;
            border-color: {$filter_select_border}!important;
            color: {$filter_select_text}!important;
        }

        /* RevWoo - Custom CSS */
        {$custom_css}
        ";

        return $css;
    }

   
}

new CPR_Style_Settings();