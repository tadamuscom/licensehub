<?php
/**
 * Holds the License Key model
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Model;

use Exception;
use LicenseHub\Includes\Abstract\Model;
use LicenseHub\Includes\Interface\Model_Blueprint;
use LicenseHub\Includes\Lib\Validator;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Model\License_Key' ) ) {
	/**
	 * The model for license keys
	 */
	class License_Key extends Model implements Model_Blueprint {
		/**
		 * The string for the active status
		 *
		 * @var string
		 */
		public static string $active_status = 'active';

		/**
		 * The string for the inactive status
		 *
		 * @var string
		 */
		public static string $inactive_status = 'inactive';

		/**
		 * Return a list of license keys based on the product ID
		 *
		 * @since 1.0.0
		 *
		 * @param int         $product_id   The ID of the product.
		 * @param bool|string $status       The status of the product.
		 *
		 * @return array
		 */
		public static function get_all_by_product_id( int $product_id, bool|string $status = false ): array {
			global $wpdb;

			if ( $status && ! in_array( $status, array( self::$active_status, self::$inactive_status ) ) ) {
				return array();
			}

			$returnable = array();
			$table      = ( new self() )->generate_table_name();

			if ( $status ) {
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM %s WHERE product_id = %s AND status = %s;', $table, $product_id, $status ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM %s WHERE product_id = %s;', $table, $product_id ) );
			}

			foreach ( $results as $result ) {
				$returnable[] = new self( $result->id );
			}

			return $returnable;
		}

		/**
		 * Return all license keys of a given user
		 *
		 * @since 1.0.0
		 *
		 * @param int         $user_id The ID of the user.
		 * @param bool|string $status  The status of the key.
		 *
		 * @return array
		 */
		public static function get_all_by_user_id( int $user_id, bool|string $status = false ): array {
			global $wpdb;

			if ( $status && ! in_array( $status, array( self::$active_status, self::$inactive_status ) ) ) {
				return array();
			}

			$returnable = array();
			$table      = ( new self() )->generate_table_name();

			if ( $status ) {
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM %s WHERE user_id = %s AND status = %s;', $table, $user_id, $status ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM %s WHERE user_id = %s;', $table, $user_id ) );
			}

			foreach ( $results as $result ) {
				$returnable[] = new self( $result->id );
			}

			return $returnable;
		}

		/**
		 * The name of the table
		 *
		 * @var string
		 */
		protected string $table = 'license_keys';

		/**
		 * The fields of the model
		 *
		 * @var array|array[]
		 */
		protected array $fields = array(
			'license_key' => array( 'required', 'string', 'unique' ),
			'status'      => array( 'required', 'string' ),
			'user_id'     => array( 'required', 'integer' ),
			'product_id'  => array( 'required', 'integer' ),
			'created_at'  => array( 'required', 'date' ),
			'expires_at'  => array( 'required', 'date' ),
			'meta'			 	=> array( 'serialized' ),
		);

		/**
		 * The ID of the object
		 *
		 * @var int
		 */
		public int $id = 0;

		/**
		 * The key of the license
		 *
		 * @var string
		 */
		public string $license_key = '';

		/**
		 * The status of the key
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
		 * The ID of the product
		 *
		 * @var int
		 */
		public int $product_id = 0;

		/**
		 * The date the key was created at
		 *
		 * @var string
		 */
		public string $created_at = '';

		/**
		 * The date the key was expires at
		 *
		 * @var string
		 */
		public string $expires_at = '';

		/**
		 * The date the key expires at
		 *
		 * @var string
		 */
		public string $expires_a = '';

		/**
		 * The meta fields of the key
		 *
		 * @var mixed
		 */
		public mixed $meta = array();

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
                    license_key varchar(255) NOT NULL,
                    status varchar(255) DEFAULT 'inactive',
                    user_id mediumint(9) NOT NULL,
                    product_id mediumint(9) NOT NULL,
                    created_at varchar(255) NOT NULL,
                    expires_at varchar(255) NOT NULL,
                    meta TEXT,
                    PRIMARY KEY  (id)
                "
				);
			}
		}

		/**
		 * Generate a license key
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 */
		public function generate(): void {
			$key               = hash( 'sha256', uniqid( get_current_user_id() ) );
			$this->license_key = $key;

			$validation = ( new Validator( $this, 'license_key', array( 'unique' ) ) );

			if ( ! $validation->result() ) {
				throw new Exception( 'Cannot generate a valid key, please contact support!' );
			}
		}

		/**
		 * Return the product for the license key
		 *
		 * @since 1.0.0
		 *
		 * @return Product
		 * @throws Exception A regular exception.
		 */
		public function product(): Product {
			if ( ! $this->exists( $this->id ) ) {
				throw new Exception( 'No license key has been summoned' );
			}

			if ( empty( $this->product_id ) ) {
				throw new Exception( 'This license key does not have any products attached' );
			}

			return new Product( $this->product_id );
		}

		/**
		 * Return the user object of the key owner
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
	}
}
