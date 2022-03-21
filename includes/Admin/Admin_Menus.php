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

if ( class_exists( 'Admin_Menus', false ) ) {
	return new Admin_Menus();
}

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
		add_action( 'admin_enqueue_scripts', array( $this, 'init' ) );
	}

	public function init() {
		if( 'wpretail' === $_GET['page'] ) {
			wp_enqueue_style( 'wpretail_style_font', plugins_url( '/assets/fontawesome/css/all.min.css', WPRETAIL_PLUGIN_FILE ) , [] );
			wp_enqueue_style( 'wpretail_style_bootstrap', plugins_url( '/assets/bootstrap/css/bootstrap.css', WPRETAIL_PLUGIN_FILE ) , [] );
			wp_enqueue_style( 'wpretail_style_layout', plugins_url( '/assets/css/layout.css', WPRETAIL_PLUGIN_FILE) , [], WPRETAIL_VERSION );
		}
	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		add_menu_page( esc_html__( 'WP Retail', ' wpretail' ), esc_html__( 'WP Retail', 'wpretail' ), 'manage_options', 'wpretail', null, 'dashicons-businessman', '25' );
		add_menu_page( esc_html__( 'Dashboard', ' wpretail' ), esc_html__( 'Dashboard', 'wpretail' ), 'manage_options', 'wpretail', [ $this, 'dashboard' ], 'dashicons-businessman', '25' );
	}

	/**
	 * Dashboard
	 *
	 * @return void
	 */
	public function dashboard() {
		include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/templates/layout.php';
	}
}
