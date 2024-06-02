<?php
/**
 * Holds the AJAX class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Controller\Core\AJAX' ) ) {
	/**
	 * Handle AJAX
	 */
	class AJAX {
        public function __construct() {
            $this->hooks();
        }

        public function hooks(): void {
            add_action( 'wp_ajax_lchb_create_release', array( $this, 'upload_release' ) );
        }

        public function upload_release(): void {
            if ( ! wp_doing_ajax() ) {
                wp_send_json_error( array( 'message' => __( 'Invalid Request', 'licensehub' ) ) );
            }

            if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'lchb_releases' ) ) {
                wp_send_json_error( array( 'message' => __( 'Invalid Nonce', 'licensehub' ) ) );
            }

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error( array( 'message' => __( 'Invalid Permissions', 'licensehub' ) ) );
            }

            if ( ! isset( $_FILES['file'] ) ) {
                wp_send_json_error( array( 'message' => __( 'No file uploaded', 'licensehub' ) ) );
            }

            $uploadedfile = $_FILES['file'];
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

            if ( ! $movefile || isset( $movefile['error'] ) ) {
                wp_send_json_error( array( 'message' => $movefile['error'] || __('File can\'t be uploaded', 'licensehub' ) ) );
            }

            $filetype = wp_check_filetype( basename( $movefile['file'] ), null );

            $attachment = array(
                'guid'           => $movefile['url'], 
                'post_mime_type' => $filetype['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $movefile['file'] ) ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $movefile['file'] );

            wp_send_json_success( array( 'attachment_id' => $attach_id ) );
        }
    }

    new AJAX();
}