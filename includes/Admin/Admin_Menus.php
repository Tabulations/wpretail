<?php
namespace WPRetail\Admin;

/**
 * Setup menus in WP admin.
 *
 * @package WP-Retail\Admin
 * @version 1.0.0
 * @since   1.0.0
 */


defined( 'ABSPATH' ) || exit;

/**
 * WpRetail_Admin_Menus Class.
 */
class Admin_Menus {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Add menus.
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'init' ] );
	}

	/**
	 * Inint.
	 */
	public function init() {
		if ( isset( $_GET['page'] ) && 'wpretail' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			// Styles.
			wp_enqueue_style( 'wpretail_style_fontawesome', plugins_url( '/assets/vendors/fontawesome/css/all.min.css', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );
			wp_enqueue_style( 'wpretail_style_bootstrap', plugins_url( '/assets/vendors/bootstrap/css/bootstrap.min.css', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );
			wp_enqueue_style( 'wpretail_style_layout', plugins_url( '/assets/css/style.css', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );

			// Scripts.
			wp_enqueue_script( 'wpretail_script_bootstrap', plugins_url( '/assets/vendors/bootstrap/js/bootstrap.bundle.min.js', WPRETAIL_PLUGIN_FILE ), [ 'jquery' ], WPRETAIL_VERSION, true );
		}
	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		add_menu_page( esc_html__( 'WP Retail', 'wpretail' ), esc_html__( 'WP Retail', 'wpretail' ), 'manage_options', 'wpretail', [ $this, 'wpretail_admin' ], 'dashicons-businessman', '25' );
	}

	/**
	 * Menus
	 */
	public function menus() {
		return apply_filters( 'wpretail_menus', [] );
	}

	/**
	 * Dashboard
	 * s
	 *
	 * @return void
	 */
	public function wpretail_admin() {
		wpretail()->builder->html( 'div', [ 'id' => 'wpretail-wrapper' ] ); // Opening Wrapper.
		include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/views/header.php';
		wpretail()->builder->html( 'div', [ 'id' => 'wpretail-main' ] ); // Opening Main.
		$this->include_page();
		wpretail()->builder->html( 'div' ); // Closing Wrapper.
	}

	public function include_page() {
		if ( isset( $_GET['page'] ) && 'wpretail' === $_GET['page'] ) {
			if ( isset( $_GET['target'] ) ) {
				switch ( $_GET['target'] ) {
					case 'dashboard':
						include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/views/dashboard.php';
						break;
					case 'purchase':
						include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/views/purchase.php';
						break;
					case 'product':
						include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/views/product.php';
						break;
					case 'settings':
						include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/views/settings.php';
						break;
					default:
						include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/views/dashboard.php';
				}
			} else {
				include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/views/dashboard.php';
			}
		}
	}
}
