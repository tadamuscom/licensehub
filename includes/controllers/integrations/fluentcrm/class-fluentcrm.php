<?php
/**
 * Holds the FluentCRM class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\Integration\FluentCRM;

use LicenseHub\Includes\Model\Product;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'LicenseHub\Includes\Controller\Integration\FluentCRM\FluentCRM' ) ) {
	/**
	 * Handle the FluentCRM integration
	 */
	class FluentCRM {
		/**
		 * Check if the FluentCRM is installed
		 *
		 * @since 1.0.0
		 *
		 * @return bool
		 */
		public static function is_installed(): bool {
			if ( defined( 'FLUENTCRM' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if the integration is enabled in settings
		 *
		 * @since 1.0.0
		 *
		 * @return bool
		 */
		public static function is_active(): bool {
			return defined( 'FLUENTCRM' ) && 'true' === get_option( 'lchb_fluentcrm_integration' );
		}

		/**
		 * Construct the class
		 */
		public function __construct() {
			add_action( 'lchb-license-key-generated', array( $this, 'add_contact' ), 10, 3 );
		}

		/**
		 * Add a contact to Fluent if there isn't one already for the user and apply the saved tags and lists
		 *
		 * @since 1.0.0
		 *
		 * @param WP_User $user The user object.
		 * @param Product $product The product object.
		 *
		 * @return void
		 */
		public function add_contact( WP_User $user, Product $product ): void {
			if ( self::is_active() ) {
				$contact_api = FluentCrmApi( 'contacts' );

				if ( ! $contact_api->getContact( $user->user_email ) ) {
					$data = array(
						'first_name' => $user->first_name,
						'last_name'  => $user->last_name,
						'email'      => $user->user_email,
						'status'     => 'pending',
					);

					if ( $product->get_meta( 'fluentcrm_lists' ) ) {
						$data['lists'] = $product->get_meta( 'fluentcrm_lists' );
					}

					if ( $product->get_meta( 'fluentcrm_tags' ) ) {
						$data['tags'] = $product->get_meta( 'fluentcrm_tags' );
					}

					$contact_api->createOrUpdate( $data );
				}
			}
		}
	}

	new FluentCRM();
}
