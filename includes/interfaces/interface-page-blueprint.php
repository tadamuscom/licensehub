<?php
/**
 * Holds the Page_Blueprint interface
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! interface_exists( 'Page_Blueprint' ) ) {
	interface Page_Blueprint {
		/**
		 * This method should init the model and create its table and add its fields
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function menu(): void;

		/**
		 * Callback for the page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function callback(): void;
	}
}
