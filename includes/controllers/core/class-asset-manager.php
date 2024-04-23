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

if ( ! class_exists( 'LicenseHub\Includes\Controller\Core\Asset_Manager' ) ){
	/**
	 * Manage assets
	 */
	class Asset_Manager{
		/**
		 * Hold the names of the asset files and their hashes
		 *
		 * @since 1.0.0
		 *
		 * @var array The manifest of the assets.
		 */
		private array $asset_manifest = [];

		/**
		 * Retrieve the asset options for the given path
		 *
		 * @param string $path
		 *
		 * @return array
		 */
		public static function get_asset_meta(string $path): array {
			return file_exists($path) ? require $path : array( 'dependencies' => array(), 'version' => LCHB_VERSION);
		}

		/**
		 * Construct the object
		 */
		public function __construct(){
			$this->asset_manifest = $this->get_manifest();
		}

		/**
		 * Retrieve the name and hash of the given asset
		 *
		 * @since 1.0.0
		 *
		 * @param string $asset
		 *
		 * @return string
		 */
		public function get_asset( string $asset ): string {
			if ( ! isset( $this->asset_manifest[$asset] ) ){
				return '';
			}

			return $this->asset_manifest[$asset];
		}

		/**
		 * Read the manifest file and retrieve the names and hashes
		 *
		 * @since 1.0.0
		 *
		 * @return array
		 */
		private function get_manifest(): array {
			return wp_json_file_decode( LCHB_PATH . '/public/build/manifest.json', ['associative' => true] );
		}
	}
}
