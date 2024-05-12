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
		public array $raw_settings = array(
			'rest' => true
		);
		private string $option_name = 'lchb_settings';

		/**
		 * Settings constructor.
		 */
		public function __construct(){
			$option_settings = get_option( $this->option_name, array() );

			if ( ! empty( $option_settings ) ){
				foreach ($option_settings as $key => $setting){
					$this->raw_settings[$key] = $setting;
				}
			}
		}

		public function is_enabled( string $setting_name ): bool {
			return ( ! empty ( $this->raw_settings[$setting_name] ) && $this->raw_settings[$setting_name] === true );
		}

		public function get( string $key ){
			return $this->raw_settings[$key];
		}

		public function set( string $key, $value ): void {
			$this->raw_settings[$key] = $value;
		}

		public function save(): void {
			update_option( $this->option_name, $this->raw_settings );
		}
	}
}
