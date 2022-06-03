<?php

namespace WPRetail\Modules\Core\Views;

/**
 * Views.
 *
 * This class loads views of the plugins.
 * To add helper, register views using wpretail_register_views filter.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sales Handler class.
 */
class Views {

	/**
	 * Views.
	 *
	 * @var array
	 */
	public $views = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register Views.
		$this->register_views();
	}

	/**
	 * Load Views.
	 *
	 * @return void
	 */
	public function register_views() {
		$views = apply_filters(
			'wpretail_register_views',
			[
				'layout'      => plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . 'includes/Modules/Core/Views/Layout.php',
				'sidebar'     => plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . 'includes/Modules/Core/Views/sidebar.php',
				'header'      => plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . 'includes/Modules/Core/Views/Header.php',
				'footer'      => plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . 'includes/Modules/Core/Views/Footer.php',
				'dashboard'   => plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . 'includes/Modules/Core/Views/Dashboard.php',
				'add_product' => plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . 'includes/Modules/Core/Views/Add_Product.php',
			]
		);

		foreach ( $views as $view_key => $view ) {
			$this->views[ $view_key ] = preg_replace( '/\//', '\\\\', $view );
		}
	}

	/**
	 * Load Views.
	 *
	 * @param string $view View.
	 * @return void
	 */
	public function load( $view ) {
		include_once $this->views[ $view ];
	}
}
