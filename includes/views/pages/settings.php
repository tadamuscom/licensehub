<?php

use LicenseHub\Includes\Lib\Form;

$form = new Form('lchb-settings', 'lchb-settings', 'lchb-settings');
$form->heading(array(
	'id' => 'lchb-settings-main-heading',
	'content' => 'LicenseMate Settings'
), 'h1');

$form->render();