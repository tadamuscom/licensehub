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

if ( ! class_exists('\LicenseHub\Includes\Controller\API\Internal\Products_API') ){
	class Products_API{
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

		public function routes(): void {
			register_rest_route(
				API_Helper::generate_prefix('products'),
				'/new-product',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'add_new_product' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
                API_Helper::generate_prefix('products'),
				'/delete-product',
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'delete_product' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix('products'),
				'/update-product',
				array(
					'methods'             => 'PUT',
					'callback'            => array( $this, 'update_product' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
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
		public function add_new_product( WP_REST_Request $request ): void {
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
		public function delete_product( WP_REST_Request $request ): void {
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
		public function update_product( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_products' ) ) {
				API_Helper::update_model_field($params, Product::class);
			}
		}
	}

	new Products_API();
}
