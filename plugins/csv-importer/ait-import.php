<?php
/**
 * Importar post desde CSV Plugin
 *
 * @package AitImport
 * @author  AitThemes.com <info@ait-themes.com>
 * @link    http://www.AitThemes.com/
 *
 * @wordpress-plugin
 * Plugin Name: Importar post desde CSV Plugin
 * Plugin URI:  http://www.jonasgraterol.com/
 * Description: Importar contenido desde archivos CSV para insertar nuevos post's de forma masiva
 * Version:     1.0
 * Author:      Jonas Graterol
 * Author URI:  http://www.jonasgraterol.com
 * Text Domain: ait
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('AIT_IMPORT_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('AIT_IMPORT_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

require_once( AIT_IMPORT_PLUGIN_PATH . 'class-import-type.php' );
require_once( AIT_IMPORT_PLUGIN_PATH . 'class-import-taxonomy.php' );
require_once( AIT_IMPORT_PLUGIN_PATH . 'class-ait-import.php' );
// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'AitImport', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'AitImport', 'deactivate' ) );

add_action( 'init', 'ait_import_make_instance', 100 );
function ait_import_make_instance() {
	AitImport::get_instance();
}