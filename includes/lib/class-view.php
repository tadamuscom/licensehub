<?php

namespace LicenseHub\Includes\Lib;

if( ! class_exists( 'View' ) ){
    class View{
        private string $view;
        private $with;

        public function __construct( string $view, $with = false ) {
            $this->view = $view;

            if( $with ){
                $this->with = $with;
            }

            $this->render();
        }

	    /**
         * Add notifications to the session
         *
         * @since 1.0.0
         *
	     * @return void
	     */
        private function add_notifications() : void {
            if( isset( $_COOKIE['lchb_redirect_type'] ) && isset( $_COOKIE['lchb_redirect_message'] ) ){
                $type = $this->generate_message_class( $_COOKIE['lchb_redirect_type'] );
                $message = $_COOKIE['lchb_redirect_message'];

                $this->show_redirect_message( $type, $message );
            }
        }

        /**
         * The notifications partial
         *
         * @since 1.0.0
         *
         * @param string $type
         * @param string $message
         * @return void
         */
        private function show_redirect_message( string $type, string $message ) : void {
            ?>
            <div class="lchb-message-container">
                <div class="lchb-notice <?php echo $type; ?>">
                    <p><?php echo $message; ?></p>
                </div>
            </div>

            <script>
                document.cookie = "lchb_redirect_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "lchb_redirect_message=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            </script>
            <?php
        }

	    /**
         * Decide which HTML class to add
         *
         * @since 1.0.0
         *
	     * @param string $type
	     *
	     * @return string
	     */
        private function generate_message_class( string $type ) : string {
	        return match ( $type ) {
		        'success' => 'lchb-success',
		        'alert' => 'lchb-alert',
		        'error' => 'lchb-error',
		        default => '',
	        };
        }

	    /**
         * Render the view
         *
         * @since 1.0.0
         *
	     * @return void
	     */
         private function render() : void {
             $this->add_notifications();

             if( ! empty($this->with) ){
                 foreach($this->with as $key => $value){
                     ${$key} = $value;
                 }
             }

	         require LCHB_PATH . '/includes/views/' . $this->view . '.php';
         }
    }
}