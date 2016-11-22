<?php
/*
Plugin Name: Pixan Web Services
Description: Pixan Web Services
Version: 1
Author: Javolero
Author URI: https://github.com/javolero/
*/

if ( ! defined( 'PIXAN_API_PLUGIN_DIR' ) ) {
	define( 'PIXAN_API_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PIXAN_API_PLUGIN_FILE' ) ) {
	define( 'PIXAN_API_PLUGIN_FILE', __FILE__ );
}


if ( ! defined( 'PIXAN_API_AUTH_KEY' ) ) {
	define('PIXAN_API_AUTH_KEY', '62c7T5ljHphf83abXs0o2zDDO687P6DF');
}


/*
 * @todo
 * - Make it easy for webservice developers to create custom settings
 */
class Pixan_Web_Service {

	const WEBSERVICE_REWRITE = 'api/([a-zA-Z0-9_-]+)$';
	const OPTION_KEY         = 'wpw_options';

	private static $instance = null;

	/**
	 * Get singleton instance of class
	 *
	 * @return null|Pixan_Web_Service
	 */
	public static function get() {

		if ( self::$instance == null ) {
			self::$instance = new self();
		}
		return self::$instance;

	}

	/**
	 * Function that runs on install
	 */
	public static function install() {

		// Clear the permalinks
		flush_rewrite_rules();

	}

	/**
	 * Constructor
	 */
	private function __construct() {

		// Load files
		$this->includes();

		// Init
		$this->init();

	}

	/**
	 * Load required files
	 */
	private function includes() {

		require_once( PIXAN_API_PLUGIN_DIR . 'classes/class-pixan_api_rewrite_rules.php' );
		require_once( PIXAN_API_PLUGIN_DIR . 'classes/class-pixan_api-webservices.php' );

		require_once( PIXAN_API_PLUGIN_DIR . 'lib/woocommerce-api.php' );

		if ( is_admin() ) {
			// Backend

			require_once( PIXAN_API_PLUGIN_DIR . 'classes/class-pixan_api-settings.php' );

		}
		else {
			// Frondend

			require_once( PIXAN_API_PLUGIN_DIR . 'classes/class-pixan_api-catch-request.php' );
			require_once( PIXAN_API_PLUGIN_DIR . 'classes/class-pixan_api-output.php' );
		}

	}

	/**
	 * Initialize class
	 */
	private function init() {

		// Setup Rewrite Rules
		PIXAN_API_Rewrite_Rules::get();

		// Default webservice
		PIXAN_API_Web_services::get();

		if ( is_admin() ) {
			// Backend

			// Setup settings
			PIXAN_API_Settings::get();

		}
		else {
			// Frondend

			// Catch request
			PIXAN_API_Catch_Request::get();
		}

	}

	/**
	 * The correct way to throw an error in a webservice
	 *
	 * @param $error_string
	 */
	public function throw_error( $error_string ) {
		wp_die( '<b>Webservice error:</b> ' . $error_string );
	}

	/**
	 * Function to get the plugin options
	 *
	 * @return array
	 */
	public function get_options() {
		return get_option( self::OPTION_KEY, array() );
	}

	/**
	 * Function to save the plugin options
	 *
	 * @param $options
	 */
	public function save_options( $options ) {
		update_option( self::OPTION_KEY, $options );
	}

}

/**
 * Function that returns singleton instance of Pixan_Web_Service class
 *
 * @return null|Pixan_Web_Service
 */
function Pixan_Web_Service() {
	return Pixan_Web_Service::get();
}

// Load plugin
add_action( 'plugins_loaded', create_function( '', 'Pixan_Web_Service::get();' ) );

// Install hook
register_activation_hook( PIXAN_API_PLUGIN_FILE, array( 'Pixan_Web_Service', 'install' ) );