<?php

use LicenseHub\Includes\Model\Product;

$instance = new Product();
$products = $instance->get_all();
$fields   = $instance->get_fields();
?>
<div class="lchb-table-layout">
	<div class="lchb-table-layout-header">
		<h1>Products</h1>
	</div>
	<div class="lchb-table-wrap">
		<table class="lchb-table">
			<tr class="lchb-table-head">
				<?php
                    foreach( $fields as $field ):?>
                        <th><?php echo $field; ?></th>
                    <?php
                    endforeach;
                ?>
			</tr>
		</table>
	</div>
</div>
