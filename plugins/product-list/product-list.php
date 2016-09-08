<?php
/**
 * @package AdministradorDeCursosYC
 */
/*
Plugin Name: Listas de Productos Woocommerce
Description: Creación y Gestión de listas de productos recurrentes para Woocommerce
Version: 1.0.0
Author: Jonas Graterol
Author URI: http://pcuervo.com
*/

if( ! defined( 'PRODUCT_LIST_URL' ) ){
	define( 'PRODUCT_LIST_URL', plugin_dir_url( __FILE__ ) );
}
if( ! defined( 'PRODUCT_LIST_DIR' ) ){
	define( 'PRODUCT_LIST_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'PRODUCT_LIST_FILE' ) ) {
	define( 'PRODUCT_LIST_FILE', __FILE__ );
}
if ( ! defined( 'SITEURL' ) ) {
	define( 'SITEURL', site_url('/') );
}


register_activation_hook( PRODUCT_LIST_FILE, array( 'Product_List', 'install' ) );
register_deactivation_hook( PRODUCT_LIST_FILE, array( 'Product_List', 'uninstall' ) );
add_action( 'plugins_loaded', create_function( '', 'Product_List::get();' ) );

class Product_List {

	const PRODUCT_LIST_VERSION = '1.0.0';


	private static $instance = null;
	public static $endpoint = 'product-list';

	/**
	 * Get singleton instance of class
	 * @return Product_List instance
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
		// Initialize plugin
		$this->hooks();
		$this->init();
		// Check version for update
		if( get_option( 'product_list_version' ) != Product_List::PRODUCT_LIST_VERSION  ){
			
			update_option( 'product_list_version', Product_List::PRODUCT_LIST_VERSION );
			error_log('Updating plugin "Listas Woocommerce" to version: ' . Product_List::PRODUCT_LIST_VERSION );
		}
	}

	/**
	 * Create required database tables.
	 */
	public static function install() {
		error_log('INSTALLL plugin');
		$product_list = Product_List::get();
		$product_list->create_product_list_table();
		$product_list->create_product_list_detail_table();
		add_option( 'product_list_version', Product_List::PRODUCT_LIST_VERSION );

		wp_schedule_event( time(), 'hourly', 'my_cron_event' );

	}

	/**
	 * Delete installed database tables.
	 */
	public static function uninstall() {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}product_list" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}product_list_detail" );
		delete_option( 'product_list_version' );

		wp_clear_scheduled_hook('my_cron_event');

	}

	/**
	 * Load required files for Wordpress Admin Panel and for Frontend.
	 */
	private function includes() {
		require_once( PRODUCT_LIST_DIR . 'classes/class-product_list.php' );
		//require_once( PRODUCT_LIST_DIR . 'classes/class-yc_admin_cursos-settings.php' );
	}

	/**
	 * Initialize class
	 */
	private function init() {
		error_log('init plugin');
		Product_List_Settings::get();
	}

	/**
	 * Hooks
	 */
	private function hooks() {
		//ADD CUSTOM MY ACCOUNT MENU
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
		
	}

	/**
	 * Register new endpoint to use inside My Account page.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
	 */
	public function add_endpoints() {
		add_rewrite_endpoint( self::$endpoint, EP_ROOT | EP_PAGES );
		//flush_rewrite_rules();
	}

	/**
	 * Add new query var.
	 *
	 * @param array $vars
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[] = self::$endpoint;
		return $vars;
	}

	/**
	 * Create table "product_list"
	 */
	private function create_product_list_table(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'product_list';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
					  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
					  `user_id` INTEGER NOT NULL,
					  `nombre` VARCHAR(100),
					  `recurrencia` INTEGER,
					  `fecha` DATETIME,
					  PRIMARY KEY (`id`)
					)
					$charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}// create_user_lesson_table

	/**
	 * Create table "product_list_detail"
	 */
	private function create_product_list_detail_table(){
		global $wpdb;

		$table_name = $wpdb->prefix . 'product_list_detail';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
					  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
					  `product_list_id` INTEGER UNSIGNED NOT NULL,
					  `product_id` INTEGER UNSIGNED NOT NULL,
					  `cantidad` INTEGER UNSIGNED DEFAULT 1,
					  PRIMARY KEY (`id`)
					)
					$charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}// create_courses_modules_table

}// Product_List

