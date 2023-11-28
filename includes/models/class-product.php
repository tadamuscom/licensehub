<?php

namespace LicenseHub\Includes\Model;

use WP_User;
use Exception;
use LicenseHub\Includes\Abstract\Model;
use LicenseHub\Includes\Interface\Model_Blueprint;

if( ! class_exists( 'Product' ) ) {
	class Product extends Model implements Model_Blueprint{
		public static string $ACTIVE_STATUS = 'active';
		public static string $INACTIVE_STATUS = 'inactive';

		/**
		 * Return a list of product IDs
		 *
		 * @since 1.0.0
		 *
		 * @param int $user_id
		 *
		 * @return array
		 */
		public static function product_list_by_user_id( int $user_id ) : array {
			global $wpdb;

			$returnable = array();
			$table = ( new self() )->generate_table_name();

			$results = $wpdb->get_results( 'SELECT id FROM ' . $table . ' WHERE user_id = ' . $user_id . ';' );

			foreach( $results as $result ){
				$returnable[] = $result->id;
			}

			return $returnable;
		}

		protected string $table = 'products';
		protected array $fields = array(
			'name'          => array( 'required', 'string' ),
			'status'        => array( 'required', 'string' ),
			'user_id'       => array( 'required', 'integer' ),
			'created_at'    => array( 'required', 'date' ),
		);

		public function init() : void {
			if( ! $this->table_exists( $this->table ) ){
				$this->create_table( $this->generate_table_name( $this->table ), "
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    status varchar(255) DEFAULT 'inactive',
                    user_id mediumint(9) NOT NULL,
                    created_at datetime NOT NULL,
                    expires_at datetime NOT NULL,
                    PRIMARY KEY  (id)
                " );
			}
		}

		/**
		 * Return the user object of the product owner
		 *
		 * @since 1.0.0
		 *
		 * @return WP_User
		 * @throws Exception
		 */
		public function user() : WP_User {
			if( ! $this->exists( $this->id ) ){
				throw new Exception( 'No license key has been summoned' );
			}

			if( empty( $this->user_id ) ){
				throw new Exception( 'This license key does not have any users attached' );
			}

			return get_user_by( 'id', $this->user_id );
		}
	}
}