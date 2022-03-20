<?php

namespace WPRetail;
use WPRetail\Admin\Admin_Menus;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	protected static $instance;

	/**
	 * Prevent cloning.
	 *
	 * @since 1.0.0
	 */
	private function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'wpretail' ), '1.0.0' );
	}

	/**
	 * Prevent unserializing.
	 *
	 * @since 1.0.0
	 */
	final public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'wpretail' ), '1.0.0' );
	}

	/**
	 * Main plugin class instance.
	 *
	 * Ensures only one instance of the plugin is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @return object Main instance of the class.
	 */
	final public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Plugin Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Load plugin text domain.
			add_action( 'init', [ $this, 'load_plugin_textdomain' ], 0 );
			add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 20, 2 );
			add_action( 'init', [ $this, 'init' ]);
			
	}

	/**
	 * Initialize plugin.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		new Admin_Menus();
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/wpretail/wpretail-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/wpretail-LOCALE.mo
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wpretail' );

		load_textdomain( 'wpretail', WP_LANG_DIR . '/wpretail/wpretail-' . $locale . '.mo' );
		load_plugin_textdomain( 'wpretail', false, plugin_basename( dirname( WPRETAIL_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Display row meta in the Plugins list table.
	 *
	 * @param array  $plugin_meta Plugin Row Meta.
	 * @param string $plugin_file Plugin Base file.
	 * @return array Array of modified plugin row meta.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( plugin_basename( WPRETAIL_PLUGIN_FILE ) === $plugin_file ) {
			$new_plugin_meta = [
				'docs' => '<a href="' . esc_url( 'https://docs.wpcanny.com/document/wpretail/' ) . '" aria-label="' . esc_attr__( 'View WPRetail documentation', 'wpretail' ) . '">' . esc_html__( 'Docs', 'wpretail' ) . '</a>',
			];

			return array_merge( $plugin_meta, $new_plugin_meta );
		}

		return (array) $plugin_meta;
	}

	/**
	 * Check if the plugin assets are built and minified.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function check_build_dependencies() {
		// Check if we have compiled CSS.
		if ( ! file_exists( plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . 'css/admin-starter.css' ) ) {
			return false;
		}

		// Check if we have minified JS.
		if ( ! file_exists( plugin_dir_path( WPRETAIL_PLUGIN_FILE ) . 'js/admin-starter.min.js' ) ) {
			return false;
		}

		return true;
	}

}
