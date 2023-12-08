<?php

namespace LicenseHub\Includes\Abstract;

use DateTime;
use Exception;
use LicenseHub\Includes\Lib\Validator;

if( ! class_exists( 'Model' ) ){
    abstract class Model{
        protected string $table;
        protected array $fields;
        protected string $error;

		public function __construct( $id = false ) {
			if( ! $id ){
				return $this->new();
			}else{
				if($this->exists( $id ) ){
					return $this->load_by_id( $id );
				}else{
					return $this->new();
				}
			}
		}

	    /**
	     * Generate table name for the current model
	     *
	     * @param bool $name
	     *
	     * @return string
	     *@since 1.0.0
	     *
	     */
	    public function generate_table_name( mixed $name = false ) : string {
            global $wpdb;

            $prefix = 'lchb';

            if ( defined( 'LCHB_DB_PREFIX' ) ) {
                $prefix = LCHB_DB_PREFIX;
            }

            if( ! empty( $this->table ) ){
                return $wpdb->prefix . $prefix . '_' . $this->table;
            }

            return $wpdb->prefix . $prefix . '_' . $name;
        }

	    /**
	     * Check if the current model exists
	     *
	     * @since 1.0.0
	     *
	     * @param $id
	     *
	     * @return bool
	     */
        public function exists( $id ) : bool {
            global $wpdb;

            if ( $wpdb->get_row( 'SELECT * FROM ' . $this->generate_table_name( $this->table ) . ' WHERE id=' . $id ) ) {
                    return true;
            }

            return false;
        }

	    /**
	     * Load the model by its ID
	     *
	     * @since 1.0.0
	     *
	     * @param $id
	     *
	     * @return $this|false
	     */
        public function load_by_id( $id ) : bool|static {
            global $wpdb;

            $object = $wpdb->get_results( 'SELECT * FROM ' . $this->generate_table_name() . ' WHERE id=' . $id . ';' );

            if( $object ){
				$this->object_format( $object );

                return $this;
            }

            return false;
        }

	    /**
	     * Load the model by a specific field
	     *
	     * @since 1.0.0
	     *
	     * @param string $field
	     * @param        $value
	     * @param        $failsafe
	     *
	     * @return $this|false
	     * @throws Exception
	     */
	    public function load_by_field( string $field, $value, $failsafe = true ) : bool|static {
			$property = $this->fields[$field];

			if( $failsafe ){
				if( is_array( $property ) ){
					if( ! in_array( 'unique', $property ) ){
						throw new Exception( 'The loading fields needs to be set as unique' );
					}
				}

				if( is_string( $property ) ){
					if( $property != 'unique' ){
						throw new Exception( 'The loading fields needs to be set as unique' );
					}
				}
			}

			if( is_string( $value ) ){
				$value = '"' . $value . '"';
			}

			global $wpdb;

			$object = $wpdb->get_results( 'SELECT * FROM ' . $this->generate_table_name() . ' WHERE '. $field . ' = ' . $value . ';' );

			if( $object ){
				$this->object_format( $object );

				return $this;
			}

			return false;
		}

	    /**
	     * Return all the instances of the model
	     *
	     * @since 1.0.0
	     *
	     * @return array
	     */
		public function get_all() : array {
			global $wpdb;

			$results = $wpdb->get_results( 'SELECT * FROM ' . $this->generate_table_name() . ';' );

			foreach( $results as $result ){
				unset( $result->meta );
			}

			return $results;
		}

	    /**
	     * Return all the fields of the model
	     *
	     * @since 1.0.0
	     *
	     * @return array|string[]
	     */
	    public function get_fields( $meta = false ) : array {
		    if( isset( $this->fields ) ){
			    $returnable = array();

			    foreach( $this->fields as $key => $field ){
					if( ! $meta ){
						if( $key === 'meta' ){
							continue;
						}
					}

				    $returnable[] = $key;
			    }

			    return $returnable;
		    }

		    return array(
			    'error' => 'There are no fields to be retrieved'
		    );
	    }

	    /**
	     * Save the model to the database
	     *
	     * @since 1.0.0
	     *
	     * @return $this|void
	     */
        public function save(){
	        global $wpdb;

	        $data = $this->array_format();

	        if( empty( $this->id ) || ! $this->exists( $this->id ) ) {
		        if( $this->validation() ) {
			        $wpdb->insert( $this->generate_table_name(), $data );
		        }else {
			        wp_die( $this->error );
		        }
			}else {
		        if( $this->validation( true ) ) {
			        $wpdb->update( $this->generate_table_name(), $data, ['id' => $this->id] );
		        }else {
			        wp_die( $this->error );
		        }
            }

	        $this->object_format( array( $this->load_by_id( $wpdb->insert_id ) ) );

	        return $this;
        }

	    /**
	     * Check if the table exists
	     *
	     * @since 1.0.0
	     *
	     * @param $table_name
	     *
	     * @return bool
	     */
        protected function table_exists( $table_name ) : bool {
            global $wpdb;

            $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

            if ( $wpdb->get_var( $query ) == $table_name ) {
                return true;
            }

            return false;
        }

	    /**
	     * Create table for the model
	     *
	     * @since 1.0.0
	     *
	     * @param $table_name
	     * @param $fields
	     *
	     * @return void
	     */
        protected function create_table( $table_name, $fields ) : void {
            if ( ! $this->table_exists( $table_name ) ) {
                global $wpdb;

                $charset = $wpdb->get_charset_collate();
                $sql = "CREATE TABLE $table_name ( $fields ) $charset;";

                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

                dbDelta( $sql );
            }
        }

	    /**
	     * Initiate a new empty model
	     *
	     * @since 1.0.0
	     *
	     * @return $this
	     */
		protected function new() : self {
			foreach ( $this->fields as $field => $rules ){
				if( $field === 'id' ){
					continue;
				}

				if( is_array( $rules ) ) {
					if ( in_array( 'string', $rules ) ) {
						$this->{$field} = '';
					} else if ( in_array( 'integer', $rules ) ) {
						$this->{$field} = 0;
					} else if ( in_array( 'date', $rules ) ) {
						$today = ( new DateTime() )->setTimestamp( time() );

						$this->{$field} = $today->format( LCHB_TIME_FORMAT );
					} else {
						$this->{$field} = null;
					}
				}else{
					$this->{$field} = null;
				}
			}

			return $this;
		}

	    /**
	     * Validate the contents of the model
	     *
	     * @since 1.0.0
	     *
	     * @param bool $edit
	     *
	     * @return bool
	     */
        private function validation( bool $edit = false ) : bool {
            foreach( $this->fields as $field => $options ){
				if( $field === 'id' ){
					continue;
				}

				if( ! is_array( $options ) ){
					$options = array();
				}

				if( $edit ){
					$validator = new Validator( $this ,$field, $options, true );
				}else{
					$validator = new Validator( $this ,$field, $options );
				}

                if( ! $validator->result() ){
                    $this->error = $validator->get_error();

                    return false;
                }
            }

            return true;
        }

	    /**
	     * Feed the model data to the object
	     *
	     * @since 1.0.0
	     *
	     * @param $object
	     *
	     * @return void
	     */
        private function object_format( $object ) : void {
            $fields = $this->get_fields();

			if( is_object( $object[0] ) ){
				$this->id = $object[0]->id;
			}

            foreach( $fields as $field ){
				if( $field === 'meta' ){
					continue;
				}

				if( is_object( $object[0] ) ){
					$this->{$field} = $object[0]->{$field};
				}
            }
        }

	    /**
	     * Format the data as an array
	     *
	     * @since 1.0.0
	     *
	     * @return array
	     */
        private function array_format() : array {
            $returnable = array();
            $fields = $this->get_fields( true );

            foreach( $fields as $field ){
				if( $field === 0 || $field === 'id' ){
					continue;
				}

                $returnable[$field] = $this->{$field};
            }

            return $returnable;
        }
    }
}