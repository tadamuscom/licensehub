<?php
/**
 * Holds the API_Key model
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

if ( ! class_exists( '\LicenseHub\Includes\Model\API_Key' ) ) {
	/**
	 * Model for API keys
	 */
	class API_Key extends Model implements Model_Blueprint {
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
		 * The name of the table
		 *
		 * @var string
		 */
		protected string $table = 'api_keys';

		/**
		 * Fields of the model
		 *
		 * @var array|array[]
		 */
		protected array $fields = array(
			'api_key'    => array( 'required', 'string', 'unique' ),
			'status'     => array( 'required', 'string' ),
			'user_id'    => array( 'required', 'numeric' ),
			'created_at' => array( 'required', 'date' ),
			'expires_at' => array( 'required', 'date' ),
			'meta'       => array(),
		);

		/**
		 * The ID of the object
		 *
		 * @var int
		 */
		public int $id = 0;

		/**
		 * The key
		 *
		 * @var string
		 */
		public string $api_key = '';

		/**
		 * The status of the key
		 *
		 * @var string
		 */
		public string $status = 'inactive';

		/**
		 * The ID of the user
		 *
		 * @var mixed
		 */
		public mixed $user_id = '';

		/**
		 * The date at which the key was created
		 *
		 * @var string
		 */
		public string $created_at = '';

		/**
		 * The date at which the key expires
		 *
		 * @var string
		 */
		public string $expires_at = '';

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
                    api_key varchar(255) NOT NULL,
                    status varchar(255) DEFAULT 'inactive',
                    user_id mediumint(9) NOT NULL,
                    created_at datetime NOT NULL,
                    expires_at datetime NOT NULL,
                    meta TEXT,
                    PRIMARY KEY  (id)
                "
				);
			}
		}

		/**
		 * Generate a new API key
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 * @since 1.0.0
		 */
		public function generate(): void {
			$key           = hash( 'sha256', uniqid( get_current_user_id() ) );
			$this->api_key = $key;

			$validation = ( new Validator( $this, 'api_key', array( 'unique' ) ) );

			if ( ! $validation->result() ) {
				throw new Exception( 'Cannot generate a valid key, please contact support!' );
			}
		}

		/**
		 * Return the user object for the owner of the API Key
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
		 * Save the API Key to the database
		 *
		 * @return void
		 * @throws Exception A regular exception.
		 * @since 1.0.0
		 */
		public function save(): void {
			if ( $this->load_by_field( 'user_id', $this->user_id, false ) ) {
				$this->generate();
			}

			parent::save();
		}
	}
}
