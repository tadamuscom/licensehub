<?php

use LicenseHub\Includes\Helper\Helper;

/**
 * Returns a dump of the given property and kills the process
 *
 * @since 1.0.0
 *
 * @param $body
 *
 * @return bool
 */
if( ! function_exists( 'lchb_dd' ) ){
	function lchb_dd( $body ) : void {
		Helper::dd( $body );
	}	
}

/**
 * Redirects people to a given URL with notifications if needed
 *
 * @since 1.0.0
 *
 * @param $url
 * @param $type
 * @param $message
 *
 * @return bool
 */
if( ! function_exists( 'lchb_redirect' ) ){
	function lchb_redirect( $url, $type = null, $message = null ) : bool {
		return Helper::redirect( $url, $type, $message );
	}
}

/**
 * If an option exists in the WordPress API it updates it. Or creates a new one if it doesn't exist
 *
 * @since 1.0.0
 *
 * @param $tag
 * @param $value
 *
 * @return bool
 */
if( ! function_exists( 'lchb_add_or_update_option' ) ) {
	function lchb_add_or_update_option( $tag, $value ) : bool {
		return Helper::add_or_update_option( $tag, $value );
	}
}

/**
 * If a user meta exists in the WordPress database it updates it. Or creates a new one if it doesn't exist
 *
 * @since 1.0.0
 *
 * @param $user_id
 * @param $tag
 * @param $value
 *
 * @return bool
 */
if( ! function_exists( 'lchb_add_or_update_user_meta' ) ) {
	function lchb_add_or_update_user_meta( $user_id, $tag, $value ): bool {
		return Helper::add_or_update_user_meta( $user_id, $tag, $value );
	}
}