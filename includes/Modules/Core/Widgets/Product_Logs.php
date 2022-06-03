<?php

namespace WPRetail\Modules\Core\Widgets;

use WPRetail\Interfaces\Widget;

/**
 * Product Logs
 *
 * @package WPRetail\Core
 *
 * @since 1.0.0
 */
class Product_Logs implements Widget {

	/**
	 * Title.
	 *
	 * @var mixed
	 */
	public $title;

	/**
	 * Constructor.
	 *
	 * @param mixed $args Args.
	 */
	public function __construct( $args = [] ) {
		$args        = wp_parse_args(
			$args,
			[
				'title' => __( 'Product Logs', 'wpretail' ),
			]
		);
		$this->title = $args['title'];
	}

	/**
	 * Render Widget.
	 *
	 * @return void
	 */
	public function render() {
		echo '<div class="wpretail-widget">';
		echo '<h3 class="h3 mb-2 text-gray-800">' . esc_html( $this->title ) . '</h3>';
		$logs = [
			[
				'id'    => 1,
				'title' => 'Product Added',
				'badge' => 'Insert',
			],
			[
				'id'    => 2,
				'title' => 'Product Updated',
				'badge' => 'Update',
			],
			[
				'id'    => 3,
				'title' => 'Product Deleted',
				'badge' => 'Delete',
			],
		];

		echo '<table class="table"><thead><tr><th colspan="2">Logs</th></tr></thead><tbody>';
		foreach ( $logs as $log ) {
			echo '<tr><td><span class="badge badge-primary">' . esc_html( $log['badge'] ) . '</span></td><td>' . esc_html( $log['title'] ) . '</td></tr>';
		}
		echo '</tbody></table>';
		echo '</div>';
	}
}
