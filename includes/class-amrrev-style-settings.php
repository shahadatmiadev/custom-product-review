<?php
/**
 * Style Settings Class
 * includes/class-amrrev-style-settings.php
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class AMRREV_Style_Settings {

    public function __construct() {
        add_action( 'admin_init', array( $this, 'register_style_settings' ) );
    }

    /**
     * Register Style Settings
     */
    public function register_style_settings() {
        // Form Styles
        register_setting( 'amrrev_style_settings', 'amrrev_form_bg_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#ffffff',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_border_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#e0e0e0',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_border_width', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '1px',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_border_radius', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '8px',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_padding', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '20px',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_title_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#333333',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_label_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#555555',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_input_bg_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#ffffff',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_input_border_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#dddddd',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_input_text_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#333333',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_button_bg_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#0073aa',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_button_text_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#ffffff',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_button_hover_bg_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#005a87',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_form_button_hover_text_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#ffffff',
        ) );

        // Review Styles
        register_setting( 'amrrev_style_settings', 'amrrev_review_box_bg_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#f9f9f9',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_review_box_border_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#e0e0e0',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_review_box_border_width', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_review_box_border_radius', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '8px',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_review_box_padding', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '15px',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_review_name_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#333333',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_review_date_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#777777',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_review_content_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#555555',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_star_size', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '16px',
        ) );

        // Filter Styles
        register_setting( 'amrrev_style_settings', 'amrrev_filter_bg_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#f9f9f9',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_border_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#f0e6d3',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_border_width', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '1',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_border_radius', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '8px',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_padding', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '15px',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_title_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#333333',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_label_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#555555',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_input_bg_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#ffffff',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_input_border_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#dddddd',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_input_text_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#333333',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_checkbox_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#0073aa',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_select_bg_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#ffffff',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_select_border_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#dddddd',
        ) );

        register_setting( 'amrrev_style_settings', 'amrrev_filter_select_text_color', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#333333',
        ) );
    }

    /**
     * Render Style Settings Page
     */
    public function render_style_settings_page() {

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading WordPress core settings-updated parameter
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error(
                'amrrev_style_messages',
                'amrrev_style_message',
                esc_html__( 'Styles saved successfully', 'amrrev-product-reviews-for-woocommerce' ),
                'updated'
            );
        }

        settings_errors( 'amrrev_style_messages' );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'amrrev_style_settings' ); ?>

                <div class="amrrev-style-settings-tabs">
                    <h2 class="nav-tab-wrapper">
                        <a href="#form-styles" class="nav-tab nav-tab-active"><?php esc_html_e( 'Form Styles', 'amrrev-product-reviews-for-woocommerce' ); ?></a>
                        <a href="#review-styles" class="nav-tab"><?php esc_html_e( 'Review Styles', 'amrrev-product-reviews-for-woocommerce' ); ?></a>
                        <a href="#filter-styles" class="nav-tab"><?php esc_html_e( 'Filter Styles', 'amrrev-product-reviews-for-woocommerce' ); ?></a>
                    </h2>

                    <div id="form-styles" class="amrrev-style-tab-content">
                        <?php $this->render_form_styles(); ?>
                    </div>

                    <div id="review-styles" class="amrrev-style-tab-content" style="display:none;">
                        <?php $this->render_review_styles(); ?>
                    </div>

                    <div id="filter-styles" class="amrrev-style-tab-content" style="display:none;">
                        <?php $this->render_filter_styles(); ?>
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
        $filter_bg = get_option( 'amrrev_filter_bg_color', '#f9f9f9' );
        $filter_border = get_option( 'amrrev_filter_border_color', '#f0e6d3' );
        $filter_border_width = get_option( 'amrrev_filter_border_width', '1' );
        $filter_radius = get_option( 'amrrev_filter_border_radius', '8px' );
        $filter_padding = get_option( 'amrrev_filter_padding', '15px' );
        $title_color = get_option( 'amrrev_filter_title_color', '#333333' );
        $label_color = get_option( 'amrrev_filter_label_color', '#555555' );
        $input_bg = get_option( 'amrrev_filter_input_bg_color', '#ffffff' );
        $input_border = get_option( 'amrrev_filter_input_border_color', '#dddddd' );
        $input_text = get_option( 'amrrev_filter_input_text_color', '#333333' );
        $checkbox_color = get_option( 'amrrev_filter_checkbox_color', '#0073aa' );
        $select_bg = get_option( 'amrrev_filter_select_bg_color', '#ffffff' );
        $select_border = get_option( 'amrrev_filter_select_border_color', '#dddddd' );
        $select_text = get_option( 'amrrev_filter_select_text_color', '#333333' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Section Background', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_bg_color" value="<?php echo esc_attr( $filter_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Section Border', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_border_color" value="<?php echo esc_attr( $filter_border ); ?>" />
                    <input type="text" name="amrrev_filter_border_width" value="<?php echo esc_attr( $filter_border_width ); ?>" class="small-text" placeholder="1px" />
                    <input type="text" name="amrrev_filter_border_radius" value="<?php echo esc_attr( $filter_radius ); ?>" class="small-text" placeholder="8px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Section Padding', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="text" name="amrrev_filter_padding" value="<?php echo esc_attr( $filter_padding ); ?>" class="regular-text" placeholder="15px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Title Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_title_color" value="<?php echo esc_attr( $title_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Label Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_label_color" value="<?php echo esc_attr( $label_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Input Background', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_input_bg_color" value="<?php echo esc_attr( $input_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Input Border', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_input_border_color" value="<?php echo esc_attr( $input_border ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Input Text Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_input_text_color" value="<?php echo esc_attr( $input_text ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Checkbox Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_checkbox_color" value="<?php echo esc_attr( $checkbox_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Select Background', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_select_bg_color" value="<?php echo esc_attr( $select_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Select Border', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_select_border_color" value="<?php echo esc_attr( $select_border ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Filter Select Text Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_filter_select_text_color" value="<?php echo esc_attr( $select_text ); ?>" />
                </td>
            </tr>
        </table>
        <?php
}

    /**
     * Render Form Styles Section
     */
    private function render_form_styles() {
        $form_bg = get_option( 'amrrev_form_bg_color', '#ffffff' );
        $form_border = get_option( 'amrrev_form_border_color', '#e0e0e0' );
        $form_border_width = get_option( 'amrrev_form_border_width', '1px' );
        $form_radius = get_option( 'amrrev_form_border_radius', '8px' );
        $form_padding = get_option( 'amrrev_form_padding', '20px' );
        $title_color = get_option( 'amrrev_form_title_color', '#333333' );
        $label_color = get_option( 'amrrev_form_label_color', '#555555' );
        $input_bg = get_option( 'amrrev_form_input_bg_color', '#ffffff' );
        $input_border = get_option( 'amrrev_form_input_border_color', '#dddddd' );
        $input_text = get_option( 'amrrev_form_input_text_color', '#333333' );
        $button_bg = get_option( 'amrrev_form_button_bg_color', '#0073aa' );
        $button_text = get_option( 'amrrev_form_button_text_color', '#ffffff' );
        $button_hover_bg = get_option( 'amrrev_form_button_hover_bg_color', '#005a87' );
        $button_hover_text = get_option( 'amrrev_form_button_hover_text_color', '#ffffff' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Form Background', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_bg_color" value="<?php echo esc_attr( $form_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Form Border', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_border_color" value="<?php echo esc_attr( $form_border ); ?>" />
                    <input type="text" name="amrrev_form_border_width" value="<?php echo esc_attr( $form_border_width ); ?>" class="small-text" placeholder="1px" />
                    <input type="text" name="amrrev_form_border_radius" value="<?php echo esc_attr( $form_radius ); ?>" class="small-text" placeholder="8px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Form Padding', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="text" name="amrrev_form_padding" value="<?php echo esc_attr( $form_padding ); ?>" class="regular-text" placeholder="20px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Form Title Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_title_color" value="<?php echo esc_attr( $title_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Form Label Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_label_color" value="<?php echo esc_attr( $label_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Input Background', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_input_bg_color" value="<?php echo esc_attr( $input_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Input Border', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_input_border_color" value="<?php echo esc_attr( $input_border ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Input Text Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_input_text_color" value="<?php echo esc_attr( $input_text ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Button Background', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_button_bg_color" value="<?php echo esc_attr( $button_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Button Text Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_button_text_color" value="<?php echo esc_attr( $button_text ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Button Hover Background', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_button_hover_bg_color" value="<?php echo esc_attr( $button_hover_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Button Hover Text', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_form_button_hover_text_color" value="<?php echo esc_attr( $button_hover_text ); ?>" />
                </td>
            </tr>
        </table>
        <?php
}

    /**
     * Render Review Styles Section
     */
    private function render_review_styles() {
        $review_bg = get_option( 'amrrev_review_box_bg_color', '#f9f9f9' );
        $review_border = get_option( 'amrrev_review_box_border_color', '#e0e0e0' );
        $review_border_width = get_option( 'amrrev_review_box_border_width', '' );
        $review_radius = get_option( 'amrrev_review_box_border_radius', '8px' );
        $review_padding = get_option( 'amrrev_review_box_padding', '15px' );
        $name_color = get_option( 'amrrev_review_name_color', '#333333' );
        $date_color = get_option( 'amrrev_review_date_color', '#777777' );
        $content_color = get_option( 'amrrev_review_content_color', '#555555' );
        $star_size = get_option( 'amrrev_star_size', '16px' );
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e( 'Review Box Background', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_review_box_bg_color" value="<?php echo esc_attr( $review_bg ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Review Box Border', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_review_box_border_color" value="<?php echo esc_attr( $review_border ); ?>" />
                    <input type="text" name="amrrev_review_box_border_width" value="<?php echo esc_attr( $review_border_width ); ?>" class="small-text" placeholder="1px" />
                    <input type="text" name="amrrev_review_box_border_radius" value="<?php echo esc_attr( $review_radius ); ?>" class="small-text" placeholder="8px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Review Box Padding', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="text" name="amrrev_review_box_padding" value="<?php echo esc_attr( $review_padding ); ?>" class="regular-text" placeholder="15px" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Reviewer Name Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_review_name_color" value="<?php echo esc_attr( $name_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Review Date Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_review_date_color" value="<?php echo esc_attr( $date_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Review Content Color', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="color" name="amrrev_review_content_color" value="<?php echo esc_attr( $content_color ); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Star Size', 'amrrev-product-reviews-for-woocommerce' ); ?></th>
                <td>
                    <input type="text" name="amrrev_star_size" value="<?php echo esc_attr( $star_size ); ?>" class="small-text" placeholder="16px" />
                </td>
            </tr>
        </table>
        <?php
}

    /**
     * SS dimension values sanitize helper method।
     * valid CSS units (px, em, rem, %, vh, vw) allow
    */
    private static function sanitize_css_dimension( $value, $default = '' ) {
        $value = sanitize_text_field( $value );
        if ( preg_match( '/^\d+(\.\d+)?(px|em|rem|%|vh|vw)$/', $value ) ) {
            return $value;
        }
        return $default;
    }

    /**
     * Generate Custom CSS
     */
    public static function get_custom_css() {

        // FORM
        $form_bg           = sanitize_hex_color( get_option( 'amrrev_form_bg_color' ) );
        $form_border       = sanitize_hex_color( get_option( 'amrrev_form_border_color' ) );
        $form_border_width = get_option( 'amrrev_form_border_width' );
        $form_border_width = is_numeric( $form_border_width ) ? absint( $form_border_width ) : '';
        $form_radius       = self::sanitize_css_dimension( get_option( 'amrrev_form_border_radius' ) );
        $form_padding      = self::sanitize_css_dimension( get_option( 'amrrev_form_padding' ) );
        $title_color       = sanitize_hex_color( get_option( 'amrrev_form_title_color' ) );
        $label_color       = sanitize_hex_color( get_option( 'amrrev_form_label_color' ) );
        $input_bg          = sanitize_hex_color( get_option( 'amrrev_form_input_bg_color' ) );
        $input_border      = sanitize_hex_color( get_option( 'amrrev_form_input_border_color' ) );
        $input_text        = sanitize_hex_color( get_option( 'amrrev_form_input_text_color' ) );
        $button_bg         = sanitize_hex_color( get_option( 'amrrev_form_button_bg_color' ) );
        $button_text       = sanitize_hex_color( get_option( 'amrrev_form_button_text_color' ) );
        $button_hover_bg   = sanitize_hex_color( get_option( 'amrrev_form_button_hover_bg_color' ) );
        $button_hover_text = sanitize_hex_color( get_option( 'amrrev_form_button_hover_text_color' ) );

        // REVIEW BOX
        $review_bg           = sanitize_hex_color( get_option( 'amrrev_review_box_bg_color' ) );
        $review_border       = sanitize_hex_color( get_option( 'amrrev_review_box_border_color' ) );
        $review_border_width = get_option( 'amrrev_review_box_border_width' );
        $review_border_width = is_numeric( $review_border_width ) ? absint( $review_border_width ) : '';
        $review_radius       = self::sanitize_css_dimension( get_option( 'amrrev_review_box_border_radius' ) );
        $review_padding      = self::sanitize_css_dimension( get_option( 'amrrev_review_box_padding' ) );
        $name_color          = sanitize_hex_color( get_option( 'amrrev_review_name_color' ) );
        $date_color          = sanitize_hex_color( get_option( 'amrrev_review_date_color' ) );
        $content_color       = sanitize_hex_color( get_option( 'amrrev_review_content_color' ) );
        $star_size           = self::sanitize_css_dimension( get_option( 'amrrev_star_size' ) );

        // FILTER
        $filter_bg             = sanitize_hex_color( get_option( 'amrrev_filter_bg_color' ) );
        $filter_border         = sanitize_hex_color( get_option( 'amrrev_filter_border_color' ) );
        $filter_border_width   = get_option( 'amrrev_filter_border_width' );
        $filter_border_width   = is_numeric( $filter_border_width ) ? absint( $filter_border_width ) : '';
        $filter_radius         = self::sanitize_css_dimension( get_option( 'amrrev_filter_border_radius' ) );
        $filter_padding        = self::sanitize_css_dimension( get_option( 'amrrev_filter_padding' ) );
        $filter_title_color    = sanitize_hex_color( get_option( 'amrrev_filter_title_color' ) );
        $filter_label_color    = sanitize_hex_color( get_option( 'amrrev_filter_label_color' ) );
        $filter_checkbox_color = sanitize_hex_color( get_option( 'amrrev_filter_checkbox_color' ) );
        $filter_select_bg      = sanitize_hex_color( get_option( 'amrrev_filter_select_bg_color' ) );
        $filter_select_border  = sanitize_hex_color( get_option( 'amrrev_filter_select_border_color' ) );
        $filter_select_text    = sanitize_hex_color( get_option( 'amrrev_filter_select_text_color' ) );

        // value থাকলে property output, না থাকলে skip — CSS file override হবে না
        $css = '';

        // FORM SECTION
        $form_props = '';
        if ( $form_bg )     $form_props .= 'background-color: ' . $form_bg . '; ';
        if ( $form_border && $form_border_width !== '' ) $form_props .= 'border: ' . $form_border_width . 'px solid ' . $form_border . '; ';
        if ( $form_radius ) $form_props .= 'border-radius: ' . $form_radius . '; ';
        if ( $form_padding ) $form_props .= 'padding: ' . $form_padding . '; ';
        if ( $form_props )  $css .= '.amrrev-review-form-section { ' . $form_props . "}
";

        if ( $title_color ) $css .= '.amrrev-review-form-section h3 { color: ' . $title_color . "; }
";
        if ( $label_color ) $css .= '.amrrev-form-field label { color: ' . $label_color . "; }
";

        $input_props = '';
        if ( $input_bg )     $input_props .= 'background-color: ' . $input_bg . '; ';
        if ( $input_border ) $input_props .= 'border-color: ' . $input_border . '; ';
        if ( $input_text )   $input_props .= 'color: ' . $input_text . '; ';
        if ( $input_props ) {
            $css .= '.amrrev-form-field input[type="text"], ';
            $css .= '.amrrev-form-field input[type="email"], ';
            $css .= '.amrrev-form-field textarea { ' . $input_props . "}
";
        }

        if ( $button_bg || $button_text ) {
            $btn = '';
            if ( $button_bg )   $btn .= 'background-color: ' . $button_bg . '; ';
            if ( $button_text ) $btn .= 'color: ' . $button_text . '; ';
            $css .= '.amrrev-submit-btn { ' . $btn . "}
";
        }
        if ( $button_hover_bg || $button_hover_text ) {
            $btn_h = '';
            if ( $button_hover_bg )   $btn_h .= 'background-color: ' . $button_hover_bg . '; ';
            if ( $button_hover_text ) $btn_h .= 'color: ' . $button_hover_text . '; ';
            $css .= '.amrrev-submit-btn:hover { ' . $btn_h . "}
";
        }

        // REVIEW BOX SECTION
        $box_props = '';
        if ( $review_bg )     $box_props .= 'background-color: ' . $review_bg . '; ';
        if ( $review_border && $review_border_width !== '' ) $box_props .= 'border: ' . $review_border_width . 'px solid ' . $review_border . '; ';
        if ( $review_radius ) $box_props .= 'border-radius: ' . $review_radius . '; ';
        if ( $review_padding ) $box_props .= 'padding: ' . $review_padding . '; ';
        if ( $box_props )     $css .= '.cpt-review-full-box { ' . $box_props . "}
";

        if ( $name_color )    $css .= '.cpt-name { color: ' . $name_color . "; }
";
        if ( $date_color )    $css .= '.cpt-date { color: ' . $date_color . "; }
";
        if ( $content_color ) $css .= '.cpt-review-content { color: ' . $content_color . "; }
";
        if ( $star_size )     $css .= '.cpt-review-count span { font-size: ' . $star_size . "; }
";

        // FILTER SECTION
        $filter_props = '';
        if ( $filter_bg )     $filter_props .= 'background-color: ' . $filter_bg . '; ';
        if ( $filter_border && $filter_border_width !== '' ) $filter_props .= 'border: ' . $filter_border_width . 'px solid ' . $filter_border . '; ';
        if ( $filter_radius ) $filter_props .= 'border-radius: ' . $filter_radius . '; ';
        if ( $filter_padding ) $filter_props .= 'padding: ' . $filter_padding . '; ';
        if ( $filter_props )  $css .= '.amrrev-review-filters { ' . $filter_props . "}
";

        if ( $filter_title_color ) $css .= '.amrrev-review-filters h4 { color: ' . $filter_title_color . "; }
";
        if ( $filter_label_color ) $css .= '.amrrev-filter-group label { color: ' . $filter_label_color . "; }
";
        if ( $filter_checkbox_color ) {
            $css .= '.amrrev-rating-filter input[type="checkbox"]:checked, ';
            $css .= '.amrrev-filter-group input[type="checkbox"]:checked { accent-color: ' . $filter_checkbox_color . "; }
";
        }

        $select_props = '';
        if ( $filter_select_bg )     $select_props .= 'background-color: ' . $filter_select_bg . '; ';
        if ( $filter_select_border ) $select_props .= 'border-color: ' . $filter_select_border . '; ';
        if ( $filter_select_text )   $select_props .= 'color: ' . $filter_select_text . '; ';
        if ( $select_props ) $css .= '.amrrev-age-filter select { ' . $select_props . "}
";

        return $css;
    }

}

new AMRREV_Style_Settings();