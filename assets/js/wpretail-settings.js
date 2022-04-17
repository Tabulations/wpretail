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
				var formTuple = $( v ),
					btn = formTuple.find( '.wpretail-submit' );

				btn.on( 'click', function ( e ) {
					e.preventDefault();

					$( document.body ).append(
						'<div class="modal-backdrop wpretail-backdrop fade show"></div>'
					);

					formTuple.find( '.wpretail-success-message' ).remove();
					formTuple.find( '.wpretail-error-message' ).remove();

					formTuple
						.find( '.invalid-feedback' )
						.css( 'display', 'none' );

					var formId = formTuple.attr( 'id' ),
						data = formTuple.serializeArray();

					formTuple.attr( 'submit-text', btn.html() );

					// Change the text to user defined property.
					$( this ).html(
						undefined !== formTuple.data( 'process-text' )
							? formTuple.data( 'process-text' )
							: 'Processing'
					);

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
									var listId = formId.replace(
										'wpretail_',
										'wpretail_list_'
									);

									if (
										$( document ).has( '#' + listId ).length
									) {
										if (
											undefined !==
											xhr.data.success.updated
										) {
											var row = $( document )
												.find( '#' + listId + ' tr' )
												.has(
													'button[data-id="' +
														xhr.data.success.id +
														'"]'
												);

											if ( undefined !== row ) {
												$( document )
													.find(
														'#' + listId + ' th'
													)
													.each( function () {
														var index = $(
															this
														).index();
														var target = $(
															this
														).data( 'target' );
														row.find( 'td' )
															.eq( index )
															.html(
																xhr.data.success
																	.updated[
																	target
																]
															);
													} );
											}
										} else {
											var lastRow = $( document ).find(
												'#' + listId + ' tr:last'
											);
											if ( undefined !== lastRow ) {
												if ( lastRow.has( 'th' ) ) {
													lastRow.html(
														$(
															lastRow
																.html()
																.replace(
																	'th',
																	'td'
																)
														)
													);
												}
												lastRow
													.find(
														'td:not(:last-child)'
													)
													.html( '' );
												lastRow
													.find( 'td:last' )
													.find( 'button' )
													.attr(
														'data-id',
														xhr.data.success.id
													);
												$( document )
													.has( '#' + listId )
													.find( 'th' )
													.each( function () {
														var index = $(
															this
														).index();
														var target = $(
															this
														).data( 'target' );
														lastRow
															.find( 'td' )
															.eq( index )
															.html(
																xhr.data.success
																	.inserted[
																	target
																]
															);
													} );

												$( document )
													.find(
														'#' + listId + ' tbody'
													)
													.append( lastRow );
											}
										}
									}

									$( document )
										.find( '.wpretail-modal' )
										.modal( 'hide' );

									$.alert( {
										title: '',
										type: 'green',
										content:
											'<p class="wpretail-error-message alert alert-success">' +
											message +
											'</p>',
										autoClose: 'close|3000',
										buttons: {
											close: {
												text: 'Close Me',
												isHidden: true,
											},
										},
									} );
								}
							} else {
								var err = JSON.parse(
									errorThrown.responseText
								);
								var errors = err.data.errors;
								if ( undefined !== errors.message ) {
									$.alert( {
										title: '',
										type: 'red',
										content:
											'<p class="wpretail-error-message alert alert-danger">' +
											errors.message +
											'</p>',
										autoClose: 'close|3000',
										buttons: {
											close: {
												text: 'Close Me',
												isHidden: true,
											},
										},
									} );
								}
								$.each( errors, function ( index, value ) {
									var output = '<ul class="wpretail-errors">';
									for ( var vvalue in value ) {
										output +=
											'<li>' + value[ vvalue ] + '</li>';
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
							formTuple.trigger( 'focusout' ).trigger( 'change' );
						} )
						.always( function ( xhr ) {
							$( document )
								.find( '.modal-backdrop.wpretail-backdrop' )
								.remove();

							var redirectUrl =
								xhr.data && xhr.data.redirect_url
									? xhr.data.redirect_url
									: '';

							btn.html( formTuple.attr( 'submit-text' ) );
							if (
								! redirectUrl &&
								$( document ).find( '.wpretail-error-message' )
									.length > 0
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

			wpretailSettings.buildUiActions();
		},
		buildUiActions: function () {
			$( document ).on( 'click', '.wpretail-table-delete', function () {
				$( document.body ).append(
					'<div class="modal-backdrop wpretail-backdrop fade show"></div>'
				);
				var $el = $( this );
				$.confirm( {
					title: 'Confirm!',
					content:
						undefined !== $( this ).attr( 'confirm' )
							? $( this ).attr( 'confirm' )
							: 'Are you sure you want to remove?',
					buttons: {
						confirm: function () {
							var data = [];

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
								value: $el.closest( 'table' ).attr( 'id' ),
							} );

							data.push( {
								name: 'id',
								value: $el.data( 'id' ),
							} );

							data.push( {
								name: 'event',
								value: 'delete',
							} );
							// Fire the ajax request.
							$.ajax( {
								url: wpretailSettingsParams.ajax_url,
								type: 'POST',
								data: data,
							} )
								.done( function (
									xhr,
									textStatus,
									errorThrown
								) {
									if ( true === xhr.success ) {
										var message = xhr.data.success.message;
										if ( undefined !== message ) {
											$.alert( message );
										}
										$el.closest( 'tr' ).remove();
									} else {
										var err = JSON.parse(
											errorThrown.responseText
										);
										var errors = err.data.errors;
										if ( undefined !== errors.message ) {
											$.alert( errors.message );
										}
									}
								} )
								.fail( function () {} )
								.always( function () {
									$( document )
										.find(
											'.modal-backdrop.wpretail-backdrop'
										)
										.remove();
								} );
						},
						cancel: {
							function() {
								$( document )
									.find( '.modal-backdrop.wpretail-backdrop' )
									.remove();
							},
						},
					},
				} );
			} );

			$( document ).on( 'click', '.wpretail-table-edit', function () {
				$( document.body ).append(
					'<div class="modal-backdrop wpretail-backdrop fade show"></div>'
				);
				var $el = $( this ),
					data = [],
					formId = $el
						.closest( 'table' )
						.attr( 'id' )
						.replace( 'list_', '' );

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
					value: $el.closest( 'table' ).attr( 'id' ),
				} );

				data.push( {
					name: 'id',
					value: $el.data( 'id' ),
				} );

				data.push( {
					name: 'event',
					value: 'edit',
				} );

				// Fire the ajax request.
				$.ajax( {
					url: wpretailSettingsParams.ajax_url,
					type: 'POST',
					data: data,
				} )
					.done( function ( xhr, textStatus, errorThrown ) {
						if ( true === xhr.success ) {
							var location = xhr.data.success.location;
							if ( undefined !== $( '#' + formId ) ) {
								$( '#' + formId )
									.find( 'input, select, textarea' )
									.each( function () {
										var $input = $( this );
										var name = $input
											.attr( 'name' )
											.replace( /\]$/, '' )
											.replace( /.*\[/, '' );
										switch ( $input.attr( 'type' ) ) {
											case 'text':
												if (
													Object.keys(
														location
													).includes( name )
												) {
													$input.val(
														location[ name ]
													);
												}
												break;
											case 'textarea':
												if (
													Object.keys(
														location
													).includes( name )
												) {
													$input.html(
														location[ name ]
													);
												}
												break;
											case 'select':
												if (
													Object.keys(
														location
													).includes( name )
												) {
													$input
														.find( 'option' )
														.each( function () {
															if (
																location[
																	name
																].includes(
																	$(
																		this
																	).val()
																)
															) {
																$( this ).prop(
																	'selected',
																	true
																);
															}
														} );
												}
												break;
										}
									} );

								$( '#' + formId ).prepend(
									'<input type="hidden" name="id" value="' +
										$el.data( 'id' ) +
										'">'
								);

								$( '#' + formId + '_modal' )
									.find( '.modal-title' )
									.html( xhr.data.success.message );
								$(
									'[data-bs-target="#' + formId + '_modal"]'
								).trigger( 'click' );
							}
						} else {
							var err = JSON.parse( errorThrown.responseText );
							var errors = err.data.errors;
							if ( undefined !== errors.message ) {
								$.alert( errors.message );
							}
						}
					} )
					.fail( function () {} )
					.always( function () {
						$( document )
							.find( '.modal-backdrop.wpretail-backdrop' )
							.remove();
					} );
			} );

			$( document ).on( 'hide.bs.modal', '.modal', function () {
				if ( undefined !== $( this ).find( 'form.wpretail-form' ) ) {
					$( this )
						.find( 'form.wpretail-form' )
						.find( 'input[name="id"]' )
						.remove();
					$( this ).find( 'form.wpretail-form' ).trigger( 'reset' );
				}
			} );
		},
	};
	wpretailSettings.init();
} );
