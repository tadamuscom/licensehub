<?php
/**
 * Holds the API_Keys_Page class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Pages;

use LicenseHub\Includes\Controller\Core\Asset_Manager;
use LicenseHub\Includes\Interface\Page_Blueprint;
use LicenseHub\Includes\Model\API_Key;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Controller\Pages\API_Keys_Page' ) ) {
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
				'licensehub',
				__('License Hub - API Keys', 'licensehub'),
				__('API Keys', 'licensehub'),
				'manage_options',
				'licensehub-api',
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
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-api.php' )
			);

			wp_enqueue_style(
				'lchb-api-keys-style',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-api.css' ),
				array(),
				LCHB_VERSION
			);
			wp_enqueue_script(
				'lchb-api-keys-script',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-api.js' ),
				$asset_meta['dependencies'],
				$asset_meta['version']
			);

			$users = get_users();

			$api_keys_instance = new API_Key();
			$keys              = $api_keys_instance->get_all();
			$fields            = array(
				array(
					'name' => 'id',
					'editable' => false
				),
				array(
					'name' => 'api_key',
					'editable' => false,
					'hidden' => true
				),
				array(
					'name' => 'status',
					'editable' => true
				),
				array(
					'name' => 'user_id',
					'editable' => true
				),
				array(
					'name' => 'created_at',
					'editable' => false
				),
				array(
					'name' => 'expires_at',
					'editable' => true
				),
			);

			wp_localize_script(
				'lchb-api-keys-script',
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
}
