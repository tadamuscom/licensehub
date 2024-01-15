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

if ( ! function_exists( 'lchb_dd' ) ) {
	/**
	 * Returns a dump of the given property and kills the process
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $body The data that needs to be dumped.
	 *
	 * @return void
	 */
	function lchb_dd( mixed $body ): void {
		Helper::dd( $body );
	}
}

if ( ! function_exists( 'lchb_redirect' ) ) {
	/**
	 * Redirects people to a given URL with notifications if needed
	 *
	 * @since 1.0.0
	 *
	 * @param string      $url The url to redirect to.
	 * @param string|null $type The type of the redirect.
	 * @param string|null $message The message to be passed with the redirect.
	 *
	 * @return bool
	 */
	function lchb_redirect( string $url, string|null $type = null, string|null $message = null ): bool {
		return Helper::redirect( $url, $type, $message );
	}
}

if ( ! function_exists( 'lchb_add_or_update_option' ) ) {
	/**
	 * If an option exists in the WordPress API it updates it. Or creates a new one if it doesn't exist
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag The name of the tag.
	 * @param mixed  $value The value of the option.
	 *
	 * @return bool
	 */
	function lchb_add_or_update_option( string $tag, mixed $value ): bool {
		return Helper::add_or_update_option( $tag, $value );
	}
}

if ( ! function_exists( 'lchb_add_or_update_user_meta' ) ) {
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
	function lchb_add_or_update_user_meta( string|int $user_id, string $tag, mixed $value ): bool {
		return Helper::add_or_update_user_meta( $user_id, $tag, $value );
	}
}

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
