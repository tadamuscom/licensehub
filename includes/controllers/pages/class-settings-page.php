<?php
/**
 * Holds the Settings_Page class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Pages;

use LicenseHub\Includes\Controller\Core\Asset_Manager;
use LicenseHub\Includes\Controller\Core\Settings;
use LicenseHub\Includes\Interface\Page_Blueprint;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Controller\Pages\Settings_Page' ) ) {
	/**
	 * Handle all the settings page
	 */
	class Settings_Page implements Page_Blueprint {

		/**
		 * Settings_Page constructor.
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'menu' ) );
		}

		/**
		 * Add menu item
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function menu(): void {
			add_submenu_page(
				'licensehub',
				__( 'License Hub - Settings', 'licensehub' ),
				__( 'Settings', 'licensehub' ),
				'manage_options',
				'licensehub-settings',
				array( $this, 'callback' )
			);
		}

		/**
		 * Callback for the page
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function callback(): void {
			$asset_meta = Asset_Manager::get_asset_meta(
				LCHB_PATH . '/public/build/licensehub-settings.asset.php'
			);

			wp_enqueue_style(
				'lchb-settings-style',
				LCHB_URL . 'public/build/licensehub-settings.css',
				array(),
				LCHB_VERSION
			);
			wp_enqueue_script(
				'lchb-settings-script',
				LCHB_URL . 'public/build/licensehub-settings.js',
				$asset_meta['dependencies'],
				$asset_meta['version'],
				array( 'in_footer', true )
			);

			/**
			 * Filters the default values.
			 *
			 * @param array $default_values The current default values.
			 *
			 * @return array Update the default values.
			 * @since 1.0.0
			 */
			$default_values = apply_filters(
				'lchb_settings_front_default_values',
				array(
					'logo'            => LCHB_IMG . '/tadamus-logo.png',
					'nonce'           => wp_create_nonce( 'lchb_settings' ),
					'enable_rest_api' => ( new Settings() )->is_enabled( 'rest' ),
				)
			);

			wp_add_inline_script(
				'lchb-settings-script',
				'window.lchb_settings = ' . wp_json_encode( $default_values ) . ';',
				'before'
			);

			echo '<div id="settings-root"></div>';
		}
	}
}
