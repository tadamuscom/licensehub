<?php

/**
 * Holds the Products_Page class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Pages;

use LicenseHub\Includes\Controller\Core\Asset_Manager;
use LicenseHub\Includes\Interface\Page_Blueprint;
use LicenseHub\Includes\Model\Product;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('\LicenseHub\Includes\Controller\Pages\Products_Page')) {
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
            $menu_slug = 'licensehub';
            $menu_title = __('License Hub', 'licensehub');
        
            add_menu_page(
                $menu_title,
                $menu_title,
                'manage_options',
                $menu_slug,
                array( $this, 'callback' ),
                'dashicons-media-spreadsheet'
            );
        
            add_submenu_page(
                $menu_slug, 
                __('Products - LicenseHub', 'licensehub'), 
                __('Products', 'licensehub'), 
                'manage_options', 
                $menu_slug . '-products', 
                array($this, 'callback')
            );

            add_action('admin_init', function () {
                remove_submenu_page('licensehub', 'licensehub');
            });
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
			$asset_meta = Asset_Manager::get_asset_meta(
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-products.php' )
			);

			wp_enqueue_style(
				'lchb-products-style',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-products.css' ),
				array(),
				LCHB_VERSION
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
				),
				array(
					'name' => __('name', 'licensehub'),
                    'button' => 'edit'
				),
				array(
					'name' => __('status', 'licensehub'),
				),
				array(
					'name' => __('user_id', 'licensehub'),
				),
				array(
					'name' => __('created_at', 'licensehub'),
                ),
                array(
                    'name' => __('add_a_release', 'licensehub'),
                    'button' => true,
                    'hidden' => true
                ),
			);

            wp_add_inline_script(
                'lchb-products-script',
                'window.lchb_products = ' . wp_json_encode(
                    array(
                    		'logo'                  => LCHB_IMG . '/tadamus-logo.png',
                    		'nonce'                 => wp_create_nonce( 'lchb_products' ),
                    		'products'              => $products,
                    		'fields'                => $fields,
                    )
                ), 
                'before'
            );

			echo '<div id="products-root"></div>';
		}
	}
}
