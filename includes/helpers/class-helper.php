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
