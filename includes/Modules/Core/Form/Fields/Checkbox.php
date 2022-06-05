<?php

namespace WPRetail\Modules\Core\Form\Fields;

use WPRetail\Modules\Core\Form\Fields\Field;

/**
 * Core Functions.
 *
 * This class loads helper class of the plugins.
 * To add helper, register helper using wpretail_register_helpers filter.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sales Handler class.
 */
class Checkbox extends Field {

	/**
	 * Constructor.
	 *
	 * @param array $field Field.
	 * @param array $form Form.
	 */
	public function __construct( $field, $form ) {
		$this->type = 'checkbox';

		parent::__construct( $field, $form );
	}

	/**
	 * Format Field Value.
	 *
	 * @param array $args Field Properties.
	 *
	 * @return void
	 */
	public function format( $args ) {
		echo esc_html( $args[0] );
	}

	/**
	 * Display Field.
	 *
	 * @return void
	 */
	public function display() {
		self::input( $this->field );
	}
}
