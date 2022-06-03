<?php

namespace WPRetail\Modules;

/**
 * Modules.
 *
 * Contains a bunch of modules for WPRetail Pro.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sales Handler class.
 */
class Modules {

	/**
	 * Test Function.
	 */
	public function __construct() {
		$this->register_modules();
	}

	/**
	 * Registering All The Modules.
	 *
	 * @return void
	 */
	public function register_modules() {

		// Get Modules.
		$modules = apply_filters(
			'wpretails_regsiter_modules',
			[
				'core' => 'WPRetail\\Modules\\Core\\Core',
				'pdf'  => 'WPRetail\\Modules\\Pdf\\Pdf',
			]
		);

		// Registering Modules.
		foreach ( $modules as $module ) {
			if ( class_exists( $module ) ) {
				new $module();
			}
		}
	}
}
