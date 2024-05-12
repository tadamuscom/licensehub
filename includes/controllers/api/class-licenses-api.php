<?php
/**
 * Holds the Licenses API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API;

use DateTime;
use Exception;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;
use WP_REST_Request;

if ( ! class_exists('\LicenseHub\Includes\Controller\API\Licenses_API') ){
	class Licenses_API{
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

		public function routes(): void {
			$this->internal_routes();
			$this->external_routes();
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
		public function internal_add_new_license_key( WP_REST_Request $request ): void {
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
		public function internal_delete_license_key( WP_REST_Request $request ): void {
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

		public function internal_update_license_key( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_license_keys' ) ) {
				API_Helper::update_model_field($params, License_Key::class);
			}
		}

		/**
		 * Check if the license is valid
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 */
		public function external_validate_license( WP_REST_Request $request ): void {
			$key = $request->get_param( 'license_key' );

			if ( empty( $key ) ){
				wp_send_json_error( array(
					'message' => __( 'License key cannot be empty', 'licensehub' )
				) );

				return;
			}

			$license = ( new License_Key() )->load_by_field( 'license_key', $key );

			if ( $license ) {
				wp_send_json_success( array( 'message' => 'License key is valid' ) );
				return;
			}

			wp_send_json_error( array( 'message' => 'License key could not be validated' ) );
		}

		/**
		 * Create a license
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 */
		public function external_create_license( WP_REST_Request $request ): void {
			if ( ! API_Helper::auth( $request ) ) {
				wp_send_json_error( API_Helper::$error_text );
				return;
			}

			if ( $this->validate_new_license( $request ) ) {
				$product_id = $request->get_param( 'product_id' );

				$license = new License_Key();
				$license->generate();
				$license->product_id = (int) sanitize_text_field( $product_id );
				$license->user_id    = API_Helper::$user->ID;
				$license->status     = License_Key::$active_status;
				$license->save();

				wp_send_json_success(
					array(
						'id'         => $license->id,
						'key'        => $license->license_key,
						'created_at' => $license->created_at,
						'expires_at' => $license->expires_at,
					)
				);
			} else {
				wp_send_json_error( API_Helper::$error_text );
			}
		}

		private function internal_routes(): void {
			// Add new license key.
			register_rest_route(
				API_Helper::$namespace,
				'/new-license-key',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'internal_add_new_license_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Delete License Key.
			register_rest_route(
				API_Helper::$namespace,
				'/delete-license-key',
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'internal_delete_license_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Update License Key.
			register_rest_route(
				API_Helper::$namespace,
				'/update-license-key',
				array(
					'methods'             => 'PUT',
					'callback'            => array( $this, 'internal_update_license_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}

		private function external_routes(): void {
			// Validate if a license is valid
			register_rest_route(
				API_Helper::$namespace . '/licenses',
				'/validate',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'external_validate_license' ),
					'permission_callback' => ''
				)
			);

			// Create license
			register_rest_route(
				API_Helper::$namespace . '/licenses',
				'/create',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'external_create_license' ),
					'permission_callback' => ''
				)
			);
		}

		/**
		 * Validate if the license is good
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return bool
		 */
		private function validate_new_license( WP_REST_Request $request ): bool {
			$product_id = $request->get_param( 'product_id' );

			if ( ! $product_id ) {
				API_Helper::$error_text = __('No product_id has been passed. Please pass a valid product_id', 'licensehub');
				return false;
			}

			if ( ! is_int( $product_id ) ) {
				API_Helper::$error_text = __('The product_id must be an integer', 'licensehub');
				return false;
			}

			if ( ! ( new Product() )->exists( $product_id ) ) {
				API_Helper::$error_text = __('The product does not exist', 'licensehub');
				return false;
			}

			return true;
		}
	}

	new Licenses_API();
}
