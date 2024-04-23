<?php
/**
 * Holds the API_Keys_Page class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Layout;

use LicenseHub\Includes\Controller\Core\Asset_Manager;
use LicenseHub\Includes\Interface\Page_Blueprint;
use LicenseHub\Includes\Model\API_Key;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'LicenseHub\Includes\Controller\Layout\APIKeys_Page' ) ) {
	/**
	 * Handle all the settings page
	 */
	class API_Keys_Page implements Page_Blueprint {
		/**
		 * API_Keys_Page constructor.
		 */
		public function __construct(){
			add_action( 'admin_menu', array( $this, 'menu' ) );
		}

		/**
		 * Add the menu item
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function menu(): void
		{
			add_submenu_page(
				'license-hub',
				__('License Hub - API Keys', 'licensehub'),
				__('API Keys', 'licensehub'),
				'manage_options',
				'license-hub-api-keys',
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
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-api.css' ),
				array(),
				LCHB_VERSION
			);
			wp_enqueue_script(
				'lchb-api-keys-page',
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-api.js' ),
				array(),
				LCHB_VERSION
			);

			$users = get_users();

			$api_keys_instance = new API_Key();
			$keys              = $api_keys_instance->get_all();
			$fields            = $api_keys_instance->get_fields();

			wp_localize_script(
				'lchb-api-keys-page',
				'lchb_api_keys',
				array(
					'logo'   => LCHB_IMG . '/tadamus-logo.png',
					'nonce'  => wp_create_nonce( 'lchb_api_keys' ),
					'keys'   => $keys,
					'users'  => $users,
					'fields' => $fields,
				)
			);

			echo '<div id="api-keys-root"></div>';
		}
	}

	new API_Keys_Page();
}
