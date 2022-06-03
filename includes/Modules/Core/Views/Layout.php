<?php
/**
 * Layout For WPRetail View
 *
 * @package WPRetail
 */

use WPRetail\Modules\Core\Views\Views;

/**
 * @var mixed $view
 */
$view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : 'dashboard'; // phpcs:ignore

echo '<div id="wpretail-wrapper"><div id="wrapper">';

$views = new Views();
$views->load( 'sidebar' );


echo '<!-- Content Wrapper --><div id="content-wrapper" class="d-flex flex-column"><!-- Main Content --><div id="content">';
$views->load( 'header' );
do_action( 'wpretail_view_' . $view );
$views->load( 'footer' );

echo '</div></div></div></div>';

?>

<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
	<i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				<a class="btn btn-primary" href="login.html">Logout</a>
			</div>
		</div>
	</div>
</div>
