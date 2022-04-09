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
 * Sales Handler class.
 */
class WPRetail_Ajax extends WPRetail_Sanitizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_wpretail_ajax_form_submission', [ $this, 'process_ajax' ] );
	}

	/**
	 * Processing Ajax.
	 *
	 * @return mixed
	 */
	public function process_ajax() {

		$this->sanitized_data();

		if ( ! empty( $this->errors ) ) {
			return wp_send_json_error( [ 'errors' => $this->errors ] );
		}

		do_action( $this->target . '_handler', $this );

		if ( ! empty( $this->errors ) ) {
			return wp_send_json_error( [ 'errors' => $this->errors ] );
		}

		if ( empty( $this->success['message'] ) ) {
			$this->success['message'] = __( 'Form has been saved successfully', 'wpretail' );
		}

		wp_send_json_success( [ 'success' => $this->success ] );
	}
}
