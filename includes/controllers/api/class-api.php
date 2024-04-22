<?php
/**
 * Holds the API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller;

use LicenseHub\Includes\Model\API_Key;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;
use \WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'API' ) ) {
	/**
	 * Handles the external API methods
	 */
	class API {
		/**
		 * The name of the error
		 *
		 * @var string
		 */
		private string $error_text;

		/**
		 * The user
		 *
		 * @var mixed|bool
		 */
		private mixed $user = false;

		/**
		 * The API key
		 *
		 * @var mixed|bool
		 */
		private mixed $key = false;

		/**
		 * Construct the class
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'init_endpoints' ) );
		}

		/**
		 * Add the endpoints
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function init_endpoints(): void {
			$this->license_endpoints();
			$this->product_endpoints();
		}

		/**
		 * Check if the license is valid
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws \Exception A regular exception.
		 */
		public function validate_license( WP_REST_Request $request ): void {
			$key = $request->get_param( 'license_key' );

			if ( $key ) {
				$license = ( new License_Key() )->load_by_field( 'license_key', $key );

				if ( $license ) {
					wp_send_json_success( array( 'message' => 'License key is valid' ) );
					return;
				}
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
		 * @throws \Exception A regular exception.
		 */
		public function create_license( WP_REST_Request $request ): void {
			if ( ! $this->auth( $request ) ) {
				wp_send_json_error( $this->error_text );
				return;
			}

			if ( $this->validate_new_license( $request ) ) {
				$product_id = $request->get_param( 'product_id' );

				$license = new License_Key();
				$license->generate();
				$license->product_id = (int) sanitize_text_field( $product_id );
				$license->user_id    = $this->user->ID;
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
				wp_send_json_error( $this->error_text );
			}
		}

		/**
		 * Create a product
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws \Exception A regular exception.
		 */
		public function create_product( WP_REST_Request $request ): void {
			if ( ! $this->auth( $request ) ) {
				wp_send_json_error( $this->error_text );
				return;
			}

			if ( $this->validate_new_product( $request ) ) {
				$product          = new Product();
				$product->name    = sanitize_text_field( $request->get_param( 'name' ) );
				$product->status  = Product::$active_status;
				$product->user_id = $this->user->ID;
				$product->save();

				wp_send_json_success(
					array(
						'id'         => $product->id,
						'name'       => $product->name,
						'status'     => $product->status,
						'created_at' => $product->created_at,
					)
				);
			} else {
				wp_send_json_error( $this->error_text );
			}
		}

		/**
		 * Return a product based on the ID
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws \Exception A regular exception.
		 */
		public function retrieve_product( WP_REST_Request $request ): void {
			if ( ! $this->auth( $request ) ) {
				wp_send_json_error( $this->error_text );
				return;
			}

			if ( $this->validate_existing_product( $request ) ) {
				$product = new Product( $request->get_param( 'product_id' ) );

				wp_send_json_success(
					array(
						'id'         => $product->id,
						'name'       => $product->name,
						'status'     => $product->status,
						'created_at' => $product->created_at,
					)
				);

			} else {
				wp_send_json_error( $this->error_text );
			}
		}

		/**
		 * Add the endpoints for the license actions
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function license_endpoints(): void {
			// Public.
			register_rest_route(
				'lchb/v1/license',
				'/validate',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'validate_license' ),
				)
			);

			// Private.
			register_rest_route(
				'lchb/v1/license',
				'/create',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'create_license' ),
				)
			);
		}

		/**
		 * Add the endpoints for the product actions
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function product_endpoints(): void {
			register_rest_route(
				'lchb/v1/product',
				'/retrieve',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'retrieve_product' ),
				)
			);
			register_rest_route(
				'lchb/v1/product',
				'/create',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'create_product' ),
				)
			);
		}

		/**
		 * Check for the authentication header and validate it
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return bool
		 * @throws \Exception A regular exception.
		 */
		private function auth( WP_REST_Request $request ): bool {
			$key = $request->get_header( 'LCHB-API-KEY' );

			if ( ! $key ) {
				$this->error_text = 'Please use an API key';

				return false;
			}

			$load = ( new API_Key() )->load_by_field( 'api_key', $key );

			if ( ! $load ) {
				$this->error_text = esc_attr__( 'API Key is invalid', 'licensehub' );

				return false;
			}

			$this->key  = $load;
			$this->user = $this->key->user();

			return true;
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
				$this->error_text = 'No product_id has been passed. Please pass a valid product_id';
				return false;
			}

			if ( ! is_int( $product_id ) ) {
				$this->error_text = 'The product_id must be an integer';
				return false;
			}

			if ( ! ( new Product() )->exists( $product_id ) ) {
				$this->error_text = 'The product does not exist';
				return false;
			}

			return true;
		}

		/**
		 * Validate if the data for the new product is correct
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return bool
		 */
		private function validate_new_product( WP_REST_Request $request ): bool {
			if ( ! $request->get_param( 'name' ) ) {
				$this->error_text = 'Please provide a name for the product';
				return false;
			}

			if ( ! is_string( $request->get_param( 'name' ) ) ) {
				$this->error_text = 'The product name must be a string';
				return false;
			}

			return true;
		}

		/**
		 * Validate data for an existing plugin
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return bool
		 */
		private function validate_existing_product( WP_REST_Request $request ): bool {
			$id = $request->get_param( 'product_id' );

			if ( ! $id ) {
				$this->error_text = 'Please an id for the product';
				return false;
			}

			if ( ! is_int( $id ) ) {
				$this->error_text = 'The product_id must be an integer';
				return false;
			}

			if ( ! ( new Product() )->exists( $id ) ) {
				$this->error_text = 'There is no product with that id';
				return false;
			}

			return true;
		}
	}

	new API();
}
