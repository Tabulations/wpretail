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
		add_action( 'wpretail_view_tax_setting', [ $this, 'view_tax_setting' ] );

		// DB Handler.
		// Business.
		add_action( 'wpretail_business_settings_handler', [ $this, 'business_setting_handler' ] );
		add_action( 'wpretail_list_busiless_setting_handler', [ $this, 'list_business_setting_handler' ] );

		// Locations.
		add_action( 'wpretail_location_setting_handler', [ $this, 'location_setting_handler' ] );
		add_action( 'wpretail_list_location_setting_handler', [ $this, 'list_location_setting_handler' ] );

		// Tax Rates.
		add_action( 'wpretail_tax_rate_setting_handler', [ $this, 'tax_rate_setting_handler' ] );
		add_action( 'wpretail_list_tax_rate_setting_handler', [ $this, 'list_tax_rate_setting_handler' ] );

		// Tax Groups.
		add_action( 'wpretail_tax_group_setting_handler', [ $this, 'tax_group_setting_handler' ] );
		add_action( 'wpretail_list_tax_group_setting_handler', [ $this, 'list_tax_group_setting_handler' ] );

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
	 * List Business Setting Handler.
	 *
	 * @param mixed $ajax Ajax Object.
	 * @return void
	 */
	public function list_business_setting_handler( $ajax ) {
		if ( ! empty( $ajax->event ) ) {
			if ( ! empty( $ajax->event['action'] ) && ! empty( $ajax->event['id'] ) && 'delete' === $ajax->event['action'] ) {
				$db    = new WPRetail\Db\WPRetail_Db( 'wpretail_business' );
				$where = [ 'id' => $ajax->event['id'] ]; // Business ID is always 1.
				try {
					$id = $db->update( [ 'status' => false ], $where );
					if ( $id ) {
						$ajax->success['message'] = __( 'Business Removed Successsfully', 'wpretail' );
					} else {
						$ajax->errors['message'] = __( 'Business Removed Successsfully', 'wpretail' );
					}
				} catch ( \Exception $e ) {
					$ajax->errors['message'] = $e->getMessage();
				}
			} else {
				$ajax->errors['message'] = __( 'Object not found, Please try again', 'wpretail' );
			}
		} else {
			$ajax->errors['message'] = __( 'Event not found, Please try again', 'wpretail' );
		}
	}

	/**
	 * List Business Setting Handler.
	 *
	 * @param mixed $ajax Ajax Object.
	 * @return void
	 */
	public function list_location_setting_handler( $ajax ) {
		if ( ! empty( $ajax->event ) ) {
			if ( ! empty( $ajax->event['action'] ) ) {
				switch ( $ajax->event['action'] ) {
					case 'delete':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db    = new WPRetail\Db\WPRetail_Db( 'wpretail_business_location' );
							$where = [ 'id' => $ajax->event['id'] ]; // Business ID is always 1.
							try {
								$id = $db->update( [ 'status' => false ], $where );
								if ( $id ) {
									$ajax->success['message'] = __( 'Location removed successsfully', 'wpretail' );
								} else {
									$ajax->errors['message'] = __( 'Location could not be removed, Please repload the page and try again', 'wpretail' );
								}
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						} else {
							$ajax->errors['message'] = __( 'Id not found, please reload the page and try again', 'wpretail' );
						}
						break;
					case 'edit':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db = new WPRetail\Db\WPRetail_Db( 'wpretail_business_location' );
							try {
								$location                  = $db->get_location( $ajax->event['id'] );
								$fields                    = [
									'business_id'         => '1', // Always.
									'location_id'         => $location['location_id'],
									'landmark'            => $location['landmark'],
									'country'             => $location['country'],
									'state'               => $location['state'],
									'city'                => $location['city'],
									'zipcode'             => $location['zip_code'],
									'mobile'              => $location['mobile'],
									'alt_mobile'          => $location['alternate_number'],
									'email'               => $location['email'],
									'website'             => $location['website'],
									'invoice_scheme'      => $location['invoice_scheme'],
									'invoice_layout_pos'  => $location['invoice_layout_pos'],
									'invoice_layout_sale' => $location['invoice_layout_sale'],
									'selling_price_group' => $location['selling_price_group'],
								];
								$ajax->success['message']  = __( 'Update Location', 'wpretail' );
								$ajax->success['location'] = $fields;
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						}
						break;
					default:
						$ajax->errors['message'] = __( 'Object not found, Please try again', 'wpretail' );
				}
			} else {
				$ajax->errors['message'] = __( 'Event not found, Please try again', 'wpretail' );
			}
		}
	}

	/**
	 * Business setting handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function location_setting_handler( $ajax ) {
		$fields = [
			'business_id'         => '1', // Always.
			'status'              => '1', // Always.
			'location_id'         => $ajax->sanitized_fields['location_id'],
			'landmark'            => $ajax->sanitized_fields['landmark'],
			'country'             => $ajax->sanitized_fields['country'],
			'state'               => $ajax->sanitized_fields['state'],
			'city'                => $ajax->sanitized_fields['city'],
			'zip_code'            => $ajax->sanitized_fields['zipcode'],
			'mobile'              => $ajax->sanitized_fields['mobile'],
			'alternate_number'    => $ajax->sanitized_fields['alt_mobile'],
			'email'               => $ajax->sanitized_fields['email'],
			'website'             => $ajax->sanitized_fields['website'],
			'invoice_scheme'      => $ajax->sanitized_fields['invoice_scheme'],
			'invoice_layout_pos'  => $ajax->sanitized_fields['invoice_layout_pos'],
			'invoice_layout_sale' => $ajax->sanitized_fields['invoice_layout_sale'],
			'selling_price_group' => $ajax->sanitized_fields['selling_price_group'],
		];

		$db = new WPRetail\Db\WPRetail_Db( 'wpretail_business_location' );

		try {
			if ( ! empty( $ajax->event['id'] ) ) {
				$where = [ 'id' => $ajax->event['id'] ];
				$id    = $db->update( $fields, $where );
				if ( $id ) {
					$ajax->success['message'] = __( 'Location updated successfully', 'wpretail' );
					$ajax->success['id']      = $ajax->event['id'];
					$formatted_fields         = [
						'business_id'      => '1', // Always.
						'status'           => '1', // Always.
						'location_id'      => $fields['location_id'],
						'landmark'         => $fields['landmark'],
						'country'          => $fields['country'],
						'state'            => $fields['state'],
						'city'             => $fields['city'],
						'zipcode'          => $fields['zip_code'],
						'mobile'           => $fields['mobile'],
						'alternate_number' => $fields['alt_mobile'],
						'email'            => $fields['email'],
						'website'          => $fields['website'],
					];
					$ajax->success['updated'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = __( 'Location Could not be updated', 'wpretail' );
				}
				return;
			}
			$id = $db->insert( $fields );
			if ( $id ) {
				$ajax->success['message']  = __( 'Location added successfully', 'wpretail' );
				$ajax->success['id']       = $db->get_last_insert_id();
				$ajax->success['inserted'] = $fields;
			} else {
				$ajax->errors['message'] = __( 'Location Could not be added', 'wpretail' );
			}
		} catch ( Exception $e ) {
			$ajax->errors['message'] = $e->getMessage();
		}
	}

	/**
	 * List Tax Rate Setting Handler.
	 *
	 * @param mixed $ajax Ajax Object.
	 * @return void
	 */
	public function list_tax_rate_setting_handler( $ajax ) {
		if ( ! empty( $ajax->event ) ) {
			if ( ! empty( $ajax->event['action'] ) ) {
				switch ( $ajax->event['action'] ) {
					case 'delete':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db    = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_rates' );
							$where = [ 'id' => $ajax->event['id'] ]; // Business ID is always 1.
							try {
								$id = $db->update( [ 'status' => false ], $where );
								if ( $id ) {
									$ajax->success['message'] = __( 'Tax removed successsfully', 'wpretail' );
								} else {
									$ajax->errors['message'] = __( 'Tax could not be removed, Please repload the page and try again', 'wpretail' );
								}
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						} else {
							$ajax->errors['message'] = __( 'Id not found, please reload the page and try again', 'wpretail' );
						}
						break;
					case 'edit':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_rates' );
							try {
								$tax                       = $db->get_tax_rates( $ajax->event['id'] );
								$fields                    = [
									'tax_name'  => $tax['name'],
									'tax_rate'  => $tax['rate'],
									'for_group' => $tax['for_group'],
								];
								$ajax->success['message']  = __( 'Update Tax', 'wpretail' );
								$ajax->success['location'] = $fields;
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						}
						break;
					default:
						$ajax->errors['message'] = __( 'Object not found, Please try again', 'wpretail' );
				}
			} else {
				$ajax->errors['message'] = __( 'Event not found, Please try again', 'wpretail' );
			}
		}
	}

	/**
	 * List Tax Rate Setting Handler.
	 *
	 * @param mixed $ajax Ajax Object.
	 * @return void
	 */
	public function list_tax_group_setting_handler( $ajax ) {
		if ( ! empty( $ajax->event ) ) {
			if ( ! empty( $ajax->event['action'] ) ) {
				switch ( $ajax->event['action'] ) {
					case 'delete':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db    = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_groups' );
							$where = [ 'id' => $ajax->event['id'] ]; // Business ID is always 1.
							try {
								$id = $db->update( [ 'status' => false ], $where );
								if ( $id ) {
									$ajax->success['message'] = __( 'Tax Group removed successsfully', 'wpretail' );
								} else {
									$ajax->errors['message'] = __( 'Tax Group could not be removed, Please repload the page and try again', 'wpretail' );
								}
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						} else {
							$ajax->errors['message'] = __( 'Id not found, please reload the page and try again', 'wpretail' );
						}
						break;
					case 'edit':
						if ( ! empty( $ajax->event['id'] ) ) {
							$db = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_groupss' );
							try {
								$tax                       = $db->get_tax_groups( $ajax->event['id'] );
								$fields                    = [
									'group_name' => $tax['name'],
									'sub_taxes' => wpretail()->helper->unserialize($tax['taxes']),
								];
								$ajax->success['message']  = __( 'Update Group', 'wpretail' );
								$ajax->success['location'] = $fields;
							} catch ( \Exception $e ) {
								$ajax->errors['message'] = $e->getMessage();
							}
						}
						break;
					default:
						$ajax->errors['message'] = __( 'Object not found, Please try again', 'wpretail' );
				}
			} else {
				$ajax->errors['message'] = __( 'Event not found, Please try again', 'wpretail' );
			}
		}
	}

	/**
	 * Tax setting handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function tax_rate_setting_handler( $ajax ) {
		$fields = [
			'business_id' => '1', // Always.
			'status'      => '1', // Always.
			'name'        => $ajax->sanitized_fields['tax_name'],
			'rate'        => $ajax->sanitized_fields['tax_rate'],
			'for_group'   => $ajax->sanitized_fields['for_group'],
		];

		$db = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_rates' );

		try {
			if ( ! empty( $ajax->event['id'] ) ) {
				$where = [ 'id' => $ajax->event['id'] ];
				$id    = $db->update( $fields, $where );
				if ( $id ) {
					$ajax->success['message'] = __( 'Tax updated successfully', 'wpretail' );
					$ajax->success['id']      = $ajax->event['id'];
					$formatted_fields         = [
						'tax_name'  => $fields['name'],
						'tax_rate'  => $fields['rate'],
						'for_group' => $fields['for_group'],
					];
					$ajax->success['updated'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = __( 'Tax Could not be updated', 'wpretail' );
				}
				return;
			}
			$id = $db->insert( $fields );
			if ( $id ) {
				$ajax->success['message']  = __( 'Tax added successfully', 'wpretail' );
				$ajax->success['id']       = $db->get_last_insert_id();
				$ajax->success['inserted'] = $fields;
			} else {
				$ajax->errors['message'] = __( 'Tax Could not be added', 'wpretail' );
			}
		} catch ( Exception $e ) {
			$ajax->errors['message'] = $e->getMessage();
		}
	}

	/**
	 * Tax setting handler.
	 *
	 * @param mixed $ajax Ajax.
	 * @return void
	 */
	public function tax_group_setting_handler( $ajax ) {
		$fields = [
			'business_id' => '1', // Always.
			'status'      => '1', // Always.
			'name'        => $ajax->sanitized_fields['group_name'],
			'taxes'       => wpretail()->helper->serialize( $ajax->sanitized_fields['sub_taxes'] ),
		];

		$db = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_groups' );

		try {
			if ( ! empty( $ajax->event['id'] ) ) {
				$where = [ 'id' => $ajax->event['id'] ];
				$id    = $db->update( $fields, $where );
				if ( $id ) {
					$ajax->success['message'] = __( 'Tax Group updated successfully', 'wpretail' );
					$ajax->success['id']      = $ajax->event['id'];
					$formatted_fields         = [
						'group_name' => $fields['name'],
						'sub_taxes' => wpretail()->helper->unserialize( $fields['taxes'] ),
					];
					$ajax->success['updated'] = $formatted_fields;
				} else {
					$ajax->errors['message'] = __( 'Tax Group Could not be updated', 'wpretail' );
				}
				return;
			}
			$id = $db->insert( $fields );
			if ( $id ) {
				$ajax->success['message']  = __( 'Tax Group added successfully', 'wpretail' );
				$ajax->success['id']       = $db->get_last_insert_id();
				$ajax->success['inserted'] = $fields;
			} else {
				$ajax->errors['message'] = __( 'Tax Group Could not be added', 'wpretail' );
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
		$db         = new WPRetail\Db\WPRetail_Db( 'wpretail_business_location' );
		$buisness   = $db->get_business( '1' ); // Always.
		foreach ( wpretail()->helper->wpretail_get_currencies() as $key => $currency ) {
			$currencies[ $key ] = $currency['name'];
		}
		$business_settings = [
			'business_name'             => [
				'label'       => [
					'content' => __( 'Business Name', 'wpretail' ) . '*',
				],
				'input'       => [
					'type'  => 'text',
					'name'  => 'business_name',
					'id'    => 'business_name',
					'value' => $buisness['name'],
					'attr'  => [
						'required',
						'min' => 5,
						'max' => 15,
					],
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
					'value' => $buisness['start_date'],
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
					'type'  => 'text',
					'name'  => 'profit_percent',
					'id'    => 'profit_percent',
					'value' => $buisness['default_profit_percent'],
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
					'value'   => $buisness['currency_id'],
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
					'value'   => $buisness['fiscal_year_start_month'],
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
					'value'   => $buisness['accounting_method'],
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
					'type'  => 'text',
					'name'  => 'default_sales_discount',
					'id'    => 'default_sales_discount',
					'value' => $buisness['default_sale_discount'],
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
					'type'  => 'text',
					'name'  => 'transaction_edit_days',
					'id'    => 'transaction_edit_days',
					'value' => 30,
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
				'label'       => [
					'content' => __( 'City', 'wpretail' ) . '*',
				],
				'input'       => [
					'type' => 'text',
					'name' => 'city',
					'id'   => 'city',
				],
				'col'         => 'col-md-6',
				'validations' => [ 'required' ],
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
		$tax_rate_settings = [
			'tax_name'  => [
				'label' => [
					'content' => __( 'Tax Name', 'wpretail' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'tax_name',
					'id'   => 'tax_name',
				],
				'col'   => 'col-md-6',
			],
			'tax_rate'  => [
				'label' => [
					'content' => __( 'Tax Rate %', 'wpretail' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'tax_rate',
					'id'   => 'tax_rate',
				],
				'col'   => 'col-md-6',
			],
			'for_group' => [
				'input' => [
					'type'    => 'checkbox',
					'name'    => 'for_group',
					'id'      => 'for_group',
					'options' => [
						'items' => [
							'1' => __( 'For tax group only', 'wpretail' ),
						],
					],
					'has_key' => true,
				],
				'col'   => 'col-md-6',
			],
		];

		$db                  = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_rates' );
		$tax_rates           = $db->get_tax_rates();
		$tax_rates_formatted = [];
		if ( ! empty( $tax_rates ) ) {
			foreach ( $tax_rates as $rate ) {
				$tax_rates_formatted[ $rate->id ] = $rate->name;
			}
		}
		$tax_group_settings = [
			'group_name' => [
				'label' => [
					'content' => __( 'Group Name', 'wpretail' ) . '*',
				],
				'input' => [
					'type' => 'text',
					'name' => 'group_name',
					'id'   => 'group_name',
				],
				'col'   => 'col-md-6',
			],
			'sub_taxes'  => [
				'label' => [
					'content' => __( 'Tax Rate %', 'wpretail' ) . '*',
				],
				'input' => [
					'type'    => 'select',
					'name'    => 'sub_taxes',
					'id'      => 'sub_taxes',
					'options' => $tax_rates_formatted,
					'has_key' => true,
					'attr'    => [
						'multiple',
					],
				],
				'col'   => 'col-md-6',
			],
		];

		return array_filter(
			array_merge(
				$field_options,
				[
					'business_settings' => $business_settings,
					'location_setting'  => $location_settings,
					'tax_rate_setting'  => $tax_rate_settings,
					'tax_group_setting' => $tax_group_settings,
				]
			)
		);
	}

	/**
	 * Buisness Settinh View.
	 *
	 * @return void
	 */
	public function view_tax_setting() {

		$field_options = apply_filters( 'wpretail_form_fields_options', [] );

		$tax_rate_setting  = $field_options['tax_rate_setting'];
		$tax_group_setting = $field_options['tax_group_setting'];

		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_tax',
				'content' => __( 'Add Tax', 'wpretail' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail_tax_rate_setting_modal',
				],
			]
		);

		$db = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_rates' );

		wpretail()->builder->table(
			[
				'head'    => [
					'labels' => [
						'name' => __( 'Name', 'wpretail' ),
						'rate' => __( 'Tax Rate', 'wpretail' ),
					],
					'data'   => [
						'name' => 'tax_name',
						'rate' => 'tax_	rate',
					],
				],
				'actions' => [
					'options'        => [
						'edit',
						'delete',
					],
					'delete_confirm' => __( 'Are you sure you want to remove?', 'wpretail' ),
					'update_confirm' => __( 'Are you sure you want to update?', 'wpretail' ),
				],
				'id'      => 'wpretail_list_tax_rate_setting',
				'body'    => $db->get_tax_rates(),
				'class'   => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'     => 'col-md-12',
			]
		);

		$args = [
			'form_args'  => [
				'id'                => 'wpretail_tax_rate_setting',
				'class'             => [ 'wpretail-tax-rate-setting' ],
				'attr'              => [
					'action' => admin_url(),
					'method' => 'post',
				],
				'form_title'        => __( 'Add Tax', 'wpretail' ),
				'form_submit_id'    => 'wpretail_add_tax',
				'form_submit_label' => __( 'Add Tax', 'wpretail' ),
				'is_modal'          => true,
				'modal'             => 'modal-lg modal-dialog-centered modal-dialog-scrollable',
			],
			'input_args' => $tax_rate_setting,
		];

		wpretail()->builder->form( $args );

		wpretail()->builder->html(
			'button',
			[
				'id'      => 'add_tax_group',
				'content' => __( 'Add Group', 'wpretail' ),
				'class'   => [ 'mb-3 btn btn-primary' ],
				'closed'  => true,
				'attr'    => [ 'type' => 'button' ],
				'data'    => [
					'bs-toggle' => 'modal',
					'bs-target' => '#wpretail_tax_group_setting_modal',
				],
			]
		);

		$db = new WPRetail\Db\WPRetail_Db( 'wpretail_tax_groups' );

		wpretail()->builder->table(
			[
				'head'    => [
					'labels' => [
						'name' => __( 'Name', 'wpretail' ),
					],
					'data'   => [
						'name' => 'group_name',
					],
				],
				'actions' => [
					'options'        => [
						'edit',
						'delete',
					],
					'delete_confirm' => __( 'Are you sure you want to remove?', 'wpretail' ),
					'update_confirm' => __( 'Are you sure you want to update?', 'wpretail' ),
				],
				'id'      => 'wpretail_list_tax_group_setting',
				'body'    => $db->get_tax_groups(),
				'class'   => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'     => 'col-md-12',
			]
		);

		$args = [
			'form_args'  => [
				'id'                => 'wpretail_tax_group_setting',
				'class'             => [ 'wpretail-tax-group-setting' ],
				'attr'              => [
					'action' => admin_url(),
					'method' => 'post',
				],
				'form_title'        => __( 'Add Group', 'wpretail' ),
				'form_submit_id'    => 'wpretail_add_tax',
				'form_submit_label' => __( 'Add Group', 'wpretail' ),
				'is_modal'          => true,
				'modal'             => 'modal-lg modal-dialog-centered modal-dialog-scrollable',
			],
			'input_args' => $tax_group_setting,
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

		$settings = $field_options['location_setting'];
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
					'bs-target' => '#wpretail_location_setting_modal',
				],
			]
		);

		$db = new WPRetail\Db\WPRetail_Db( 'wpretail_business' );

		wpretail()->builder->table(
			[
				'head'    => [
					'labels' => [
						'location_id' => __( 'Location ID', 'wpretail' ),
						'landmark'    => __( 'Landmark', 'wpretail' ),
						'city'        => __( 'City', 'wpretail' ),
						'state'       => __( 'State', 'wpretail' ),
						'country'     => __( 'Country', 'wpretail' ),
						'zip_code'    => __( 'Zip Code', 'wpretail' ),
						'mobile'      => __( 'Mobile', 'wpretail' ),
						'email'       => __( 'Email', 'wpretail' ),
					],
					'data'   => [
						'location_id' => 'location_id',
						'landmark'    => 'landmark',
						'city'        => 'city',
						'state'       => 'state',
						'country'     => 'country',
						'zip_code'    => 'zipcode',
						'mobile'      => 'mobile',
						'email'       => 'email',
					],
				],
				'actions' => [
					'options'        => [
						'edit',
						'delete',
					],
					'delete_confirm' => __( 'Are you sure you want to remove?', 'wpretail' ),
					'update_confirm' => __( 'Are you sure you want to update?', 'wpretail' ),
				],
				'id'      => 'wpretail_list_location_setting',
				'body'    => $db->get_location(),
				'class'   => [ 'wpretail-datatable', 'table table-primary mt-5' ],
				'col'     => 'col-md-12',
			]
		);

		$args = [
			'form_args'  => [
				'id'                => 'wpretail_location_setting',
				'class'             => [ 'wpretail-location-setting' ],
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

	/**
	 * Tax Settinh View.
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
}
