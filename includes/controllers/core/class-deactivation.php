<?php
/**
 * Holds the deactivation class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Controller\Core\Deactivation' ) ) {
	/**
	 * Handle the deactivation of the plugin
	 */
	class Deactivation {
		/**
		 * Construct the class
		 */
		public function __construct() {
			$option = get_option( 'lchb_erase_on_deactivation' );

			if ( $option ) {
				if ( 'true' === $option ) {
					$this->erase_all();
				}
			}
		}

		/**
		 * Delete all tables and options
		 *
		 * @return void
		 * @since 1.0.0
		 */
		private function erase_all(): void {
			$this->erase_tables();
			$this->erase_options();
		}

		/**
		 * Delete all tables
		 *
		 * @return void
		 * @since 1.0.0
		 */
		private function erase_tables(): void {
			$models = array(
				'Product',
				'License_Key',
				'API_Key',
			);

			foreach ( $models as $model ) {
				$this->delete_table( ( new $model() )->generate_table_name() );
			}
		}

		/**
		 * Delete all the options
		 *
		 * @return void
		 * @since 1.0.0
		 */
		private function erase_options(): void {
			$options = array(
				'lchb_erase_on_deactivation',
			);

			foreach ( $options as $option ) {
				delete_option( $option );
			}
		}

		/**
		 * Delete the table
		 *
		 * @param string $table_name The name of the table.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		private function delete_table( string $table_name ): void {
			global $wpdb;

			//phpcs:ignore
			$wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS %s', $table_name ) );
		}
	}
}
