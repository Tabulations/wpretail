<?php

namespace WPRetail\Db;

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
class WPRetail_Db {

	/**
	 * Table.
	 *
	 * @var mixed
	 */
	protected $table;

	/**
	 * DB.
	 *
	 * @var object $wpdb Wpdb.
	 */
	protected $db;

	/**
	 * Constructor.
	 *
	 * @param mixed $table Table.
	 */
	public function __construct( $table ) {
		global $wpdb;
		$this->db    = $wpdb;
		$this->table = $wpdb->prefix . $table;
	}

	/**
	 * Insert Data.
	 *
	 * @param mixed $args Args.
	 * @return mixed $args Args.
	 */
	public function insert( $args ) {
		return $this->db->insert(
			$this->table,
			$args
		);
	}

	/**
	 * Update Data.
	 *
	 * @param mixed $args Args.
	 * @param mixed $where Args.
	 * @return int ID.
	 */
	public function update( $args, $where ) {
		return $this->db->update(
			$this->table,
			$args,
			$where
		);
	}

	    /**
		 * Product Data.
		 *
		 * @param mixed $args Args.
		 * @param mixed $where Args.
		 * @return int ID.
		 */
		public function get_product( $id = null ) {
			if ( ! empty( $id ) ) {
				$unit = $this->db->get_row(
					$this->db->prepare(
						"SELECT * FROM {$this->db->prefix}wpretail_products
							WHERE `id` = %s",
						$id
					)
				);
			} else {
				$unit = $this->db->get_results(
					"SELECT * FROM {$this->db->prefix}wpretail_products
						WHERE status= TRUE"
				);
			}
			return (array) $unit;
		}

		/**
		 * Brand Data.
		 *
		 * @param mixed $args Args.
		 * @param mixed $where Args.
		 * @return int ID.
		 */
	public function get_brand( $id = null ) {
		if ( ! empty( $id ) ) {
			$brand = $this->db->get_row(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_brands WHERE `id` = %s",
					$id
				)
			);
		} else {
			$brand = $this->db->get_results(
				"SELECT * FROM {$this->db->prefix}wpretail_brands WHERE status = TRUE"
			);
		}
		return (array) $brand;
	}

		/**
		 * Categiry Data.
		 *
		 * @param mixed $args Args.
		 * @param mixed $where Args.
		 * @return int ID.
		 */
	public function get_category( $id = null ) {
		if ( ! empty( $id ) ) {
			$category = $this->db->get_row(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_categories WHERE `id` = %s",
					$id
				)
			);
		} else {
			$category = $this->db->get_results(
				"SELECT * FROM {$this->db->prefix}wpretail_categories WHERE status= TRUE"
			);
		}
		return (array) $category;
	}

		/**
		 * Warranty Data.
		 *
		 * @param mixed $args Args.
		 * @param mixed $where Args.
		 * @return int ID.
		 */
	public function get_warranty( $id = null ) {
		if ( ! empty( $id ) ) {
			$category = $this->db->get_row(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_warranties
						WHERE `id` = %s",
					$id
				)
			);
		} else {
			$category = $this->db->get_results(
				"SELECT * FROM {$this->db->prefix}wpretail_warranties
					WHERE status= TRUE"
			);
		}
		return (array) $category;
	}

			/**
		 * Unit Data.
		 *
		 * @param mixed $args Args.
		 * @param mixed $where Args.
		 * @return int ID.
		 */
		public function get_unit( $id = null ) {
			if ( ! empty( $id ) ) {
				$unit = $this->db->get_row(
					$this->db->prepare(
						"SELECT * FROM {$this->db->prefix}wpretail_units
							WHERE `id` = %s",
						$id
					)
				);
			} else {
				$unit = $this->db->get_results(
					"SELECT * FROM {$this->db->prefix}wpretail_units
						WHERE status= TRUE"
				);
			}
			return (array) $unit;
		}

	/**
	 * Get Business.
	 *
	 * @param mixed $id ID.
	 * @return mixed $business Business.
	 */
	public function get_business( $id = null ) {
		if ( ! empty( $id ) ) {
			$business = $this->db->get_row(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_business WHERE `id` = %s",
					$id
				)
			);
		} else {
			$business = $this->db->get_results(
				"SELECT * FROM {$this->db->prefix}wpretail_business WHERE status = TRUE"
			);
		}
		return (array) $business;
	}

	/**
	 * Get location.
	 *
	 * @param mixed $id ID.
	 * @return mixed $businesss Business.
	 */
	public function get_location( $id = null ) {
		if ( ! empty( $id ) ) {
			$business = $this->db->get_row(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_business_location WHERE `id` = %s",
					$id
				)
			);
		} else {
			$business = $this->db->get_results(
				"SELECT * FROM {$this->db->prefix}wpretail_business_location WHERE status = TRUE"
			);
		}
		return (array) $business;
	}

	/**
	 * Tax Rates.
	 *
	 * @param mixed $id ID.
	 * @return mixed $tax_rates Tax Rates.
	 */
	public function get_tax_rates( $id = null ) {
		if ( ! empty( $id ) ) {
			$tax_rates = $this->db->get_row(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_tax_rates WHERE `id` = %s",
					$id
				)
			);
		} else {
			$tax_rates = $this->db->get_results(
				"SELECT * FROM {$this->db->prefix}wpretail_tax_rates WHERE status = TRUE"
			);
		}
		return (array) $tax_rates;
	}

	/**
	 * Tax Rates.
	 *
	 * @param mixed $id ID.
	 * @return mixed $tax_rates Tax Rates.
	 */
	public function get_tax_groups( $id = null ) {
		if ( ! empty( $id ) ) {
			$tax_groups = $this->db->get_row(
				$this->db->prepare(
					"SELECT * FROM {$this->db->prefix}wpretail_tax_groups WHERE `id` = %s",
					$id
				)
			);
		} else {
			$tax_groups = $this->db->get_results(
				"SELECT * FROM {$this->db->prefix}wpretail_tax_groups WHERE status = TRUE"
			);
		}
		return (array) $tax_groups;
	}

	/**
	 * Get last insert ID.
	 *
	 * @return int ID
	 */
	public function get_last_insert_id() {
		return $this->db->insert_id;
	}
}
