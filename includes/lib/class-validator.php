<?php
/**
 * Holds the Validator class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Lib;

use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Lib\Validator' ) ) {
	/**
	 * Helps validate data
	 */
	class Validator {
		/**
		 * The model object
		 *
		 * @var object
		 */
		private object $model;

		/**
		 * The field
		 *
		 * @var string
		 */
		private string $field;

		/**
		 * The name of the error
		 *
		 * @var string
		 */
		private string $error;

		/**
		 * The validation options
		 *
		 * @var array
		 */
		private array $options;

		/**
		 * Weather the process is an edit or a create process
		 *
		 * @var bool|mixed
		 */
		private bool $edit;

		/**
		 * Data to be returned
		 *
		 * @var bool
		 */
		private bool $returnable;

		/**
		 * Construct the object
		 *
		 * @param mixed  $model      The model object.
		 * @param string $field      The name of the field.
		 * @param array  $options    The validation options.
		 * @param bool   $edit       Weather the process is an edit or a create process.
		 */
		public function __construct( mixed $model, string $field, array $options, bool $edit = false ) {
			$this->model      = $model;
			$this->field      = $field;
			$this->options    = $options;
			$this->edit       = $edit;
			$this->returnable = true;

			if ( 'meta' !== $field ) {
				$this->validate();
			}
		}

		/**
		 * Init the validation
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function validate(): void {
			foreach ( $this->options as $option ) {
				$this->individual_validation( $option );

				if ( false === $this->returnable ) {
					break;
				}
			}
		}

		/**
		 * Return the result of the validation
		 *
		 * @since 1.0.0
		 *
		 * @return bool
		 */
		public function result(): bool {
			return $this->returnable;
		}

		/**
		 * Get the errors if there is an error
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function get_error(): string {
			if ( ! empty( $this->error ) ) {
				return $this->error;
			}

			return 'No error';
		}

		/**
		 * Trigger an error
		 *
		 * @since 1.0.0
		 *
		 * @param string $contents The contents of the error.
		 *
		 * @return void
		 */
		private function trigger_error( string $contents ): void {
			$this->returnable = false;

			// translators: the error generated by the validation.
			$this->error = printf( esc_attr__( 'Error: %1$s %2$s', 'wp-fusion-slack' ), esc_attr( $this->field ), esc_attr( $contents ) );
		}

		/**
		 * Decide which validation method to use
		 *
		 * @since 1.0.0
		 *
		 * @param string $option The name of the option.
		 *
		 * @return void
		 */
		private function individual_validation( string $option ): void {
			switch ( $option ) {
				case 'required':
					$this->required( $this->model->{$this->field} );
					return;
				case 'serialized':
					$this->serialized( $this->model->{$this->field} );
					return;
				case 'string':
					$this->string( $this->model->{$this->field} );
					return;
				case 'integer':
					$this->integer( $this->model->{$this->field} );
					return;
				case 'numeric':
					$this->numeric( $this->model->{$this->field} );
					return;
				case 'date':
					$this->date( $this->model->{$this->field} );
					return;
				case 'unique':
					$this->unique( $this->model->{$this->field} );
					return;
			}
		}

		/**
		 * Check if the field exists
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The value.
		 *
		 * @return void
		 */
		private function required( mixed $value ): void {
			if ( empty( $value ) ) {
				$this->trigger_error( 'is required' );
			}
		}

		/**
		 * Check if the field is a string
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The value.
		 *
		 * @return void
		 */
		private function string( mixed $value ): void {
			if ( ! is_string( $value ) ) {
				$this->trigger_error( 'must be a string' );
			}
		}

		/**
		 * Check if the field is a serialized string
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The value.
		 *
		 * @return void
		 */
		private function serialized( mixed $value ): void {
			if ( ! empty( $value ) && ! unserialize( $value ) ) {
				$this->trigger_error( 'must be a serialized object as a string' );
			}
		}

		/**
		 * Check if the field is an integer
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The value.
		 *
		 * @return void
		 */
		private function integer( mixed $value ): void {
			if ( ! is_integer( (int) $value ) ) {
				$this->trigger_error( 'must be a integer' );
			}
		}

		/**
		 * Check if the field is numeric
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The value.
		 *
		 * @return void
		 */
		private function numeric( mixed $value ): void {
			if ( ! is_numeric( $value ) ) {
				$this->trigger_error( 'must be a number' );
			}
		}

		/**
		 * Check if the field is a date
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The value.
		 *
		 * @return void
		 */
		private function date( mixed $value ): void {
			if ( ! DateTime::createFromFormat( LCHB_TIME_FORMAT, $value ) ) {
				$this->trigger_error( 'must be a date' );
			}
		}

		/**
		 * Check if the field value already exists in the database
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The value.
		 *
		 * @return void
		 */
		private function unique( mixed $value ): void {
			if ( ! $this->edit ) {
				global $wpdb;

				if ( is_string( $value ) ) {
					$value = '"' . $value . '"';
				}

				if ( $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE %s=%s;', $this->model->generate_table_name(), $this->field, $value ) ) ) {
					$this->trigger_error( 'must be unique' );
				}
			}
		}
	}
}
