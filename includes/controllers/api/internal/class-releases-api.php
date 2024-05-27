<?php
/**
 * Holds the Releases API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\Internal;

use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\Product;
use LicenseHub\Includes\Model\Release;
use WP_REST_Request;

if ( ! class_exists('\LicenseHub\Includes\Controller\API\Internal\Releases_API') ){
	class Releases_API{
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

        public function routes(): void {
            register_rest_route(
				API_Helper::generate_prefix('releases'),
				'/new-release',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'add_new_release' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}

		public function add_new_release( WP_REST_Request $request ): void {
            $params = $request->get_params();
			$params = json_decode($params[0]);

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_releases' ) ) {
                $version = sanitize_text_field( $params->version );
                $change_log = sanitize_text_field( $params->changeLog );
                $product_id = sanitize_text_field( $params->productID );

				if ( empty( $version ) ) {
					wp_send_json_error(
						array( 'message' => __( 'Version cannot be empty', 'licensehub' ) ) );

					return;
				}

                $product = new Product($params->productID);
                $release = $product->last_release();

                if ( $release && version_compare($release->version, $version, '>=') ) {
                    wp_send_json_error( array( 'message' => __( 'The version must be greater than the last release', 'licensehub' ) ) );
                }

                if ( empty( $change_log ) ) {
					wp_send_json_error(
						array( 'message' => __( 'Change log cannot be empty', 'licensehub' ) ) );

					return;
				}

                $release = new Release();
                $release->product_id = $product_id;
                $release->changelog = $change_log;
                $release->version = $version;
                $release->save();

				wp_send_json_success( array( 'message' => __( 'The product was saved!', 'licensehub' ) ) );
			}
        }
	}

	new Releases_API();
}
