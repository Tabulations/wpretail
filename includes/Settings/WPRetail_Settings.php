<?php

namespace WPRetail\Settings;

use Exception;
use WPRetail;

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

		// Settings Menu Options.
		add_filter( 'wpretail_settings_options', [ $this, 'options' ] );
		// UI Field Options.
		add_filter( 'wpretail_form_fields_options', [ $this, 'form_fields_option' ] );

		// UI.
		add_action( 'wpretail_view_business_setting', [ $this, 'view_business_setting' ] );
		add_action( 'wpretail_view_location_setting', [ $this, 'view_location_setting' ] );

		// DB Handler.
		add_action( 'wpretail_business_settings_handler', [ $this, 'business_setting_handler' ] );
	}

	/**
	 * Business setting handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function business_setting_handler( $ajax ) {
		$fields = [
			'name'                    => $ajax->sanitized_fields['business_name'],
			'currency_id'             => $ajax->sanitized_fields['currency'],
			'start_date'              => gmdate( 'Y-m-d H:i:s', strtotime( $ajax->sanitized_fields['start_date'] ) ),
			'tax_number_1'            => '1010212010',
			'tax_number_2'            => '',
			'tax_label_1'             => 'PAN',
			'tax_label_2'             => '',
			'default_profit_percent'  => $ajax->sanitized_fields['profit_percent'],
			'owner_id'                => 1,
			'fiscal_year_start_month' => $ajax->sanitized_fields['financial_month_start'],
			'accounting_method'       => $ajax->sanitized_fields['stock_accounting_method'],
			'default_sale_discount'   => (float) $ajax->sanitized_fields['default_sales_discount'],
			'logo'                    => '',
			'sku_prefix'              => '',
			'enable_tooltip'          => 0,
		];

		$where = [ 'id' => 1 ]; // Business ID is always 1.

		$db = new WPRetail\Db\WPRetail_Db( 'wpretail_business' );

		try {
			$id = $db->update( $fields, $where );
			if ( $id ) {
				$ajax->success['message'] = 'Business Updated Successfully';
				$ajax->success['id']      = $id;
			} else {
				$ajax->errors['message'] = 'Business Could not be Updated';
			}
		} catch ( Exception $e ) {
			$ajax->errors['message'] = $e->getMessage();
		}
	}

	/**
	 * Menu.
	 *
	 * @param mixed $options Menu Options.
	 *
	 * @return mixed $options Options.
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

	/**
	 * Fields Options.
	 *
	 * @param mixed $field_options Field Options.
	 * @return mixed $field_options Field Options.
	 */
	public function form_fields_option( $field_options = [] ) {
		$currencies = [];
		foreach ( wpretail()->helper->wpretail_get_currencies() as $key => $currency ) {
			$currencies[ $key ] = $currency['name'];
		}
		$business_settings = [
			'business_name'             => [
				'label'       => [
					'content' => __( 'Business Name', 'wpretail' ) . '*',
				],
				'input'       => [
					'type' => 'text',
					'name' => 'business_name',
					'id'   => 'business_name',
				],
				'col'         => 'col-md-4',
				'validations' => [ 'required', 'min:5', 'max:15' ],
			],
			'start_date'                => [
				'label'            => [
					'content' => __( 'Start Date', 'wpretail' ) . '*',
				],
				'input'            => [
					'type'  => 'datepicker',
					'name'  => 'start_date',
					'id'    => 'start_date',
					'value' => '04/04/2021',
				],
				'icon'             => 'fa-solid fa-calendar',
				'icon_after_input' => true,
				'col'              => 'col-md-4',
				'validations'      => [ 'required' ],
			],
			'profit_percent'            => [
				'label'       => [
					'content' => __( 'Default Profit Percent', 'wpretail' ) . '*',
				],
				'input'       => [
					'type' => 'text',
					'name' => 'profit_percent',
					'id'   => 'profit_percent',
				],
				'icon'        => 'fa-solid fa-circle-plus',
				'col'         => 'col-md-4',
				'validations' => [ 'required', 'number' ],
			],
			'currency'                  => [
				'label'       => [
					'content' => __( 'Currency', 'wpretail' ) . '*',
				],
				'input'       => [
					'type'    => 'select',
					'name'    => 'currency',
					'id'      => 'currency',
					'options' => $currencies,
					'has_key' => true,
				],
				'icon'        => 'fa-solid fa-dollar-sign',
				'col'         => 'col-md-4',
				'validations' => [ 'required', 'min:3', 'max:3' ],
			],

			'currency_symbol_placement' => [
				'label'       => [
					'content' => __( 'Currency Symbol Placement', 'wpretail' ) . '*',
				],
				'input'       => [
					'type'    => 'select',
					'name'    => 'currency_symbol_placement',
					'id'      => 'currency_symbol_placement',
					'options' => [
						'before' => __( 'Before amount', 'wpretail' ),
						'after'  => __( 'After amount', 'wpretail' ),
					],
					'has_key' => true,
				],
				'col'         => 'col-md-4',
				'validations' => [ 'required' ],
			],

			'logo'                      => [
				'label' => [
					'content' => __( 'Upload Logo', 'wpretail' ),
				],
				'input' => [
					'type' => 'file',
					'name' => 'logo',
					'id'   => 'logo',
				],
				'col'   => 'col-md-4',
			],

			'financial_month_start'     => [
				'label'            => [
					'content' => __( 'Financial Year Start Month', 'wpretail' ) . '*',
				],
				'input'            => [
					'type'    => 'select',
					'name'    => 'financial_month_start',
					'id'      => 'financial_month_start',
					'options' => [
						'1'  => __( 'January', 'wpretail' ),
						'2'  => __( 'February', 'wpretail' ),
						'3'  => __( 'March', 'wpretail' ),
						'4'  => __( 'April', 'wpretail' ),
						'5'  => __( 'May', 'wpretail' ),
						'6'  => __( 'June', 'wpretail' ),
						'7'  => __( 'July', 'wpretail' ),
						'8'  => __( 'August', 'wpretail' ),
						'9'  => __( 'September', 'wpretail' ),
						'10' => __( 'October', 'wpretail' ),
						'11' => __( 'November', 'wpretail' ),
						'12' => __( 'December', 'wpretail' ),
					],
					'has_key' => true,
				],
				'icon'             => 'fa-solid fa-calendar',
				'icon_after_input' => true,
				'col'              => 'col-md-4',
			],

			'stock_accounting_method'   => [
				'label'       => [
					'content' => __( 'Stock Accounting Method', 'wpretail' ) . '*',
				],
				'input'       => [
					'type'    => 'select',
					'name'    => 'stock_accounting_method',
					'id'      => 'stock_accounting_method',
					'options' => [
						'fifo' => __( 'FIFO (First In First Out)', 'wpretail' ),
						'lifo' => __( 'LIFO (First In Last Out)', 'wpretail' ),
					],
					'has_key' => true,
				],
				'icon'        => 'fa-solid fa-calculator',
				'col'         => 'col-md-4',
				'validations' => [ 'required' ],
			],

			'default_sales_discount'    => [
				'label'       => [
					'content' => __( 'Default Sales Discount', 'wpretail' ),
				],
				'input'       => [
					'type' => 'text',
					'name' => 'default_sales_discount',
					'id'   => 'default_sales_discount',
				],
				'icon'        => 'fa-solid fa-pen-to-square',
				'col'         => 'col-md-4',
				'validations' => [ 'number' ],
			],

			'transaction_edit_days'     => [
				'label'       => [
					'content' => __( 'Trancaction Edit Days', 'wpretail' ) . '*',
				],
				'input'       => [
					'type' => 'text',
					'name' => 'transaction_edit_days',
					'id'   => 'transaction_edit_days',
				],
				'icon'        => 'fa-solid fa-pen-to-square',
				'col'         => 'col-md-4',
				'validations' => [ 'required', 'number' ],
			],

			'date_format'               => [
				'label'       => [
					'content' => __( 'Date Format', 'wpretail' ) . '*',
				],
				'input'       => [
					'type'    => 'select',
					'name'    => 'date_format',
					'id'      => 'date_format',
					'options' => [
						'd-m-y' => 'dd-mm-yyyy',
						'm-d-y' => 'mm-dd-yyyy',
						'd/m/y' => 'dd/mm/yyyy',
						'm/d/y' => 'mm/dd/yyyy',
					],
					'has_key' => true,
				],
				'icon'        => 'fa-solid fa-calculator',
				'col'         => 'col-md-4',
				'validations' => [ 'required' ],
			],

			'time_format'               => [
				'label'       => [
					'content' => __( 'Time Format', 'wpretail' ) . '*',
				],
				'input'       => [
					'type'    => 'select',
					'name'    => 'time_format',
					'id'      => 'time_format',
					'options' => [
						'12' => '12 Hour',
						'24' => '24 Hour',
					],
					'has_key' => true,
				],
				'icon'        => 'fa-solid fa-calculator',
				'col'         => 'col-md-4',
				'validations' => [ 'required' ],
			],

		];
		$location_settings = [
			'location_id'         => [
				'label' => [
					'content' => __( 'Location ID', 'wpretail' ) . '*',
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
					'content' => __( 'Land Mark', 'wpretail' ) . '*',
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
					'content' => __( 'City', 'wpretail' ) . '*',
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
					'content' => __( 'Zip Code', 'wpretail' ) . '*',
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
					'content' => __( 'State', 'wpretail' ) . '*',
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
					'content' => __( 'Country', 'wpretail' ) . '*',
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
					'content' => __( 'Mobile', 'wpretail' ),
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
					'content' => __( 'Alertnative Contact Number', 'wpretail' ),
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
					'content' => __( 'Email', 'wpretail' ),
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
					'content' => __( 'Website', 'wpretail' ),
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
					'content' => __( 'Invoice Scheme', 'wpretail' ),
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
					'content' => __( 'Invoice layout for POS', 'wpretail' ),
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
					'content' => __( 'Invoice layout for Sale', 'wpretail' ),
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
					'content' => __( 'Default Selling Price Group', 'wpretail' ),
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
				[
					'business_settings'         => $business_settings,
					'business_location_setting' => $location_settings,
				]
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

		$settings = $field_options['business_settings'];

		$args = [
			'form_args'  => [
				'id'                => 'wpretail_business_settings',
				'class'             => [ 'wpretail-business-settings' ],
				'attr'              => [
					'action' => admin_url(),
					'method' => 'post',
				],
				'form_title'        => __( 'Update Business Information', 'wpretail' ),
				'form_submit_id'    => 'wpretail_update_business',
				'form_submit_label' => __( 'Update Business', 'wpretail' ),
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

		$settings = $field_options['business_location_setting'];
		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_location',
				'content' => __( 'Add Location', 'wpretail' ),
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
				'form_title'        => __( 'Add Business Location', 'wpretail' ),
				'form_submit_id'    => 'wpretail_add_location',
				'form_submit_label' => __( 'Add Location', 'wpretail' ),
				'is_modal'          => true,
				'modal'             => 'modal-lg modal-dialog-centered modal-dialog-scrollable',
			],
			'input_args' => $settings,
		];

		wpretail()->builder->form( $args );
	}
}
