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
			wp_enqueue_style( 'wpretail_style_font', plugins_url( '/assets/fontawesome/css/all.min.css', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );
			wp_enqueue_style( 'wpretail_style_bootstrap', plugins_url( '/assets/bootstrap/css/bootstrap.css', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );
			wp_enqueue_style( 'wpretail_style_layout', plugins_url( '/assets/css/layout.css', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION );
			wp_enqueue_script( 'wpretail_script_jquery', plugins_url( '/assets/jquery/jquery.min.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );
			wp_enqueue_script( 'wpretail_script_bootstrap', plugins_url( '/assets/bootstrap/js/bootstrap.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );
			wp_enqueue_script( 'wpretail_script_layout', plugins_url( '/assets/js/layout.js', WPRETAIL_PLUGIN_FILE ), [], WPRETAIL_VERSION, true );
		}
	}

	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		add_menu_page( esc_html__( 'WP Retail', 'wpretail' ), esc_html__( 'WP Retail', 'wpretail' ), 'manage_options', 'wpretail', [ $this, 'dashboard' ], 'dashicons-businessman', '25' );
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
	public function dashboard() {
		// Menus.
		$menus = $this->menus(); // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		include plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . '/templates/layout.php';
	}
}
