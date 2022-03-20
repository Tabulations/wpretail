<?php

namespace WPRetail;

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

		// Checks if WPForms is installed.
		if ( defined( 'WPFORMS_VERSION' ) && version_compare( WPFORMS_VERSION, '1.6.5', '>=' ) ) {
			add_action( 'wpforms_loaded', [ $this, 'init' ] );
			add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 20, 2 );
			add_action( 'admin_notices', [ $this, 'build_dependencies_notice' ] );
		} else {
			add_action( 'admin_init', [ $this, 'wpforms_addon_deactivate' ] );
			add_action( 'admin_notices', [ $this, 'wpforms_addon_required_notice' ] );
		}
	}

	/**
	 * Initialize plugin.
	 *
	 * @since 1.0.0
	 */
	public function init() {}

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
		load_plugin_textdomain( 'wpretail', false, plugin_basename( dirname( WPRetail_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Display row meta in the Plugins list table.
	 *
	 * @param array  $plugin_meta Plugin Row Meta.
	 * @param string $plugin_file Plugin Base file.
	 * @return array Array of modified plugin row meta.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( plugin_basename( WPRetail_PLUGIN_FILE ) === $plugin_file ) {
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
		if ( ! file_exists( plugin_dir_path( WPRetail_PLUGIN_FILE ) . 'css/admin-starter.css' ) ) {
			return false;
		}

		// Check if we have minified JS.
		if ( ! file_exists( plugin_dir_path( WPRetail_PLUGIN_FILE ) . 'js/admin-starter.min.js' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Output an admin notice if build dependencies has not been met.
	 *
	 * @since 1.0.0
	 */
	public function build_dependencies_notice() {
		if ( $this->check_build_dependencies() ) {
			return;
		}

		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			sprintf(
				/* translators: 1: grunt command. 2: URL of the GitHub Repository releases page */
				esc_html__( 'You have installed a development version of WPRetail which requires files to be built and minified. From the plugin directory, run %1$s to build and minify assets. Or you can download a pre-built version of the plugin from the %2$s.', 'wpretail' ),
				'<code>grunt assets</code>',
				'<a href="https://github.com/WPCanny/wpretail/releases">GitHub Repository releases page</a>'
			)
		);
	}

	/**
	 * Deactivate plugin if WPForms version has not been met.
	 *
	 * @since 1.0.0
	 */
	public function wpforms_addon_deactivate() {
		deactivate_plugins( plugin_basename( WPRetail_PLUGIN_FILE ) );
	}

	/**
	 * Output an admin notice if WPForms version has not been met.
	 *
	 * @since 1.0.0
	 */
	public function wpforms_addon_required_notice() {
		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		printf(
			'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
			sprintf(
				/* translators: %s: WPForms version */
				esc_html__( 'The WPRetail plugin has been deactivated, because it requires %s or later to work!', 'wpretail' ),
				'<a href="https://wpforms.com" target="_blank">' . esc_html__( 'WPForms 1.6.5', 'wpretail' ) . '</a>'
			)
		);
	}
}