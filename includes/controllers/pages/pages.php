<?php
/**
 * Register the pages in the right order
 */

use LicenseHub\Includes\Controller\Layout\API_Keys_Page;
use LicenseHub\Includes\Controller\Layout\License_Keys_Page;
use LicenseHub\Includes\Controller\Layout\Products_Page;
use LicenseHub\Includes\Controller\Layout\Settings_Page;

new Products_Page();
new License_Keys_Page();
new API_Keys_Page();
new Settings_Page();
