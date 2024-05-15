<?php
/**
 * Holds the Product model
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Model;

use Exception;
use LicenseHub\Includes\Abstract\Model;
use LicenseHub\Includes\Interface\Model_Blueprint;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Model\Product' ) ) {
	/**
	 * The model for Products
	 */
	class Product extends Model implements Model_Blueprint {
		/**
		 * String for the active status
		 *
		 * @var string
		 */
		public static string $active_status = 'active';

		/**
		 * String for the inactive status
		 *
		 * @var string
		 */
		public static string $inactive_status = 'inactive';

		/**
		 * Return a list of product IDs
		 *
		 * @since 1.0.0
		 *
		 * @param int $user_id The ID of the user.
		 *
		 * @return array
		 */
		public static function product_list_by_user_id( int $user_id ): array {
			global $wpdb;

			$returnable = array();
			$table      = ( new self() )->generate_table_name();

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM %s WHERE user_id = %s;', $table, $user_id ) );

			foreach ( $results as $result ) {
				$returnable[] = $result->id;
			}

			return $returnable;
		}

		/**
		 * The name of the product
		 *
		 * @var string
		 */
		public string $table = 'products';

		/**
		 * The fields of the model
		 *
		 * @var array|array[]
		 */
		protected array $fields = array(
			'name'       => array( 'required', 'string' ),
			'status'     => array( 'required', 'string' ),
			'user_id'    => array( 'required', 'integer' ),
			'created_at' => array( 'required', 'date' ),
			'meta'       => array( 'serialized' ),
		);

		/**
		 * The ID of the object
		 *
		 * @var int
		 */
		public int $id;

		/**
		 * The name of the product
		 *
		 * @var string
		 */
		public string $name;

		/**
		 * The status of the product
		 *
		 * @var string
		 */
		public string $status;

		/**
		 * The ID of the user
		 *
		 * @var int
		 */
		public int $user_id;

		/**
		 * Meta fields of the model
		 *
		 * @var mixed
		 */
		public mixed $meta;

		/**
		 * The date the product was created at
		 *
		 * @var string
		 */
		public string $created_at;

		/**
		 * Initiate the model
		 *
		 * @return void
		 */
		public function init(): void {
			if ( ! $this->table_exists( $this->table ) ) {
				$this->create_table(
					$this->generate_table_name( $this->table ),
					"
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    name varchar(255) NOT NULL,
                    meta TEXT,
                    status varchar(255) DEFAULT 'inactive',
                    user_id mediumint(9) NOT NULL,
                    created_at datetime NOT NULL,
                    PRIMARY KEY  (id)
                "
				);
			}
		}

		/**
		 * Return the user object of the product owner
		 *
		 * @since 1.0.0
		 *
		 * @return WP_User
		 * @throws Exception A regular exception.
		 */
		public function user(): WP_User {
			if ( ! $this->exists( $this->id ) ) {
				throw new Exception( 'No license key has been summoned' );
			}

			if ( empty( $this->user_id ) ) {
				throw new Exception( 'This license key does not have any users attached' );
			}

			return get_user_by( 'id', $this->user_id );
		}

		/**
		 * Retrieve a meta value from the model
		 *
		 * @since 1.0.0
		 *
		 * @param string $meta_name The name of the meta.
		 *
		 * @return mixed
		 */
		public function get_meta( string $meta_name ): mixed {
			global $wpdb;

			$object = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id = %s', $this->generate_table_name(), $this->id ) );

			$meta = unserialize( $object->meta );

			if ( isset( $meta[ $meta_name ] ) ) {
				return $meta[ $meta_name ];
			}

			return false;
		}
	}
}
