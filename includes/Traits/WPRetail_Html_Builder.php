<?php

namespace WPRetail\Traits;

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
trait WPRetail_Html_Builder {

	/**
	 * Html Div Function.
	 *
	 * @param string $tag Tag.
	 * @param string $args Args.
	 */
	public function html( $tag, $args = [] ) {
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

		$this->parse_args( $args );

		echo ' >';

		$this->parse_content( $args );
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
	public function parse_args( $args ) {
		foreach ( $args as $arg_key => $arg ) {
			switch ( $arg_key ) {
				case 'class':
					$this->parse_class( $arg );
					break;
				case 'id':
					$this->parse_id( $arg );
					break;
				case 'data':
					$this->parse_data( $arg );
					break;
				case 'attr':
					$this->parse_attr( $args['attr'] );
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
	public function parse_content( $args ) {
		if ( ! empty( $args['content'] ) ) {
			switch ( $args['tag'] ) {
				case 'div':
				case 'p':
					echo wp_kses_post( $args['content'] );
					break;
				case 'strong':
				case 'small':
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
	public function parse_class( $class ) {
		if ( empty( $class ) ) {
			echo ' class="' . esc_attr( implode( ' ', $class ) ) . '"';
		}
	}

	/**
	 * Parse ID.
	 *
	 * @param mixed $id ID.
	 * @return void
	 */
	public function parse_id( $id ) {
		echo ' id="wpretail-' . esc_attr( $id ) . '"';
	}

	/**
	 * Parse Data
	 *
	 * @param mixed $data Data.
	 * @return void
	 */
	public function parse_data( $data ) {
		foreach ( $data as $d => $dt ) {
			echo ' data-' . esc_attr( $d ) . '="' . esc_attr( $dt ) . '"';
		}
	}

	/**
	 * Parse Attr.
	 *
	 * @param mixed $attr Attr.
	 * @return void
	 */
	public function parse_attr( $attr ) {
		foreach ( $attr as $a => $at ) {
			echo ' ' . esc_attr( $a );
			if ( ! empty( $at ) ) {
				echo '="' . esc_attr( $at ) . '"';
			}
		}
	}
}