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
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}


	/**
	 * Add menu items.
	 */
	public function admin_menu() {
		add_menu_page( esc_html__( 'WP Retail', ' wpretail' ), esc_html__( 'WP Retail', 'wpretail' ), 'manage_options', 'wpretail', null,'dashicons-businessman', '25' );
	}
}
