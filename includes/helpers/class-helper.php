<?php
/**
 * Holds the helper class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Helper;

use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Helper' ) ) {
	/**
	 * Main class that holds all the helper methods
	 */
	class Helper {

		/**
		 * Returns a dump of the given property and kills the process
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The value to be dumped.
		 *
		 * @return void
		 */
		public static function dd( mixed $value ): void {
			echo '<pre>';
			var_dump( $value );
			echo '</pre>';
			die();
		}

		/**
		 * Redirects people to a given URL with notifications if needed
		 *
		 * @since 1.0.0
		 *
		 * @param string      $url The URL to be redirected to.
		 * @param string|null $type The type of the redirect.
		 * @param string|null $message The message to be passed.
		 *
		 * @return bool
		 */
		public static function redirect( string $url, string|null $type = null, string|null $message = null ): bool {
			if ( null !== $type && null !== $message ) {
				setcookie( 'lchb_redirect_type', $type, time() + 3600, '/' );
				setcookie( 'lchb_redirect_message', $message, time() + 3600, '/' );

				return wp_redirect( $url );
			}

			return wp_redirect( $url );
		}

		/**
		 * If an option exists in the WordPress API it updates it. Or creates a new one if it doesn't exist
		 *
		 * @since 1.0.0
		 *
		 * @param string $tag The name of the tag.
		 * @param mixed  $value The value for the option.
		 *
		 * @return bool
		 */
		public static function add_or_update_option( string $tag, mixed $value ): bool {
			if ( get_option( $tag ) ) {
				return update_option( $tag, $value );
			}

			return add_option( $tag, $value );
		}

		/**
		 * If a user meta exists in the WordPress database it updates it. Or creates a new one if it doesn't exist
		 *
		 * @since 1.0.0
		 *
		 * @param string|int $user_id The ID of the user.
		 * @param string     $tag The name of the tag.
		 * @param mixed      $value The value of the option.
		 *
		 * @return bool
		 */
		public static function add_or_update_user_meta( string|int $user_id, string $tag, mixed $value ): bool {
			if ( get_user_meta( $user_id, $tag ) ) {
				return update_user_meta( $user_id, $tag, $value );
			}

			return add_user_meta( $user_id, $tag, $value );
		}

		/**
		 * Return a user object if the user exists or create one if it doesn't exist
		 *
		 * @since 1.0.0
		 *
		 * @param string $email The email address.
		 *
		 * @return WP_User
		 */
		public static function get_or_create_user_by_email( string $email ): WP_User {
			$user = get_user_by( 'email', $email );

			if ( ! $user ) {
				return get_user_by( 'id', wp_create_user( $email, uniqid(), $email ) );
			}

			return $user;
		}
	}
}
