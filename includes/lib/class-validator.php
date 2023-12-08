<?php

namespace LicenseHub\Includes\Lib;

use DateTime;

if( ! class_exists( 'Validator' ) ){
    class Validator{
        private object $model;
        private string $field;
        private string $error;
        private array $options;
        private bool $returnable;

        public function __construct( $model, $field, $options )
        {
            $this->model = $model;
            $this->field = $field;
            $this->options = $options;
			$this->returnable = true;

            $this->validate();
        }

	    /**
	     * Init the validation
	     *
	     * @since 1.0.0
	     *
	     * @return void
	     */
        public function validate() : void {
            foreach( $this->options as $option ){
                $this->individual_validation( $option );

                if( $this->returnable === false ){
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
        public function result() : bool {
            return $this->returnable;
        }

	    /**
	     * Get the errors if there is an error
	     *
	     * @since 1.0.0
	     *
	     * @return string
	     */
        public function get_error() : string {
            if( ! empty( $this->error ) ){
                return $this->error;
            }

            return 'No error';
        }

	    /**
	     * Trigger an error
	     *
	     * @since 1.0.0
	     *
	     * @param $contents
	     *
	     * @return void
	     */
        private function trigger_error( $contents ) : void {
            $this->returnable = false;
            $this->error = __( $this->field . ' ' . $contents, LCHB_SLUG );

        }

	    /**
	     * Decide which validation method to use
	     *
	     * @since 1.0.0
	     *
	     * @param $option
	     *
	     * @return void
	     */
        private function individual_validation( $option ) : void {
            switch ( $option ){
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
	     * @param $value
	     *
	     * @return void
	     */
        private function required( $value ) : void {
            if(empty( $value ) ){
                $this->trigger_error( 'is required' );
            }

        }

	    /**
	     * Check if the field is a string
	     *
	     * @since 1.0.0
	     *
	     * @param $value
	     *
	     * @return void
	     */
        private function string( $value ) : void {
            if( ! is_string( $value ) ){
                $this->trigger_error( 'must be a string' );
            }
        }

	    /**
	     * Check if the field is a serialized string
	     *
	     * @since 1.0.0
	     *
	     * @param $value
	     *
	     * @return void
	     */
	    private function serialized( $value ) : void {
		    if( ! empty( $value ) && ! unserialize( $value ) ){
			    $this->trigger_error( 'must be a serialized object as a string' );
		    }
	    }

	    /**
	     * Check if the field is an integer
	     *
	     * @since 1.0.0
	     *
	     * @param $value
	     *
	     * @return void
	     */
        private function integer( $value ) : void {
            if( ! is_integer( $value ) ){
                $this->trigger_error( 'must be a integer' );
            }

        }

	    /**
	     * Check if the field is numeric
	     *
	     * @since 1.0.0
	     *
	     * @param $value
	     *
	     * @return void
	     */
	    private function numeric( $value ) : void {
		    if( ! is_numeric( $value ) ){
			    $this->trigger_error( 'must be a number' );
		    }
	    }

	    /**
	     * Check if the field is  adate
	     *
	     * @since 1.0.0
	     *
	     * @param $value
	     *
	     * @return void
	     */
        private function date( $value ) : void {
            if( ! DateTime::createFromFormat(LCHB_TIME_FORMAT, $value ) ){
	            $this->trigger_error( 'must be a date' );
            }

        }

	    /**
	     * Check if the field value already exists in the database
	     *
	     * @since 1.0.0
	     *
	     * @param $value
	     *
	     * @return void
	     */
        private function unique( $value ) : void {
            global $wpdb;

			if( is_string( $value ) ){
				$value = '"' . $value . '"';
			}

            if( $wpdb->get_row( 'SELECT * FROM '. $this->model->generate_table_name() .' WHERE ' . $this->field . '=' . $value ) ){
                $this->trigger_error( 'must be unique' );
            }
        }
    }
}