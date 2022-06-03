<?php
/**
 * @package WPretail\Core
 *
 * @since 1.0.0
 */

use WPRetail\Modules\Core\Form\Form;
use WPRetail\Modules\Core\Widgets\Product_Logs;

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8">
			<h1 class="h3 mb-2 text-gray-800">Add Product</h1>
			<p class="mb-4">Add product to your inventory. For more information about products and its attributes, please visit the <a target="_blank" href="https://tabulations.io"> official WPRetail documentation</a></p>

			<?php
				$form = new Form( 'add_product', [] );
				$form->load();
			?>
		</div>
		<div class="col-md-4">
			<?php
				$product_log = new Product_Logs();
				$product_log->render();
			?>
		</div>
	</div>
</div>
