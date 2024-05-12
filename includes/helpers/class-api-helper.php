<?php
/**
 * Holds the helper class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Helper;

use Exception;
use LicenseHub\Includes\Model\API_Key;
use WP_REST_Request;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('\LicenseHub\Includes\Helper\API_Helper') ){
	class API_Helper{
		public static string $namespace = 'licensehub/v1';
		public static string $error_text = '';
		public static API_Key $key;
		public static WP_User $user;

		/**
		 * Check for the authentication header and validate it
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return bool
		 * @throws Exception A regular exception.
		 */
		public static function auth( WP_REST_Request $request ): bool {
			$key = $request->get_header( 'LCHB-API-KEY' );

			if ( ! $key ) {
				self::$error_text = 'Please use an API key';

				return false;
			}

			$load = ( new API_Key() )->load_by_field( 'api_key', $key );

			if ( ! $load ) {
				self::$error_text = esc_attr__( 'API Key is invalid', 'licensehub' );

				return false;
			}

			self::$key  = $load;
			self::$user = self::$key->user();

			return true;
		}

		public static function update_model_field( $params, $model ): void {
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

			$instance = new $model($id);
			$instance->{$column} = $value;
			$instance->save();

			wp_send_json_success( $instance );
		}
	}
}
