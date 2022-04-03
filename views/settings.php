<div class="wpretail-main container-fluid">
	<div class="row flex-nowrap">
		<div class="wpretail-sidebar col-auto col-md-3 col-xl-2 px-0">
			<div class="d-flex flex-column px-0">
				<ul class="nav  nav-tabs flex-column mb-sm-auto mb-0" id="menu">
					<?php
					$setting_options = apply_filters( 'wpretail_settings_options', [] );
					if ( ! empty( $setting_options ) ) {
						foreach ( $setting_options as $id => $option ) {
							echo '<li class="nav-item">';
							echo '<a href="#' . esc_attr( $id ) . '" class="nav-link wpretail-sidebar-link align-middle"  role="tab" aria-controls="addproduct" data-bs-toggle="tab">';
							echo '<span class="ms-1 d-none d-sm-inline">' . esc_html( $option['name'] ) . '</span>';
							echo '</a>';
							echo '</li>';
						}
					}
					?>
					</li>
				</ul>
			</div>
		</div>
		<div class="col py-3">
			<div class="container card p-4">
				<div class="row">
					<div class="col-md-12">
					<div class="tab-content">
						<?php
						if ( ! empty( $setting_options ) ) {
							foreach ( $setting_options as $id => $option ) {
								echo '<div class="tab-pane ' . ( 'business_setting' === $id ? 'active' : '' ) . '" id="' . esc_attr( $id ) . '" role="tabpanel">';
									do_action( 'wpretail_view_' . $id );
								echo '</div>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(function(){
		jQuery('.wpretail-datepicker').each( function() {
			console.log(jQuery( this ).data('format'))
			jQuery( this ).datepicker({
				format:'dd/mm/yyyy'
			});
		});

		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
		})

		jQuery( '.wpretail-datatable' ).each( function() {
			jQuery( this ).DataTable();
		})
	});
</script>
