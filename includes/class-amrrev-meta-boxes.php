<?php
/**
 * Meta Boxes Class
 * includes/class-amrrev-meta-boxes.php
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AMRREV_Meta_Boxes {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
        add_action('save_post', array($this, 'save_review_meta'), 10, 2);
    }

    /**
     * Register Meta Boxes
     */
    public function register_meta_boxes() {
        add_meta_box(
            'amrrev_review_details',
            __('Review Details', 'amrrev-product-reviews-for-woocommerce'),
            array($this, 'render_meta_box'),
            'amrrev_review',      
            'normal',
            'high'
        );
    }

    /**
     * Render Meta Box HTML
     */
    public function render_meta_box($post) {

        // Add nonce for security
        wp_nonce_field('amrrev_save_review_meta', 'amrrev_review_meta_nonce');

        // Get existing values
        $rating = get_post_meta($post->ID, '_amrrev_rating', true);
        $age_range = get_post_meta($post->ID, '_amrrev_age_range', true);
        $file_url = get_post_meta($post->ID, '_amrrev_file_url', true);
        $verified = get_post_meta($post->ID, '_amrrev_verified_buyer', true);

        ?>
        <p>
            <label for="amrrev_rating"><?php esc_html_e('Star Rating (1-5)', 'amrrev-product-reviews-for-woocommerce'); ?></label><br>
            <input type="number" id="amrrev_rating" name="amrrev_rating" min="1" max="5" value="<?php echo esc_attr($rating); ?>" />
        </p>

        <p>
            <label for="amrrev_age_range"><?php esc_html_e('Age Range', 'amrrev-product-reviews-for-woocommerce'); ?></label><br>
            <select name="amrrev_age_range" id="amrrev_age_range">
                <option value="">--Select--</option>
            <option value="under-18" <?php selected($age_range, 'under-18'); ?>>Under 18</option>
            <option value="18-24" <?php selected($age_range, '18-24'); ?>>18 - 24</option>
            <option value="25-34" <?php selected($age_range, '25-34'); ?>>25 - 34</option>
            <option value="35-44" <?php selected($age_range, '35-44'); ?>>35 - 44</option>
            <option value="45-54" <?php selected($age_range, '45-54'); ?>>45 - 54</option>
            <option value="55-64" <?php selected($age_range, '55-64'); ?>>55 - 64</option>
            <option value="65+" <?php selected($age_range, '65+'); ?>>65+</option>
            </select>
        </p>

        <p>
            <label for="amrrev_file"><?php esc_html_e('Upload File (JPG, PNG, PDF)', 'amrrev-product-reviews-for-woocommerce'); ?></label><br>
            <input type="file" name="amrrev_file" id="amrrev_file" /><br>
            <?php if ($file_url): ?>
                <a href="<?php echo esc_url($file_url); ?>" target="_blank"><?php esc_html_e('View Uploaded File', 'amrrev-product-reviews-for-woocommerce'); ?></a>
            <?php endif; ?>
            <img src="<?php echo esc_url($file_url); ?>" alt="">
        </p>

        <p>
            <label for="amrrev_verified_buyer">
                <input type="checkbox" name="amrrev_verified_buyer" id="amrrev_verified_buyer" value="1" <?php checked($verified, '1'); ?> />
                <?php esc_html_e('Verified Buyer', 'amrrev-product-reviews-for-woocommerce'); ?>
            </label>
        </p>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_review_meta($post_id, $post) {

        // Verify nonce
        if ( ! isset( $_POST['amrrev_review_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['amrrev_review_meta_nonce'] ) ), 'amrrev_save_review_meta' ) ) {
            return;
        }

        // Avoid autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) return;

        // Save Rating
        if (isset($_POST['amrrev_rating'])) {
            update_post_meta($post_id, '_amrrev_rating', intval($_POST['amrrev_rating']));
        }

        // Save Age Range
        if ( isset( $_POST['amrrev_age_range'] ) ) {
            update_post_meta( $post_id, '_amrrev_age_range', sanitize_text_field( wp_unslash( $_POST['amrrev_age_range'] ) ) );
        }

        // Save Verified Buyer
        $verified = isset($_POST['amrrev_verified_buyer']) ? '1' : '0';
        update_post_meta($post_id, '_amrrev_verified_buyer', $verified);

        if (!empty($_FILES['amrrev_file']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            
            // ফাইল টাইপ চেক
            $allowed_types = array('jpg', 'jpeg', 'png', 'pdf');
            $file_info = wp_check_filetype($_FILES['amrrev_file']['name']);
            if (!in_array(strtolower($file_info['ext']), $allowed_types)) {
                wp_die(esc_html__('Invalid file type. Only JPG, PNG, and PDF files are allowed.', 'amrrev-product-reviews-for-woocommerce'));
            }
            
            $uploaded = wp_handle_upload($_FILES['amrrev_file'], [
                'test_form' => false,
                'mimes' => array(
                    'jpg|jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'pdf' => 'application/pdf'
                )
            ]);
        }
    }
}

// Initialize Meta Boxes
new AMRREV_Meta_Boxes();
