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
class WPRetail_Html_Builder {

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
					echo esc_html( $args['content'] );
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
	 * @param mixed $args Args.
	 * @return void
	 */
	public static function input( $args ) {
		$container = [];
		$label     = [];
		$input     = [];

		if ( ! empty( $args['container'] ) ) {
			$container = $args['container'];
		}
		if ( ! empty( $container ) && ! empty( $container['class'] ) ) {
			$container['class'] [] = 'mb-3';
		} else {
			$container['class'] = [ 'mb-3' ];
		}

		if ( ! empty( $args['label'] ) ) {
			$label = $args['label'];
		}
		if ( ! empty( $label ) && ! empty( $label['label'] ) ) {
			$label['class'] [] = 'form-label';
		} else {
			$label['class'] = [ 'form-label' ];
		}

		if ( ! empty( $args['input'] ) ) {
			$input = $args['input'];
		}
		if ( ! empty( $input ) && ! empty( $input['input'] ) ) {
			$input['class'] [] = 'form-label';
		} else {
			$input['class'] = [ 'form-control' ];
		}

		if ( ! empty( $input['id'] ) ) {
			$label['attr']['for'] = $input['id'];
		}

		$label['closed'] = true;
		$input['closed'] = true;

		// Open Div.
		self::html( 'div', $container );
		self::html( 'label', $label );

		if ( empty( $input['type'] ) ) {
			$input['type'] = '';
		}

		if ( empty( $input['name'] ) ) {
			$input['name'] = '';
		}

		if ( empty( $input['id'] ) ) {
			$input['id'] = '';
		}

		if ( empty( $input['value'] ) ) {
			$input['value'] = '';
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
				if ( ! empty( $input['options']['items'] ) ) {
					$div_args   = ! empty( $input['options']['div'] ) ? $input['options']['div'] : [];
					$input_args = ! empty( $input['options']['input'] ) ? $input['options']['input'] : [];
					$label_args = ! empty( $input['options']['label'] ) ? $input['options']['label'] : [];

					if ( empty( $div_args['class'] ) ) {
						$div_args['class'] = [ 'form-check' ];
					} else {
						$div_args['class'] [] = 'form-check';
					}

					if ( empty( $input_args['class'] ) ) {
						$input_args['class'] = [ 'form-check-input' ];
					} else {
						$input_args['class'] [] = 'form-check-input';
					}
					$input_args['attr']['type']  = $input['type'];
					$input_args['attr']['name']  = $input['name'];
					$input_args['attr']['value'] = $input['value'];

					if ( empty( $label_args['class'] ) ) {
						$label_args['class'] = [ 'form-check-label' ];
					} else {
						$label_args['class'] [] = 'form-check-label';
					}

					$input_args['closed'] = true;
					$label_args['closed'] = true;

					if ( ! empty( $input['options'] ) ) {
						foreach ( $input['options']['items'] as $key => $option ) {
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
								$label_args['content'] = $key;
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
				}
				break;
			case 'range':
				$input['class'][]       = 'form-range';
				$input['attr']['name']  = $input['name'];
				$input['attr']['value'] = $input['value'];
				$input['attr']['type']  = $input['type'];
				self::html( 'label', $label );
				self::html( 'input', $input );
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

		// Invalid Message.
		self::html(
			'div',
			[
				'class'  => [ 'invalid-feedback' ],
				'closed' => true,
			]
		);
		// Close Div.
		self::html( 'div' );
	}
}
