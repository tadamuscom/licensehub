<?php
/**
 * Holds the Model class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Abstract;

use DateTime;
use Exception;
use LicenseHub\Includes\Lib\Validator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Abstract\Model' ) ) {
	/**
	 * Main Model class which will be inherited by all the models
	 */
	abstract class Model {
		/**
		 * The name of the table
		 *
		 * @var string
		 */
		protected string $table;

		/**
		 * The fields of the model
		 *
		 * @var array
		 */
		protected array $fields;

		/**
		 * The error if there is one
		 *
		 * @var string
		 */
		protected string $error;

		/**
		 * Construct the object
		 *
		 * @param mixed $id The ID of the object.
		 */
		public function __construct( mixed $id = false ) {
			if ( $id || $this->exists( $id ) ) {
                return $this->load_by_id( $id );
            }

            return $this->new();
		}

		/**
		 * Generate table name for the current model
		 *
		 * @param mixed $name The name of the table.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function generate_table_name( mixed $name = false ): string {
			global $wpdb;

			$prefix = 'lchb';

			if ( defined( 'LCHB_DB_PREFIX' ) ) {
				$prefix = LCHB_DB_PREFIX;
			}

			if ( ! empty( $this->table ) ) {
				return $wpdb->prefix . $prefix . '_' . $this->table;
			}

			return $wpdb->prefix . $prefix . '_' . $name;
		}

		/**
		 * Check if the current model exists
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $id The ID of the object.
		 *
		 * @return bool
		 */
		public function exists( mixed $id ): bool {
			global $wpdb;

			if ( $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id=%s;', $this->generate_table_name( $this->table ), $id ) ) ) {
					return true;
			}

			return false;
		}

		/**
		 * Load the model by its ID
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $id The ID of the object.
		 *
		 * @return $this|false
		 */
		public function load_by_id( mixed $id ): bool|static {
			global $wpdb;

			$object = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE id=%s;', $this->generate_table_name(), $id ) );

			if ( $object ) {
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
		 * @param string $field The field.
		 * @param mixed  $value The value.
		 * @param bool   $failsafe Load or not.
		 *
		 * @return $this|false
		 * @throws Exception Throw regular exception.
		 */
		public function load_by_field( string $field, mixed $value, bool $failsafe = true ): bool|static {
			$property = $this->fields[ $field ];

			if ( $failsafe ) {
				if ( is_array( $property ) ) {
					if ( ! in_array( 'unique', $property, true ) ) {
						throw new Exception( 'The loading fields needs to be set as unique' );
					}
				}

				if ( is_string( $property ) ) {
					if ( 'unique' !== $property ) {
						throw new Exception( 'The loading fields needs to be set as unique' );
					}
				}
			}

			global $wpdb;

			$object = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE %i = %s;', $this->generate_table_name(), $field, $value ) );

			if ( $object ) {
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
		public function get_all(): array {
			global $wpdb;

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i;', $this->generate_table_name() ) );

			foreach ( $results as $result ) {
				unset( $result->meta );
			}

			return $results;
		}

		/**
		 * Return all the fields of the model
		 *
		 * @since 1.0.0
		 *
		 * @param bool $meta Weather to add the meta fields to the object or not.
		 *
		 * @return array|string[]
		 */
		public function get_fields( bool $meta = false ): array {
			if ( isset( $this->fields ) ) {
				$returnable = array();

				foreach ( $this->fields as $key => $field ) {
					if ( ! $meta ) {
						if ( 'meta' === $key ) {
							continue;
						}
					}

					$returnable[] = $key;
				}

				return $returnable;
			}

			return array(
				'error' => 'There are no fields to be retrieved',
			);
		}

		/**
		 * Save the model to the database
		 *
		 * @since 1.0.0
		 *
		 * @return $this|void
		 */
		public function save() {
			global $wpdb;

			$data = $this->array_format();

			if ( empty( $this->id ) || ! $this->exists( $this->id ) ) {
				if ( $this->validation() ) {
                    do_action( 'lchb-before-create-' . get_class( $this ), $this );

					$wpdb->insert( $this->generate_table_name(), $data );

                    do_action( 'lchb-after-create-' . get_class( $this ), $this );
				} else {
					wp_die( esc_attr( $this->error ) );
				}
			} elseif ( $this->validation( true ) ) {
                do_action( 'lchb-before-update-' . get_class( $this ), $this );

                $wpdb->update( $this->generate_table_name(), $data, array( 'id' => $this->id ) );
                
                do_action( 'lchb-after-update-' . get_class( $this ), $this );
			} else {
				wp_die( esc_attr( $this->error ) );
			}

			$this->object_format( array( $this->load_by_id( $wpdb->insert_id ) ) );

			return $this;
		}

		/**
		 * Delete the instance from the database
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function destroy(): void {
			global $wpdb;

			$wpdb->delete( $this->generate_table_name(), array( 'id' => $this->id ) );
		}

		/**
		 * Check if the table exists
		 *
		 * @since 1.0.0
		 *
		 * @param string $table_name The name of the SQL table.
		 *
		 * @return bool
		 */
		protected function table_exists( string $table_name ): bool {
			global $wpdb;

			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ) ) === $table_name ) {
				return true;
			}

			return false;
		}

		/**
		 * Create table for the model
		 *
		 * @since 1.0.0
		 *
		 * @param string $table_name The name of the table.
		 * @param string $fields The fields of the table.
		 *
		 * @return void
		 */
		protected function create_table( string $table_name, string $fields ): void {
			if ( ! $this->table_exists( $table_name ) ) {
				global $wpdb;

				$charset = $wpdb->get_charset_collate();
				$sql     = "CREATE TABLE $table_name ( $fields ) $charset;";

				require_once ABSPATH . 'wp-admin/includes/upgrade.php';

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
		protected function new(): self {
			foreach ( $this->fields as $field => $rules ) {
				if ( 'id' === $field ) {
					continue;
				}

				if ( is_array( $rules ) ) {
					if ( in_array( 'string', $rules ) ) {
						$this->{$field} = '';
					} elseif ( in_array( 'integer', $rules, true ) ) {
						$this->{$field} = 0;
					} elseif ( in_array( 'date', $rules, true ) ) {
						$today = ( new DateTime() )->setTimestamp( time() );

						$this->{$field} = $today->format( LCHB_TIME_FORMAT );
					}elseif ( in_array( 'array', $rules, true )  ) {
						$this->{$field} = array();
					} else {
						$this->{$field} = null;
					}
				} else {
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
		 * @param bool $edit Weather it is an edit process or a new process.
		 *
		 * @return bool
		 */
		private function validation( bool $edit = false ): bool {
			foreach ( $this->fields as $field => $options ) {
				if ( 'id' === $field ) {
					continue;
				}

				if ( ! is_array( $options ) ) {
					$options = array();
				}

				if ( $edit ) {
					$validator = new Validator( $this, $field, $options, true );
				} else {
					$validator = new Validator( $this, $field, $options );
				}

				if ( ! $validator->result() ) {
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
		 * @param mixed $object The object.
		 *
		 * @return void
		 */
		private function object_format( mixed $object ): void {
			$fields = $this->get_fields();

			if ( is_object( $object[0] ) ) {
				$this->id = $object[0]->id;
			}

			foreach ( $fields as $field ) {
				if ( 'meta' === $field ) {
					continue;
				}

				if ( is_object( $object[0] ) ) {
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
		private function array_format(): array {
			$returnable = array();
			$fields     = $this->get_fields( true );

			foreach ( $fields as $field ) {
				if ( 0 == $field || 'id' === $field ) {
					continue;
				}

				if ( 'meta' === $field ) {
					continue;
				}

				$returnable[ $field ] = $this->{$field};
			}

			return $returnable;
		}
	}
}
