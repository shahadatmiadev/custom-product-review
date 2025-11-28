<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class CPR_Ajax {
    public function __construct() {
        add_action( 'wp_ajax_cpr_approve_review', array( $this, 'approve_review' ) );
        add_action( 'wp_ajax_cpr_reject_review', array( $this, 'reject_review' ) );
        add_action( 'wp_ajax_cpr_delete_review', array( $this, 'delete_review' ) );
    }

    public function approve_review() {
        check_ajax_referer( 'cpr_admin_ajax', 'nonce' );
       
        $review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;

        if ( !$review_id ) {
            wp_send_json_error( array( 'message' => 'Invalid review ID.' ) );
        }
        
        $update = array(
            'ID' => $review_id,
            'post_status' => 'publish',
        );
        wp_update_post( $update );

        if($update && !is_wp_error($update)) {
            wp_send_json_success( array( 'message' => 'Review approved successfully.' ) );
        }else {
            wp_send_json_error( array( 'message' => 'Failed to approve review.' ) );
        }
    }

    public function reject_review() {
        check_ajax_referer( 'cpr_admin_ajax', 'nonce' );
       
        $review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;

        if ( !$review_id ) {
            wp_send_json_error( array( 'message' => 'Invalid review ID.' ) );
        }
        
        $update = array(
            'ID' => $review_id,
            'post_status' => 'draft',
        );
        wp_update_post( $update );

        if($update && !is_wp_error($update)) {
            wp_send_json_success( array( 'message' => 'Review rejected successfully.' ) );
        }else {
            wp_send_json_error( array( 'message' => 'Failed to reject review.' ) );
        }
    }

    public function delete_review() {
        check_ajax_referer( 'cpr_admin_ajax', 'nonce' );
       
        $review_id = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;

        if ( !$review_id ) {
            wp_send_json_error( array( 'message' => 'Invalid review ID.' ) );
        }
        
        
       $deleted = wp_delete_post( $review_id, true );

        if ( $deleted ) {
            wp_send_json_success( array( 'message' => 'Review deleted permanently!' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Failed to delete review.' ) );
        }
    }
}