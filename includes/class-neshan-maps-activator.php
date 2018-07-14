<?php

/**
 * Fired during plugin activation
 *
 * @link       https://platform.neshan.org
 * @since      1.0.0
 *
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Neshan_Maps
 * @subpackage Neshan_Maps/includes
 * @author     Neshan Platform <platform@neshan.org>
 */
class Neshan_Maps_Activator {

	/**
	 * Creating table for storing maps data if it's not exists.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$usersTable = "CREATE TABLE IF NOT EXISTS neshan_maps
		    (
		        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		        title TEXT,
		        created_at DATETIME NOT NULL,
		        updated_at DATETIME NOT NULL,
		        data VARCHAR(255) NOT NULL
		    ) ENGINE = InnoDB DEFAULT CHARSET = UTF8 AUTO_INCREMENT = 1;";

		! function_exists( 'dbDelta' ) && require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $usersTable );
	}

}
