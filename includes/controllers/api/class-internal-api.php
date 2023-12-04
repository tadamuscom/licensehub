<?php

namespace LicenseHub\Includes\Controller;

use FluentCrm\Framework\Database\Orm\DateTime;
use LicenseHub\Includes\Model\Product;

if( ! class_exists( 'Internal_API' ) ) {
	class Internal_API {
		private string $namespace = 'tadamus/lchb/v1';

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'api_routes' ) );
		}

		/**
		 * Register the routes
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function api_routes() : void {
			// Save general settings
			register_rest_route(
				$this->namespace,
				'/new-product',
				array(
					'methods' => 'POST',
					'callback' => array($this, 'add_new_product'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
				)
			);
		}

		/**
		 * Add a new product
		 *
		 * @since 1.0.0
		 *
		 * @param \WP_REST_Request $request
		 *
		 * @return void
		 */
		public function add_new_product( \WP_REST_Request $request ) : void {
			$params = $request->get_params();

			if( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_products' ) ){
				if( empty( $params[ 'name' ] ) ){
					wp_send_json_error( array(
						'message' => __( 'Name cannot be empty', 'licensehub' )
					) );

					return;
				}

				$product = new Product();
				$product->name = sanitize_text_field( $params['name'] );
				$product->status = Product::$ACTIVE_STATUS;
				$product->user_id = get_current_user_id();
				$product->created_at = (new DateTime())->format( LCHB_TIME_FORMAT );
				$product->save();

				wp_send_json_success( array(
					'message' => __( 'The product was saved!', 'licensehub' ),
					'product' => $product
				) );
			}
		}
	}

	new Internal_API();
}