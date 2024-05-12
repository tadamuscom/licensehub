<?php
/**
 * Holds the Settings class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'LicenseHub\Includes\Controller\Core\Settings' ) ) {
	/**
	 * Handles all the emails being sent by the plugin
	 */
	class Settings
	{
		private bool $rest = true;
		private string $option_name = 'lchb_settings';

		/**
		 * Settings constructor.
		 */
		public function __construct(){
			$option_settings = get_option( $this->option_name, array() );

			foreach( $option_settings as $key => $setting ){
				$this->{$key} = $setting;
			}
		}

		public function get( string $key ){
			return $this->{$key};
		}

		public function set( string $key, $value ): void {
			$this->{$key} = $value;
			$this->save();
		}

		public function save(){
			$properties = get_object_vars( $this );
			$settings = array();

			foreach ( $properties as $property ){
				if( $property === 'option_name' ){
					unset( $property );
				}

				$settings[$property] = $this->{$property};
			}

			update_option( $this->option_name, $settings );
		}
	}
}
