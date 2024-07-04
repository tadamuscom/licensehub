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

if ( ! class_exists( '\LicenseHub\Includes\Controller\Core\Settings' ) ) {
	/**
	 * Handles all the emails being sent by the plugin
	 */
	class Settings {
		/**
		 * The raw settings
		 *
		 * @var array
		 */
		public array $raw_settings = array(
			'rest' => true,
		);

		/**
		 * The option name
		 *
		 * @var string
		 */
		private string $option_name = 'lchb_settings';

		/**
		 * Settings constructor.
		 */
		public function __construct() {
			$option_settings = get_option( $this->option_name, array() );

			if ( ! empty( $option_settings ) ) {
				foreach ( $option_settings as $key => $setting ) {
					$this->raw_settings[ $key ] = $setting;
				}
			}
		}

		/**
		 * Check if a setting is enabled
		 *
		 * @param string $setting_name The setting name.
		 *
		 * @return bool
		 */
		public function is_enabled( string $setting_name ): bool {
			return ( ! empty( $this->raw_settings[ $setting_name ] ) && true === $this->raw_settings[ $setting_name ] );
		}

		/**
		 * Get a setting
		 *
		 * @param string $key The setting key.
		 *
		 * @return mixed
		 */
		public function get( string $key ): mixed {
			return $this->raw_settings[ $key ];
		}

		/**
		 * Set a setting
		 *
		 * @param string $key The setting key.
		 * @param mixed  $value The setting value.
		 *
		 * @return void
		 */
		public function set( string $key, mixed $value ): void {
			$this->raw_settings[ $key ] = $value;
		}

		/**
		 * Save the settings
		 *
		 * @return void
		 */
		public function save(): void {
			update_option( $this->option_name, $this->raw_settings );
		}
	}
}
