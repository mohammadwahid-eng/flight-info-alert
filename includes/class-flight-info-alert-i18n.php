<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://mohammadwahid.com
 * @since      1.0.0
 *
 * @package    Flight_Info_Alert
 * @subpackage Flight_Info_Alert/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Flight_Info_Alert
 * @subpackage Flight_Info_Alert/includes
 * @author     Mohammad Wahid <mohammadwahid.eng@gmail.com>
 */
class Flight_Info_Alert_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'flight-info-alert',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
