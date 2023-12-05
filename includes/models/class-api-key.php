<?php

namespace LicenseHub\Includes\Model;

use WP_User;
use Exception;
use LicenseHub\Includes\Abstract\Model;
use LicenseHub\Includes\Interface\Model_Blueprint;
use LicenseHub\Includes\Lib\Validator;

if( ! class_exists( 'API_Key' ) ) {
	class API_Key extends Model implements Model_Blueprint{
		public static string $ACTIVE_STATUS = 'active';
		public static string $INACTIVE_STATUS = 'inactive';

		protected string $table = 'api_keys';
		protected array $fields = array(
			'api_key'           => array( 'required', 'string', 'unique' ),
			'status'            => array( 'required', 'string' ),
			'user_id'           => array( 'required', 'numeric' ),
			'created_at'        => array( 'required', 'date' ),
			'expires_at'        => array( 'required', 'date' )
		);

		public function init() : void {
			if( ! $this->table_exists( $this->table ) ){
				$this->create_table( $this->generate_table_name( $this->table ), "
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    api_key varchar(255) NOT NULL,
                    status varchar(255) DEFAULT 'inactive',
                    user_id mediumint(9) NOT NULL,
                    created_at datetime NOT NULL,
                    expires_at datetime NOT NULL,
                    PRIMARY KEY  (id)
                " );
			}
		}

		/**
		 * Generate a new API key
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 * @throws Exception
		 */
		public function generate() : void {
			$key = hash( 'sha256', uniqid( get_current_user_id() ) );
			$this->api_key = $key;

			$validation = ( new Validator( $this, 'api_key', array( 'unique' ) ) );

			if( ! $validation->result() ){
				throw new Exception( 'Cannot generate a valid key, please contact support!' );
			}
		}

		/**
		 * Return the user object for the owner of the API Key
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

		/**
		 * Save the API Key to the database
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 * @throws Exception
		 */
		public function save() : void {
			if( $this->load_by_field( 'user_id', $this->user_id, false ) ){
				$this->generate();
			}

			parent::save();
		}
	}
}