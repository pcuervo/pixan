<?php
/**
 * @package AreaEntregaPixan
 */
/*
Plugin Name: Checkout de Área de entrega Pixan
Description: Agregar input en checkout para introducir la direcccion y validar si se encuentra o no en una de las Areas de entrega definidas previamente
Version: 1.0.0
Author: Jonás Graterol
Author URI: http://pcuervo.com
*/

if( ! defined( 'AREA_ENTREGA_PIXAN_PLUGIN_URL' ) ){
	define( 'AREA_ENTREGA_PIXAN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if( ! defined( 'AREA_ENTREGA_PIXAN_PLUGIN_DIR' ) ){
	define( 'AREA_ENTREGA_PIXAN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'AREA_ENTREGA_PIXAN_PLUGIN_FILE' ) ) {
	define( 'AREA_ENTREGA_PIXAN_PLUGIN_FILE', __FILE__ );
}

register_activation_hook( AREA_ENTREGA_PIXAN_PLUGIN_FILE, array( 'Area_Entrega_Pixan', 'install' ) );
register_deactivation_hook( AREA_ENTREGA_PIXAN_PLUGIN_FILE, array( 'Area_Entrega_Pixan', 'uninstall' ) );
add_action( 'plugins_loaded', create_function( '', 'Area_Entrega_Pixan::get();' ) );

class Area_Entrega_Pixan {

	const AREA_ENTREGA_PIXAN_VERSION = '1.0.0';

	private static $instance = null;

	/**
	 * Get singleton instance of class
	 * @return Area_Entrega_Pixan instance
	 */
	public static function get() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		// Load files
		$this->includes();
		$this->init();
	}

	/**
	 * Create required database tables.
	 */
	public static function install() {
		add_option( 'area_entrega_pixan_version', Area_Entrega_Pixan::AREA_ENTREGA_PIXAN_VERSION );
	}

	/**
	 * Delete installed database tables.
	 */
	public static function uninstall() {
		delete_option( 'area_entrega_pixan_version' );
	}

	/**
	 * Load required files for Wordpress Admin Panel and for Frontend.
	 */
	private function includes() {
		if( is_admin() ){
			require_once( AREA_ENTREGA_PIXAN_PLUGIN_DIR . 'classes/area-entrega-pixan-settings.php' );
			require_once( AREA_ENTREGA_PIXAN_PLUGIN_DIR . 'classes/ordenes-dia-pixan.php' );
			//return;
		}

		require_once( AREA_ENTREGA_PIXAN_PLUGIN_DIR . 'classes/area-entrega-pixan-checkout.php' );
	}

	/**
	 * Initialize class
	 */
	private function init() {
		if( is_admin() ){
			Area_Entrega_Pixan_Settings::get();
			Ordenes_Dia_Pixan::get();
			//return;
		}
		Area_Entrega_Checkout_Pixan_Settings::get();
	}

}// Area_Entrega_Pixan