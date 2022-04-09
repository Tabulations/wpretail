<?php

namespace WPRetail\Products;

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
class WPRetail_Products {

	/**
	 * Test Function.
	 */
	public function __construct() {
		add_filter( 'wpretail_menus', [ $this, 'menu' ] );
	}

	/**
	 * Menu.
	 *
	 * @param mixed $menus Menu.
	 *
	 * @return mixed
	 */
	public function menu( $menus ) {
		return array_filter(
			array_merge(
				$menus,
				[
					'Products' => [
						[
							'label'  => 'List Products',
							'slug'   => 'list-products',
							'icon'   => 'list-product-icon',
							'class'  => [],
							'is_pro' => false,
						],
						[
							'label'  => 'List Products',
							'slug'   => 'add-products',
							'icon'   => 'list-product-icon',
							'class'  => [],
							'is_pro' => false,
						],
					],
				]
			)
		);
	}
}
