<?php

use LicenseHub\Includes\Model\License_Key;

$licenses = ( new License_Key )->get_all();

foreach( $licenses as $license ){
	lchb_dd( get_object_vars( $license ) );
}