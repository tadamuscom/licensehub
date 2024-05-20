<?php
/**
 * Holds the Releases_Page class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Pages;

use LicenseHub\Includes\Controller\Core\Asset_Manager;
use LicenseHub\Includes\Interface\Page_Blueprint;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Controller\Pages\Releases_Page' ) ) {
	/**
	 * Handle all the api keys page
	 */
	class Releases_Page implements Page_Blueprint {
        public function __construct(){
            add_action( 'admin_menu', array( $this, 'menu' ) );
        }

        public function menu(): void
        {
            add_submenu_page(
				'licensehub',
				__('License Hub - Releases', 'licensehub'),
				__('Releases', 'licensehub'),
				'manage_options',
				'licensehub-releases',
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
				LCHB_PATH . '/public/build/' . $asset_manager->get_asset( 'licensehub-releases.php' )
			);

			wp_enqueue_style(
				'lchb-releases-style',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-releases.css' ),
				array(),
				LCHB_VERSION
			);
			wp_enqueue_script(
				'lchb-releases-script',
				LCHB_URL . 'public/build/' . $asset_manager->get_asset( 'licensehub-releases.js' ),
				$asset_meta['dependencies'],
				$asset_meta['version']
			);

            wp_add_inline_script(
                'lchb-releases-script',
                'window.lchb_releases = ' . wp_json_encode( 
                    array(
                        'logo' => LCHB_IMG . '/tadamus-logo.png',
                    ) 
                ) . ';',
                'before'
            );

			echo '<div id="releases-root"></div>';

		}
    }

}