<?php

namespace LicenseHub\Includes\Controller\Layout;

use LicenseHub\Includes\Controller\Integration\FluentCRM\FluentCRM;
use LicenseHub\Includes\Model\API_Key;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;

if( ! class_exists( 'Pages' ) ){
	class Pages{
		public function __construct()
		{
			add_action( 'admin_menu', array( $this, 'menu' ) );
		}

		public function menu() : void {
			add_menu_page( 'License Hub', 'License Hub', 'manage_options', 'license-hub', array( $this, 'products_callback' ), 'dashicons-media-spreadsheet' );
			add_submenu_page( 'license-hub', 'License Hub - License Keys', 'License Keys', 'manage_options', 'license-hub-license-keys', array( $this, 'license_keys_callback' ) );
			add_submenu_page( 'license-hub', 'License Hub - API Keys', 'API Keys', 'manage_options', 'license-hub-api-keys', array( $this, 'api_keys_callback' ) );
			add_submenu_page( 'license-hub', 'License Hub - Settings', 'Settings', 'manage_options', 'license-hub-settings', array( $this, 'settings_callback' ) );
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

			$fluent = 'false';

			if( FluentCRM::is_active() ){
				$fluent = 'true';
			}

			wp_localize_script( 'lchb-products-page', 'lchb_products', [
				'logo'                  => LCHB_IMG . '/tadamus-logo.png',
				'nonce'                 => wp_create_nonce( 'lchb_products' ),
				'products'              => $products,
				'fields'                => $fields,
				'stripe'                => get_option( 'lchb_stripe_integration' ),
				'fluentcrm_integration' => $fluent
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
			wp_enqueue_style( 'lchb-admin-page', LCHB_CSS . '/admin/main.css', array(), LCHB_VERSION );
			wp_enqueue_script( 'lchb-license-keys-page', LCHB_PAGE . '/license_keys/build/index.js', array(
				'wp-i18n',
				'wp-element',
				'wp-api-fetch'
			), LCHB_VERSION );

			$product_instance = new Product();
			$products = $product_instance->get_all();

			$users = get_users();

			$license_key_instance = new License_Key();
			$license_keys = $license_key_instance->get_all();
			$fields = $license_key_instance->get_fields();

			wp_localize_script( 'lchb-license-keys-page', 'lchb_license_keys', [
				'logo'                  => LCHB_IMG . '/tadamus-logo.png',
				'nonce'                 => wp_create_nonce( 'lchb_license_keys' ),
				'keys'                  => $license_keys,
				'products'              => $products,
				'users'                 => $users,
				'fields'                => $fields
			] );

			echo '<div id="license-keys-root"></div>';
		}

		/**
		 * Render the API keys page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function api_keys_callback() : void {
			wp_enqueue_style( 'lchb-admin-page', LCHB_CSS . '/admin/main.css', array(), LCHB_VERSION );
			wp_enqueue_script( 'lchb-api-keys-page', LCHB_PAGE . '/api_keys/build/index.js', array(
				'wp-i18n',
				'wp-element',
				'wp-api-fetch'
			), LCHB_VERSION );

			$users = get_users();

			$api_keys_instance = new API_Key();
			$keys = $api_keys_instance->get_all();
			$fields = $api_keys_instance->get_fields();

			wp_localize_script( 'lchb-api-keys-page', 'lchb_api_keys', [
				'logo'                  => LCHB_IMG . '/tadamus-logo.png',
				'nonce'                 => wp_create_nonce( 'lchb_api_keys' ),
				'keys'                  => $keys,
				'users'                 => $users,
				'fields'                => $fields
			] );

			echo '<div id="api-keys-root"></div>';
		}

		/**
		 * Render the settings page
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function settings_callback() : void {
			wp_enqueue_style( 'lchb-admin-page', LCHB_CSS . '/admin/main.css', array(), LCHB_VERSION );
			wp_enqueue_script( 'lchb-settings-page', LCHB_PAGE . '/settings/build/index.js', array(
				'wp-i18n',
				'wp-element',
				'wp-api-fetch'
			), LCHB_VERSION );

			$fluent = 'false';

			if( FluentCRM::is_active() ){
				$fluent = 'true';
			}

			wp_localize_script( 'lchb-settings-page', 'lchb_settings', [
				'logo'                  => LCHB_IMG . '/tadamus-logo.png',
				'nonce'                 => wp_create_nonce( 'lchb_settings' ),
				'stripe_integration'    => get_option( 'lchb_stripe_integration' ),
				'stripe_public_key'     => get_option( 'lchb_stripe_public_key' ),
				'stripe_private_key'    => get_option( 'lchb_stripe_private_key' ),
				'fluentcrm_integration' => $fluent
			] );

			echo '<div id="settings-root"></div>';
		}
	}

	new Pages();
}