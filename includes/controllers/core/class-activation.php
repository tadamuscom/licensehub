<?php
/**
 * Holds the Activation class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Core;

use LicenseHub\Includes\Model\API_Key;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Activation' ) ) {
	/**
	 * Handle the plugin activation
	 */
	class Activation {
		/**
		 * Construct the class
		 */
		public function __construct() {
			$this->tables();
			$this->options();
		}

		/**
		 * Create the tables if they don't exist already
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function tables(): void {
			( new Product() )->init();
			( new API_Key() )->init();
			( new License_Key() )->init();
		}

		/**
		 * Add options if they don't exist already
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function options(): void {
			if ( ! get_option( 'lchb_erase_on_deactivation' ) ) {
				update_option( 'lchb_erase_on_deactivation', 'false' );
			}
		}
	}
}
