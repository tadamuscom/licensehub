<?php
/**
 * Holds the Model_Blueprint interface
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! interface_exists( '\LicenseHub\Includes\Interface\Model_Blueprint' ) ) {
	interface Model_Blueprint {
		/**
		 * This method should init the model and create its table and add its fields
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function init(): void;
	}
}
