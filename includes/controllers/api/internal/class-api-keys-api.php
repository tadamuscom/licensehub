<?php
/**
 * Holds the API Keys API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\Internal;

use DateTime;
use Exception;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\API_Key;
use WP_REST_Request;

if ( ! class_exists( '\LicenseHub\Includes\Controller\API\Internal\API_Keys_API' ) ) {
	/**
	 * Holds the API Keys API class
	 */
	class API_Keys_API {
		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

		/**
		 * Register the routes
		 *
		 * @return void
		 */
		public function routes(): void {
			register_rest_route(
				API_Helper::generate_prefix( 'api-keys' ),
				'/new-api-key',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'create' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix( 'api-keys' ),
				'/delete-api-key',
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'delete' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix( 'api-keys' ),
				'/update-api-key',
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
		 * Add a new API key
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
			$params = json_decode( $params[0] );

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_api_keys' ) ) {
				if ( empty( $params->user ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'User cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

                // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
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

                // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
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
		public function delete( WP_REST_Request $request ): void {
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
		 * Update the API Key
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @throws Exception - A regular exception.
		 * @return void
		 */
		public function update( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_api_keys' ) ) {
				API_Helper::update_model_field( $params, API_Key::class );
			}
		}
	}

	new API_Keys_API();
}
