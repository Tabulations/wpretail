<?php

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
final class WPRetail {

	/**
	 * Plugin  DIR.
	 *
	 * @var $products
	 */
	public $products;

	/**
	 * Sales Object.
	 *
	 * @var $sales
	 */
	public $sales;

	/**
	 * Function  Object.
	 *
	 * @var $helper
	 */
	public $helper;

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
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'wpretail' ), '1.0.0' );
	}

	/**
	 * Main WPRetail Instance.
	 *
	 * Ensures only one instance of WPRetail is loaded or can be loaded.
	 *
	 * @since  1.0.0
	 * @static
	 * @see    WPRetail()
	 * @return WPRetail - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * WPRetail Constructor.
	 */
	public function __construct() {
		$upload_dir                = wp_upload_dir( null, false );
		$this->plugin_dir          = dirname( WPRETAIL_PLUGIN_FILE ) . '/';
		$this->base_name           = plugin_basename( WPRETAIL_PLUGIN_FILE );
		$this->version             = WPRETAIL_VERSION;
		$this->log_dir             = $upload_dir['basedir'] . '/wpretail-logs/';
		$this->session_cache_group = 'wpretail_session_id';
		$this->template_debug_mode = false;

		// REgister Activation Hook.
		register_activation_hook( WPRETAIL_PLUGIN_FILE, [ 'WPRetail\WPRetail_Install', 'install' ] );

		// Plugin Loaded.
		add_action( 'plugins_loaded', [ $this, 'load_modules' ], 1 );
		// WPRetail Loaded.
		do_action( 'wpretail_loaded' );
	}

	/**
	 * Setup objects.
	 *
	 * @since 1.0.0
	 */
	public function load_modules() {
		// Load Modules.
		new \WPRetail\Modules\Modules();
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
}
