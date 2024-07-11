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
	 * Handle all the api keys page
	 */
	class API_Keys_Page implements Page_Blueprint {
		/**
		 * API_Keys_Page constructor.
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'menu' ) );
		}

		/**
		 * Add the menu item
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function menu(): void {
			add_submenu_page(
				'licensehub',
				__( 'License Hub - API Keys', 'licensehub' ),
				__( 'API Keys', 'licensehub' ),
				'manage_options',
				'licensehub-api',
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
				LCHB_PATH . '/public/build/licensehub-api.asset.php'
			);

			wp_enqueue_style(
				'lchb-api-keys-style',
				LCHB_URL . 'public/build/licensehub-api.css',
				array(),
				LCHB_VERSION
			);
			wp_enqueue_script(
				'lchb-api-keys-script',
				LCHB_URL . 'public/build/licensehub-api.js',
				$asset_meta['dependencies'],
				$asset_meta['version'],
				array( 'in_footer', true )
			);

			$users = get_users();

			$api_keys_instance = new API_Key();
			$keys              = $api_keys_instance->get_all();
			$fields            = array(
				array(
					'name'     => 'id',
					'editable' => false,
				),
				array(
					'name'     => 'api_key',
					'editable' => false,
					'hidden'   => true,
				),
				array(
					'name'     => 'status',
					'editable' => true,
				),
				array(
					'name'     => 'user_id',
					'editable' => true,
				),
				array(
					'name'     => 'created_at',
					'editable' => false,
				),
				array(
					'name'     => 'expires_at',
					'editable' => true,
				),
			);

			wp_add_inline_script(
				'lchb-api-keys-script',
				'window.lchb_api_keys = ' . wp_json_encode(
					array(
						'logo'   => LCHB_IMG . '/tadamus-logo.png',
						'nonce'  => wp_create_nonce( 'lchb_api_keys' ),
						'keys'   => $keys,
						'users'  => $users,
						'fields' => $fields,
					)
				),
				'before'
			);

			echo '<div id="api-keys-root"></div>';
		}
	}
}
