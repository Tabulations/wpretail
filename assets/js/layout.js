/**
 * EverestFormsRepeaterFieldsFrontEnd JS
 */
jQuery( function ( $ ) {
	var WPRetail = {
		init: function () {
			WPRetail.bindUIActions();
		},

		/**
		 * Element bindings
		 */
		bindUIActions: function () {
			// Functions to write to control add and remove buttons.
			$( '#wpretail-wrapper #sidebarToggleTop' ).click( function () {
				if ( $( '#wpretail-wrapper .sidebar' ).is( ':visible' ) ) {
					$( '#wpretail-wrapper .sidebar' ).css( 'display', 'none' );
				} else {
					$( '#wpretail-wrapper .sidebar' ).css( 'display', 'block' );
				}
			} );
		},
	};
	WPRetail.init( jQuery );
} );
