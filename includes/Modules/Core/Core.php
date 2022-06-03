<?php

namespace WPRetail\Modules\Core;

use WPRetail\Modules\Core\Views\Views as Views;

/**
 * Core Module.
 *
 * This is the Default/Free module.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WPRetail Core Module.
 */
class Core {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_filter( 'wpretail_register_helpers', [ $this, 'core_helpers' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wpretail_view_content_dashboard', [ $this, 'dashboard' ] );

		$this->load_features();
	}

	/**
	 * Load Features.
	 *
	 * @return void
	 */
	public function load_features() {
		$features = [
			'product' => 'Products\\Products',
		];

		foreach ( $features as $feature ) {
			$feature = 'WPRetail\\Modules\\Core\\' . $feature;
			if ( class_exists( $feature ) ) {
				new $feature();
			}
		}
	}

	/**
	 * Dashboard.
	 *
	 * @return void
	 */
	public function dashboard() {
		wpretail()->helper->view->load( 'dashboard' );
	}

		/**
		 * Inint.
		 */
	public function enqueue_scripts() {
		if ( isset( $_GET['page'] ) && 'wpretail' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// SBADMIN.
			wp_enqueue_style( 'wpretail-sbadmin', plugins_url( 'assets/vendors/sbadmin/css/sb-admin-2.min.css', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );
			wp_enqueue_style( 'wpretail-font', plugins_url( 'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );

			// Custom CSS.
			wp_enqueue_style( 'wpretail-stylelayout', plugins_url( '/assets/css/style.css', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );

			// Bootstrap.
			wp_enqueue_script( 'wpretail-jquery', plugins_url( 'assets/vendors/sbadmin/vendor/jquery/jquery.min.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );
			wp_enqueue_script( 'wpretail-bootstrap', plugins_url( 'assets/vendors/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js', WPRETAIL_PLUGIN_FILE ), [ 'wpretail-jquery' ], WPRETAIL_VERSION, true );

			// Core Plugin.
			wp_enqueue_script( 'wpretail-easingjs', plugins_url( 'assets/vendors/sbadmin/vendor/jquery-easing/jquery.easing.min.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );

			// Custom.
			wp_enqueue_script( 'wpretail-sbadmin', plugins_url( 'assets/vendors/sbadmin/js/sb-admin-2.min.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );

			// Page Level.
			wp_enqueue_script( 'wpretail-chart', plugins_url( 'assets/vendors/sbadmin/vendor/chart.js/Chart.min.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );

			// Page Level Custom Service.
			wp_enqueue_script( 'wpretail-area-demo', plugins_url( 'assets/vendors/sbadmin/js/demo/chart-area-demo.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );
			wp_enqueue_script( 'wpretail-pir-demo', plugins_url( 'assets/vendors/sbadmin/js/demo/chart-pie-demo.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );
		}

	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		add_menu_page( esc_html__( 'WP Retail', 'wpretail' ), esc_html__( 'WP Retail', 'wpretail' ), 'manage_options', 'wpretail', [ $this, 'wpretail_admin' ], 'dashicons-businessman', '25' );
	}

	/**
	 * Dashboard
	 *
	 * @return void
	 */
	public function wpretail_admin() {
		$view = new Views();
		$view->load( 'layout' );
	}

	/**
	 * Core Helpers.
	 *
	 * @param array $helpers Helpers.
	 *
	 * @return mixed
	 */
	public function core_helpers( $helpers ) {
		$helpers = array_filter(
			array_merge(
				$helpers,
				[
					'builder' => 'WPRetail\\Modules\\Core\\Helper\\Builder\\Builder',
					'view'    => 'WPRetail\\Modules\\Core\\Helper\\Views\\Views',
				]
			)
		);
		return $helpers;
	}
}
