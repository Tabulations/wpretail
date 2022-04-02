<?php

namespace WPRetail;

/**
 * Core Functions.
 *
 * Contains a bunch of helper methods.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sales Handler class.
 */
class WPRetail_Install {

	/**
	 * DB updates and callbacks that need to be run per version.
	 *
	 * @var array
	 */
	private static $db_updates = [
		'1.0.0' => [
			'wpretail_update_100_db_version',
		],
	];

	/**
	 * Background update class.
	 *
	 * @var object
	 */
	private static $background_updater;

	/**
	 * Init.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'check_version' ], 5 );
		add_action( 'admin_init', [ __CLASS__, 'install_actions' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( WPRETAIL_PLUGIN_FILE ), [ __CLASS__, 'plugin_action_links' ] );

	}

	/**
	 * Init background updates
	 */
	public static function init_background_updater() {

	}

		/**
		 * Check WPRETAIL version and run the updater is required.
		 *
		 * This check is done on all requests and runs if the versions do not match.
		 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && version_compare( get_option( 'wpretail_version' ), wpretail()->version, '<' ) ) {
			self::install();
			do_action( 'wpretail_updated' );
		}
	}

	/**
	 * Install actions when a update button is clicked within the admin area.
	 *
	 * This function is hooked into admin_init to affect admin only.
	 */
	public static function install_actions() {
		if ( ! empty( $_GET['do_update_wpretail'] ) ) {
			check_admin_referer( 'wpretail_db_update', 'evf_db_update_nonce' );
			self::update();
		}
		if ( ! empty( $_GET['force_update_wpretail'] ) ) {
			do_action( 'wp_' . get_current_blog_id() . '_wpretail_updater_cron' );
			wp_safe_redirect( admin_url( 'admin.php?page=wpretail-settings' ) );
			exit;
		}
	}

		/**
		 * Display action links in the Plugins list table.
		 *
		 * @param  array $actions Plugin Action links.
		 * @return array
		 */
	public static function plugin_action_links( $actions ) {
		$new_actions = [
			'settings' => '<a href="' . admin_url( 'admin.php?page=wpretail-settings' ) . '" aria-label="' . esc_attr__( 'View WPRETAIL  Settings', 'wpretail' ) . '">' . esc_html__( 'Settings', 'wpretail' ) . '</a>',
		];

		return array_merge( $new_actions, $actions );
	}

	/**
	 * Install EVF
	 *
	 * @since 1.0.0
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'wpretail_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'wpretail_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		self::remove_admin_notices();
		self::create_options();
		self::create_tables();
		self::create_files();

		// Removing Transient.
		delete_transient( 'wpretail_installing' );

		do_action( 'wpretail_flush_rewrite_rules' );
		do_action( 'wpretail_installed' );
	}

	/**
	 * Create Options.
	 *
	 * @return void
	 */
	public static function create_options() {
		// Later to be implemented.
	}

	/**
	 * Create Files.
	 *
	 * @return void
	 */
	public static function create_files() {
		// Later to be implemented.
	}

	/**
	 * Reset any notices added to admin.
	 */
	private static function remove_admin_notices() {
		// Later to be implemented.
	}

	/**
	 * Setup WPRETAIL environment - post types, taxonomies, endpoints.
	 */
	private static function setup_environment() {
		// Later to be implemented.
	}

	/**
	 * Is this a brand new WPRETAIL install?
	 *
	 * @return boolean
	 */
	private static function is_new_install() {
		return is_null( get_option( 'wpretail_version', null ) ) && is_null( get_option( 'wpretail_db_version', null ) );
	}

	/**
	 * Is a DB update needed?
	 *
	 * @return void
	 */
	public static function needs_db_update() {
		// Later to be implemented.
	}

	/**
	 * See if we need to set redirect transients for activation or not.
	 */
	private static function maybe_set_activation_transients() {
		if ( self::is_new_install() ) {
			set_transient( '_wpretail_activation_redirect', 1, 30 );
		}
	}

		/**
		 * See if we need to show or run database updates during install.
		 */
	private static function maybe_update_db_version() {
		// Later to be implemented.
	}

	/**
	 * Store the initial plugin activation date during install.
	 */
	private static function maybe_add_activated_date() {
		$activated_date = get_option( 'wpretail_activated', '' );
		if ( empty( $activated_date ) ) {
			update_option( 'wpretail_activated', time() );
		}
	}

