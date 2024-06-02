<?php
/**
 * Facades of the helper class
 *
 * @package licensehub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use LicenseHub\Includes\Helper\Helper;

if ( ! function_exists( 'lchb_get_or_create_user_by_email' ) ) {
	/**
	 * Return a user object if the user exists or create one if it doesn't exist
	 *
	 * @since 1.0.0
	 *
	 * @param string $email The email address.
	 *
	 * @return WP_User
	 */
	function lchb_get_or_create_user_by_email( string $email ): WP_User {
		return Helper::get_or_create_user_by_email( $email );
	}
}
