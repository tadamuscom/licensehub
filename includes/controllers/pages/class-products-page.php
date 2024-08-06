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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Controller\Pages\Products_Page' ) ) {
	/**
	 * Handle all the settings page
	 */
	class Products_Page implements Page_Blueprint {

		/**
		 * Products_Page constructor.
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'menu' ) );
		}

		/**
		 * Add menu item
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function menu(): void {
			$menu_slug  = 'licensehub';
			$menu_title = __( 'License Hub', 'licensehub' );

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
				__( 'Products - LicenseHub', 'licensehub' ),
				__( 'Products', 'licensehub' ),
				'manage_options',
				$menu_slug . '-products',
				array( $this, 'callback' )
			);

			add_action(
				'admin_init',
				function () {
					remove_submenu_page( 'licensehub', 'licensehub' );
				}
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
				LCHB_PATH . '/public/build/licensehub-products.asset.php'
			);

			wp_enqueue_style(
				'lchb-products-style',
				LCHB_URL . 'public/build/licensehub-products.css',
				array(),
				LCHB_VERSION
			);
			wp_enqueue_script(
				'lchb-products-script',
				LCHB_URL . 'public/build/licensehub-products.js',
				$asset_meta['dependencies'],
				$asset_meta['version'],
				array( 'in_footer', true )
			);

			$products = ( new Product() )->get_all( false );
			$fields   = array(
				array(
					'name' => __( 'id', 'licensehub' ),
				),
				array(
					'name'   => __( 'name', 'licensehub' ),
					'button' => 'edit',
				),
				array(
					'name' => __( 'status', 'licensehub' ),
				),
				array(
					'name' => __( 'user_id', 'licensehub' ),
				),
				array(
					'name' => __( 'created_at', 'licensehub' ),
				),
			);

			$products_meta = array();
			foreach ( $products as $product ) {
				$products_meta[] = array(
					'id'   => $product->id,
					'meta' => $product->meta ? json_decode( $product->meta ) : '',
				);

				// We unset it here because we don't want to expose the meta to the product table.
				unset( $product->meta );
			}

			/**
			 * Filters the default values.
			 *
			 * @param array $default_values The current default values.
			 *
			 * @return array Update the default values.
			 * @since 1.0.0
			 */
			$default_values = apply_filters(
				'lchb_product_front_default_values',
				array(
					'logo'           => LCHB_IMG . '/tadamus-logo.png',
					'nonce'          => wp_create_nonce( 'lchb_products' ),
					'products'       => $products,
					'products_meta'  => $products_meta,
					'fields'         => $fields,
					'releases_nonce' => wp_create_nonce( 'lchb_releases' ),
					'releases_url'   => admin_url( 'admin.php?page=licensehub-releases' ),
					'ajax_url'       => admin_url( 'admin-ajax.php' ),
				)
			);

			wp_add_inline_script(
				'lchb-products-script',
				'window.lchb_products = ' . wp_json_encode( $default_values ),
				'before'
			);

			echo '<div id="products-root"></div>';
		}
	}
}
