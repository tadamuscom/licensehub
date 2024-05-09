<?php
/**
 * Holds the Internal API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller;

use DateTime;
use Exception;
use LicenseHub\Includes\Controller\Integration\Stripe\Stripe;
use LicenseHub\Includes\Model\API_Key;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Internal_API' ) ) {
	/**
	 * Handles all the internal API work
	 */
	class Internal_API {
		/**
		 * The namespace
		 *
		 * @var string
		 */
		private string $namespace = 'tadamus/lchb/v1';

		/**
		 * Construct the class
		 */
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
		public function api_routes(): void {
			// Add new product.
			register_rest_route(
				$this->namespace,
				'/new-product',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'add_new_product' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Add new license key.
			register_rest_route(
				$this->namespace,
				'/new-license-key',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'add_new_license_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Add new API key.
			register_rest_route(
				$this->namespace,
				'/new-api-key',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'add_new_api_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Save settings.
			register_rest_route(
				$this->namespace,
				'/general-settings',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'save_settings' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Delete Product.
			register_rest_route(
				$this->namespace,
				'/delete-product',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'delete_product' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Delete License Key.
			register_rest_route(
				$this->namespace,
				'/delete-license-key',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'delete_license_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Delete API Key.
			register_rest_route(
				$this->namespace,
				'/delete-api-key',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'delete_api_key' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Update Product.
			register_rest_route(
				$this->namespace,
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

				$meta = array(
					'download_link' => $params->downloadLink,
				);

				$stripe_integration = get_option( 'lchb_stripe_integration' ) === 'true';

				if ( $stripe_integration ) {
					if ( empty( $params->stripeID ) ) {
						wp_send_json_error(
							array(
								'message' => __( 'Stripe ID cannot be empty', 'licensehub' ),
							)
						);

						return;
					}

					if ( Stripe::product_id_exists( $params->stripeID ) ) {
						wp_send_json_error(
							array(
								'message' => __( 'That Stripe product already has an integration', 'licensehub' ),
							)
						);
					}

					$meta['stripeID'] = $params->stripeID;
				}

				if ( isset( $params->fluentCRMLists ) ) {
					if ( str_contains( $params->fluentCRMLists, ',' ) ) {
						$lists = explode( ',', $params->fluentCRMLists );

						$meta['fluentCRMLists'] = $lists;
					} else {
						$meta['fluentCRMLists'] = $params->fluentCRMLists;
					}
				}

				if ( isset( $params->fluentCRMTags ) ) {
					if ( str_contains( $params->fluentCRMTags, ',' ) ) {
						$tags = explode( ',', $params->fluentCRMTags );

						$meta['fluentCRMTags'] = $tags;
					} else {
						$meta['fluentCRMTags'] = $params->fluentCRMTags;
					}
				}

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
		 * Add a new license key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 */
		public function add_new_license_key( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_license_keys' ) ) {
				if ( empty( $params['user'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'User cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				if ( empty( $params['product'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'Product cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				if ( empty( $params['expires_at'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'Expiry date cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				$key = new License_Key();
				$key->generate();
				$key->status     = License_Key::$active_status;
				$key->user_id    = (int) sanitize_text_field( $params['user'] );
				$key->product_id = (int) sanitize_text_field( $params['product'] );
				$key->created_at = ( new DateTime() )->format( LCHB_TIME_FORMAT );
				$key->expires_at = ( DateTime::createFromFormat( 'Y-m-d', $params['expires_at'] )->format( LCHB_TIME_FORMAT ) );
				$key->save();

				wp_send_json_success(
					array(
						'message' => __( 'The license key was saved!', 'licensehub' ),
					)
				);
			}
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
		public function add_new_api_key( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_api_keys' ) ) {
				if ( empty( $params['user'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'User cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				if ( empty( $params['expires_at'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'Expiry date cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				$key = new API_Key();
				$key->generate();
				$key->status     = License_Key::$active_status;
				$key->user_id    = (int) sanitize_text_field( $params['user'] );
				$key->created_at = ( new DateTime() )->format( LCHB_TIME_FORMAT );
				$key->expires_at = ( DateTime::createFromFormat( 'Y-m-d', $params['expires_at'] )->format( LCHB_TIME_FORMAT ) );
				$key->save();

				wp_send_json_success(
					array(
						'message' => __( 'The license key was saved!', 'licensehub' ),
					)
				);
			}
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
		public function save_settings( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode($params[0]);

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_settings' ) ) {
				$stripe = $params->stripeIntegration;

				if ( $stripe === true ) {
					if ( empty( $params->stripePublicKey ) ) {
						wp_send_json_error(
							array(
								'message' => __( 'Public Key cannot be empty', 'licensehub' ),
								'field'   => 'lchb-stripe-public-key'
							)
						);

						return;
					}

					if ( empty( $params->stripePrivateKey ) ) {
						wp_send_json_error(
							array(
								'message' => __( 'Private Key cannot be empty', 'licensehub' ),
								'field'   => 'lchb-stripe-private-key'
							)
						);

						return;
					}

					update_option( 'lchb_stripe_integration', 'true' );
					update_option( 'lchb_stripe_public_key', sanitize_text_field( $params->stripePublicKey ) );
					update_option( 'lchb_stripe_private_key', sanitize_text_field( $params->stripePrivateKey ) );
				} else {
					update_option( 'lchb_stripe_integration', 'false' );
				}

				if ( $params->fluentCRMIntegration === true ) {
					update_option( 'lchb_fluentcrm_integration', 'true' );
				} else {
					update_option( 'lchb_fluentcrm_integration', 'false' );
				}

				wp_send_json_success(
					array(
						'message' => __( 'Settings Saved!', 'licensehub' ),
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
		 * Delete a license key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function delete_license_key( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_license_keys' ) ) {
				if ( empty( $params['id'] ) ) {
					wp_send_json_error(
						array(
							'message' => __( 'ID cannot be empty', 'licensehub' ),
						)
					);

					return;
				}

				$license_key = new License_Key( $params['id'] );
				$license_key->destroy();

				wp_send_json_success(
					array(
						'message' => __( 'License Key Deleted!', 'licensehub' ),
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
		public function delete_api_key( WP_REST_Request $request ): void {
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
				$id = sanitize_text_field( $params['id'] );
				$column = sanitize_text_field( $params['column'] );
				$value = sanitize_text_field( $params['value'] );

				if ( empty( $id ) ){
					wp_send_json_error( array(
						'message' => __( 'ID cannot be empty', 'licensehub' )
					) );

					return;
				}

				if ( empty( $column ) ){
					wp_send_json_error( array(
						'message' => __( 'Column cannot be empty', 'licensehub' )
					) );

					return;
				}

				if ( empty( $value ) ){
					wp_send_json_error( array(
						'message' => __( 'A value is required', 'licensehub' )
					) );

					return;
				}

				if ( 'status' === $column ){
					$supported_statuses = array( 'active', 'inactive' );

					if( ! in_array( $value, $supported_statuses ) ){
						wp_send_json_error( array(
							'message' => __( 'Status can only be set to \'active\' or \'inactive\'', 'licensehub' )
						) );

						return;
					}
				}

				if ( 'user_id' === $column ){
					$user = get_user_by( 'id', $value );

					if ( ! $user ){
						wp_send_json_error( array(
							'message' => __( 'The user does not exist! Please add a valid user id.', 'licensehub' )
						) );
					}
				}

				$product = new Product( $id );
				$product->{$column} = $value;
				$product->save();

				wp_send_json_success( $product );
			}
		}
	}

	new Internal_API();
}
