<?php
/**
 * Holds the Asset_Manager class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Controller\Core\Asset_Manager' ) ) {
	/**
	 * Manage assets
	 */
	class Asset_Manager {
		/**
		 * Retrieve the asset options for the given path
		 *
		 * @param string $path The path to the asset.
		 *
		 * @return array
		 */
		public static function get_asset_meta( string $path ): array {
			return file_exists( $path ) ? require $path : array(
				'dependencies' => array(),
				'version'      => LCHB_VERSION,
			);
		}
	}
}
