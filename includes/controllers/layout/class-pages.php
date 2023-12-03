<?php

namespace LicenseHub\Includes\Controller\Layout;

use LicenseHub\Includes\Lib\View;
use LicenseHub\Includes\Model\Product;

if( ! class_exists( 'Pages' ) ){
	class Pages{
		public function __construct()
		{
			add_action( 'admin_menu', array( $this, 'menu' ) );
		}

		public function menu() : void {
			add_menu_page( 'License Hub', 'License Hub', 'manage_options', 'license-hub', array( $this, 'dashboard_callback' ), 'dashicons-media-spreadsheet' );
			add_submenu_page( 'license-hub', 'License Hub - Products', 'Products', 'manage_options', 'license-hub-products', array( $this, 'products_callback' ) );
			add_submenu_page( 'license-hub', 'License Hub - License Keys', 'License Keys', 'manage_options', 'license-hub-license-keys', array( $this, 'license_keys_callback' ) );
			add_submenu_page( 'license-hub', 'License Hub - API Keys', 'API Keys', 'manage_options', 'license-hub-api-keys', array( $this, 'api_keys_callback' ) );
			add_submenu_page( 'license-hub', 'License Hub - Settings', 'Settings', 'manage_options', 'license-hub-settings', array( $this, 'settings_callback' ) );
		}

		/**
		 * Render the dashboard page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function dashboard_callback() : void {
			// Not yet
		}

		/**
		 * Render the settings page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function settings_callback() : void {
			// Not yet
		}

		/**
		 * Render the products page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function products_callback() : void {
			wp_enqueue_style( 'lchb-admin-page', LCHB_CSS . '/admin/main.css', array(), LCHB_VERSION );
			wp_enqueue_script( 'lchb-products-page', LCHB_PAGE . '/products/build/index.js', array(
				'wp-i18n',
				'wp-element',
				'wp-api-fetch'
			), LCHB_VERSION );

			$product_instance = new Product();
			$products = $product_instance->get_all();
			$fields = $product_instance->get_fields();

			wp_localize_script( 'lchb-products-page', 'lchb_products', [
				'logo'                  => LCHB_IMG . '/tadamus-logo.png',
				'nonce'                 => wp_create_nonce( 'lchb_products' ),
				'products'              => $products,
				'fields'                => $fields
			] );

			echo '<div id="products-root"></div>';
		}

		/**
		 * Render the license keys page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function license_keys_callback() : void {
			// Not yet
		}

		/**
		 * Render the API keys page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function api_keys_callback() : void {
			// Not yet
		}
	}

	new Pages();
}