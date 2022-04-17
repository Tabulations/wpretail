<?php

namespace WPRetail\Ajax;

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
 * Post Data Sanitizer and Validator class.
 */
abstract class WPRetail_Sanitizer {

	/**
	 * Errors.
	 *
	 * @var mixed
	 */
	public $errors;

	/**
	 * Success.
	 *
	 * @var mixed
	 */
	public $success;

	/**
	 * Sanitized fields.
	 *
	 * @var mixed
	 */
	public $sanitized_fields;

	/**
	 * Target.
	 *
	 * @var mixed
	 */
	public $target;

	/**
	 * Handle.
	 *
	 * @var mixed
	 */
	public $handle;

	/**
	 * Event.
	 *
	 * @var mixed
	 */
	public $event;

	/**
	 * Sanitized Post Data
	 *
	 * @return $sanitized_fields Sanized fields.
	 */
	public function sanitized_data() {

		if ( isset( $_POST['wpretail_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['wpretail_nonce'] ) ), 'wpretail_nonce' ) ) {

			if ( isset( $_POST['id'] ) ) {
				$this->event['id'] = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
			}

			$this->target = isset( $_POST['form_id'] ) ? sanitize_text_field( wp_unslash( $_POST['form_id'] ) ) : '';

			if ( empty( $this->target ) ) {
				$this->errors['message'] = __( 'Target couldnot be found, Please reload the page and try again', 'wpretail' );
				return;
			};

			// Detecting Delete Action.
			if ( preg_match( '/wpretail_list/', $this->target ) ) {
				$this->event['action'] = isset( $_POST['event'] ) ? sanitize_text_field( wp_unslash( $_POST['event'] ) ) : '';
				return;
			}

			$this->handle = str_replace( 'wpretail_', '', $this->target );

			$all_fields = apply_filters( 'wpretail_form_fields_options', [] );

			if ( ! array_key_exists( $this->handle, $all_fields ) ) {
				$this->errors['message'] = __( 'Handle is invalid, Please reload the page and try again', 'wpretail' );
				return;
			};

			$form_fields = $all_fields[ $this->handle ];
			foreach ( $form_fields as $field ) {
				if ( empty( $field['input'] ) || empty( $field['input']['type'] ) || empty( $field['input']['name'] ) ) {
					continue;
				}
				$field_name                            = $field['input']['name'];
				$this->sanitized_fields[ $field_name ] = '';
				switch ( $field['input']['type'] ) {
					case 'text':
					case 'number':
					case 'select':
					case 'checkbox':
					case 'radio':
					case 'datepicker':
						if ( isset( $_POST['wpretail'][ $this->target ][ $field_name ] ) ) {
							$this->sanitized_fields[ $field_name ] = sanitize_text_field( wp_unslash( $_POST['wpretail'][ $this->target ][ $field_name ] ) );
						}
						break;
					case 'textarea':
						if ( isset( $_POST['wpretail'][ $this->target ][ $field_name ] ) ) {
							$this->sanitized_fields[ $field_name ] = sanitize_textarea_field( wp_unslash( $_POST['wpretail'][ $this->target ][ $field_name ] ) );
						}
						break;
				}

				$this->validate( $this->sanitized_fields[ $field_name ], $field );
			}

			if ( empty( $this->sanitized_fields ) ) {
				$this->errors['message'] = __( 'Form fields not found, Please reload the page and try again', 'wpretail' );
			};
			return;
		}
		$this->errors['message'] = __( 'Nonce verification failed, Please reload the page and try again', 'wpretail' );
	}

	/**
	 * Validate Field.
	 *
	 * @param mixed $value Value.
	 * @param mixed $field Field.
	 * @return void
	 */
	public function validate( $value, $field ) {
		if ( empty( $field['validations'] ) ) {
			return;
		}

		if ( ! in_array( 'required', $field['validations'], true ) && $this->required_validator( $value, $field ) ) {
			return;
		}

		$field_label = ! empty( $field['label']['content'] ) ? ucfirst( strtolower( preg_replace( '/\*$/', '', trim( $field['label']['content'] ) ) ) ) : 'This field';
		$validations = $field['validations'];
		foreach ( $validations as $validation ) {
			switch ( $validation ) {
				case 'required':
					if ( $this->required_validator( $value, $field ) ) {
						// translators: %s Field Label.
						$this->errors[ $field['input']['id'] ]['required'] = sprintf( __( '%s is required', 'wpretail' ), $field_label );
					}
					break;
				case 'email':
					if ( ! is_email( $value ) ) {
						$this->errors[ $field['input']['id'] ]['required'] = __( 'Please enter a valid email', 'wpretail' );
					}
					break;
				case preg_match( '/min\:.*/', $validation ) ? true : false:
					preg_match_all( '/min\:(\d+)/', $validation, $min );
					if ( ! empty( $min[1][0] ) && strlen( $value ) < $min[1][0] ) {
						// translators: %1$s  Field Label, %2$d Minimum length.
						$this->errors[ $field['input']['id'] ]['min'] = sprintf( __( '%1$s can not have less than %2$d characters', 'wpretail' ), $field_label, $min[1][0] );
					}
					break;
				case preg_match( '/max:.*/', $validation ) ? true : false:
					preg_match_all( '/max:(\d+)/', $validation, $max );
					if ( ! empty( $max[1][0] ) && strlen( $value ) > $max[1][0] ) {
						// translators: %1$s  Field Label, %2$d Maximum length.
						$this->errors[ $field['input']['id'] ]['max'] = sprintf( __( '%1$s can not have more than %2$d characters', 'wpretail' ), $field_label, $max[1][0] );
					}
					break;
				case 'number':
					if ( ! is_numeric( $value ) ) {
						// translators: %s Field Label.
						$this->errors[ $field['input']['id'] ]['number'] = sprintf( __( '%s must have numbers only', 'wpretail' ), $field_label );
					}
					break;
				case 'custom':
					do_action( 'wpretail_custom_validation', $field, $this );
					break;
			}
		}
	}

	/**
	 * Required Field Validator,
	 *
	 * @param mixed $value Value.
	 * @param mixed $field Field.
	 * @return bool
	 */
	public function required_validator( $value, $field ) {
		switch ( $field['input']['type'] ) {
			case 'text':
			case 'number':
			case 'select':
			case 'checkbox':
			case 'textarea':
				return empty( $value );
			case 'radio':
				return empty( $value );
			default:
				return empty( $value );
		}
	}
}
