<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://platform.neshan.org
 * @since             1.0.0
 * @package           Neshan_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       Neshan Maps
 * Plugin URI:        https://platform.neshan.org
 * Description:       Handy tool for creating stylized maps using Neshan Maps Platform.
 * Version:           1.0.0
 * Author:            Neshan Maps Platform
 * Author URI:        https://neshan.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       neshan-maps
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NESHAN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-neshan-maps-activator.php
 */
function activate_neshan_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-neshan-maps-activator.php';
	Neshan_Maps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-neshan-maps-deactivator.php
 */
function deactivate_neshan_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-neshan-maps-deactivator.php';
	Neshan_Maps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_neshan_maps' );
register_deactivation_hook( __FILE__, 'deactivate_neshan_maps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-neshan-maps.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_neshan_maps() {
	$plugin = new Neshan_Maps();
	$plugin->run();
}

run_neshan_maps();
