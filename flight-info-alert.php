<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://mohammadwahid.com
 * @since             1.0.0
 * @package           Flight_Info_Alert
 *
 * @wordpress-plugin
 * Plugin Name:       Flight Info Alert
 * Plugin URI:        http://mohammadwahid.com
 * Description:       Flight Info Alerts enables customers to access near real-time change events in the full spectrum of schedules data that OAG holds without needing an API lookup.
 * Version:           1.0.0
 * Author:            Mohammad Wahid
 * Author URI:        http://mohammadwahid.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       flight-info-alert
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
define( 'FLIGHT_INFO_ALERT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flight-info-alert-activator.php
 */
function activate_flight_info_alert() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flight-info-alert-activator.php';
	Flight_Info_Alert_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flight-info-alert-deactivator.php
 */
function deactivate_flight_info_alert() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flight-info-alert-deactivator.php';
	Flight_Info_Alert_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flight_info_alert' );
register_deactivation_hook( __FILE__, 'deactivate_flight_info_alert' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flight-info-alert.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flight_info_alert() {

	$plugin = new Flight_Info_Alert();
	$plugin->run();

}
run_flight_info_alert();
