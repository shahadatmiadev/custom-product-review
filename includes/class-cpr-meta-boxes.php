<?php
/**
 * Meta Boxes Class
 * includes/class-cpr-meta-boxes.php
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Meta_Boxes {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
        add_action('save_post', array($this, 'save_review_meta'), 10, 2);
    }

    /**
     * Register Meta Boxes
     */
    public function register_meta_boxes() {
        add_meta_box(
            'cpr_review_details',
            __('Review Details', 'reviewnest-product-reviews'),
            array($this, 'render_meta_box'),
            'cpr_review',      
            'normal',
            'high'
        );
    }

    /**
     * Render Meta Box HTML
     */
    public function render_meta_box($post) {

        // Add nonce for security
        wp_nonce_field('cpr_save_review_meta', 'cpr_review_meta_nonce');

        // Get existing values
        $rating = get_post_meta($post->ID, '_cpr_rating', true);
        $age_range = get_post_meta($post->ID, '_cpr_age_range', true);
        $file_url = get_post_meta($post->ID, '_cpr_file_url', true);
        $verified = get_post_meta($post->ID, '_cpr_verified_buyer', true);

        ?>
        <p>
            <label for="cpr_rating"><?php esc_html_e('Star Rating (1-5)', 'reviewnest-product-reviews'); ?></label><br>
            <input type="number" id="cpr_rating" name="cpr_rating" min="1" max="5" value="<?php echo esc_attr($rating); ?>" />
        </p>

        <p>
            <label for="cpr_age_range"><?php esc_html_e('Age Range', 'reviewnest-product-reviews'); ?></label><br>
            <select name="cpr_age_range" id="cpr_age_range">
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
            <label for="cpr_file"><?php esc_html_e('Upload File (JPG, PNG, PDF)', 'reviewnest-product-reviews'); ?></label><br>
            <input type="file" name="cpr_file" id="cpr_file" /><br>
            <?php if ($file_url): ?>
                <a href="<?php echo esc_url($file_url); ?>" target="_blank"><?php esc_html_e('View Uploaded File', 'reviewnest-product-reviews'); ?></a>
            <?php endif; ?>
            <img src="<?php echo esc_url($file_url); ?>" alt="">
        </p>

        <p>
            <label for="cpr_verified_buyer">
                <input type="checkbox" name="cpr_verified_buyer" id="cpr_verified_buyer" value="1" <?php checked($verified, '1'); ?> />
                <?php esc_html_e('Verified Buyer', 'reviewnest-product-reviews'); ?>
            </label>
        </p>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_review_meta($post_id, $post) {

        // Verify nonce
        if ( ! isset( $_POST['cpr_review_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpr_review_meta_nonce'] ) ), 'cpr_save_review_meta' ) ) {
            return;
        }

        // Avoid autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) return;

        // Save Rating
        if (isset($_POST['cpr_rating'])) {
            update_post_meta($post_id, '_cpr_rating', intval($_POST['cpr_rating']));
        }

        // Save Age Range
        if ( isset( $_POST['cpr_age_range'] ) ) {
            update_post_meta( $post_id, '_cpr_age_range', sanitize_text_field( wp_unslash( $_POST['cpr_age_range'] ) ) );
        }

        // Save Verified Buyer
        $verified = isset($_POST['cpr_verified_buyer']) ? '1' : '0';
        update_post_meta($post_id, '_cpr_verified_buyer', $verified);

        if (!empty($_FILES['cpr_file']['name'])) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            
            // ফাইল টাইপ চেক
            $allowed_types = array('jpg', 'jpeg', 'png', 'pdf');
            $file_info = wp_check_filetype($_FILES['cpr_file']['name']);
            if (!in_array(strtolower($file_info['ext']), $allowed_types)) {
                wp_die(esc_html__('Invalid file type. Only JPG, PNG, and PDF files are allowed.', 'xohanniloy017-custom-product-reviews'));
            }
            
            $uploaded = wp_handle_upload($_FILES['cpr_file'], [
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
new CPR_Meta_Boxes();
