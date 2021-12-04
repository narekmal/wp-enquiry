<?php

/**
 * Fired during plugin activation
 *
 * @link       https://narek-dev.com
 * @since      1.0.0
 *
 * @package    Enquiry
 * @subpackage Enquiry/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Enquiry
 * @subpackage Enquiry/includes
 * @author     Narek Malkhasyan <narek.mal@gmail.com>
 */
class Enquiry_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'enquiry_form_data';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			first_name text NOT NULL,
			last_name text NOT NULL,
			email text NOT NULL,
			subject text NULL,
			message text NOT NULL,
			PRIMARY KEY  (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
