/* eslint-disable max-len */
/* global wpretailSettingsParams */
jQuery( function ( $ ) {
	'use strict';

	// wpretailSettings_params is required to continue, ensure the object exists.
	if ( typeof wpretailSettingsParams === 'undefined' ) {
		return false;
	}

	var wpretailSettings = {
		init: function () {
			var form = $( 'form.wpretail-form' );
			form.each( function ( i, v ) {
				$( document ).ready( function () {
					var formTuple = $( v ),
						btn = formTuple.find( '.wpretail-submit' );

					btn.on( 'click', function ( e ) {
						e.preventDefault();

						formTuple.find( '.wpretail-success-message' ).remove();
						formTuple.find( '.wpretail-error-message' ).remove();

						var formErrors = formTuple.find(
							'.invalid-feedback:visible'
						);

						if ( formErrors.length > 0 ) {
							$( [
								document.documentElement,
								document.body,
							] ).animate(
								{
									scrollTop: formErrors.last().offset().top,
								},
								800
							);
							return;
						}

						var formId = formTuple.attr( 'id' ),
							data = formTuple.serializeArray();

						formTuple.attr( 'submit-text', btn.html() );

						// Change the text to user defined property.
						$( this ).html(
							undefined !== formTuple.data( 'process-text' )
								? formTuple.data( 'process-text' )
								: 'Processing'
						);

						formTuple
							.find( '.invalid-feedback' )
							.css( 'display', 'none' );

						// Add action intend for ajax_form_submission endpoint.
						data.push( {
							name: 'action',
							value: 'wpretail_ajax_form_submission',
						} );

						data.push( {
							name: 'wpretail_nonce',
							value: wpretailSettingsParams.nonce,
						} );

						data.push( {
							name: 'form_id',
							value: formId,
						} );

						// Fire the ajax request.
						$.ajax( {
							url: wpretailSettingsParams.ajax_url,
							type: 'POST',
							data: data,
						} )
							.done( function ( xhr, textStatus, errorThrown ) {
								if ( true === xhr.success ) {
									var message = xhr.data.success.message;
									if ( undefined !== message ) {
										$(
											'<p class="wpretail-success-message alert alert-success">' +
												message +
												'</p>'
										).insertAfter(
											formTuple.find( '.form-title' )
										);
									}
								} else {
									var err = JSON.parse(
										errorThrown.responseText
									);
									var errors = err.data.errors;
									if ( undefined !== errors.message ) {
										$(
											'<p class="wpretail-error-message alert alert-danger">' +
												errors.message +
												'</p>'
										).insertAfter(
											formTuple.find( '.form-title' )
										);
									}
									$.each( errors, function ( index, value ) {
										var output =
											'<ul class="wpretail-errors">';
										if (
											false === $.isEmptyObject( value )
										) {
											$.each( value, function (
												vindex,
												vvaluue
											) {
												output +=
													'<li>' + vvaluue + '</li>';
											} );
										}
										output += '</ul>';
										formTuple
											.find( '#' + formId + '_' + index )
											.closest( '.wpretail-field' )
											.find( '.invalid-feedback' )
											.html( output )
											.css( 'display', 'block' );
									} );
								}
							} )
							.fail( function () {
								btn.attr( 'disabled', false ).html( 'Submit' );
								formTuple
									.trigger( 'focusout' )
									.trigger( 'change' );
							} )
							.always( function ( xhr ) {
								var redirectUrl =
									xhr.data && xhr.data.redirect_url
										? xhr.data.redirect_url
										: '';

								btn.html( formTuple.attr( 'submit-text' ) );
								if (
									! redirectUrl &&
									$( '.wpretail-error-message' ).length
								) {
									$( [
										document.documentElement,
										document.body,
									] ).animate(
										{
											scrollTop: $(
												'.wpretail-error-message'
											).offset().top,
										},
										800
									);
								} else if (
									! redirectUrl &&
									$( '.wpretail-success-message' ).length
								) {
									$( [
										document.documentElement,
										document.body,
									] ).animate(
										{
											scrollTop: $(
												'.wpretail-success-message'
											).offset().top,
										},
										800
									);
								}
							} );
					} );
				} );
			} );
		},
	};
	wpretailSettings.init();
} );
