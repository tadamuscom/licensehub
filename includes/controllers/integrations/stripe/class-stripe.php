<?php

namespace LicenseHub\Includes\Controller\Integration\Stripe;

use \DateTime;
use \DateInterval;
use \WP_User;
use \WP_REST_Request;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;
use Stripe\StripeClient;

if( ! class_exists( 'LicenseHub\Includes\Controller\Integration\Stripe\Stripe' ) ){
	class Stripe{
		/**
		 * Check if there is a product with the given Stripe Product ID
		 *
		 * @since 1.0.0
		 *
		 * @param string $product_id
		 *
		 * @return bool|Product
		 */
		public static function product_id_exists( string $product_id ) : bool|Product {
			global $wpdb;

			$product = new Product();

			$results = $wpdb->get_results( "SELECT * FROM " . $product->generate_table_name( $product->table ) . " WHERE meta LIKE '%" . $product_id . "%'" );

			if( empty( $results ) ){
				return false;
			}else{
				return new Product( $results[0]->id );
			}
		}

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

		/**
		 * Add the route to the WordPress API
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function routes() : void {
			// Public
			register_rest_route( 'lchb/integrations', '/stripe', array(
				'methods' => 'POST',
				'callback' => array( $this, 'listener' ),
			) );
		}

		/**
		 * Callback for the route that looks for the right event type and either creates or updates a license key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request
		 *
		 * @return void
		 * @throws \Stripe\Exception\ApiErrorException
		 * @throws \Exception
		 *
		 * @return void
		 */
		public function listener( WP_REST_Request $request ) : void {
			$stripe = new StripeClient( get_option( 'lchb_stripe_private_key' ) );
			$event = $stripe->events->retrieve( $request->get_param( 'id' ) );

			if( $event->type === 'charge.succeeded' ){
				$charge = $stripe->charges->retrieve( ( $event->data->values() )[0]->id );
				$invoice = $stripe->invoices->retrieve( $charge->invoice );

				$user = lchb_get_or_create_user_by_email( $invoice->customer_email );
				$product = self::product_id_exists( $invoice->lines->data[0]->plan->product );

				if( $product ){
					$interval = DateInterval::createFromDateString( $invoice->lines->data[0]->plan->interval_count . ' ' . $invoice->lines->data[0]->plan->interval );
					$expires_at = ( new DateTime() )->add( $interval )->format( LCHB_TIME_FORMAT );

					$keys = License_Key::get_all_by_user_id( $user->ID );

					if( empty( $keys ) ){
						$this->generate_key( $user, $product, $expires_at );
					}else{
						$updated = false;

						foreach( $keys as $key ){
							if( $key->product_id == $product->id ){
								$key->expires_at = $expires_at;
								$key->save();

								$updated = true;
							}
						}

						if( ! $updated ){
							$this->generate_key( $user, $product, $expires_at );
						}
					}

					wp_send_json_success();
				}
			}

		}

		/**
		 * Generate a new key
		 *
		 * @since 1.0.0
		 *
		 * @param WP_User $user
		 * @param Product $product
		 * @param string  $expires_at
		 *
		 * @return void
		 * @throws \Exception
		 */
		private function generate_key( WP_User $user, Product $product, string $expires_at ) : void {
			$key = new License_Key();
			$key->generate();
			$key->status = License_Key::$ACTIVE_STATUS;
			$key->user_id = $user->ID;
			$key->product_id = ( int ) $product->id;
			$key->created_at = ( new DateTime() )->format( LCHB_TIME_FORMAT );
			$key->expires_at = $expires_at;
			$key->save();
		}
	}

	new Stripe();
}