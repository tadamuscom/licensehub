<?php
/**
 * Holds the Releases API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\Internal;

use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\Product;
use LicenseHub\Includes\Model\Release;
use WP_REST_Request;

if ( ! class_exists( '\LicenseHub\Includes\Controller\API\Internal\Releases_API' ) ) {
	/**
	 * Holds the Releases_API class
	 */
	class Releases_API {
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
				API_Helper::generate_prefix( 'releases' ),
				'/new-release',
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'create' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix( 'releases' ),
				'/delete-release',
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'delete' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			register_rest_route(
				API_Helper::generate_prefix( 'releases' ),
				'/update-release',
				array(
					'methods'             => 'PUT',
					'callback'            => array( $this, 'update' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}

		/**
		 * Create a new release
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function create( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode( $params[0] );

			if ( ! empty( $params->nonce ) && wp_verify_nonce( $params->nonce, 'lchb_releases' ) ) {
				$version = sanitize_text_field( $params->version );

                // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$change_log = sanitize_text_field( $params->changeLog );

                // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$product_id = sanitize_text_field( $params->productID );

                // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$attachment_id = sanitize_text_field( $params->attachmentID );

				if ( empty( $version ) ) {
					wp_send_json_error(
						array( 'message' => __( 'Version cannot be empty', 'licensehub' ) )
					);

					return;
				}

                // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$product = new Product( $params->productID );
				$release = $product->last_release();

				if ( $release && version_compare( $release->version, $version, '>=' ) ) {
					wp_send_json_error( array( 'message' => __( 'The version must be greater than the last release', 'licensehub' ) ) );
				}

				if ( empty( $change_log ) ) {
					wp_send_json_error(
						array( 'message' => __( 'Change log cannot be empty', 'licensehub' ) )
					);

					return;
				}

				$release             = new Release();
				$release->product_id = $product_id;
				$release->changelog  = $change_log;
				$release->version    = $version;

				if ( ! empty( $attachment_id ) ) {
					$release->attachment_id = (int) $attachment_id;
				}

				$release->save();

				wp_send_json_success( array( 'message' => __( 'The product was saved!', 'licensehub' ) ) );
			}
		}

		/**
		 * Delete a release
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function delete( WP_REST_Request $request ): void {
			$params = $request->get_params();

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_releases' ) ) {
				if ( empty( $params['id'] ) ) {
					wp_send_json_error( array( 'message' => __( 'ID cannot be empty', 'licensehub' ) ) );

					return;
				}

				$release = new Release( $params['id'] );
				$release->destroy();

				wp_send_json_success( array( 'message' => __( 'Release Deleted!', 'licensehub' ) ) );
			}
		}

		/**
		 * Update a release
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return void
		 */
		public function update( WP_REST_Request $request ): void {
			$params = $request->get_params();
			$params = json_decode( $params[0], true );

			if ( ! empty( $params['nonce'] ) && wp_verify_nonce( $params['nonce'], 'lchb_releases' ) ) {
				$id        = sanitize_text_field( $params['id'] );
				$version   = sanitize_text_field( $params['version'] );
				$changelog = sanitize_text_field( $params['changelog'] );

				if ( empty( $id ) ) {
					wp_send_json_error( array( 'message' => __( 'ID cannot be empty', 'licensehub' ) ) );

					return;
				}

				if ( empty( $version ) ) {
					wp_send_json_error( array( 'message' => __( 'Version cannot be empty', 'licensehub' ) ) );

					return;
				}

				if ( empty( $changelog ) ) {
					wp_send_json_error( array( 'message' => __( 'Changelog cannot be empty', 'licensehub' ) ) );

					return;
				}

				$release = new Release( $id );
				$product = new Product( $release->product_id );

				if ( 0 === $release->id ) {
					wp_send_json_error( array( 'message' => __( 'Release not found', 'licensehub' ) ) );
				}

				if ( $product->last_release() && ( $product->last_release() )->version !== $version && version_compare( ( $product->last_release() )->version, $version, '>=' ) ) {
					wp_send_json_error( array( 'message' => __( 'The version must be greater than the last release', 'licensehub' ) ) );
				}

				$release->version   = $version;
				$release->changelog = $changelog;
				$release->save();

				wp_send_json_success(
					array( 'message' => __( 'Product updated!', 'licensehub' ) )
				);
			}
		}
	}

	new Releases_API();
}
