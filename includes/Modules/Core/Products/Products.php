<?php

namespace WPRetail\Modules\Core\Products;

use WPRetail\Modules\Core\Views\Views;

/**
 * PDF Module.
 *
 * Create and manages PDFs in wpretail.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WPRetail Core Module.
 */
class Products {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'wpretail_registered_menus', [ $this, 'register_product_menus' ] );

		add_action( 'wpretail_view_add_product', [ $this, 'add_product_view' ] );

		add_filter( 'wpretail_register_forms', [ $this, 'add_product_form' ] );
	}

	/**
	 * Add Product Forms.
	 *
	 * @param mixed $forms Forms.
	 * @return mixed
	 */
	public function add_product_form( $forms ) {
		$forms['add_product'] = [
			'id'     => 'add-product',
			'title'  => __( 'Add Product', 'wpretail' ),
			'fields' => [
				[
					'label'    => __( 'Product Name', 'wpretail' ),
					'name'     => 'product_name',
					'type'     => 'text',
					'value'    => 'hello',
					'required' => true,
				],
				[
					'label' => __( 'Product Name', 'wpretail' ) . '*',
					'type'  => 'text',
					'name'  => 'product_name',
				],
				[
					'label' => __( 'SKU', 'wpretail' ),
					'type'  => 'text',
					'name'  => 'sku',
				],
				[
					'label'   => __( 'Barcode Type', 'wpretail' ) . '*',
					'type'    => 'select',
					'name'    => 'barcode_type',
					'options' => [
						'c128'  => __( 'Code 128 (C128)', 'wpretail' ),
						'c39'   => __( 'Code 39 (C39)', 'wpretail' ),
						'ean13' => __( 'EAN-13', 'wpretail' ),
						'ean8'  => __( 'EAN-8', 'wpretail' ),
						'upca'  => __( 'UPC-A', 'wpretail' ),
						'upce'  => __( 'UPC-E', 'wpretail' ),
					],
					'has_key' => true,
				],
				[
					'label'   => __( 'Unit', 'wpretail' ) . '*',
					'type'    => 'select',
					'name'    => 'unit',
					'options' => [
						'c128' => __( 'KG', 'wpretail' ),
						'c39'  => __( 'Meter', 'wpretail' ),
					],
					'has_key' => true,
				],
				[
					'label'   => __( 'Brand', 'wpretail' ) . '*',
					'type'    => 'select',
					'name'    => 'brand',
					'options' => [
						'c128' => __( 'Samsung', 'wpretail' ),
						'c39'  => __( 'CG', 'wpretail' ),
					],
					'has_key' => true,
				],
				[
					'label'   => __( 'Category', 'wpretail' ) . '*',
					'type'    => 'select',
					'name'    => 'category',
					'options' => [
						'c128' => __( 'Mobile', 'wpretail' ),
						'c39'  => __( 'TV', 'wpretail' ),
					],
					'has_key' => true,
				],
				[
					'label'   => __( 'Sub Category', 'wpretail' ) . '*',
					'type'    => 'select',
					'name'    => 'sub_category',
					'options' => [
						'c128' => __( 'Core', 'wpretail' ),
						'c39'  => __( 'Galaxy', 'wpretail' ),
					],
					'has_key' => true,
				],
				[
					'label'   => __( 'Business Location', 'wpretail' ) . '*',
					'type'    => 'select',
					'name'    => 'sub_category',
					'options' => [
						'c128' => __( 'Pokhara', 'wpretail' ),
						'c39'  => __( 'Kathmandu', 'wpretail' ),
					],
					'has_key' => true,
				],
				[
					'label'   => __( 'Alert Quantity', 'wpretail' ) . '*',
					'type'    => 'text',
					'name'    => 'alert_quantity',
				],
			],
		];
		return $forms;
	}

	/**
	 * Add Product.
	 *
	 * @return void
	 */
	public function add_product_view() {
		$view = new Views();
		$view->load( 'add_product' );
	}

	/**
	 * Register Product Menus.
	 *
	 * @param mixed $menus Menus.
	 * @return array
	 */
	public function register_product_menus( $menus ) {
		return array_filter(
			array_merge(
				$menus,
				[
					[
						'label' => 'Inventory',
						'index' => 1,
						'menus' => [
							'products' => [
								'label'     => __( 'Products', 'wpretail' ),
								'sub_label' => __( 'lite', 'wpretail' ),
								'menus'     => [
									'add_product'    => [
										'link'  => admin_url( '?page=wpretail&view=add_product' ),
										'title' => __( 'Add Product', 'wpretail' ),
									],
									'manage_product' => [
										'link'  => '#',
										'title' => __( 'Manage Products', 'wpretail' ),
									],
									'log'            => [
										'link'  => '#',
										'title' => __( 'Product Logs', 'wpretail' ),
									],
								],
							],
						],
					],
				]
			)
		);
	}
}
