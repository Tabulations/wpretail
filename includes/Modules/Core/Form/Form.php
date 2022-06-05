<?php

namespace WPRetail\Modules\Core\Form;

/**
 * Forms.
 *
 * Abatract field class.
 *
 * @package WPRetail
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sales Handler class.
 */
class Form {

	/**
	 * Form ID.
	 *
	 * @var mixed
	 */
	public $form_id;

	/**
	 * Forms Args
	 *
	 * @param array $args Form Args.
	 *
	 * @var mixed
	 */
	public $args;

	/**
	 * Constructor.
	 *
	 * @param mixed $form_id Form Id.
	 * @param mixed $args Args.
	 */
	public function __construct( $form_id, $args ) {
		$this->form_id = $form_id;
		$this->args    = wp_parse_args(
			$args,
			[
				'display_title'       => true,
				'display_label'       => true,
				'display_field_icons' => false,
				'preview_form'        => false,
				'multi_step_form'     => false,
				'label_position'      => 'left',
			]
		);
	}

	/**
	 * Load Fields.
	 *
	 * @param array $field Field.
	 * @param array $form Form.
	 *
	 * @return object Field.
	 */
	public static function field( $field, $form ) {
		$fields = apply_filters(
			'wpretail_load_fields',
			[
				'text'     => 'WPRetail\\Modules\\Core\\Form\\Fields\\Text',
				'select'   => 'WPRetail\\Modules\\Core\\Form\\Fields\\Select',
				'checkbox' => 'WPRetail\\Modules\\Core\\Form\\Fields\\Checkbox',
			]
		);
		if ( class_exists( $fields[ $field['type'] ] ) ) {
			return new $fields[ $field['type'] ]( $field, $form );
		}
	}

	/**
	 * Load.
	 *
	 * @return void
	 */
	public function load() {
		$form = $this->get_form( $this->form_id );

		$class    = ! empty( $form['class'] ) ? $form['class'] : [];
		$class [] = 'wpretail-form';

		$id = ! empty( $form['id'] ) ? 'wpretail-' . $form['id'] : '';

		if ( empty( $form ) || empty( $form['fields'] ) ) {
			echo '<p class="alert alert-info mb-2 text-gray-800">' . esc_html__( 'Form not found, Please contact to your system administrator.', 'wpretail' ) . '</p>';
			return;
		}

		$data = ! empty( $form['data'] ) ? $form['data'] : [];

		echo '<form action="#" id="' . esc_attr( $id ) . '" class="' . esc_attr( implode( ' ', $class ) ) . '"';

		foreach ( $data as $k => $v ) {
			if ( ! empty( $v ) ) {
				echo ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
			} else {
				echo ' ' . esc_attr( $k );
			}
		}

		echo '>';

		if ( empty( $this->args['multi_step_form'] ) ) {
			foreach ( $form['fields'] as $field ) {
				$field = self::field( $field, $form );
				$field->display_before();
				$field->display();
				$field->display_after();
			}
		}

		echo '<div class="mb-3">';
		echo '<input type="submit" name="submit" value="submit"/>';
		echo '</div>';

		echo '</form>';

		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			echo wp_kses_post( '<pre>' . print_r( $form, true ) . '</pre>' );
		}
	}

	/**
	 * Get Forms.
	 *
	 * @param mixed $form Form.
	 * @return mixed
	 */
	public function get_form( $form ) {
		$forms = apply_filters( 'wpretail_register_forms', [] );
		return $forms[ $form ];
	}
}
