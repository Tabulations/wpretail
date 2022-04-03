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

		add_filter( 'wpretail_form_fields_options', [ $this, 'form_fields_option' ] );
	}

	/**
	 * Fields Options
	 *
	 * @param mixed $field_options
	 * @return void
	 */
	public function form_fields_option( $field_options ) {
		$currencies = [];
		foreach ( wpretail()->helper->wpretail_get_currencies() as $key => $currency ) {
			$currencies[ $key ] = $currency['name'];
		}
		$business_settings = [
			'business_name'             => [
				'label' => [
					'content' => __( 'Business Name' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'business_name',
					'id'   => 'business_name',
				],
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
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
				'col' => 'col-md-4'
			],

		];
		$location_settings = [
			'location_id'         => [
				'label' => [
					'content' => __( 'Location ID' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'location_id',
					'id'   => 'location_id',
				],
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
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
				'col'   => 'col-md-6',
			],
		];

		return array_filter(
			array_merge(
				$field_options,
				[ 'business_settings' => $business_settings, 'business_location_setting' => $location_settings,  ]
			)
		);
	}

	/**
	 * Buisness Settinh View.
	 *
	 * @return void
	 */
	public function view_business_setting() {

		$field_options = apply_filters( 'wpretail_form_fields_options', [] );

		$settings =  $field_options['business_settings'];

		$args = [
			'form_args'  => [
				'id'                => 'wpretail-business-setting',
				'class'             => [ 'wpretail-business-setting' ],
				'attr'              => [
					'action' => admin_url(),
					'method' => 'post',
				],
				'form_title'        => __( 'Add Business Location', 'wpreatil' ),
				'form_submit_id'    => 'wpretail_add_location',
				'form_submit_label' => __( 'Add Location', 'wpretail' ),
			],
			'input_args' => $settings,
		];

		wpretail()->builder->form( $args );
	}

	/**
	 * Location Settinh View.
	 *
	 * @return void
	 */
	public function view_location_setting() {
		$field_options = apply_filters( 'wpretail_form_fields_options', [] );

		$settings =  $field_options['business_location_setting'];
		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_location',
				'content' => __( 'Add Location' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail-location-setting',
				],
			]
		);

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
				'col'   => 'col-md-12',

			]
		);

		$args = [
			'form_args'  => [
				'id'                => 'wpretail-location-setting',
				'class'             => [ 'wpretail-location-setting card' ],
				'attr'              => [
					'action' => admin_url(),
					'method' => 'post',
				],
				'form_title'        => __( 'Add Business Location', 'wpreatil' ),
				'form_submit_id'    => 'wpretail_add_location',
				'form_submit_label' => __( 'Add Location', 'wpretail' ),
				'is_modal'          => true,
				'modal'             => 'modal-lg modal-dialog-centered modal-dialog-scrollable',
			],
			'input_args' => $settings,
		];

		wpretail()->builder->form( $args );
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
