<?php
/**
 * Holds the Settings API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\Internal;

use Exception;
use LicenseHub\Includes\Controller\Core\Settings;
use LicenseHub\Includes\Helper\API_Helper;
use WP_REST_Request;

if ( ! class_exists( '\LicenseHub\Includes\Controller\API\Internal\Settings_API' ) ) {
	/**
	 * Holds the Settings_API class
	 */
	class Settings_API {
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
				API_Helper::generate_prefix( 'settings' ),
				'/general',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'save' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}

		/**
		 * Save the settings
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 */
		public function save( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode( $params[0] );

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_settings' ) ) {
				$settings = new Settings();

				( true === $params->enable_rest_api ) ? $settings->set( 'rest', true ) : $settings->set( 'rest', false );
				$settings->save();

				wp_send_json_success(
					array(
						'message' => __( 'Settings Saved!', 'licensehub' ),
					)
				);
			}
		}
	}

	new Settings_API();
}