	/**
	 * Set up the database tables which the plugin needs to function.
	 */
	public static function create_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		/**
		 * Change wp_wpretail_sessions schema to use a bigint auto increment field
		 * instead of char(32) field as the primary key. Doing this change primarily
		 * as it should reduce the occurrence of deadlocks, but also because it is
		 * not a good practice to use a char(32) field as the primary key of a table.
		 */
		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wpretail_sessions'" ) ) {
			if ( ! $wpdb->get_var( "SHOW KEYS FROM {$wpdb->prefix}wpretail_sessions WHERE Key_name = 'PRIMARY' AND Column_name = 'session_id'" ) ) {
				$wpdb->query(
					"ALTER TABLE `{$wpdb->prefix}wpretail_sessions` DROP PRIMARY KEY, DROP KEY `session_id`, ADD PRIMARY KEY(`session_id`), ADD UNIQUE KEY(`session_key`)"
				);
			}
		}
		dbDelta( self::get_schema() );
	}

	/**
	 * Get Schema.
	 *
	 * @return mixed
	 */
	private static function get_schema() {
		global $wpdb;
		$charset_collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$charset_collate = $wpdb->get_charset_collate();
		}
		$tables = "
			CREATE TABLE {$wpdb->prefix}wpretail_currencies(
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(256),
				description VARCHAR(200) NULL,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
			CREATE TABLE {$wpdb->prefix}wpretail_business (
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name text NOT NULL,
				currency_id BIGINT UNSIGNED NOT NULL,
				start_date DATETIME NULL,
				tax_number_1 VARCHAR(100) NOT NULL,
				tax_label_1 VARCHAR(10) NOT NULL,
				tax_number_2 VARCHAR(100) NULL,
				tax_label_2 VARCHAR(10) NULL,
				default_profit_percent FLOAT(5,2) DEFAULT 0,
				owner_id BIGINT UNSIGNED,
				fiscal_year_start_month TINYINT DEFAULT 1,
				accounting_method ENUM('fifo','lifo','avco') DEFAULT 'fifo',
				default_sale_discount DECIMAL(5,2) NULL,
				FOREIGN KEY(currency_id) REFERENCES {$wpdb->prefix}wpretail_currencies(id),
				logo text NULL,
				sku_prefix text NULL,
				enable_tooltip BOOLEAN DEFAULT 1,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
			CREATE TABLE {$wpdb->prefix}wpretail_business_location(
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				business_id BIGINT UNSIGNED NOT NULL,
				FOREIGN KEY(business_id) REFERENCES {$wpdb->prefix}wpretail_business(id) ON DELETE CASCADE,
				name VARCHAR(256) NOT NULL,
				landmark text NULL,
				country VARCHAR(100) NOT NULL,
				state VARCHAR(100) NOT NULL,
				city VARCHAR(100) NOT NULL,
				zip_code VARCHAR(256) NOT NULL,
				mobile VARCHAR(256) NUll,
				alternate_number VARCHAR(10) NUll,
				email VARCHAR(256) NUll,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
			CREATE TABLE {$wpdb->prefix}wpretail_categories(
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(256),
				business_id BIGINT UNSIGNED NOT NULL,
				FOREIGN KEY(business_id) REFERENCES {$wpdb->prefix}wpretail_business(id) ON DELETE CASCADE,
				short_code VARCHAR(256) NULL,
				parent_id BIGINT,
				created_by BIGINT UNSIGNED,
				FOREIGN KEY(created_by) REFERENCES {$wpdb->prefix}users(id) ON DELETE CASCADE,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
			CREATE TABLE {$wpdb->prefix}wpretail_brands(
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				business_id BIGINT UNSIGNED NOT NULL,
				FOREIGN KEY(business_id) REFERENCES {$wpdb->prefix}wpretail_business(id) ON DELETE CASCADE,
				name VARCHAR(256),
				description text NUll,
				created_by BIGINT UNSIGNED,
				FOREIGN KEY(created_by) REFERENCES {$wpdb->prefix}users(id) ON DELETE CASCADE,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
			CREATE TABLE {$wpdb->prefix}wpretail_warranties(
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(256),
				business_id BIGINT,
				description VARCHAR(200) NULL,
				duration BIGINT,
				duration_type ENUM('days','months','years'),
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
			CREATE TABLE {$wpdb->prefix}wpretail_tax_rates(
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(256),
				business_id BIGINT,
				description VARCHAR(200) NULL,
				duration BIGINT,
				duration_type ENUM('days','months','years'),
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
			CREATE TABLE {$wpdb->prefix}wpretail_products(
				id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name VARCHAR(256),
				business_id BIGINT UNSIGNED,
				FOREIGN KEY(business_id) REFERENCES {$wpdb->prefix}wpretail_business(id) ON DELETE CASCADE,
				type ENUM('single','variable'),
				brand_id BIGINT UNSIGNED,
				FOREIGN KEY(brand_id) REFERENCES {$wpdb->prefix}wpretail_brands(id) ON DELETE CASCADE,
				category_id BIGINT UNSIGNED,
				FOREIGN KEY(category_id) REFERENCES {$wpdb->prefix}wpretail_categories(id) ON DELETE CASCADE,
				sub_category_id BIGINT UNSIGNED,
				FOREIGN KEY(sub_category_id) REFERENCES {$wpdb->prefix}wpretail_categories(id) ON DELETE CASCADE,
				tax BIGINT UNSIGNED NULL,
				FOREIGN KEY(tax) REFERENCES {$wpdb->prefix}wpretail_tax_rates(id),
				tax_type ENUM('inclusive','exclusive'),
				enable_stock BOOLEAN DEFAULT 0,
				alert_quantity DECIMAL(22,4) DEFAULT 0,
				sku VARCHAR(256),
				barcode_type ENUM('C39', 'C128', 'EAN-13', 'EAN-8', 'UPC-A', 'UPC-E', 'ITF-14'),
				created_by BIGINT UNSIGNED,
				FOREIGN KEY(created_by) REFERENCES {$wpdb->prefix}users(id) ON DELETE CASCADE,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			) $charset_collate;
		";
		return $tables;
	}

	/**
	 * Return a list of WPREATAIL tables. Used to make sure all UM tables are dropped when uninstalling the plugin
	 * in a single site or multi site environment.
	 *
	 * @return array UM tables.
	 */
	public static function get_tables() {
		global $wpdb;

		$tables = [
			"{$wpdb->prefix}business",
			"{$wpdb->prefix}business_location",
			"{$wpdb->prefix}products",
			"{$wpdb->prefix}brands",
			"{$wpdb->prefix}categories",
			"{$wpdb->prefix}warranties",
			"{$wpdb->prefix}wpretail_sessions",
		];

		return $tables;
	}

	/**
	 * Drop WPRETAIL tables.
	 */
	public static function drop_tables() {
		global $wpdb;

		$tables = self::get_tables();

		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
	}

	/**
	 * Uninstall tables when MU blog is deleted.
	 *
	 * @param  array $tables List of tables that will be deleted by WP.
	 * @return string[]
	 */
	public static function wpmu_drop_tables( $tables ) {
		return array_merge( $tables, self::get_tables() );
	}

	/**
	 * Create roles and capabilities.
	 */
	public static function create_roles() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles(); // @codingStandardsIgnoreLine
		}

		$capabilities = self::get_core_capabilities();

		foreach ( $capabilities as $cap_group ) {
			foreach ( $cap_group as $cap ) {
				$wp_roles->add_cap( 'administrator', $cap );
			}
		}
	}

	/**
	 * Get the core capabilities.
	 *
	 * Core capabilities are assigned to admin during installation or reset.
	 *
	 * @since 1.0.0
	 * @since 1.7.5 Removed unused post type capabilities and added supported ones.
	 *
	 * @return array $capabilities Core capabilities.
	 */
	private static function get_core_capabilities() {
		$capabilities = [];

		$capabilities['core'] = [
			'manage_wpretail',
		];

		$capability_types = [ 'table' ];

		foreach ( $capability_types as $capability_type ) {
			if ( 'forms' === $capability_type ) {
				$capabilities[ $capability_type ][] = "wpretail_create_{$capability_type}";
			}

			foreach ( [ 'view', 'edit', 'delete' ] as $context ) {
				$capabilities[ $capability_type ][] = "wpretail_{$context}_{$capability_type}";
				$capabilities[ $capability_type ][] = "wpretail_{$context}_others_{$capability_type}";
			}
		}
		return $capabilities;
	}
}
