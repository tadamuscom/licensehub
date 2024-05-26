<?php
/**
 * Holds the Releases API class
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Controller\API\Internal;

use DateTime;
use Exception;
use LicenseHub\Includes\Helper\API_Helper;
use LicenseHub\Includes\Model\API_Key;
use WP_REST_Request;

if ( ! class_exists('\LicenseHub\Includes\Controller\API\Internal\Releases_API') ){
	class Releases_API{
		public function __construct() {
			// add_action( 'rest_api_init', array( $this, 'routes' ) );
		}

        public function routes(): void {
            // routes
		}

		
	}

	new Releases_API();
}
