<?php
/**
 * Holds the Products API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API;

use DateTime;
use Exception;
use LicenseHub\Includes\Controller\Core\Settings;
use LicenseHub\Includes\Controller\Integration\Stripe\Stripe;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\Product;
use WP_REST_Request;

if ( ! class_exists('\LicenseHub\Includes\Controller\API\Products_API') ){
	class Products_API{
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

		public function routes(): void {
			$this->internal_routes();
			$settings = new Settings();
			
			if ($settings->is_enabled('rest')){
				$this->external_routes();
			}
		}

		/**
		 * Add a new product.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function internal_add_new_product( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode($params[0]);

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_products' ) ) {
				if ( empty( $params->name ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'Name cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				$product             = new Product();
				$product->name       = sanitize_text_field( $params->name );
				$product->status     = Product::$active_status;
				$product->user_id    = get_current_user_id();
				$product->created_at = ( new DateTime() )->format( LCHB_TIME_FORMAT );

				$meta = array(
					'download_link' => $params->downloadLink,
				);

				$product->meta = serialize( $meta );
				$product->save();

				wp_send_json_success(
					array(
						'message' => __( 'The product was saved!', 'licensehub' ),
					)
				);
			}
		}

		/**
		 * Delete a product
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function internal_delete_product( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_products' ) ) {
				if ( empty( $params['id'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'ID cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				$product = new Product( $params['id'] );
				$product->destroy();

				wp_send_json_success(
					array(
						'message' => __( 'Product Deleted!', 'licensehub' ),
					)
				);
			}
		}

		/**
		 * Update product
		 *
		 * @since 1.0.4
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function internal_update_product( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_products' ) ) {
				API_Helper::update_model_field($params, Product::class);
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
		 * @throws Exception A regular exception.
		 */
		public function external_retrieve_product( WP_REST_Request $request ): void {
			if ( ! API_Helper::auth( $request ) ) {
				wp_send_json_error( API_Helper::$error_text );
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
				wp_send_json_error( API_Helper::$error_text );
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
		 * @throws Exception A regular exception.
		 */
		public function external_create_product( WP_REST_Request $request ): void {
			if ( ! API_Helper::auth( $request ) ) {
				wp_send_json_error( API_Helper::$error_text );
				return;
			}

			if ( $this->validate_new_product( $request ) ) {
				$product          = new Product();
				$product->name    = sanitize_text_field( $request->get_param( 'name' ) );
				$product->status  = Product::$active_status;
				$product->user_id = API_Helper::$user->ID;
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
				wp_send_json_error( API_Helper::$error_text );
			}
		}

		private function internal_routes(): void {
			// Add new product.
			register_rest_route(
				API_Helper::$namespace,
				'/new-product',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'internal_add_new_product' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Delete Product.
			register_rest_route(
				API_Helper::$namespace,
				'/delete-product',
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'internal_delete_product' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Update Product.
			register_rest_route(
				API_Helper::$namespace,
				'/update-product',
				array(
					'methods'             => 'PUT',
					'callback'            => array( $this, 'internal_update_product' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}

		private function external_routes(): void {
			// Retrieve a product
			register_rest_route(
				API_Helper::$namespace . '/products',
				'/retrieve',
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'external_retrieve_product' ),
					'permission_callback' => ''
				)
			);

			// Create a product external
			register_rest_route(
				API_Helper::$namespace . '/products',
				'/create',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'external_create_product' ),
					'permission_callback' => ''
				)
			);
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
				API_Helper::$error_text = 'Please an id for the product';
				return false;
			}

			if ( ! is_int( $id ) ) {
				API_Helper::$error_text = 'The product_id must be an integer';
				return false;
			}

			if ( ! ( new Product() )->exists( $id ) ) {
				API_Helper::$error_text = 'There is no product with that id';
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
				API_Helper::$error_text = 'Please provide a name for the product';
				return false;
			}

			if ( ! is_string( $request->get_param( 'name' ) ) ) {
				API_Helper::$error_text = 'The product name must be a string';
				return false;
			}

			return true;
		}
	}

	new Products_API();
}
