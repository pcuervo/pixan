<?php
/**
 * AxiomThemes Framework
 *
 * @package axiomthemes
 * @since axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'ORGANICS_FW_DIR' ) )		define( 'ORGANICS_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'ORGANICS_START_TIME' ) )	define( 'ORGANICS_START_TIME', microtime());			// Framework start time
if ( ! defined( 'ORGANICS_START_MEMORY' ) )	define( 'ORGANICS_START_MEMORY', memory_get_usage());	// Memory usage before core loading



if ( !function_exists( 'organics_variables_storage_setup' ) ) {
	function organics_variables_storage_setup() {
		// Global variables storage
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS = array(
			'page_template'	=> '',
			'allowed_tags'	=> array(		// Allowed tags list (with attributes) in translations
				'b' => array(),
				'strong' => array(),
				'i' => array(),
				'em' => array(),
				'u' => array(),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
					'id' => array(),
					'class' => array()
				),
				'span' => array(
					'id' => array(),
					'class' => array()
				)
			)	
		);
	}
}
organics_variables_storage_setup();


/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'organics_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'organics_loader_theme_setup', 20 );
	function organics_loader_theme_setup() {
		
		// Init admin url and nonce
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['admin_url']	= get_admin_url();
		$ORGANICS_GLOBALS['admin_nonce']= wp_create_nonce(get_admin_url());
		$ORGANICS_GLOBALS['ajax_url']	= admin_url('admin-ajax.php');
		$ORGANICS_GLOBALS['ajax_nonce']	= wp_create_nonce(admin_url('admin-ajax.php'));

		// Before init theme
		do_action('organics_action_before_init_theme');

		// Load current values for main theme options
		organics_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			organics_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */

// Manual load important libraries before load all rest files
// core.strings must be first - we use organics_str...() in the organics_get_file_dir()
require_once (file_exists(get_stylesheet_directory().(ORGANICS_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(ORGANICS_FW_DIR).'core/core.strings.php';
// core.files must be first - we use organics_get_file_dir() to include all rest parts
require_once (file_exists(get_stylesheet_directory().(ORGANICS_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(ORGANICS_FW_DIR).'core/core.files.php';
// core.files must be first - we use organics_get_file_dir() to include all rest parts
require_once (file_exists(get_stylesheet_directory().(ORGANICS_FW_DIR).'core/core.debug.php') ? get_stylesheet_directory() : get_template_directory()).(ORGANICS_FW_DIR).'core/core.debug.php';

// Include custom theme files
organics_autoload_folder( 'includes' );

// Include core files
organics_autoload_folder( 'core' );

// Include theme-specific plugins and post types
organics_autoload_folder( 'plugins' );

// Include theme templates
organics_autoload_folder( 'templates' );

// Include theme widgets
organics_autoload_folder( 'widgets' );
?>