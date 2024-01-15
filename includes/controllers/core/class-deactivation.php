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

if ( ! class_exists( 'Deactivation' ) ) {
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
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function erase_all(): void {
			$this->erase_tables();
			$this->erase_options();
		}

		/**
		 * Delete all tables
		 *
		 * @since 1.0.0
		 *
		 * @return void
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
		 * @since 1.0.0
		 *
		 * @return void
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
		 * @since 1.0.0
		 *
		 * @param string $table_name The name of the table.
		 *
		 * @return void
		 */
		private function delete_table( string $table_name ): void {
			global $wpdb;

			$wpdb->query( $wpdb->prepare( 'DROP TABLE IF EXISTS %s', $table_name ) );
		}
	}
}
