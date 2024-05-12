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

if ( ! class_exists( 'LicenseHub\Includes\Controller\Pages\Settings_Page' ) ) {
	/**
	 * Handle all the settings page
	 */
	class Settings_Page implements Page_Blueprint {

		/**
		 * Settings_Page constructor.
		 */
		public function __construct(){
			add_action( 'admin_menu', array( $this, 'menu' ) );
		}

		/**
		 * Add menu item
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function menu(): void
		{
			add_submenu_page(
				'licensehub',
				__('License Hub - Settings', 'licensehub'),
				__('Settings', 'licensehub'),
				'manage_options',
				'licensehub-settings',
				array( $this, 'callback' )
			);
		}

		/**
		 * Callback for the page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function callback(): void {
			$asset_manager = new Asset_Manager();
			$asset_meta = Asset_Manager::get_asset_meta(
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-settings.php' )
			);

			wp_enqueue_style(
				'lchb-settings-style',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-settings.css' ),
				array(),
				LCHB_VERSION
			);
			wp_enqueue_script(
				'lchb-settings-script',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-settings.js' ),
				$asset_meta['dependencies'],
				$asset_meta['version']
			);

			wp_localize_script(
				'lchb-settings-script',
				'lchb_settings',
				array(
					'logo'                  => LCHB_IMG . '/tadamus-logo.png',
					'nonce'                 => wp_create_nonce( 'lchb_settings' ),
					'enable_rest_api'       => ( new Settings() )->is_enabled( 'rest' ),
				)
			);

			echo '<div id="settings-root"></div>';

		}
	}
}
