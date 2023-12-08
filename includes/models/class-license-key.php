<?php

namespace LicenseHub\Includes\Model;

use WP_User;
use Exception;
use LicenseHub\Includes\Abstract\Model;
use LicenseHub\Includes\Interface\Model_Blueprint;
use LicenseHub\Includes\Lib\Validator;

if( ! class_exists( 'License_Key' ) ) {
    class License_Key extends Model implements Model_Blueprint{
	    public static string $ACTIVE_STATUS = 'active';
	    public static string $INACTIVE_STATUS = 'inactive';

	    /**
	     * Return a list of license keys based on the product ID
	     *
	     * @since 1.0.0
	     *
	     * @param int         $product_id
	     * @param bool|string $status
	     *
	     * @return array
	     *
	     */
	    public static function get_all_by_product_id( int $product_id, bool|string $status = false ) : array {
			global $wpdb;

			if( $status && ! in_array( $status, array( self::$ACTIVE_STATUS, self::$INACTIVE_STATUS ) ) ){
				return array();
			}

		    $returnable = array();
			$table = ( new self() )->generate_table_name();

			if( $status ){
				$results = $wpdb->get_results( 'SELECT id FROM ' . $table . ' WHERE product_id = ' . $product_id . ' AND status = "'. $status .'";' );
			}else{
				$results = $wpdb->get_results( 'SELECT id FROM ' . $table . ' WHERE product_id = ' . $product_id . ';' );
			}

		    foreach( $results as $result ){
			    $returnable[] = new self( $result->id );
		    }

		    return $returnable;
	    }

	    /**
	     * Return all license keys of a given user
	     *
	     * @since 1.0.0
	     *
	     * @param int         $user_id
	     * @param bool|string $status
	     *
	     * @return array
	     */
		public static function get_all_by_user_id( int $user_id, bool|string $status = false ) : array {
			global $wpdb;

			if( $status && ! in_array( $status, array( self::$ACTIVE_STATUS, self::$INACTIVE_STATUS ) ) ){
				return array();
			}

			$returnable = array();
			$table = ( new self() )->generate_table_name();

			if( $status ){
				$results = $wpdb->get_results( 'SELECT id FROM ' . $table . ' WHERE user_id = ' . $user_id . ' AND status = "'. $status .'";' );
			}else{
				$results = $wpdb->get_results( 'SELECT id FROM ' . $table . ' WHERE user_id = ' . $user_id . ';' );
			}

			foreach( $results as $result ){
				$returnable[] = new self( $result->id );
			}

			return $returnable;
		}

        protected string $table = 'license_keys';
        protected array $fields = array(
            'license_key' => array( 'required', 'string', 'unique' ),
            'status'      => array( 'required', 'string' ),
            'user_id'     => array( 'required', 'integer' ),
            'product_id'  => array( 'required', 'integer' ),
            'created_at'  => array( 'required', 'date' ),
            'expires_at'  => array( 'required', 'date' )
        );

        public function init() : void {
            if( ! $this->table_exists( $this->table ) ){
                $this->create_table( $this->generate_table_name( $this->table ), "
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    license_key varchar(255) NOT NULL,
                    status varchar(255) DEFAULT 'inactive',
                    user_id mediumint(9) NOT NULL,
                    product_id mediumint(9) NOT NULL,
                    created_at varchar(255) NOT NULL,
                    expires_at varchar(255) NOT NULL,
                    PRIMARY KEY  (id)
                " );
            }
        }

	    /**
	     * Generate a license key
	     *
	     * @since 1.0.0
	     *
	     * @return void
	     * @throws Exception
	     */
	    public function generate() : void {
			$key = hash( 'sha256', uniqid( get_current_user_id() ) );
			$this->license_key = $key;

			$validation = ( new Validator($this, 'license_key', array( 'unique' ) ) );

			if( ! $validation->result() ){
				throw new Exception( 'Cannot generate a valid key, please contact support!' );
			}
		}

	    /**
	     * Return the product for the license key
	     *
	     * @since 1.0.0
	     *
	     * @return Product
	     * @throws Exception
	     */
	    public function product() : Product {
			if( ! $this->exists( $this->id) ){
				throw new Exception( 'No license key has been summoned' );
			}

			if( empty( $this->product_id ) ){
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