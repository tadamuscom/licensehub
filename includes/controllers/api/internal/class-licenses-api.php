<?php
/**
 * Holds the Licenses API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\Internal;

use DateTime;
use Exception;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\License_Key;
use WP_REST_Request;

if ( ! class_exists('\LicenseHub\Includes\Controller\API\Internal\Licenses_API') ){
	class Licenses_API{
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

		public function routes(): void {
			register_rest_route(
				API_Helper::generate_prefix('licenses'),
				'/new-license-key',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'create' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix('licenses'),
				'/delete-license-key',
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'delete' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix('licenses'),
				'/update-license-key',
				array(
					'methods'             => 'PUT',
					'callback'            => array( $this, 'update' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}

		/**
		 * Add a new license key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 */
		public function create( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode($params[0]);

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_license_keys' ) ) {
				if ( empty( $params->user ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'User cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				if ( empty( $params->product ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'Product cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				if ( empty( $params->expiresAt ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'Expiry date cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				$key = new License_Key();
				$key->generate();
				$key->status     = License_Key::$active_status;
				$key->user_id    = (int) sanitize_text_field( $params->user );
				$key->product_id = (int) sanitize_text_field( $params->product );
				$key->created_at = ( new DateTime() )->format( LCHB_TIME_FORMAT );
				$key->expires_at = ( DateTime::createFromFormat( 'Y-m-d', $params->expiresAt )->format( LCHB_TIME_FORMAT ) );
				$key->save();

				wp_send_json_success(
					array(
						'message' => __( 'The license key was saved!', 'licensehub' ),
					)
				);
			}
		}

		/**
		 * Delete a license key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function delete( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_license_keys' ) ) {
				if ( empty( $params['id'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'ID cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				$license_key = new License_Key( $params['id'] );
				$license_key->destroy();

				wp_send_json_success(
					array(
						'message' => __( 'License Key Deleted!', 'licensehub' ),
					)
				);
			}
		}

		public function update( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_license_keys' ) ) {
				API_Helper::update_model_field($params, License_Key::class);
			}
		}
	}

	new Licenses_API();
}
