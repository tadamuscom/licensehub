<?php

namespace LicenseHub\Includes\Controller\Core;

use WP_User;
use LicenseHub\Includes\Model\License_Key;
use LicenseHub\Includes\Model\Product;

if( ! class_exists( 'LicenseHub\Includes\Controller\Core\Emails' ) ){
    class Emails{
        public function __construct(){
            add_action( 'lchb-license-key-generated', array( $this, 'thank_you_email' ), 15, 3 );
        }

        /**
         * Send thank you email with the license key
         *
         * @since 1.0.0
         *
         * @param WP_User     $user
         * @param Product     $product
         * @param License_Key $key
         *
         * @return void
         */
        public function thank_you_email( WP_User $user, Product $product, License_Key $key) : void {
            $to = $user->user_email;
            $subject = 'Your license key for ' . $product->name ;
            $message = '<h1 style="text-align: center;">Thank you for your purchase!</h1>';
            $message .= '<p>Hi ' . $user->first_name . ',</p>';
            $message .= '<p>Thanks for purchasing ' . $product->name . '!</p>';
            $message .= '<p>Here you have your license key:</p>';
            $message .= '<p>' . $key->license_key . '</p>';
            $message .= '<p>You can download the files here:</p>';
            $message .= '<p><a href="' . $product->get_meta( 'download_link' ) . '">' . $product->get_meta( 'download_link' ) . '</a></p>';
            $message .= '<p>If you have any questions or issues, please don\'t hesitate to reach out via the following email address</p>';
            $message .= '<p><a href="mailto:hello@tadamus.com">hello@tadamus.com</a></p>';
            $message .= '<p>Thanks!</p>';

            wp_mail( $to, $subject, $message );
        }
    }

    new Emails();
}