<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'organics_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_theme_setup', 1 );
	function organics_theme_setup() {

		// Register theme menus
		add_filter( 'organics_filter_add_theme_menus',		'organics_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'organics_filter_add_theme_sidebars',	'organics_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'organics_filter_importer_options',		'organics_importer_set_options' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 'organics_body_classes' );

	}
}


// Add theme specified classes into the body
if ( !function_exists('organics_body_classes') ) {
	//add_filter( 'body_class', 'organics_body_classes' );
	function organics_body_classes( $classes ) {

		$classes[] = 'organics_body';
		$classes[] = 'body_style_' . trim(organics_get_custom_option('body_style'));
		$classes[] = 'body_' . (organics_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(organics_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(organics_get_custom_option('article_style'));

		$blog_style = organics_get_custom_option(is_singular() && !organics_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(organics_get_template_name($blog_style));

		$body_scheme = organics_get_custom_option('body_scheme');
		if (empty($body_scheme)  || organics_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = organics_get_custom_option('top_panel_position');
		if (!organics_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else
			$classes[] = 'top_panel_hide';
		$classes[] = organics_get_sidebar_class();

		if (organics_get_custom_option('show_video_bg')=='yes' && (organics_get_custom_option('video_bg_youtube_code')!='' || organics_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (organics_get_theme_option('page_preloader')!='')
			$classes[] = 'preloader';

		return $classes;
	}
}



// Add/Remove theme nav menus
if ( !function_exists( 'organics_add_theme_menus' ) ) {
	//add_filter( 'organics_filter_add_theme_menus', 'organics_add_theme_menus' );
	function organics_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'organics');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'organics_add_theme_sidebars' ) ) {
	//add_filter( 'organics_filter_add_theme_sidebars',	'organics_add_theme_sidebars' );
	function organics_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'organics' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'organics' )
			);
            if (function_exists('organics_exists_woocommerce') && organics_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'organics' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Set theme specific importer options
if ( !function_exists( 'organics_importer_set_options' ) ) {
	//add_filter( 'organics_filter_importer_options',	'organics_importer_set_options' );
	function organics_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['domain_dev'] = 'organics.axiomthemes.com';
			$options['domain_demo'] = 'organics.axiomthemes.com';
			$options['page_on_front'] = 'Home 1';	        // Homepage title
			$options['page_for_posts'] = 'All posts';		// Blog streampage title
			$options['menus'] = array(						// Menus locations and names
				'menu-main'	  => 'Main menu',
				'menu-user'	  => 'User menu',
				'menu-footer' => 'Footer menu',
				'menu-outer'  => 'Main menu'
			);
			$options['required_plugins'] = array(			// Required plugins slugs
				'visual_composer',
				'revslider',
				'woocommerce',
				'essgrids'
			);
		}
		return $options;
	}
}


/* Include theme special files
------------------------------------------------------------------- */
require_once( get_template_directory().'/includes/theme_shortcodes.php' );
require_once( get_template_directory().'/includes/theme_shortcodes_vc.php' );
require_once( get_template_directory().'/includes/theme_shortcodes_settings.php' );
require_once( get_template_directory().'/includes/theme_core.media.php' );


/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
	require_once get_template_directory().'/fw/loader.php';
?>