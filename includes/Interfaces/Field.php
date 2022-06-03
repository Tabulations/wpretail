<?php

namespace WPRetail\Interfaces;

/**
 * Field Interface.
 *
 * @package WPRetail\Interfaces
 *
 * @since 1.0.0
 */
interface Field {

	/**
	 * Display Field.
	 *
	 * @return void
	 */
	public function display();

	/**
	 * Display Field.
	 *
	 * @return void
	 */
	public function display_before();

	/**
	 * Display Field.
	 *
	 * @return void
	 */
	public function display_after();
}
