<?php

namespace LicenseHub\Includes\Controller\Core;

if( ! class_exists( 'Enqueue' ) ){
	class Enqueue {
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin' ) );
		}

		/**
		 * Enqueue the admin CSS file
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function admin() : void {
			wp_enqueue_style( 'lchb-admin-stylesheet', LCHB_CSS . 'admin/main.css', null, false );
		}
	}

	new Enqueue();
}