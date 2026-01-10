<?php
/**
 * views/admin-reviews-page.php
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Get filter status
$status = isset( $_GET['review_status'] ) ? sanitize_text_field( wp_unslash( $_GET['review_status'] ) ) : 'pending';

$arg = array(
    'post_type'      => 'cpr_review',
    'post_status'    => $status,
    'posts_per_page' => 50,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$reviews = new WP_Query( $arg );

$pending_count = wp_count_posts('cpr_review')->pending;
$approved_count = wp_count_posts('cpr_review')->publish;
$rejected_count = wp_count_posts('cpr_review')->draft;

?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <!-- Status Tabs -->
    <ul class="subsubsub">
        <li>
            <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cpr-reviews', 'review_status' => 'pending' ) ) ); ?>" 
            class="<?php echo $status === 'pending' ? 'current' : ''; ?>">
                <?php esc_html_e( 'Pending', 'reviewnest-product-reviews' ); ?> 
                <span class="count">(<?php echo esc_html( $pending_count ); ?>)</span>
            </a> |
        </li>
        <li>
            <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cpr-reviews', 'review_status' => 'publish' ) ) ); ?>" 
            class="<?php echo $status === 'publish' ? 'current' : ''; ?>">
                <?php esc_html_e( 'Approved', 'reviewnest-product-reviews' ); ?> 
                <span class="count">(<?php echo esc_html( $approved_count ); ?>)</span>
            </a> |
        </li>
        <li>
            <a href="<?php echo esc_url( add_query_arg( array( 'page' => 'cpr-reviews', 'review_status' => 'draft' ) ) ); ?>" 
            class="<?php echo $status === 'draft' ? 'current' : ''; ?>">
                <?php esc_html_e( 'Rejected', 'reviewnest-product-reviews' ); ?> 
                <span class="count">(<?php echo esc_html( $rejected_count ); ?>)</span>
            </a>
        </li>
    </ul>

    <div class="cpr-reviews-table-wrapper">
        <?php if ( $reviews->have_posts() ): ?>
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="20%">Product</th>
                    <th width="30%">Review</th>
                    <th width="10%">Rating</th>
                    <th width="15%">Reviewer</th>
                    <th width="10%">Age</th>
                    <th width="10%">Date</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while($reviews->have_posts()): $reviews->the_post();

                    $review_id = get_the_ID();
                    $product_id = get_post_meta($review_id, '_cpr_product_id', true);  
                    $file_url = get_post_meta($review_id, '_cpr_file_url', true); 
                    $rating = get_post_meta($review_id, '_cpr_rating', true);  
                    $reviewer_name = get_post_meta($review_id, '_cpr_name', true);      
                    $reviewer_email = get_post_meta($review_id, '_cpr_email', true);      
                    $reviewer_age = get_post_meta($review_id, '_cpr_age_range', true);      
                ?>
                <tr>
                    <td width="5%"><?php echo esc_html( $i++ ); ?></td>
                    <td width="20%">
                        <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" target="_blank">
                            <strong><?php echo esc_html( get_the_title( $product_id ) ); ?></strong>
                        </a>
                    </td>

                    <td width="30%">
                        <strong><?php echo esc_html( get_the_title() ); ?></strong>
                        <p><?php echo esc_html(wp_trim_words(get_the_content(), 5)); ?></p>
                        <p>
                            <?php if ($file_url) : ?>
                                <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="cpr-attachment-link">
                                    📎 View Attachment
                                </a>
                            <?php endif; ?>
                        </p>                        
                    </td>
                    <td width="10%">
                            <span style="color: #f1c40f; font-size: 16px;">
                                <?php echo esc_html( str_repeat( '★', intval( $rating ) ) ); ?>
                                <?php echo esc_html( str_repeat( '☆', 5 - intval( $rating ) ) ); ?>
                            </span>

                    </td>
                    <td width="15%">
                        <strong><?php echo esc_html($reviewer_name); ?></strong><br>
                        <small style="color: #666;"><?php echo esc_html($reviewer_email); ?></small>
                    </td>
                    <td width="10%"><?php echo esc_html($reviewer_age ? $reviewer_age : '—'); ?></td>
                    <td width="10%"><?php echo get_the_date('M j, Y'); ?></td>
                    <td width="15%">
                        <?php if ( $status === 'pending' ) : ?>
                            <button class="button button-small cpr-approve-btn" 
                                    data-review-id="<?php echo esc_attr( $review_id ); ?>">
                                <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/approve.svg' ); ?>" 
                                    alt="<?php esc_attr_e( 'Approve', 'reviewnest-product-reviews' ); ?>"> 
                                <?php esc_html_e( 'Approve', 'reviewnest-product-reviews' ); ?>
                            </button><br>
                            <button class="button button-small cpr-reject-btn" 
                                    data-review-id="<?php echo esc_attr( $review_id ); ?>" 
                                    style="margin-top: 5px;">
                                <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/reject.svg' ); ?>" 
                                    alt="<?php esc_attr_e( 'Reject', 'reviewnest-product-reviews' ); ?>"> 
                                <?php esc_html_e( 'Reject', 'reviewnest-product-reviews' ); ?>
                            </button>
                        <?php elseif ( $status === 'publish' ) : ?>
                            <button class="button button-small cpr-reject-btn" 
                                    data-review-id="<?php echo esc_attr( $review_id ); ?>">
                                <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/reject.svg' ); ?>" 
                                    alt="<?php esc_attr_e( 'Reject', 'reviewnest-product-reviews' ); ?>"> 
                                <?php esc_html_e( 'Reject', 'reviewnest-product-reviews' ); ?>
                            </button>
                        <?php elseif ( $status === 'draft' ) : ?>
                            <button class="button button-small cpr-approve-btn" 
                                    data-review-id="<?php echo esc_attr( $review_id ); ?>">
                                <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/approve.svg' ); ?>" 
                                    alt="<?php esc_attr_e( 'Approve', 'reviewnest-product-reviews' ); ?>"> 
                                <?php esc_html_e( 'Approve', 'reviewnest-product-reviews' ); ?>
                            </button>
                        <?php endif; ?>
                        <br>
                        <button class="button button-link-delete button-small cpr-delete-btn" 
                                data-review-id="<?php echo esc_attr( $review_id ); ?>" 
                                style="margin-top: 5px; color: #a00;">
                            <img src="<?php echo esc_url( CPR_ASSETS_URL . 'images/delete.svg' ); ?>" 
                                alt="<?php esc_attr_e( 'Delete', 'reviewnest-product-reviews' ); ?>"> 
                            <?php esc_html_e( 'Delete', 'reviewnest-product-reviews' ); ?>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php
        else: ?>
          <div class="cpr-no-reviews">
                <p style="padding: 40px; text-align: center; color: #666;">
                    <?php esc_html_e( 'No reviews found with this status.', 'reviewnest-product-reviews' ); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
