<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://linkedin.com/in/farhan-noor
 * @since      1.0.0
 *
 * @package    Csukapiclient
 * @subpackage Csukapiclient/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Csukapiclient
 * @subpackage Csukapiclient/includes
 * @author     Farhan Noor <farhan.noor@wpreloaded.com>
 */
class Csukapiclient_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'csukapiclient',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
