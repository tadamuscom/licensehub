<?php

namespace LicenseHub\Includes\Controller;

use \DateTime;
use LicenseHub\Includes\Controller\Integration\Stripe\Stripe;
use LicenseHub\Includes\Model\API_Key;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;
use WP_REST_Request;

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
			// Add new product
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

			// Add new license key
			register_rest_route(
				$this->namespace,
				'/new-license-key',
				array(
					'methods' => 'POST',
					'callback' => array($this, 'add_new_license_key'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
				)
			);

			// Add new API key
			register_rest_route(
				$this->namespace,
				'/new-api-key',
				array(
					'methods' => 'POST',
					'callback' => array($this, 'add_new_api_key'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
				)
			);

			// Save settings
			register_rest_route(
				$this->namespace,
				'/general-settings',
				array(
					'methods' => 'POST',
					'callback' => array($this, 'save_settings'),
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
		 * @param WP_REST_Request $request
		 *
		 * @return void
		 */
		public function add_new_product( WP_REST_Request $request ) : void {
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

				if( isset( $params['stripe_id'] ) ){
					if( empty( $params[ 'stripe_id' ] ) ){
						wp_send_json_error( array(
							'message' => __( 'Stripe ID cannot be empty', 'licensehub' )
						) );

						return;
					}

					if( Stripe::product_id_exists( $params['stripe_id'] ) ){
						wp_send_json_error( array(
							'message' => __( 'That Stripe product already has an integration', 'licensehub' )
						) );
					}

					$product->meta = serialize( array(
						'stripe_id' => $params[ 'stripe_id' ]
					) );
				}

				$product->save();

				wp_send_json_success( array(
					'message' => __( 'The product was saved!', 'licensehub' )
				) );
			}
		}

		/**
		 * Add a new license key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return void
		 * @throws \Exception
		 */
		public function add_new_license_key( WP_REST_Request $request ) : void {
			$params = $request->get_params();

			if( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_license_keys' ) ){
				if( empty( $params[ 'user' ] ) ){
					wp_send_json_error( array(
						'message' => __( 'User cannot be empty', 'licensehub' )
					) );

					return;
				}

				if( empty( $params[ 'product' ] ) ){
					wp_send_json_error( array(
						'message' => __( 'Product cannot be empty', 'licensehub' )
					) );

					return;
				}

				if( empty( $params[ 'expires_at' ] ) ){
					wp_send_json_error( array(
						'message' => __( 'Expiry date cannot be empty', 'licensehub' )
					) );

					return;
				}

				$key = new License_Key();
				$key->generate();
				$key->status = License_Key::$ACTIVE_STATUS;
				$key->user_id = (int) sanitize_text_field( $params[ 'user' ] );
				$key->product_id = (int) sanitize_text_field( $params[ 'product' ] );
				$key->created_at = ( new DateTime() )->format( LCHB_TIME_FORMAT );
				$key->expires_at = ( DateTime::createFromFormat( 'Y-m-d', $params[ 'expires_at' ] )->format( LCHB_TIME_FORMAT ) );
				$key->save();

				wp_send_json_success( array(
					'message' => __( 'The license key was saved!', 'licensehub' )
				) );
			}
		}

		/**
		 * Add a new API key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return void
		 * @throws \Exception
		 */
		public function add_new_api_key( WP_REST_Request $request ) : void {
			$params = $request->get_params();

			if( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_api_keys' ) ){
				if( empty( $params[ 'user' ] ) ){
					wp_send_json_error( array(
						'message' => __( 'User cannot be empty', 'licensehub' )
					) );

					return;
				}

				if( empty( $params[ 'expires_at' ] ) ){
					wp_send_json_error( array(
						'message' => __( 'Expiry date cannot be empty', 'licensehub' )
					) );

					return;
				}

				$key = new API_Key();
				$key->generate();
				$key->status = License_Key::$ACTIVE_STATUS;
				$key->user_id = (int) sanitize_text_field( $params[ 'user' ] );
				$key->created_at = ( new DateTime() )->format( LCHB_TIME_FORMAT );
				$key->expires_at = ( DateTime::createFromFormat( 'Y-m-d', $params[ 'expires_at' ] )->format( LCHB_TIME_FORMAT ) );
				$key->save();

				wp_send_json_success( array(
					'message' => __( 'The license key was saved!', 'licensehub' )
				) );
			}
		}

		/**
		 * Save the settings
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return void
		 * @throws \Exception
		 */
		public function save_settings( WP_REST_Request $request ) : void {
			$params = $request->get_params();

			if( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_settings' ) ){
				$stripe = $params['stripe_integration'];

				if( $stripe === 'on' ){
					if( empty( $params[ 'stripe_public_key' ] ) ){
						wp_send_json_error( array(
							'message' => __( 'Public Key cannot be empty', 'licensehub' )
						) );

						return;
					}

					if( empty( $params[ 'stripe_private_key' ] ) ){
						wp_send_json_error( array(
							'message' => __( 'Private Key cannot be empty', 'licensehub' )
						) );

						return;
					}

					lchb_add_or_update_option( 'lchb_stripe_integration', 'true' );
					lchb_add_or_update_option( 'lchb_stripe_public_key', sanitize_text_field( $params['stripe_public_key'] ) );
					lchb_add_or_update_option( 'lchb_stripe_private_key', sanitize_text_field( $params['stripe_private_key'] ) );
				}else{
					lchb_add_or_update_option( 'lchb_stripe_integration', 'false' );
				}

				wp_send_json_success( array(
					'message' => __( 'Settings Saved!', 'licensehub' )
				) );
			}
		}
	}

	new Internal_API();
}