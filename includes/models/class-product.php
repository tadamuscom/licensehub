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
		 * @param int $user_id The ID of the user.
		 *
		 * @return array
		 * @since 1.0.0
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
			'meta'       => array(),
		);

		/**
		 * The ID of the object
		 *
		 * @var int
		 */
		public int $id = 0;

		/**
		 * The name of the product
		 *
		 * @var string
		 */
		public string $name = '';

		/**
		 * The status of the product
		 *
		 * @var string
		 */
		public string $status = '';

		/**
		 * The ID of the user
		 *
		 * @var int
		 */
		public int $user_id = 0;

		/**
		 * Meta fields of the model
		 *
		 * @var mixed
		 */
		public mixed $meta = array();

		/**
		 * The date the product was created at
		 *
		 * @var string
		 */
		public string $created_at = '';

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
		 * @return WP_User
		 * @throws Exception A regular exception.
		 * @since 1.0.0
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
		 * @param string $meta_name The name of the meta.
		 *
		 * @return mixed
		 * @since 1.0.0
		 */
		public function get_meta( string $meta_name ): mixed {
			global $wpdb;

			$object = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id = %s', $this->generate_table_name(), $this->id ) );
			$meta   = json_decode( $object->meta );

			if ( isset( $meta[ $meta_name ] ) ) {
				return $meta[ $meta_name ];
			}

			return false;
		}

		/**
		 * Return a list of releases or empty array if there are none
		 *
		 * @return array
		 * @since 1.0.0
		 */
		public function releases(): array {
			global $wpdb;

			if ( ! $this->exists( $this->id ) ) {
				return array();
			}

			$object = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE product_id = %s', ( new Release() )->generate_table_name(), $this->id ) );

			if ( empty( $object ) ) {
				return array();
			}

			$returnable = array();

			foreach ( $object as $release ) {
				$returnable[] = new Release( $release->id );
			}

			return $returnable;
		}

		/**
		 * Retrieve the latest release of the product
		 *
		 * @return Release|bool
		 */
		public function last_release(): Release|bool {
			global $wpdb;

			if ( ! $this->exists( $this->id ) ) {
				return false;
			}

			$object = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE product_id = %s ORDER BY `version` DESC LIMIT 1', ( new Release() )->generate_table_name(), $this->id ) );

			if ( empty( $object ) ) {
				return false;
			}

			return new Release( $object->id );
		}

		/**
		 * Extend the parent destroy to also delete all releases of the product
		 *
		 * @return void
		 */
		public function destroy(): void {
			global $wpdb;

			$wpdb->delete( ( new Release() )->generate_table_name(), array( 'product_id' => $this->id ) );

			parent::destroy();
		}
	}
}
