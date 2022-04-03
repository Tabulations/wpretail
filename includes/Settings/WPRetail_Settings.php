<?php

namespace WPRetail\Settings;

/**
 * Core Functions.
 *
 * Contains a bunch of helper methods.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sales Handler class.
 */
class WPRetail_Settings {

	/**
	 * Test Function.
	 */
	public function __construct() {
		add_filter( 'wpretail_settings_options', [ $this, 'options' ] );
		add_action( 'wpretail_view_business_setting', [ $this, 'view_business_setting' ] );
		add_action( 'wpretail_view_location_setting', [ $this, 'view_location_setting' ] );
	}

	/**
	 * Buisness Settinh View.
	 *
	 * @return void
	 */
	public function view_business_setting() {

		$currencies = [];
		foreach ( wpretail()->helper->wpretail_get_currencies() as $key => $currency ) {
			$currencies[ $key ] = $currency['name'];
		}
		$settings = [
			'business_name'             => [
				'label' => [
					'content' => __( 'Business Name' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'business_name',
					'id'   => 'business_name',
				],
			],
			'start_date'                => [
				'label'            => [
					'content' => __( 'Start Date' ) . '*',
				],
				'input'            => [
					'type'  => 'datepicker',
					'name'  => 'start_date',
					'id'    => 'start_date',
					'value' => '04/04/2021',
				],
				'icon'             => 'fa-solid fa-calendar',
				'icon_after_input' => true,
			],
			'profit_percent'            => [
				'label' => [
					'content' => __( 'Default Profit Percent' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'profit_percent',
					'id'   => 'profit_percent',
				],
				'icon'  => 'fa-solid fa-circle-plus',
			],
			'currency'                  => [
				'label' => [
					'content' => __( 'Currency' ) . '*',
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'currency',
					'id'      => 'currency',
					'options' => $currencies,
				],
				'icon'  => 'fa-solid fa-dollar-sign',
			],

			'currency_symbol_placement' => [
				'label' => [
					'content' => __( 'Currency Symbol Placement' ) . '*',
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'currency_symbol_placement',
					'id'      => 'currency_symbol_placement',
					'options' => [
						'before' => __( 'Before amount' ),
						'after'  => __( 'After amount' ),
					],
				],
			],

			'logo'                      => [
				'label' => [
					'content' => __( 'Upload Logo' ) . '*',
				],
				'input' => [
					'type' => 'file',
					'name' => 'logo',
					'id'   => 'logo',
				],
			],

			'financial_month_start'     => [
				'label'            => [
					'content' => __( 'Financial Year Start Month' ) . '*',
				],
				'input'            => [
					'type'    => 'select',
					'name'    => 'financial_month_start',
					'id'      => 'financial_month_start',
					'options' => [
						'1'  => __( 'January' ),
						'2'  => __( 'February' ),
						'3'  => __( 'March' ),
						'4'  => __( 'April' ),
						'5'  => __( 'May' ),
						'6'  => __( 'June' ),
						'7'  => __( 'July' ),
						'8'  => __( 'August' ),
						'9'  => __( 'September' ),
						'10' => __( 'October' ),
						'11' => __( 'November' ),
						'12' => __( 'December' ),
					],
				],
				'icon'             => 'fa-solid fa-calendar',
				'icon_after_input' => true,
			],

			'stock_accounting_method'   => [
				'label' => [
					'content' => __( 'Stock Accounting Method' ) . '*',
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'stock_accounting_method',
					'id'      => 'stock_accounting_method',
					'options' => [
						'fifo' => __( 'FIFO (First In First Out)' ),
						'lifo' => __( 'LIFO (First In Last Out)' ),
					],
				],
				'icon'  => 'fa-solid fa-calculator',
			],

			'transaction_edit_days'     => [
				'label' => [
					'content' => __( 'Trancaction Edit Days' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'transaction_edit_days',
					'id'   => 'transaction_edit_days',
				],
				'icon'  => 'fa-solid fa-pen-to-square',
			],

			'date_format'               => [
				'label' => [
					'content' => __( 'Date Format' ) . '*',
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'date_format',
					'id'      => 'date_format',
					'options' => [
						'd-m-y' => 'dd-mm-yyyy',
						'm-d-y' => 'mm-dd-yyyy',
						'd/m/y' => 'dd/mm/yyyy',
						'm/d/y' => 'mm/dd/yyyy',
					],
				],
				'icon'  => 'fa-solid fa-calculator',
			],

			'time_format'               => [
				'label' => [
					'content' => __( 'Time Format' ) . '*',
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'time_format',
					'id'      => 'time_format',
					'options' => [
						'12' => '12 Hour',
						'24' => '24 Hour',
					],
				],
				'icon'  => 'fa-solid fa-calculator',
			],

		];

		wpretail()->builder->html( 'div', [ 'class' => [ 'container card p-5' ] ] );
		wpretail()->builder->html( 'div', [ 'class' => [ 'row' ] ] );
		foreach ( $settings as $id => $setting ) {
			wpretail()->builder->html( 'div', [ 'class' => [ 'col-md-4' ] ] );
			wpretail()->builder->input( $setting );
			wpretail()->builder->html( 'div' );
		}

		wpretail()->builder->html( 'div' );
		wpretail()->builder->input(
			[
				'input' => [
					'type'    => 'submit',
					'name'    => 'update_business_setting',
					'id'      => 'update_business_setting',
					'content' => __( 'Update Setting' ),
					'class'   => [ 'mt-3 btn btn-primary' ],
				],
			]
		);
		wpretail()->builder->html( 'div' );
	}

	/**
	 * Location Settinh View.
	 *
	 * @return void
	 */
	public function view_location_setting() {
		$currencies = [];
		foreach ( wpretail()->helper->wpretail_get_currencies() as $key => $currency ) {
			$currencies[ $key ] = $currency['name'];
		}
		$settings = [
			'location_id'         => [
				'label' => [
					'content' => __( 'Location ID' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'location_id',
					'id'   => 'location_id',
				],
			],
			'landmark'            => [
				'label' => [
					'content' => __( 'Land Mark' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'landmark',
					'id'   => 'landmark',
				],
			],
			'city'                => [
				'label' => [
					'content' => __( 'City' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'city',
					'id'   => 'city',
				],
			],
			'zipcode'             => [
				'label' => [
					'content' => __( 'Zip Code' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'zipcode',
					'id'   => 'zipcode',
				],
			],

			'state'               => [
				'label' => [
					'content' => __( 'State' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'state',
					'id'   => 'state',
				],
			],

			'country'             => [
				'label' => [
					'content' => __( 'Country' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'country',
					'id'   => 'country',
				],
			],

			'mobile'              => [
				'label' => [
					'content' => __( 'Mobile' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'mobile',
					'id'   => 'mobile',
				],
			],

			'alt_mobile'          => [
				'label' => [
					'content' => __( 'Alertnative Contact Number' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'alt_mobile',
					'id'   => 'alt_mobile',
				],
			],
			'email'               => [
				'label' => [
					'content' => __( 'Email' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'email',
					'id'   => 'email',
				],
			],

			'website'             => [
				'label' => [
					'content' => __( 'Website' ),
				],
				'input' => [
					'type' => 'text',
					'name' => 'website',
					'id'   => 'website',
				],
			],
			'invoice_scheme'      => [
				'label' => [
					'content' => __( 'Invoice Scheme' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'invoice_scheme',
					'id'      => 'invoice_scheme',
					'options' => [
						1 => 'Default',
					],
				],
			],
			'invoice_layout_pos'  => [
				'label' => [
					'content' => __( 'Invoice layout for POS' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'invoice_layout_pos',
					'id'      => 'invoice_layout_pos',
					'options' => [
						1 => 'Default',
					],
				],
			],
			'invoice_layout_sale' => [
				'label' => [
					'content' => __( 'Invoice layout for Sale' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'invoice_layout_sale',
					'id'      => 'invoice_layout_sale',
					'options' => [
						1 => 'Default',
					],
				],
			],
			'selling_price_group' => [
				'label' => [
					'content' => __( 'Default Selling Price Group' ),
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'selling_price_group',
					'id'      => 'selling_price_group',
					'options' => [
						1 => 'Default',
					],
				],
			],
		];

		wpretail()->builder->html( 'div', [ 'class' => [ 'container card p-5' ] ] );
		wpretail()->builder->html( 'div', [ 'class' => [ 'row' ] ] );
		wpretail()->builder->html( 'div', [ 'class' => [ 'col-md-12 mb-3' ] ] );
		wpretail()->builder->html(
			'button',
			[
				'id'      => 'update_business_setting',
				'content' => __( 'Add Location' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type'=> 'button' ],
				'data'    => [
					'bs-toggle'      => 'modal',
					'bs-target' => '#addNewLocation',
				],
			]
		);

		wpretail()->builder->html( 'div' );
		wpretail()->builder->html( 'div', [ 'class' => [ 'col-md-12 mb-3' ] ] );

		wpretail()->builder->table(
			[
				'head'  => [
					__( 'Name', 'wpretail' ),
					__( 'Location ID', 'wpretail' ),
					__( 'Landmark', 'wpretail' ),
					__( 'City', 'wpretail' ),
					__( 'Zipcode', 'wpretail' ),
					__( 'State', 'wpretail' ),
					__( 'Country', 'wpretail' ),
					__( 'Price Group', 'wpretail' ),
					__( 'Invoice Scheme', 'wpretail' ),
					__( 'Invoide Layout for Pos', 'wpretail' ),
					__( 'Invoide Layout for Sale', 'wpretail' ),
					__( 'Action', 'wpretail' ),
				],
				'body'  => [
					[
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
						'test',
					],
				],
				'class' => [ 'wpretail-datatable', 'table table-primary mt-5' ],

			]
		);
		wpretail()->builder->html( 'div' );
		wpretail()->builder->html( 'div' );
		wpretail()->builder->html( 'div' );

		// Modal.

		echo '<div class="modal fade" id="addNewLocation" tabindex="-1" aria-labelledby="addNewLocationLabel" aria-hidden="true">';
		echo '<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">';
		echo '<div class="modal-content">';
		echo '<div class="modal-header">';
		echo '<h5 class="modal-title" id="addNewLocationLabel">' . esc_html__( 'Add New Location', 'wpretail' ) . '</h5>';
		echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
		echo '</div>';
		echo '<div class="modal-body">';
		echo '<div class="container">';
		echo '<div class="row">';
		wpretail()->builder->html( 'div', [ 'class' => [ 'col-md-12 mb-3' ] ] );
		wpretail()->builder->input(
			[
				'label' => [
					'content' => __( 'Business Name' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'business_name',
					'id'   => 'business_name',
				],
			],
		);
		wpretail()->builder->html( 'div' );
		foreach ( $settings as $id => $setting ) {
			wpretail()->builder->html( 'div', [ 'class' => [ 'col-md-6' ] ] );
			wpretail()->builder->input( $setting );
			wpretail()->builder->html( 'div' );
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '<div class="modal-footer">';
		echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
		echo '<button type="button" class="btn btn-primary wpretail-add-location">'.esc_html__( 'Add Location', 'wpretail' ).'</button>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Menu.
	 *
	 * @param mixed $menus Menu.
	 *
	 * @return mixed
	 */
	public function options( $options ) {
		return array_filter(
			array_merge(
				$options,
				[
					'business_setting'     => [
						'name' => 'Business Setting',
						'slug' => 'business_setting',
					],
					'location_setting'     => [
						'name' => 'Location',
						'slug' => 'location_setting',
					],
					'invoice_seting'       => [
						'name' => 'Invoice',
						'slug' => 'invoice_seting',
					],
					'barcode_setting'      => [
						'name' => 'Barcode',
						'slug' => 'barcode_setting',
					],
					'tax_setting'          => [
						'name' => 'Tax',
						'slug' => 'tax_setting',
					],
					'printer_setting'      => [
						'name' => 'Printer',
						'slug' => 'printer_setting',
					],
					'subscription_setting' => [
						'name' => 'Subscription',
						'slug' => 'subscription_setting',
					],
				]
			)
		);
	}
}
