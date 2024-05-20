<?php
/**
 * Holds the License_Keys_Page class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Pages;

use LicenseHub\Includes\Controller\Core\Asset_Manager;
use LicenseHub\Includes\Interface\Page_Blueprint;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Controller\Pages\License_Keys_Page' ) ) {
	/**
	 * Handle all the settings page
	 */
	class License_Keys_Page implements Page_Blueprint {
		/**
		 * License_Keys_Page constructor.
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
				__('License Hub - License Keys', 'licensehub'),
				__('License Keys', 'licensehub'),
				'manage_options',
				'licensehub-licenses',
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
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-licenses.php' )
			);

			wp_enqueue_style(
				'lchb-license-keys-style',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-licenses.css' ),
				array(),
				LCHB_VERSION
			);
			wp_enqueue_script(
				'lchb-license-keys-script',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-licenses.js' ),
				$asset_meta['dependencies'],
				$asset_meta['version']
			);

			$product_instance 		= new Product();
			$products         		= $product_instance->get_all();
			$users 								= get_users();
			$license_key_instance = new License_Key();
			$license_keys         = $license_key_instance->get_all();
			$fields               = array(
				array(
					'name' => 'id',
					'editable' => false,
				),
				array(
					'name' => 'license_key',
					'editable' => false,
					'hidden' => true,
				),
				array(
					'name' => 'status',
					'editable' => true,
				),
				array(
					'name' => 'user_id',
					'editable' => true,
				),
				array(
					'name' => 'product_id',
					'editable' => false,
				),
				array(
					'name' => 'created_at',
					'editable' => false,
				),
				array(
					'name' => 'expires_at',
					'editable' => true,
				),
			);

            wp_add_inline_script(
                'lchb-license-keys-script',
                'window.lchb_license_keys = ' . wp_json_encode(
                    array(
                        'logo'     => LCHB_IMG . '/tadamus-logo.png',
                        'nonce'    => wp_create_nonce( 'lchb_license_keys' ),
                        'keys'     => $license_keys,
                        'products' => $products,
                        'users'    => $users,
                        'fields'   => $fields,
				    )
                ), 
                'before'
            );

			echo '<div id="license-keys-root"></div>';
		}
	}
}
