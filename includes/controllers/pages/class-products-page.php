<?php

/**
 * Holds the Products_Page class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Layout;

use LicenseHub\Includes\Controller\Core\Asset_Manager;
use LicenseHub\Includes\Controller\Integration\FluentCRM\FluentCRM;
use LicenseHub\Includes\Interface\Page_Blueprint;
use LicenseHub\Includes\Model\Product;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('LicenseHub\Includes\Controller\Layout\Products_Page')) {
	/**
	 * Handle all the settings page
	 */
	class Products_Page implements Page_Blueprint
	{
		/**
		 * Products_Page constructor.
		 */
		public function __construct()
		{
			add_action('admin_menu', array($this, 'menu'));
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
			add_menu_page(
				__('License Hub', 'licensehub'),
				__( 'License Hub', 'licensehub' ),
				'manage_options',
				'licensehub',
				array( $this, 'callback' ),
				'dashicons-media-spreadsheet'
			);
		}

		/**
		 * Callback for the page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function callback(): void
		{
			$asset_manager = new Asset_Manager();

			wp_enqueue_style(
				'lchb-products-style',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-products.css' ),
				array(),
				LCHB_VERSION
			);

			$asset_meta = Asset_Manager::get_asset_meta(
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-products.php' )
			);

			wp_enqueue_script(
				'lchb-products-script',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-products.js' ),
				$asset_meta['dependencies'],
				$asset_meta['version']
			);

			$product_instance = new Product();
			$products         = $product_instance->get_all();
			$fields           = array(
				array(
					'name' => __('id', 'licensehub'),
					'editable' => false,
				),
				array(
					'name' => __('name', 'licensehub'),
					'editable' => true,
				),
				array(
					'name' => __('status', 'licensehub'),
					'editable' => true,
				),
				array(
					'name' => __('user_id', 'licensehub'),
					'editable' => true
				),
				array(
					'name' => __('created_at', 'licensehub'),
					'editable' => false
				)
			);
			$fluent 					= FluentCRM::is_active() ? 'true' : 'false';

			wp_localize_script(
				'lchb-products-script',
				'lchb_products',
				array(
					'logo'                  => LCHB_IMG . '/tadamus-logo.png',
					'nonce'                 => wp_create_nonce( 'lchb_products' ),
					'products'              => $products,
					'fields'                => $fields,
					'stripe'                => get_option( 'lchb_stripe_integration' ),
					'fluentcrm_integration' => $fluent,
				)
			);

			echo '<div id="products-root"></div>';
		}
	}
}
