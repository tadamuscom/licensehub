<?php

namespace LicenseHub\Includes\Controller\Core;

if( ! class_exists( 'Deactivation' ) ){
	class Deactivation{
		public function __construct(){
			if( $option = get_option( 'lchb_erase_on_deactivation' ) ){
				if( $option === 'true' ){
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
		private function erase_all() : void {
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
		private function erase_tables() : void {
			$models = array(
				'Product',
				'License_Key',
				'API_Key'
			);

			foreach( $models as $model ){
				$this->delete_table( ( new $model )->generate_table_name() );
			}
		}

		/**
		 * Delete all the options
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function erase_options() : void {
			$options = array(
				'lchb_erase_on_deactivation'
			);

			foreach ( $options as $option ){
				delete_option( $option );
			}
		}

		/**
		 * Delete the table
		 *
		 * @since 1.0.0
		 *
		 * @param $table_name
		 *
		 * @return void
		 */
		private function delete_table( $table_name ) : void {
			global $wpdb;

			$wpdb->query( "DROP TABLE IF EXISTS $table_name ;" );
		}
	}
}