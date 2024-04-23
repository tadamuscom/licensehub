<?php
/**
 * Holds the Settings_Page class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Layout;

use LicenseHub\Includes\Controller\Core\Asset_Manager;
use LicenseHub\Includes\Controller\Integration\FluentCRM\FluentCRM;
use LicenseHub\Includes\Interface\Page_Blueprint;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'LicenseHub\Includes\Controller\Layout\Settings_Page' ) ) {
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
				'license-hub',
				__('License Hub - Settings', 'licensehub'),
				__('Settings', 'licensehub'),
				'manage_options',
				'license-hub-settings',
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

			wp_enqueue_style(
				'lchb-admin-page',
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-settings.css' ),
				array(),
				LCHB_VERSION
			);

			wp_enqueue_script(
				'lchb-settings-page',
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-settings.js' ),
				array(),
				LCHB_VERSION
			);

			$fluent_installed = 'false';

			if ( FluentCRM::is_installed() ) {
				$fluent_installed = 'true';
			}

			$fluent = 'false';

			if ( FluentCRM::is_active() ) {
				$fluent = 'true';
			}

			wp_localize_script(
				'lchb-settings-page',
				'lchb_settings',
				array(
					'logo'                  => LCHB_IMG . '/tadamus-logo.png',
					'nonce'                 => wp_create_nonce( 'lchb_settings' ),
					'stripe_integration'    => get_option( 'lchb_stripe_integration' ),
					'stripe_public_key'     => get_option( 'lchb_stripe_public_key' ),
					'stripe_private_key'    => get_option( 'lchb_stripe_private_key' ),
					'fluentcrm_installed'   => $fluent_installed,
					'fluentcrm_integration' => $fluent,
				)
			);

			echo '<div id="settings-root"></div>';

		}
	}

	new Settings_Page();
}
