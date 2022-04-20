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
class WPRetail_Modules {

	/**
	 * Test Function.
	 */
	public function __construct() {
		add_filter( 'wpretail_registered_modules', [ $this, 'registered_modules' ] );
	}

	/**
	 * Registered modules.
	 *
	 * @param array $modules Modules
	 * @return array $modules Modules.
	 */
	public function registered_modules( $modules = [] ) {
		return array_merge(
			$modules,
			[
				'pdf'     => [
					'class' => 'WPRetail\Modules\Pdf\Module',
					'title' => 'WPRetail PDF',
					'description' => 'PDF is ............',
					'image' => 'https://play-lh.googleusercontent.com/BkRfMfIRPR9hUnmIYGDgHHKjow-g18-ouP6B2ko__VnyUHSi1spcc78UtZ4sVUtBH4g'
				],
				'invoice' => [
					'class' => 'WPRetail\Modules\Invoice\Module',
					'title' => 'WPRetail Invoice',
					'description' => 'Invoice is ............',
					'image' => 'https://image.shutterstock.com/shutterstock/photos/1928041154/display_1500/stock-vector-invoice-line-icon-design-isolated-on-white-background-1928041154.jpg'
				],
			]
		);
	}
}
