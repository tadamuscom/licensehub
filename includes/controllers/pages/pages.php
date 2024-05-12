<?php
/**
 * Register the pages in the right order
 */

use LicenseHub\Includes\Controller\Pages\API_Keys_Page;
use LicenseHub\Includes\Controller\Pages\License_Keys_Page;
use LicenseHub\Includes\Controller\Pages\Products_Page;
use LicenseHub\Includes\Controller\Pages\Settings_Page;

new Products_Page();
new License_Keys_Page();
new API_Keys_Page();
new Settings_Page();
