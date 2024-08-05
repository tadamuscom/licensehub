<?php
/**
 * Holds the Products API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\Internal;

use DateTime;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\Product;
use WP_REST_Request;

if ( ! class_exists( '\LicenseHub\Includes\Controller\API\Internal\Products_API' ) ) {
	/**
	 * Holds the Products_API class
	 */
	class Products_API {
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
				API_Helper::generate_prefix( 'products' ),
				'/new-product',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'create' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix( 'products' ),
				'/delete-product',
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'delete' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix( 'products' ),
				'/update-product',
				array(
					'methods'             => 'PUT',
					'callback'            => array( $this, 'update' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix( 'products' ),
				'/get-product/(?P<id>\d+)',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'retrieve' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix( 'products' ),
				'/(?P<id>\d+)/get-releases',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'releases' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}

		/**
		 * Add a new product.
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function create( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode( $params[0] );

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_products' ) ) {
				if ( empty( $params->name ) ) {
					wp_send_json_error(
						array( 'message' => __( 'Name cannot be empty', 'licensehub' ) )
					);

					return;
				}

				$product             = new Product();
				$product->name       = sanitize_text_field( $params->name );
				$product->status     = Product::$active_status;
				$product->user_id    = get_current_user_id();
				$product->created_at = ( new DateTime() )->format( LCHB_TIME_FORMAT );

				/**
				 * Filters the product before saving.
				 *
				 * Allows modification of the product data before it is saved to the database.
				 * Make sure you don't save the product again. It gets saved after this filter.
				 *
				 * @param Product $product The product object.
				 * @param object  $params The parameters passed to the API.
				 *
				 * @return Product|void Save method for the product.
				 * @since 1.0.0
				 */
				$product = apply_filters( 'lchb_product_before_create', $product, $params );
				$product->save();

				wp_send_json_success( array( 'message' => __( 'The product was saved!', 'licensehub' ) ) );
			}
		}

		/**
		 * Delete a product
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function delete( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_products' ) ) {
				if ( empty( $params['id'] ) ) {
					wp_send_json_error( array( 'message' => __( 'ID cannot be empty', 'licensehub' ) ) );

					return;
				}

				$product = new Product( $params['id'] );
				$product->destroy();

				wp_send_json_success( array( 'message' => __( 'Product Deleted!', 'licensehub' ) ) );
			}
		}

		/**
		 * Update product
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function update( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode( $params[0] );

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_products' ) ) {
				$id   = sanitize_text_field( $params->id );
				$name = sanitize_text_field( $params->name );

				if ( empty( $id ) ) {
					wp_send_json_error( array( 'message' => __( 'ID cannot be empty', 'licensehub' ) ) );

					return;
				}

				if ( empty( $name ) ) {
					wp_send_json_error( array( 'message' => __( 'Name cannot be empty', 'licensehub' ) ) );

					return;
				}

				$product       = new Product( $id );
				$product->name = $name;

				/**
				 * Filters the product before saving.
				 *
				 * Allows modification of the product data before it is saved to the database.
				 * Make sure you don't save the product again. It gets saved after this filter.
				 *
				 * @param Product $product The product object.
				 * @param object  $params The parameters passed to the API.
				 *
				 * @return Product|void Save method for the product.
				 * @since 1.0.0
				 */
				$product = apply_filters( 'lchb_product_before_update', $product, $params );
				$product->save();

				wp_send_json_success(
					array( 'message' => __( 'Product updated!', 'licensehub' ) )
				);
			}
		}

		/**
		 * Retrieve a product
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function retrieve( WP_REST_Request $request ): void {
			$params  = $request->get_url_params();
			$product = new Product( $params['id'] );

			if ( empty( $product->id ) ) {
				wp_send_json_error( array( 'message' => __( 'Product not found', 'licensehub' ) ) );
			}

			wp_send_json_success( $product );
		}

		/**
		 * Retrieve the releases of a product
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function releases( WP_REST_Request $request ): void {
			$params  = $request->get_url_params();
			$product = new Product( $params['id'] );

			if ( 0 === $product->id ) {
				wp_send_json_error(
					array( 'message' => __( 'Product not found', 'licensehub' ) )
				);
			}

			wp_send_json_success( $product->releases() );
		}
	}

	new Products_API();
}
