<?php
/**
 * Holds the API Keys API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API;

use DateTime;
use Exception;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\API_Key;
use WP_REST_Request;

if ( ! class_exists('\LicenseHub\Includes\Controller\API\API_Keys_API') ){
	class API_Keys_API{
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

		public function routes(): void {
			// Add new API key.
			register_rest_route(
				API_Helper::$namespace,
				'/new-api-key',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'add_new_api_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Delete API Key.
			register_rest_route(
				API_Helper::$namespace,
				'/delete-api-key',
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'delete_api_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Update API Key.
			register_rest_route(
				API_Helper::$namespace,
				'/update-api-key',
				array(
					'methods'             => 'PUT',
					'callback'            => array( $this, 'update_api_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}

		/**
		 * Add a new API key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 */
		public function add_new_api_key( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode($params[0]);

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_api_keys' ) ) {
				if ( empty( $params->user ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'User cannot be empty', 'licensehub' ),
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

				$key = new API_Key();
				$key->generate();
				$key->status     = API_Key::$active_status;
				$key->user_id    = (int) sanitize_text_field( $params->user );
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
		 * Delete an api key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function delete_api_key( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_api_keys' ) ) {
				if ( empty( $params['id'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'ID cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				$api_key = new API_Key( $params['id'] );
				$api_key->destroy();

				wp_send_json_success(
					array(
						'message' => __( 'API Key Deleted!', 'licensehub' ),
					)
				);
			}
		}

		/**
		 * @throws Exception
		 */
		public function update_api_key(WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_api_keys' ) ) {
				API_Helper::update_model_field($params, API_Key::class);
			}
		}
	}

	new API_Keys_API();
}
