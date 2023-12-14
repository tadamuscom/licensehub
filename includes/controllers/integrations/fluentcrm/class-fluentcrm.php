<?php

namespace LicenseHub\Includes\Controller\Integration\FluentCRM;

use WP_User;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;

if( ! class_exists( 'LicenseHub\Includes\Controller\Integration\FluentCRM\FluentCRM' ) ){
	class FluentCRM{
		public static function is_active() : bool {
			if( defined( 'FLUENTCRM' ) && get_option( 'lchb-fluentcrm-integration' ) === 'true' ){
				return true;
			}

			return false;
		}
		public function __construct() {
			add_action( 'lchb-license-key-generated', array( $this, 'add_contact' ), 10, 3 );
		}

		public function add_contact( WP_User $user, Product $product  ) : void {
			if( self::is_active() ){
				$contactApi = FluentCrmApi('contacts');

				if( ! $contactApi->getContact( $user->user_email ) ){
					$data = array(
						'first_name' => $user->first_name,
						'last_name' => $user->last_name,
						'email' => $user->user_email,
						'status' => 'pending'
					);

					if( $product->get_meta( 'fluentcrm_lists' ) ){
						$data['lists'] = $product->get_meta( 'fluentcrm_lists' );
					}

					if( $product->get_meta( 'fluentcrm_tags' ) ){
						$data['tags'] = $product->get_meta( 'fluentcrm_tags' );
					}

					$contactApi->createOrUpdate( $data );
				}
			}
		}
	}

	new FluentCRM();
}