/*global wpretailParams*/
jQuery( function ( $ ) {
	'use strict';

	var wpretail = {
		init: function () {
			wpretail.buildUiActions();
		},
		buildUiActions: function () {
			$( document ).ready( function () {
				// Sidebar navigation.
				$( '.wpretail-sidebar-nav ul.sidebar-menu > li > a' ).click(
					function () {
						if ( $( this ).hasClass( 'collasped' ) ) {
							$( this ).siblings( 'ul' ).slideDown( 'slide' );
							$( this ).removeClass( 'collasped' );
						} else {
							$( this ).siblings( 'ul' ).slideUp( 'slide' );
							$( this ).addClass( 'collasped' );
						}
					}
				);

				var wpretailPables = $( 'table.wpretail-datatable' );
				wpretailPables.each( function ( i, v ) {
					var wpretailTable = $( v );
					var columns = [
						{
							className: 'dt-control',
							orderable: false,
							data: null,
							defaultContent: '',
						},
					];
					wpretailTable.find( 'thead th' ).each( function () {
						if ( undefined !== $( this ).data( 'target' ) ) {
							columns.push( {
								data: $( this ).data( 'target' ),
							} );
						}
					} );

					var data = [ { nonce: wpretailParams.nonce } ];
					var table = wpretailTable.DataTable( {
						processing: true,
						serverSide: true,
						ajax: {
							url: wpretailParams.ajax_url,
							type: 'post',
							data: data,
						},
						columns: columns,
						order: [ [ 1, 'asc' ] ],
					} );

					// Add event listener for opening and closing details
					$( 'table.wpretail-datatable' ).on(
						'click',
						'td.dt-control',
						function () {
							var tr = $( this ).closest( 'tr' );
							var row = table.row( tr );

							if ( row.child.isShown() ) {
								// This row is already open - close it
								row.child.hide();
								tr.removeClass( 'shown' );
							} else {
								// Open this row
								row.child( format( row.data() ) ).show();
								tr.addClass( 'shown' );
							}
						}
					);

					/* Formatting function for row details - modify as you need */
					function format( d ) {
						// `d` is the original data object for the row
						return (
							'<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
							'<tr>' +
							'<td>Full name:</td>' +
							'<td>' +
							d.name +
							'</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Extension number:</td>' +
							'<td>' +
							d.extn +
							'</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Extra info:</td>' +
							'<td>And any further details here (images etc)...</td>' +
							'</tr>' +
							'</table>'
						);
					}
				} );
			} );
		},
	};
	wpretail.init();
} );
