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
				$this->load_by_id( $id );

				return;
			}

			$this->new();
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
		 * @param mixed $id The ID of the object.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		public function exists( mixed $id ): bool {
			global $wpdb;

			//phpcs:ignore
			if ( $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id=%s;', $this->generate_table_name( $this->table ), $id ) ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Load the model by its ID
		 *
		 * @param mixed $id The ID of the object.
		 *
		 * @return $this|false
		 * @since 1.0.0
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
		 * @param string $field The field.
		 * @param mixed  $value The value.
		 * @param bool   $failsafe Load or not.
		 *
		 * @return $this|false
		 * @throws Exception Throw regular exception.
		 * @since 1.0.0
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

			//phpcs:ignore
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
		 * @param bool $ignore_meta Weather to ignore the meta fields or not.
		 *
		 * @return array
		 * @since 1.0.0
		 */
		public function get_all( bool $ignore_meta = true ): array {
			global $wpdb;

			//phpcs:ignore
			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i;', $this->generate_table_name() ) );

			if ( $ignore_meta ) {
				foreach ( $results as $result ) {
					unset( $result->meta );
				}
			}

			return $results;
		}

		/**
		 * Return all the fields of the model
		 *
		 * @param bool $meta Weather to add the meta fields to the object or not.
		 *
		 * @return array|string[]
		 * @since 1.0.0
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
		 * @return $this|void
		 * @since 1.0.0
		 */
		public function save() {
			global $wpdb;

			$data = $this->array_format();

			if ( empty( $this->id ) || ! $this->exists( $this->id ) ) {
				if ( $this->validation() ) {
					do_action( 'lchb_before_create_' . get_class( $this ), $this );

					//phpcs:ignore
					$wpdb->insert( $this->generate_table_name(), $data );

					do_action( 'lchb_after_create_' . get_class( $this ), $this );
				} else {
					wp_die( esc_attr( $this->error ) );
				}
			} elseif ( $this->validation( true ) ) {
				do_action( 'lchb_before_update_' . get_class( $this ), $this );

				//phpcs:ignore
				$wpdb->update( $this->generate_table_name(), $data, array( 'id' => $this->id ) );

				do_action( 'lchb_after_update_' . get_class( $this ), $this );
			} else {
				wp_die( esc_attr( $this->error ) );
			}

			$this->object_format( array( $this->load_by_id( $wpdb->insert_id ) ) );

			return $this;
		}

		/**
		 * Delete the instance from the database
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function destroy(): void {
			global $wpdb;

			//phpcs:ignore
			$wpdb->delete( $this->generate_table_name(), array( 'id' => $this->id ) );
		}

		/**
		 * Check if the table exists
		 *
		 * @param string $table_name The name of the SQL table.
		 *
		 * @return bool
		 * @since 1.0.0
		 */
		protected function table_exists( string $table_name ): bool {
			global $wpdb;

			//phpcs:ignore
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ) ) === $table_name ) {
				return true;
			}

			return false;
		}

		/**
		 * Create table for the model
		 *
		 * @param string $table_name The name of the table.
		 * @param string $fields The fields of the table.
		 *
		 * @return void
		 * @since 1.0.0
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
		 * @return $this
		 * @since 1.0.0
		 */
		protected function new(): self {
			foreach ( $this->fields as $field => $rules ) {
				if ( 'id' === $field ) {
					continue;
				}

				if ( is_array( $rules ) ) {
					if ( in_array( 'string', $rules, true ) ) {
						$this->{$field} = '';
					} elseif ( in_array( 'integer', $rules, true ) ) {
						$this->{$field} = 0;
					} elseif ( in_array( 'date', $rules, true ) ) {
						$today = ( new DateTime() )->setTimestamp( time() );

						$this->{$field} = $today->format( LCHB_TIME_FORMAT );
					} elseif ( in_array( 'array', $rules, true ) ) {
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
		 * @param bool $edit Weather it is an edit process or a new process.
		 *
		 * @return bool
		 * @since 1.0.0
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
		 * @param mixed $model The object.
		 *
		 * @return void
		 * @since 1.0.0
		 */
		private function object_format( mixed $model ): void {
			$fields = $this->get_fields();

			if ( is_object( $model[0] ) ) {
				$this->id = $model[0]->id;
			}

			foreach ( $fields as $field ) {
				if ( 'meta' === $field && empty( $model[0]->meta ) ) {
					continue;
				} elseif ( is_string( $this->meta ) ) {
					$this->meta = json_decode( $model[0]->meta, true );
				} else {
					$this->meta = $model[0]->meta;
				}

				if ( is_object( $model[0] ) ) {
					$this->{$field} = $model[0]->{$field};
				}
			}
		}

		/**
		 * Format the data as an array
		 *
		 * @return array
		 * @since 1.0.0
		 */
		private function array_format(): array {
			$returnable = array();
			$fields     = $this->get_fields( true );

			foreach ( $fields as $field ) {
				// phpcs:ignore
				if ( 0 == $field || 'id' === $field ) {
					continue;
				}

				if ( 'meta' === $field && empty( $this->meta ) ) {
					continue;
				} elseif ( is_array( $this->meta ) ) {
					$this->meta = wp_json_encode( $this->meta );
				}

				$returnable[ $field ] = $this->{$field};
			}

			return $returnable;
		}
	}
}
