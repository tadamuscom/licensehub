<?php
/**
 * The main plugin file
 *
 * @package licensehub
 */

namespace LicenseHub;

use LicenseHub\Includes\Controller\Core\Activation;
use LicenseHub\Includes\Controller\Core\Deactivation;
use LicenseHub\Includes\Loader;

/**
 * Plugin Name:       LicenseHub
 * Plugin URI:        https://tadamus.com/products/licensehub
 * Description:       A lightweight plugin that allows you to create, manage and maintain software licenses for your plugins
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      8.0
 * Author:            Tadamus
 * Author URI:        https://tadamus.com
 */

define( 'LCHB_VERSION', '1.0.0' );
define( 'LCHB_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'LCHB_SLUG' ) ) {
	define( 'LCHB_SLUG', 'licensehub' );
}

if ( ! defined( 'LCHB_PATH' ) ) {
	define( 'LCHB_PATH', WP_PLUGIN_DIR . '/' . LCHB_SLUG );
}

if ( ! defined( 'LCHB_INC' ) ) {
	define( 'LCHB_INC', LCHB_PATH . '/includes' );
}

if ( ! defined( 'LCHB_VENDOR' ) ) {
	define( 'LCHB_VENDOR', LCHB_PATH . '/vendor' );
}

if ( ! defined( 'LCHB_LIB' ) ) {
	define( 'LCHB_LIB', LCHB_INC . '/lib' );
}

if ( ! defined( 'LCHB_HELPER' ) ) {
	define( 'LCHB_HELPER', LCHB_INC . '/helpers' );
}

if ( ! defined( 'LCHB_CONTROLLER' ) ) {
	define( 'LCHB_CONTROLLER', LCHB_INC . '/controllers' );
}

if ( ! defined( 'LCHB_PAGE' ) ) {
	define( 'LCHB_PAGE', LCHB_CONTROLLER . '/pages' );
}

if ( ! defined( 'LCHB_INTERFACE' ) ) {
	define( 'LCHB_INTERFACE', LCHB_INC . '/interfaces' );
}

if ( ! defined( 'LCHB_ABSTRACT' ) ) {
	define( 'LCHB_ABSTRACT', LCHB_INC . '/abstracts' );
}

if ( ! defined( 'LCHB_MODEL' ) ) {
	define( 'LCHB_MODEL', LCHB_INC . '/models' );
}

if ( ! defined( 'LCHB_ASSET' ) ) {
	define( 'LCHB_ASSET', LCHB_URL . '/assets' );
}

if ( ! defined( 'LCHB_CSS' ) ) {
	define( 'LCHB_CSS', LCHB_ASSET . '/css' );
}

if ( ! defined( 'LCHB_JS' ) ) {
	define( 'LCHB_JS', LCHB_ASSET . '/js' );
}

if ( ! defined( 'LCHB_IMG' ) ) {
	define( 'LCHB_IMG', LCHB_ASSET . '/img' );
}

if ( ! defined( 'LCHB_DB_PREFIX' ) ) {
	define( 'LCHB_DB_PREFIX', 'lchb' );
}

if ( ! defined( 'LCHB_TIME_FORMAT' ) ) {
	define( 'LCHB_TIME_FORMAT', 'Y-m-d H:i:s' );
}

if ( ! class_exists( 'LicenseHub' ) ) {
	/**
	 * The main plugin class
	 */
	class LicenseHub {
		/**
		 * Construct the object
		 */
		public function __construct() {
			$this->check_php_version();
			$this->check_wp_version();

			require LCHB_INC . '/class-loader.php';

			new Loader();

			add_action( 'activate_' . LCHB_SLUG . '/class-' . LCHB_SLUG . '.php', array( $this, 'activate' ) );
			add_action( 'deactivate_' . LCHB_SLUG . '/class-' . LCHB_SLUG . '.php', array( $this, 'deactivate' ) );
		}

		/**
		 * Check if the PHP version is correct
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function check_php_version(): void {
			if ( phpversion() < 8.0 ) {
				wp_die( esc_attr__( 'PHP version cannot be lower than 8.0', 'licensehub' ) );
			}
		}

		/**
		 * Check the WP version
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function check_wp_version(): void {
			global $wp_version;

			if ( $wp_version < 6.2 ) {
				wp_die( esc_attr__( 'WordPress version cannot be lower than 6.2', 'licensehub' ) );
			}
		}

		/**
		 * Activation function
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function activate(): void {
			new Activation();
		}

		/**
		 * Deactivation function
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function deactivate(): void {
			new Deactivation();
		}
	}

	new LicenseHub();
}
