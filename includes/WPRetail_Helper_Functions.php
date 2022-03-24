<?php

namespace WPRetail;

use WPRetail\Traits\WPRetail_Html_Builder;

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
class WPRetail_Helper_Functions {
	use WPRetail_Html_Builder;

	/**
	 * Test Function
	 */
	public function test() {
		return 'Hello, This is a test function';
	}
}
