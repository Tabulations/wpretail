<?php

namespace WPRetail;

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
class WPRetail_Helper_Functions {

	/**
	 * Get supported currencies.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function wpretail_get_currencies() {
		$currencies = [
			'USD' => [
				'name'                => esc_html__( 'U.S. Dollar', 'everest-forms-pro' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'GBP' => [
				'name'                => esc_html__( 'Pound Sterling', 'everest-forms-pro' ),
				'symbol'              => '&pound;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'EUR' => [
				'name'                => esc_html__( 'Euro', 'everest-forms-pro' ),
				'symbol'              => '&euro;',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'AUD' => [
				'name'                => esc_html__( 'Australian Dollar', 'everest-forms-pro' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'BRL' => [
				'name'                => esc_html__( 'Brazilian Real', 'everest-forms-pro' ),
				'symbol'              => 'R$',
				'symbol_pos'          => 'left',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'CAD' => [
				'name'                => esc_html__( 'Canadian Dollar', 'everest-forms-pro' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'CZK' => [
				'name'                => esc_html__( 'Czech Koruna', 'everest-forms-pro' ),
				'symbol'              => '&#75;&#269;',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'DKK' => [
				'name'                => esc_html__( 'Danish Krone', 'everest-forms-pro' ),
				'symbol'              => 'kr.',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'HKD' => [
				'name'                => esc_html__( 'Hong Kong Dollar', 'everest-forms-pro' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'right',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'HUF' => [
				'name'                => esc_html__( 'Hungarian Forint', 'everest-forms-pro' ),
				'symbol'              => 'Ft',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'ILS' => [
				'name'                => esc_html__( 'Israeli New Sheqel', 'everest-forms-pro' ),
				'symbol'              => '&#8362;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'MYR' => [
				'name'                => esc_html__( 'Malaysian Ringgit', 'everest-forms-pro' ),
				'symbol'              => '&#82;&#77;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'MXN' => [
				'name'                => esc_html__( 'Mexican Peso', 'everest-forms-pro' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'NOK' => [
				'name'                => esc_html__( 'Norwegian Krone', 'everest-forms-pro' ),
				'symbol'              => 'Kr',
				'symbol_pos'          => 'left',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'NZD' => [
				'name'                => esc_html__( 'New Zealand Dollar', 'everest-forms-pro' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'PHP' => [
				'name'                => esc_html__( 'Philippine Peso', 'everest-forms-pro' ),
				'symbol'              => 'Php',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'PLN' => [
				'name'                => esc_html__( 'Polish Zloty', 'everest-forms-pro' ),
				'symbol'              => '&#122;&#322;',
				'symbol_pos'          => 'left',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'RUB' => [
				'name'                => esc_html__( 'Russian Ruble', 'everest-forms-pro' ),
				'symbol'              => 'pyб',
				'symbol_pos'          => 'right',
				'thousands_separator' => ' ',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'SGD' => [
				'name'                => esc_html__( 'Singapore Dollar', 'everest-forms-pro' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'ZAR' => [
				'name'                => esc_html__( 'South African Rand', 'everest-forms-pro' ),
				'symbol'              => 'R',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'SEK' => [
				'name'                => esc_html__( 'Swedish Krona', 'everest-forms-pro' ),
				'symbol'              => 'Kr',
				'symbol_pos'          => 'right',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'decimals'            => 2,
			],
			'CHF' => [
				'name'                => esc_html__( 'Swiss Franc', 'everest-forms-pro' ),
				'symbol'              => 'CHF',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'TWD' => [
				'name'                => esc_html__( 'Taiwan New Dollar', 'everest-forms-pro' ),
				'symbol'              => '&#36;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'THB' => [
				'name'                => esc_html__( 'Thai Baht', 'everest-forms-pro' ),
				'symbol'              => '&#3647;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
			'JPY' => [
				'name'                => esc_html__( 'Japanese yen', 'everest-forms-pro' ),
				'symbol'              => '&yen;',
				'symbol_pos'          => 'left',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'decimals'            => 2,
			],
		];

		return apply_filters( 'everest_forms_currencies', $currencies );
	}
}
