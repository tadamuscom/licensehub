<?php
/**
 * Holds the Licenses API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\External;

use Exception;
use LicenseHub\Includes\Controller\Core\Settings;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;
use WP_REST_Request;

if ( ! class_exists( '\LicenseHub\Includes\Controller\API\External\Licenses_API' ) ) {
	/**
	 * Holds the Licenses_API class
	 */
	class Licenses_API {
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
			$settings = new Settings();

			if ( $settings->is_enabled( 'rest' ) ) {
				register_rest_route(
					API_Helper::generate_prefix( 'licenses' ),
					'/validate',
					array(
						'methods'  => 'POST',
						'callback' => array( $this, 'validate' ),
					)
				);

				register_rest_route(
					API_Helper::generate_prefix( 'licenses' ),
					'/create',
					array(
						'methods'  => 'POST',
						'callback' => array( $this, 'create' ),
					)
				);
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
		public function validate( WP_REST_Request $request ): void {
			$key = $request->get_param( 'key' );

			if ( empty( $key ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'License key cannot be empty', 'licensehub' ),
					)
				);

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
		public function create( WP_REST_Request $request ): void {
			if ( ! API_Helper::auth( $request ) ) {
				wp_send_json_error( API_Helper::$error_text );
				return;
			}

			if ( ! $this->validation( $request ) ) {
				wp_send_json_error( API_Helper::$error_text );
			}

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
		private function validation( WP_REST_Request $request ): bool {
			$product_id = $request->get_param( 'product_id' );

			if ( ! $product_id ) {
				API_Helper::$error_text = __( 'No product_id has been passed. Please pass a valid product_id', 'licensehub' );
				return false;
			}

			if ( ! is_int( $product_id ) ) {
				API_Helper::$error_text = __( 'The product_id must be an integer', 'licensehub' );
				return false;
			}

			if ( ! ( new Product() )->exists( $product_id ) ) {
				API_Helper::$error_text = __( 'The product does not exist', 'licensehub' );
				return false;
			}

			return true;
		}
	}

	new Licenses_API();
}
