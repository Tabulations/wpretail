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
class Select extends Field {

	/**
	 * Constructor.
	 *
	 * @param array $field Field.
	 * @param array $form Form.
	 */
	public function __construct( $field, $form ) {
		$this->type = 'select';

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
		$args = [
			'has_key' => ! empty( $this->field['has_key'] ) ? $this->field['has_key'] : false,
			'type'    => $this->type,
			'id'      => $this->field['name'],
			'name'    => $this->field['name'],
			'value'   => $this->field['value'],
			'icon'    => ! empty( $this->field['display_icon'] ),
			'options' => $this->field['options'],
		];
		self::input( $args );
	}
}
