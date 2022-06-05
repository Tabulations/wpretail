<?php

namespace WPRetail\Modules\Core\Form\Fields;

use WPRetail\Interfaces\Field as InterfacesField;

/**
 * Fields
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
abstract class Field implements InterfacesField {

	/**
	 * Type
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Field
	 *
	 * @var array
	 */
	public $field;

	/**
	 * Form
	 *
	 * @var array
	 */
	public $form;

	/**
	 * Constructor.
	 *
	 * @param array $field Field.
	 * @param array $form Form.
	 */
	public function __construct( $field, $form ) {
		$field       = wp_parse_args(
			$field,
			[
				'value' => '',
				'name'  => '',
			]
		);
		$this->field = $field;
		$this->form  = $form;
	}

	/**
	 * Display Field Before.
	 *
	 * @return void
	 */
	public function display_before() {
		echo '<div class="mb-3">';

		if ( empty( $this->form['hide_label'] ) && empty( $this->field['hide_label'] ) ) {
			echo '<label for="' . esc_attr( $this->field['name'] ) . '" class="form-label">' . esc_html( $this->field['label'] ) . ( ! empty( $this->field['required'] ) ? '*' : '' ) . '</label>';
		}
		if ( ! empty( $this->form['display_errors_top'] ) ) {
			echo '<div class="wpretail-forms-field-errors"></div>';
		}
	}

	/**
	 * Display Field After.
	 *
	 * @return void
	 */
	public function display_after() {
		if ( empty( $this->form['display_errors_top'] ) ) {
			echo '<div class="wpretail-forms-field-errors"></div>';
		}
		echo '</div>';
	}

	/**
	 * Html Div Function.
	 *
	 * @param string $tag Tag.
	 * @param string $args Args.
	 */
	public static function html( $tag, $args = [] ) {
		if ( empty( $args ) ) {
			echo '</' . esc_attr( $tag ) . '>';
			return;
		}

		if ( empty( $args['class'] ) ) {
			$args['class'] = [];
		}

		if ( empty( $args['tag'] ) ) {
			$args['tag'] = $tag;
		}

		if ( empty( $args['attr'] ) ) {
			$args['attr'] = [];
		}

		if ( empty( $args['data'] ) ) {
			$args['data'] = [];
		}

		// Opening.
		echo '<' . esc_attr( $tag );

		self::parse_args( $args );

		echo '>';

		self::parse_content( $args );
		if ( ! empty( $args['closed'] ) ) {
			echo '</' . esc_attr( $tag ) . '>';
		}
	}

	/**
	 * Parse Args.
	 *
	 * @param mixed $args Args.
	 * @return void
	 */
	public static function parse_args( $args ) {
		foreach ( $args as $arg_key => $arg ) {
			switch ( $arg_key ) {
				case 'class':
					self::parse_class( $arg );
					break;
				case 'id':
					self::parse_id( $arg );
					break;
				case 'data':
					self::parse_data( $arg );
					break;
				case 'attr':
					self::parse_attr( $arg );
					break;
			}
		}
	}

	/**
	 * Parse Connection
	 *
	 * @param mixed $args Args.
	 * @return void
	 */
	public static function parse_content( $args ) {
		if ( ! empty( $args['content'] ) ) {
			switch ( $args['tag'] ) {
				case 'div':
				case 'p':
				case 'textarea':
					echo wp_kses_post( $args['content'] );
					break;
				case 'strong':
				case 'small':
				case 'option':
				case 'span':
				case 'h1':
				case 'h2':
				case 'h3':
				case 'h4':
				case 'h5':
				case 'ul':
				case 'li':
				case 'a':
				case 'b':
				case 'i':
				case 'label':
				case 'button':
				case 'th':
				case 'td':
					if ( ! empty( $args['allowed_html'] ) ) {
						echo wp_kses( $args['content'], $args['allowed_html'] );
					} else {
						echo esc_html( $args['content'] );
					}
					break;
			}
		}
	}

	/**
	 * Parse Class.
	 *
	 * @param mixed $class Class.
	 * @return void
	 */
	public static function parse_class( $class ) {
		if ( ! empty( $class ) && is_array( $class ) ) {
			echo ' class="' . esc_attr( implode( ' ', $class ) ) . '"';
		}
	}

	/**
	 * Parse ID.
	 *
	 * @param mixed $id ID.
	 * @return void
	 */
	public static function parse_id( $id ) {
		echo ' id="' . esc_attr( $id ) . '"';
	}

	/**
	 * Parse Data
	 *
	 * @param mixed $data Data.
	 * @return void
	 */
	public static function parse_data( $data ) {
		if ( ! empty( $data ) && is_array( $data ) ) {
			foreach ( $data as $d => $dt ) {
				echo ' data-' . esc_attr( $d ) . '="' . esc_attr( $dt ) . '"';
			}
		}
	}

	/**
	 * Parse Attr.
	 *
	 * @param mixed $attr Attr.
	 * @return void
	 */
	public static function parse_attr( $attr ) {
		if ( ! empty( $attr ) && is_array( $attr ) ) {
			foreach ( $attr as $a => $at ) {
				if ( is_numeric( $a ) ) {
					echo ' ' . esc_attr( $at );
				} else {
					echo ' ' . esc_attr( $a );
					echo '="' . esc_attr( $at ) . '"';
				}
			}
		}
	}

	/**
	 * Input.
	 *
	 * @param mixed $input Input.
	 * @return void
	 */
	public static function input( $input ) {
		if ( empty( $input ) ) {
			return;
		}
		if ( ! empty( $input ) && ! empty( $input['class'] ) ) {
			$input['class'] [] = 'form-control';
		} else {
			$input['class'] = [ 'form-control' ];
		}

		if ( empty( $input['icon'] ) ) {
			$input['closed'] = true;
		}

		if ( empty( $input['type'] ) ) {
			$input['type'] = 'text';
		}

		if ( empty( $input['name'] ) ) {
			$input['name'] = '';
		}

		if ( empty( $input['id'] ) ) {
			$input['id'] = $input['name'];
		}

		if ( empty( $input['value'] ) ) {
			$input['value'] = '';
		}

		if ( ! empty( $input['icon'] ) ) {
			self::html( 'div', [ 'class' => [ 'input-group' ] ] );
			if ( empty( $input['icon_after_input'] ) ) {
				self::html( 'span', [ 'class' => [ 'input-group-text' ] ] );
				self::html(
					'i',
					[
						'class'  => [ $input['icon'] ],
						'closed' => true,
					]
				);
				self::html( 'span' );
			}
		}

		switch ( $input['type'] ) {
			case 'file':
			case 'text':
				$input['attr']['name']  = $input['name'];
				$input['attr']['value'] = $input['value'];
				$input['attr']['type']  = $input['type'];
				self::html( 'input', $input );
				break;
			case 'textarea':
				$input['attr']['name'] = $input['name'];
				$input['content']      = $input['value'];
				unset( $input['type'] );
				unset( $input['value'] );
				unset( $input['closed'] );
				self::html( 'textarea', $input );
				self::html( 'textarea' );
				break;
			case 'select':
				$input['attr']['name']  = $input['name'];
				$input['attr']['value'] = $input['value'];
				$input['attr']['type']  = $input['type'];

				if ( in_array( 'multiple', $input['attr'] ) ) {
					$input['attr']['name'] = $input['attr']['name'] . '[]';
				}
				unset( $input['type'] );
				unset( $input['value'] );
				unset( $input['closed'] );
				self::html( 'select', $input );
				if ( ! empty( $input['options'] ) ) {
					foreach ( $input['options'] as $key => $option ) {
						if ( ! empty( $input['has_key'] ) ) {
							self::html(
								'option',
								[
									'attr'    => [
										'value' => $key,
										selected( $key, ! empty( $input['attr']['value'] ) ? $input['attr']['value'] : '' ),
									],
									'content' => $option,
									'closed'  => true,
								]
							);
						} else {
							self::html(
								'option',
								[
									'attr'    => [
										'value' => $option,
										selected( $option, ! empty( $input['attr']['value'] ) ? $input['attr']['value'] : '' ),
									],
									'content' => $option,
									'closed'  => true,
								]
							);
						}
					}
				}
				self::html( 'select', $input );
				break;
			case 'radio':
			case 'checkbox':
				if ( ! empty( $input['options'] ) ) {
					foreach ( $input['options'] as $key => $option ) {
						$input_args = [
							'attr'  => [
								'type' => 'checkbox',
							],
							'class' => [ 'form-check' ],
						];
						$div_args   = [
							'class' => array_merge( ! empty( $input['class'] ) ? array_pop( $input['class'] ) : [], [ 'form-check-group' ] ),
						];
						$label_args = [];
						if ( ! empty( $input['id'] ) ) {
							$input_args['attr']['id']  = $input['id'] . '_' . $key;
							$label_args['attr']['for'] = $input['id'] . '_' . $key;
						} else {
							$input_args['attr']['id']  = $input['name'] . '_' . $key;
							$label_args['attr']['for'] = $input['name'] . '_' . $key;
						}
						if ( ! empty( $input['has_key'] ) ) {
							$input_args['attr']['value'] = $key;
							if ( ! empty( $input['value'] ) && $key === $input['value'] ) {
								$input_args['attr']['checked'] = 'checked';
							}
							$label_args['content'] = $option;
						} else {
							$input_args['attr']['value'] = $option;
							if ( ! empty( $input['value'] ) && $option === $input['value'] ) {
								$input_args['attr']['checked'] = 'checked';
							}
							$label_args['content'] = $option;
						}
						self::html( 'div', $div_args );
						self::html( 'input', $input_args );
						self::html( 'label', $label_args );
						self::html( 'div' );
						unset( $input_args['attr']['checked'] );
					}
				}
				break;
			case 'range':
				$input['class'][]       = 'form-range';
				$input['attr']['name']  = $input['name'];
				$input['attr']['value'] = $input['value'];
				$input['attr']['type']  = $input['type'];
				self::html( 'input', $input );
				break;
			case 'datepicker':
				$input['class'][]       = 'wpretail-datepicker';
				$input['attr']['name']  = $input['name'];
				$input['attr']['value'] = $input['value'];
				$input['attr']['type']  = 'text';

				$input['data']['format'] = get_option( 'date_format' );

				self::html( 'input', $input );

				wp_enqueue_style( 'wpretail_style_datepicker' );
				wp_enqueue_script( 'wpretail_script_datepicker' );
				break;
			case 'submit':
				$input['class']         = array_diff( $input['class'], [ 'form-control' ] );
				$input['attr']['name']  = $input['name'];
				$input['attr']['value'] = $input['value'];
				$input['attr']['type']  = 'submit';
				self::html( 'button', $input );
				break;
			default:
				if ( ! empty( $input['attr']['list'] ) && ! empty( $input['list_options'] ) ) {
					self::html( 'input', $input );
					self::html( 'datalist', [ 'id' => $input['attr']['list'] ] );
					foreach ( $input['list_options'] as $option ) {
						self::html(
							'option',
							[
								'attr' => [ 'value' => $option ],
							]
						);
					}
					self::html( 'datalist' );
				}
				break;
		}

		if ( ! empty( $args['icon'] ) ) {
			if ( ! empty( $args['icon_after_input'] ) ) {
				self::html( 'span', [ 'class' => [ 'input-group-text' ] ] );
				self::html(
					'i',
					[
						'class'  => [ $args['icon'] ],
						'closed' => true,
					]
				);
				self::html( 'span' );
			}
			self::html( 'div' );
		}
	}
}
