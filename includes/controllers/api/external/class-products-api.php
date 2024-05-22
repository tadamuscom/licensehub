<?php
/**
 * Holds the Products API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\External;

use Exception;
use LicenseHub\Includes\Controller\Core\Settings;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\Product;
use WP_REST_Request;

if ( ! class_exists('\LicenseHub\Includes\Controller\API\External\Products_API') ){
	class Products_API{
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

		public function routes(): void {
			$settings = new Settings();
			
			if ($settings->is_enabled('rest')){
                register_rest_route(
                    API_Helper::generate_prefix('products'),
                    '/retrieve',
                    array(
                        'methods'  => 'GET',
                        'callback' => array( $this, 'retrieve_product' ),
                    )
                );

                register_rest_route(
                    API_Helper::generate_prefix('products'),
                    '/create',
                    array(
                        'methods'  => 'POST',
                        'callback' => array( $this, 'create_product' ),
                    )
                );
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
		public function retrieve_product( WP_REST_Request $request ): void {
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
		public function create_product( WP_REST_Request $request ): void {
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
