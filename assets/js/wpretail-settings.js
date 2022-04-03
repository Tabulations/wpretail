/* eslint-disable max-len */
/* global wpretailSettingsParams */
jQuery( function ( $ ) {
	'use strict';

	// wpretailSettings_params is required to continue, ensure the object exists.
	if ( typeof wpretailSettingsParams === 'undefined' ) {
		return false;
	}

	var wpretailSettings = {
		$business_setting: $( 'form.wpretail_business_setting' ),
		init: function () {
			// Inline validation.
			this.$business_setting.on( 'submit', function ( e ) {
				e.preventDefault();
				alert( 'hello' );
			} );
		},
	};

	wpretailSettings.init();
} );
