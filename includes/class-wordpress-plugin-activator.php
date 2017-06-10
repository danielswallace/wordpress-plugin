<?php

/**
 * Fired during plugin activation
 *
 * @link       http://github.com/danielswallace
 * @since      1.0.0
 *
 * @package    Wordpress_Plugin
 * @subpackage Wordpress_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wordpress_Plugin
 * @subpackage Wordpress_Plugin/includes
 * @author     Daniel Wallace <daniel.shawn.wallace@gmail.com>
 */
class Wordpress_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'wordpress_plugin';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
