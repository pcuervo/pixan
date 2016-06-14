<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if ( !function_exists( 'organics_options_settings_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_options_settings_theme_setup3', 3 );	// Priority 1 for add organics_filter handlers, 2 for create Theme Options
	function organics_options_settings_theme_setup3() {
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['options']['top_panel_scheme']['options']		= $ORGANICS_GLOBALS['options_params']['list_bg_tints'];
		$ORGANICS_GLOBALS['options']['sidebar_main_scheme']['options']	= $ORGANICS_GLOBALS['options_params']['list_bg_tints'];
		$ORGANICS_GLOBALS['options']['sidebar_outer_scheme']['options']	= $ORGANICS_GLOBALS['options_params']['list_bg_tints'];
		$ORGANICS_GLOBALS['options']['sidebar_footer_scheme']['options']= $ORGANICS_GLOBALS['options_params']['list_bg_tints'];
		$ORGANICS_GLOBALS['options']['testimonials_scheme']['options']	= $ORGANICS_GLOBALS['options_params']['list_bg_tints'];
		$ORGANICS_GLOBALS['options']['twitter_scheme']['options']		= $ORGANICS_GLOBALS['options_params']['list_bg_tints'];
		$ORGANICS_GLOBALS['options']['contacts_scheme']['options']		= $ORGANICS_GLOBALS['options_params']['list_bg_tints'];
		$ORGANICS_GLOBALS['options']['copyright_scheme']['options']		= $ORGANICS_GLOBALS['options_params']['list_bg_tints'];
	}
}

if (!function_exists('organics_action_skin_theme_setup')) {
	add_action( 'organics_action_init_theme', 'organics_action_skin_theme_setup', 1 );
	function organics_action_skin_theme_setup() {

		// Disable less compilation
		organics_set_theme_setting('less_compiler', 'no');
		// Disable customizer demo
		organics_set_theme_setting('customizer_demo', false);

		// Add skin fonts in the used fonts list
		add_filter('organics_filter_used_fonts',			'organics_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('organics_filter_list_fonts',			'organics_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('organics_action_add_styles',			'organics_action_skin_add_styles');
		// Add skin inline styles
		add_filter('organics_filter_add_styles_inline',		'organics_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('organics_action_add_responsive',		'organics_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('organics_filter_add_responsive_inline',	'organics_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('organics_action_add_scripts',			'organics_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('organics_action_add_scripts_inline',	'organics_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('organics_filter_compile_less',			'organics_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		*/

		// Add color schemes
		organics_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'organics'),

			// Accent colors
			'accent1'				=> '#80b500',
			'accent1_hover'			=> '#669100',
			'accent2'				=> '#8c8c8c',
			'accent2_hover'			=> '#3f2803',
			'accent3'				=> '#4c4841',
			'accent3_hover'			=> '#f2f5f8',
			
			)
		);

		organics_add_color_scheme('contrast', array(

			'title'		 =>	esc_html__('Contrast', 'organics'),

			// Accent colors
			'accent1'				=> '#26c3d6',		// rgb(38,195,214)
			'accent1_hover'			=> '#24b6c8',		// rgb(36,182,200)
			'accent2'				=> '',
			'accent2_hover'			=> '',
			'accent3'				=> '',
			'accent3_hover'			=> '',
			)
		);
		organics_add_color_scheme('dark', array(

			'title'		 =>	esc_html__('Dark', 'organics'),

			// Accent colors
			'accent1'				=> '#f9c82d',		// rgb(249,200,45)
			'accent1_hover'  		=> '#e6ba29',		// rgb(230,186,41)
			'accent2'				=> '',
			'accent2_hover'			=> '',
			'accent3'				=> '',
			'accent3_hover'			=> '',
			)
		);
		organics_add_color_scheme('white', array(

			'title'		 =>	esc_html__('White', 'organics'),

			// Accent colors
			'accent1' 				=> '#48c7de',		// rgb(13,205,192)
			'accent1_hover'  		=> '#45bfd5',		// rgb(11,186,174)
			'accent2'				=> '',
			'accent2_hover'			=> '',
			'accent3'				=> '',
			'accent3_hover'			=> '',
			)
		);


		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		organics_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '3.429em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.208em',
			'margin-top'	=> '1em',
			'margin-bottom'	=> '0.8em'
			)
		);
		organics_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.194em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '3.071em',
			'margin-top'	=> '1.7em',
			'margin-bottom'	=> '1.05em'
			)
		);
		organics_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.250em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '2.143em',
			'margin-top'	=> '3em',
			'margin-bottom'	=> '1em'
			)
		);
		organics_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.222em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.571em',
			'margin-top'	=> '3.1em',
			'margin-bottom'	=> '1.5em'
			)
		);
		organics_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1.200em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.714em',
			'margin-top'	=> '2.65em',
			'margin-bottom'	=> '1.3em'
			)
		);
		organics_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '1em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.429em',
			'margin-top'	=> '3.8em',
			'margin-bottom'	=> '0.65em'
			)
		);
		organics_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Cantarell',
			'font-size' 	=> '14px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.571em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		organics_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'organics'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		organics_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Muli',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		organics_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '0.857em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.929em',
			'margin-top'	=> '0.8em',
			'margin-bottom'	=> '0.8em'
			)
		);
		organics_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'organics'),
			'description'	=> '',
			'font-family'	=> 'Raleway',
			'font-size' 	=> '0.786em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.4em',
			'margin-bottom'	=> '1.4em'
			)
		);
		organics_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'organics'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.27em',
			'margin-bottom'	=> '2.8em'
			)
		);
		organics_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'organics'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.077em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.429em'
			)
		);
		organics_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'organics'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.857em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.5em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('organics_filter_skin_used_fonts')) {
	//add_filter('organics_filter_used_fonts', 'organics_filter_skin_used_fonts');
	function organics_filter_skin_used_fonts($theme_fonts) {
		//$theme_fonts['Roboto'] = 1;
		//$theme_fonts['Love Ya Like A Sister'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('organics_filter_skin_list_fonts')) {
	//add_filter('organics_filter_list_fonts', 'organics_filter_skin_list_fonts');
	function organics_filter_skin_list_fonts($list) {
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => organics_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }
//        $list['Maven Pro'] = array(
//            'family' => 'sans-serif',
//            'link'   => 'Maven+Pro:400,900,700,500'
//            );
		if (!isset($list['Cantarell']))	$list['Cantarell'] = array('family'=>'sans-serif');
		//if (!isset($list['Muli']))	$list['Muli'] = array('family'=>'sans-serif');
		//if (!isset($list['Maven Pro']))	$list['Maven Pro'] = array('family'=>'sans-serif');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('organics_action_skin_add_styles')) {
	//add_action('organics_action_add_styles', 'organics_action_skin_add_styles');
	function organics_action_skin_add_styles() {
		// Add stylesheet files
		organics_enqueue_style( 'organics-skin-style', organics_get_file_url('skin.css'), array(), null );
		if (file_exists(organics_get_file_dir('skin.customizer.css')))
			organics_enqueue_style( 'organics-skin-customizer-style', organics_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('organics_filter_skin_add_styles_inline')) {
	//add_filter('organics_filter_add_styles_inline', 'organics_filter_skin_add_styles_inline');
	function organics_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = organics_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = organics_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		$clr = organics_get_scheme_colors();
		$clr['accent1_rgb'] = organics_hex2rgb($clr['accent1']);
		$clr['accent1_hover_rgb'] = organics_hex2rgb($clr['accent1_hover']);
		$clr['accent2_hover_rgb'] = organics_hex2rgb($clr['accent2_hover']);
		$fnt = organics_get_custom_fonts_properties();
		$css = '

body {
	'.organics_get_custom_font_css('p').';
}

h1 { '.organics_get_custom_font_css('h1').'; '.organics_get_custom_margins_css('h1').'; }
h2 { '.organics_get_custom_font_css('h2').'; '.organics_get_custom_margins_css('h2').'; }
h3 { '.organics_get_custom_font_css('h3').'; '.organics_get_custom_margins_css('h3').'; }
h4 { '.organics_get_custom_font_css('h4').'; '.organics_get_custom_margins_css('h4').'; }
h5 { '.organics_get_custom_font_css('h5').'; '.organics_get_custom_margins_css('h5').'; }
h6 { '.organics_get_custom_font_css('h6').'; '.organics_get_custom_margins_css('h6').'; }

/* Headers */
h1,
h2,
h3,
h4,
h5,
h6,
h1 a,
h2 a,
h3 a,
h4 a,
h5 a,
h6 a {
	color: '.$clr['accent2_hover'].';
}
blockquote::before {
	color: '.$clr['accent1'].';
}

a,
.scheme_dark a,
.scheme_light a {
	'.organics_get_custom_font_css('link').';
	color: '.$clr['accent1'].';
}
a:hover,
.scheme_dark a:hover,
.scheme_light a:hover {
	color: '.$clr['accent1_hover'].';
}

/* Accent1 color - use it as background and border with next classes */
.accent1 {			color: '.$clr['accent1'].'; }
.accent1_bgc {		background-color: '.$clr['accent1'].'; }
.accent1_bg {		background: '.$clr['accent1'].'; }
.accent1_border {	border-color: '.$clr['accent1'].'; }

a.accent1:hover {	color: '.$clr['accent1_hover'].'; }


/* 2.1 Common colors
-------------------------------------------------------------- */

/* Portfolio hovers */
.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect3.colored .info,
.post_content.ih-item.circle.effect4.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect6.colored .info,
.post_content.ih-item.circle.effect7.colored .info,
.post_content.ih-item.circle.effect8.colored .info,
.post_content.ih-item.circle.effect9.colored .info,
.post_content.ih-item.circle.effect10.colored .info,
.post_content.ih-item.circle.effect11.colored .info,
.post_content.ih-item.circle.effect12.colored .info,
.post_content.ih-item.circle.effect13.colored .info,
.post_content.ih-item.circle.effect14.colored .info,
.post_content.ih-item.circle.effect15.colored .info,
.post_content.ih-item.circle.effect16.colored .info,
.post_content.ih-item.circle.effect18.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect1.colored .info,
.post_content.ih-item.square.effect2.colored .info,
.post_content.ih-item.square.effect3.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect5.colored .info,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect8.colored .info,
.post_content.ih-item.square.effect9.colored .info .info-back,
.post_content.ih-item.square.effect10.colored .info,
.post_content.ih-item.square.effect11.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect14.colored .info,
.post_content.ih-item.square.effect15.colored .info,
.post_content.ih-item.circle.effect20.colored .info .info-back,
.post_content.ih-item.square.effect_book.colored .info {
	background: '.$clr['accent1'].';
}

.post_content.ih-item.circle.effect1.colored .info,
.post_content.ih-item.circle.effect2.colored .info,
.post_content.ih-item.circle.effect5.colored .info .info-back,
.post_content.ih-item.circle.effect19.colored .info,
.post_content.ih-item.square.effect4.colored .mask1,
.post_content.ih-item.square.effect4.colored .mask2,
.post_content.ih-item.square.effect6.colored .info,
.post_content.ih-item.square.effect7.colored .info,
.post_content.ih-item.square.effect12.colored .info,
.post_content.ih-item.square.effect13.colored .info,
.post_content.ih-item.square.effect_more.colored .info,
.post_content.ih-item.square.effect_fade.colored:hover .info,
.post_content.ih-item.square.effect_dir.colored .info,
.post_content.ih-item.square.effect_shift.colored .info {
	background: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6);
}

.post_content.ih-item.square.effect_fade.colored .info {
	background: -moz-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(70%,rgba(255,255,255,0)), color-stop(100%,rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6)));
	background: -webkit-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -o-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: -ms-linear-gradient(top, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
	background: linear-gradient(to bottom, rgba(255,255,255,0) 70%, rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6) 100%);
}

.post_content.ih-item.circle.effect17.colored:hover .img:before {
	-webkit-box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
	-moz-box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
	box-shadow: inset 0 0 0 110px rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6), inset 0 0 0 16px rgba(255, 255, 255, 0.8), 0 1px 2px rgba(0, 0, 0, 0.1);
}

.post_content.ih-item.circle.effect1 .spinner {
	border-right-color: '.$clr['accent1'].';
	border-bottom-color: '.$clr['accent1'].';
}


/* Tables */
.sc_table table tr:nth-child(odd) {
	background-color: '.$clr['accent3_hover'].';
}
.sc_table table tr:first-child {
	background-color: '.$clr['accent1'].';
}
.sc_table table tr td:first-child {
	color: '.$clr['accent1'].';
}


/* Table of contents */
pre.code,
#toc .toc_item.current,
#toc .toc_item:hover {
	border-color: '.$clr['accent1'].';
}


::selection,
::-moz-selection { 
	background-color: '.$clr['accent1'].';
}




/* 3. Form fields settings
-------------------------------------------------------------- */

input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="search"],
input[type="password"],
select,
textarea {
	'.organics_get_custom_font_css('input').';
}


/* 7.1 Top panel
-------------------------------------------------------------- */

.top_panel_inner_style_3 .top_panel_cart_button,
.top_panel_inner_style_4 .top_panel_cart_button {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.2);
}

.top_panel_inner_style_4 .top_panel_top,
.top_panel_inner_style_5 .top_panel_top,
.top_panel_inner_style_3 .top_panel_top .sidebar_cart,
.top_panel_inner_style_4 .top_panel_top .sidebar_cart,
.top_panel_inner_style_4 .top_panel_top a {
	background-color: '.$clr['accent3_hover'].';
    color: '.$clr['accent2'].';
}
.top_panel_inner_style_4 .top_panel_top a:hover,
.top_panel_inner_style_4 .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_5 .top_panel_top a:hover,
.top_panel_inner_style_5 .sc_socials.sc_socials_type_icons a:hover,
.top_panel_inner_style_7 .top_panel_top a:hover,
.top_panel_inner_style_7 .sc_socials.sc_socials_type_icons a:hover,
.header_mobile .sc_socials.sc_socials_type_icons a:hover {
    color: '.$clr['accent1'].';
}
.header_mobile .search_wrap, .header_mobile .login {
	background-color: '.$clr['accent1'].';
}

.top_panel_wrap.top_panel_style_7 .top_panel_wrap_inner.top_panel_inner_style_7 .top_panel_top {
}

.top_panel_top a:hover,
.top_panel_top .search_submit:hover {
	color: '.$clr['accent1_hover'].';
}


/* User menu */
.menu_user_nav > li > a:hover {
	color: '.$clr['accent1_hover'].';
}

.top_panel_inner_style_3 .menu_user_nav > li > ul:after,
.top_panel_inner_style_4 .menu_user_nav > li > ul:after,
.top_panel_inner_style_5 .menu_user_nav > li > ul:after,
.top_panel_inner_style_7 .menu_user_nav > li > ul:after,
.top_panel_inner_style_3 .menu_user_nav > li ul,
.top_panel_inner_style_4 .menu_user_nav > li ul,
.top_panel_inner_style_5 .menu_user_nav > li ul,
.top_panel_middle .sidebar_cart {
	border-color: '.$clr['accent1_hover'].';
	background-color: '.$clr['accent3_hover'].';
}
.top_panel_inner_style_3 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_3 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_3 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_4 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_4 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_4 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_5 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_5 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_5 .menu_user_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_7 .menu_user_nav > li ul li a:hover,
.top_panel_inner_style_7 .menu_user_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_7 .menu_user_nav > li ul li.current-menu-ancestor > a {
}
.top_panel_wrap .widget_shopping_cart ul.cart_list > li a:hover,
.top_panel_wrap .menu_user_currency ul li a:hover {
	color: '.$clr['accent1'].';
}




/* Top panel - middle area */
.top_panel_middle .logo {
	'.organics_get_custom_margins_css('logo').';
}
.logo .logo_text {
	'.organics_get_custom_font_css('logo').';
}

.top_panel_middle .menu_main_wrap {
	margin-top: calc('.$fnt['logo_mt'].'*1.825);
}
.top_panel_style_5 .top_panel_middle .menu_main_wrap {
	margin-top: calc('.$fnt['logo_mt'].'*0.3);
}
.top_panel_style_5 .top_panel_middle .logo {
	margin-bottom: calc('.$fnt['logo_mb'].'*0.3);
}
.top_panel_style_7 .top_panel_middle .menu_main_wrap {
	margin-top: calc('.$fnt['logo_mt'].'*2.05);
}
.top_panel_style_7 .top_panel_middle .logo {
	margin-bottom: calc('.$fnt['logo_mb'].'*1);
	margin-top: calc('.$fnt['logo_mt'].'*1.3);
}
.top_panel_middle .menu_main_cart .contact_icon {
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.top_panel_middle .menu_main_cart .contact_icon:hover {
	color: '.$clr['accent1'].';
}


/* Top panel (bottom area) */
.top_panel_bottom {
	background-color: '.$clr['accent1'].';
}



/* Top panel image in the header 7  */
.top_panel_image_hover {
	background-color: rgba('.$clr['accent1_hover_rgb']['r'].','.$clr['accent1_hover_rgb']['b'].','.$clr['accent1_hover_rgb']['b'].', 0.8);
}

/* Top panel breadcrumbs in the header  */
.top_panel_title_inner .page_thumb .breadcrumbs a.breadcrumbs_item:hover {
	color:  '.$clr['accent1'].';
}


/* Main menu */
.menu_main_nav > li > a {
	padding:'.$fnt['menu_mt'].' 1.6em '.$fnt['menu_mb'].';
	'.organics_get_custom_font_css('menu').';
}
.menu_main_nav li ul {
	background-color: '.$clr['accent3_hover'].';
	border-color: '.$clr['accent1'].';
}
.menu_main_nav > li ul li a {
	color:  '.$clr['accent2'].';
}
.menu_main_nav > li ul li a:hover,
.menu_main_nav > li ul li.current-menu-item > a,
.menu_main_nav > li ul li.current-menu-parent > a{
	color:  '.$clr['accent1'].';
}
.menu_main_nav > li > a:hover,
.menu_main_nav > li.sfHover > a,
.menu_main_nav > li#blob,
.menu_main_nav > li.current-menu-item > a,
.menu_main_nav > li.current-menu-parent > a,
.menu_main_nav > li.current-menu-ancestor > a {
  color: '.$clr['accent1'].';
}
.top_panel_inner_style_1 .menu_main_nav > li > a:hover,
.top_panel_inner_style_2 .menu_main_nav > li > a:hover {
	background-color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_1 .menu_main_nav > li ul,
.top_panel_inner_style_2 .menu_main_nav > li ul {
	border-color: '.$clr['accent1_hover'].';
	background-color: '.$clr['accent1'].';
}
.top_panel_inner_style_1 .menu_main_nav > a:hover,
.top_panel_inner_style_1 .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_1 .menu_main_nav > li#blob,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-item > a,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-parent > a,
.top_panel_inner_style_1 .menu_main_nav > li.current-menu-ancestor > a,
.top_panel_inner_style_2 .menu_main_nav > a:hover,
.top_panel_inner_style_2 .menu_main_nav > li.sfHover > a,
.top_panel_inner_style_2 .menu_main_nav > li#blob,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-item > a,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-parent > a,
.top_panel_inner_style_2 .menu_main_nav > li.current-menu-ancestor > a {
	background-color: '.$clr['accent1_hover'].';
}
.menu_main_nav > li > ul {
	'.organics_get_custom_font_css('submenu').';
}
.menu_main_nav > li > ul {
	top: calc('.$fnt['menu_mt'].'+'.$fnt['menu_mb'].'+'.$fnt['menu_lh'].');
}
.menu_main_nav > li ul li a {
	padding: '.$fnt['submenu_mt'].' 1.5em '.$fnt['submenu_mb'].';
}
.top_panel_inner_style_1 .menu_main_nav > li ul li a:hover,
.top_panel_inner_style_1 .menu_main_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_1 .menu_main_nav > li ul li.current-menu-ancestor > a,
.top_panel_inner_style_2 .menu_main_nav > li ul li a:hover,
.top_panel_inner_style_2 .menu_main_nav > li ul li.current-menu-item > a,
.top_panel_inner_style_2 .menu_main_nav > li ul li.current-menu-ancestor > a {
	background-color: '.$clr['accent1_hover'].';
}

/* Responsive menu */
.menu_main_responsive_button {
	margin-top:'.$fnt['menu_mt'].';
	margin-bottom:'.$fnt['menu_mb'].';
}
.menu_main_responsive_button:hover {	
	color: '.$clr['accent1_hover'].'; 
}
.responsive_menu .top_panel_middle .menu_main_responsive_button {
	top: '.$fnt['logo_mt'].';
}
.responsive_menu .menu_main_responsive_button {
	margin-top:calc('.$fnt['menu_mt'].'*0.8);
	margin-bottom:calc('.$fnt['menu_mb'].'*0.6);
}

.top_panel_inner_style_1 .menu_main_responsive,
.top_panel_inner_style_2 .menu_main_responsive {
	background-color: '.$clr['accent1'].';
}
.top_panel_inner_style_1 .menu_main_responsive a:hover,
.top_panel_inner_style_2 .menu_main_responsive a:hover {
	background-color: '.$clr['accent1_hover'].';
}

.menu_main_responsive li a:hover {
	background-color: '.$clr['accent1'].';
}

/* Search field */
.top_panel_bottom .search_wrap,
.top_panel_inner_style_4 .search_wrap,
.top_panel_inner_style_7 .search_wrap {
	padding-top:calc('.$fnt['menu_mt'].'*0.65);
	padding-bottom:calc('.$fnt['menu_mb'].'*0.5);
}
.top_panel_inner_style_1 .search_form_wrap,
.top_panel_inner_style_2 .search_form_wrap {
	background-color: rgba('.$clr['accent1_hover_rgb']['r'].','.$clr['accent1_hover_rgb']['g'].','.$clr['accent1_hover_rgb']['b'].', 0.2); 
}
.top_panel_wrap .search_wrap.search_state_opened .search_field {
	background-color: '.$clr['accent3_hover'].';
}

.top_panel_icon {
	margin: calc('.$fnt['menu_mt'].'*0.1) 0 '.$fnt['menu_mb'].' 2.15em;
}
.top_panel_icon.search_wrap,
.top_panel_inner_style_5 .menu_main_responsive_button,
.top_panel_inner_style_6 .menu_main_responsive_button,
.top_panel_inner_style_7 .menu_main_responsive_button {
	color: '.$clr['accent1'].';
}
.top_panel_icon .contact_icon,
.top_panel_icon .search_submit {
	color: '.$clr['accent1'].';
}
.top_panel_wrap .search_form_wrap button {
	color: '.$clr['accent3'].';
}
.top_panel_wrap .search_form_wrap button:hover {
	color: '.$clr['accent1'].';
}

/* Search results */
.search_results .post_more,
.search_results .search_results_close {
	color: '.$clr['accent1'].';
}
.search_results .post_more:hover,
.search_results .search_results_close:hover {
	color: '.$clr['accent1_hover'].';
}
.top_panel_inner_style_1 .search_results,
.top_panel_inner_style_1 .search_results:after,
.top_panel_inner_style_2 .search_results,
.top_panel_inner_style_2 .search_results:after,
.top_panel_inner_style_3 .search_results,
.top_panel_inner_style_3 .search_results:after {
	background-color: '.$clr['accent1'].'; 
	border-color: '.$clr['accent1_hover'].'; 
}


/* Fixed menu */
.top_panel_fixed .menu_main_wrap {
	padding-top:calc('.$fnt['menu_mt'].'*0.3);
	padding-top:calc('.$fnt['menu_mt'].'*1.3);
}
.top_panel_fixed .top_panel_wrap .logo {
	margin-top: calc('.$fnt['menu_mt'].'*1);
	margin-bottom: calc('.$fnt['menu_mb'].'*0.6);
}

.top_panel_title_inner .breadcrumbs a:hover, .top_panel_title_inner .breadcrumbs .breadcrumbs_item.current {
	color: '.$clr['accent1'].';
}

/* Mobile menu */
.header_mobile .panel_top {
    background-color: '.$clr['accent1_hover'].';
}
.header_mobile .menu_main_nav > li > a {
    background-color: '.$clr['accent1'].';
}
.header_mobile .menu_main_nav > li ul li.current-menu-item > a,
.header_mobile .menu_main_nav > li li li.current-menu-item > a {
    background-color: '.$clr['accent1'].';
}
.header_mobile .menu_button:hover, .header_mobile .menu_main_cart .top_panel_cart_button .contact_icon:hover {
    color: '.$clr['accent1'].';
}
.header_mobile .panel_bottom:before {
    background-color: '.$clr['accent3_hover'].';
}
.header_mobile .panel_middle span:before {
    color: '.$clr['accent1'].';
}


/* 7.4 Main content wrapper
-------------------------------------------------------------- */

/* Layout Excerpt */
.post_title .post_icon {
	color: '.$clr['accent1'].';
}

/* Layout Excerpt */
.post_item_sermons {
	background-color: '.$clr['accent3_hover'].';
}

/* Blog pagination */
.pagination > a {
	border-color: '.$clr['accent1'].';
}

/* Post image */
.hover_icon:after,
.woocommerce ul.products .post_featured .post_thumb:hover:after,
.woocommerce-page ul.products .post_featured .post_thumb:hover:after {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.25);
}

/* 7.5 Post formats
-------------------------------------------------------------- */

/* Aside */
.post_format_aside.post_item_single .post_content p,
.post_format_aside .post_descr {
	border-color: '.$clr['accent1'].';
}
.post_format_aside .post_descr {
	background-color: '.$clr['accent3_hover'].';
    color: '.$clr['accent2_hover'].';
}
/* Link */
.post_format_link .post_descr a {
    color: '.$clr['accent2_hover'].';
}
/* Status */
.post_format_status .post_descr {
    background-color: '.$clr['accent2'].';
}


/* 7.6 Posts layouts
-------------------------------------------------------------- */

.post_info {
	'.organics_get_custom_font_css('info').';
	'.organics_get_custom_margins_css('info').';
}
.post_info a[class*="icon-"] {
	color: '.$clr['accent1'].';
}
.post_info a:hover,
.wpb_widgetised_column .post_item .post_info .post_counters_comments:before:hover {
	color: '.$clr['accent1'].';
}
.post_info .post_info_counters .post_counters_item {
    color: '.$clr['accent1'].';
}
.post_info .post_info_counters .post_counters_item:hover {
    color: '.$clr['accent1_hover'].';
}

.post_item .post_readmore:hover .post_readmore_label {
	color: '.$clr['accent1_hover'].';
}

/* Related posts */
.post_item_related .post_info a:hover,
.post_item_related .post_title a:hover {
	color: '.$clr['accent1_hover'].';
}


/* Style "Colored" */
.isotope_item_colored .post_featured .post_mark_new,
.isotope_item_colored .post_featured .post_title,
.isotope_item_colored .post_content.ih-item.square.colored .info {
	background-color: '.$clr['accent1'].';
}

.isotope_item_colored .post_category a,
.isotope_item_colored .post_rating .reviews_stars_bg,
.isotope_item_colored .post_rating .reviews_stars_hover,
.isotope_item_colored .post_rating .reviews_value {
	color: '.$clr['accent1'].';
}

.isotope_item_colored .post_info_wrap .post_button .sc_button {
	color: '.$clr['accent1'].';
}

.isotope_item_colored_1 .post_title a {
	color: '.$clr['accent1'].';
}
.isotope_item_colored_1 .post_title a:hover,
.isotope_item_colored_1 .post_category a:hover {
	color: '.$clr['accent1_hover'].';
}


/* Masonry and Portfolio */
.isotope_wrap .isotope_item_colored_1 .post_featured {
	border-color: '.$clr['accent1'].';
}

/* Isotope filters */
.isotope_filters a {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.isotope_filters a.active,
.isotope_filters a:hover {
	border-color: '.$clr['accent1_hover'].';
	background-color: '.$clr['accent1_hover'].';
}




/* 7.7 Paginations
-------------------------------------------------------------- */

/* Style Pages and Slider */
.pagination_single > .pager_numbers,
.pagination_single a,
.pagination_slider .pager_cur,
.pagination_pages > a,
.pagination_pages > span {
}
.pagination_single > .pager_numbers,
.pagination_single a:hover,
.pagination_slider .pager_cur:hover,
.pagination_slider .pager_cur:focus,
.pagination_pages > .active,
.pagination_pages > a:hover {
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}

.pagination_wrap .pager_next:hover,
.pagination_wrap .pager_prev:hover,
.pagination_wrap .pager_last:hover,
.pagination_wrap .pager_first:hover {
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}



/* Style Load more */
.pagination_viewmore > a {
	background-color: '.$clr['accent1'].';
}
.pagination_viewmore > a:hover {
	background-color: '.$clr['accent1_hover'].';
}

/* Loader picture */
.viewmore_loader,
.mfp-preloader span,
.sc_video_frame.sc_video_active:before {
	background-color: '.$clr['accent1_hover'].';
}


/* 8 Single page parts
-------------------------------------------------------------- */


/* 8.1 Attachment and Portfolio post navigation
------------------------------------------------------------- */
.post_featured .post_nav_item:before {
	background-color: '.$clr['accent1'].';
}
.post_featured .post_nav_item .post_nav_info {
	background-color: '.$clr['accent1'].';
}


/* 8.2 Reviews block
-------------------------------------------------------------- */
.reviews_block .reviews_summary .reviews_item {
	background-color: '.$clr['accent1'].';
}
.reviews_block .reviews_max_level_100 .reviews_stars_hover,
.reviews_block .reviews_item .reviews_slider {
	background-color: '.$clr['accent1'].';
}
.reviews_block .reviews_item .reviews_stars_hover {
	color: '.$clr['accent1'].';
}

/* Summary stars in the post item (under the title) */
.post_item .post_rating .reviews_stars_bg,
.post_item .post_rating .reviews_stars_hover,
.post_item .post_rating .reviews_value {
	color: '.$clr['accent1'].';
}


/* 8.3 Post author
-------------------------------------------------------------- */
.post_author .post_author_title a {
	color: '.$clr['accent1'].';
}
.post_author .post_author_title a:hover {
	color: '.$clr['accent1_hover'].';
}




/* 8.4 Comments
-------------------------------------------------------- */
.comments_list_wrap ul.children,
.comments_list_wrap ul > li + li {
	border-top-color: '.$clr['accent1'].';
}
.comments_list_wrap .comment-respond {
	border-bottom-color: '.$clr['accent1'].';
}
.comments_list_wrap > ul {
	border-bottom-color: '.$clr['accent1'].';
}

.comments_list_wrap .comment_info > span.comment_author,
.comments_list_wrap .comment_info > .comment_date > .comment_date_value {
	color: '.$clr['accent1'].';
}



/* 8.5 Page 404
-------------------------------------------------------------- */
.post_item_404 .page_title,
.post_item_404 .page_subtitle {
	//font-family: '.$fnt['logo_ff'].';
}
.page-template-404 .page_content_wrap,
.error404 .page_content_wrap {
	background-color: '.$clr['accent1'].';
}




/* 9. Sidebars
-------------------------------------------------------------- */

/* Side menu */
.sidebar_outer_menu .menu_side_nav > li > a,
.sidebar_outer_menu .menu_side_responsive > li > a {
	'.organics_get_custom_font_css('menu').';
}
.sidebar_outer_menu .menu_side_nav > li ul,
.sidebar_outer_menu .menu_side_responsive > li ul {
	'.organics_get_custom_font_css('submenu').';
}
.sidebar_outer_menu .menu_side_nav > li ul li a,
.sidebar_outer_menu .menu_side_responsive > li ul li a {
	padding: '.$fnt['submenu_mt'].' 1.5em '.$fnt['submenu_mb'].';
}
.sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover,
.scheme_dark .sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover,
.scheme_light .sidebar_outer_menu .sidebar_outer_menu_buttons > a:hover {
	color: '.$clr['accent1'].';
}

/* Bg color of sidebar */
.sidebar_cart, .widget_area_inner {
}

/* Common rules */
.widget_area_inner a,
.widget_area_inner ul li:before,
.widget_area_inner ul li a:hover,
.widget_area_inner ul ul li a:hover,
.widget_area_inner button:before,
.post_info .post_info_counters .post_counters_item:before {
	color: '.$clr['accent1'].';
}
.widget_area_inner .logo_descr a {
	color: '.$clr['accent2_hover'].';
}
.widget_area_inner .logo_descr a:hover {
	color: '.$clr['accent2_hover'].';
}
.widget_area_inner a:hover,
.widget_area_inner button:hover:before {
	color: '.$clr['accent1_hover'].';
}
.post_info .post_info_counters .post_counters_item:hover:before {
	color: '.$clr['accent2_hover'].';
}
.widget_area_inner .post_title a:hover {
    color: '.$clr['accent1'].';
}
.widget_area_inner .widget_title a {
    color: '.$clr['accent2_hover'].';
}
.widget_area_inner .widget_title a:hover {
    color: '.$clr['accent2_hover'].';
}
h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover {
    color: '.$clr['accent1'].';
}
.widget_area_inner ul li {
	color: '.$clr['accent3'].';
}
.widget_area_inner ul li a {
	color: '.$clr['accent2_hover'].';
}
.widget_area_inner .widget_text a,
.widget_area_inner .post_info a {
	color: '.$clr['accent1'].';
}
.widget_area_inner .widget_text a:hover,
.widget_area_inner .post_info a:hover {
	color: '.$clr['accent1'].';
}
.widget_area_inner .post_title a {
    color: '.$clr['accent2_hover'].';
}
.widget_area_inner .post_info a {
    color: '.$clr['accent2'].';
}

/* Widget search */
.widget_area_inner .widget_product_search .search_form input,
.widget_area_inner .widget_search .search_form input,
.widget_area_inner select {
    color: '.$clr['accent2'].';
	background-color: '.$clr['accent3_hover'].';
}

/* Widget recentcomments */
.widget_area_inner ul li.recentcomments {
	color: '.$clr['accent2'].';
}

/* Widget: Calendar */
.widget_area_inner .widget_calendar td a:hover {
	background-color: '.$clr['accent1'].';
}
.widget_area_inner .widget_calendar .today .day_wrap {
	background-color: '.$clr['accent1'].';
}
.widget_area_inner .widget_calendar .weekday,
.widget_area .widget_calendar .month_cur a {
    color: '.$clr['accent2_hover'].';
}
.widget_area .widget_calendar td .day_wrap {
    color: '.$clr['accent2'].';
}
.widget_area .widget_calendar td a.day_wrap {
    background-color: '.$clr['accent3_hover'].';
}
.widget_area .widget_calendar td a.day_wrap:hover {
    background-color: '.$clr['accent1'].';
}

/* Widget: Categories */
.widget_area .widget_categories ul li {
	color: '.$clr['accent3'].';
}
.widget_categories ul ul li a {
	color: '.$clr['accent2'].';
}


/* Widget: Tag Cloud */
.widget_area_inner .widget_product_tag_cloud a,
.widget_area_inner .widget_tag_cloud a {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent3_hover'].';
}
.widget_area_inner .widget_product_tag_cloud a:hover,
.widget_area_inner .widget_tag_cloud a:hover {
	//color: '.$clr['accent1'].';
}
.widget_area_inner .widget_product_tag_cloud a,
.widget_area_inner .widget_tag_cloud a {
	border-color: #fff;
	color: '.$clr['accent2'].';
}
.widget_area_inner .widget_product_tag_cloud a:hover,
.widget_area_inner .widget_tag_cloud a:hover {
	color: #fff;
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}

/* Widget: Flickr */
.flickr_images .flickr_badge_image a:after {
	background-color: rgba('.$clr['accent2_hover_rgb']['r'].','.$clr['accent2_hover_rgb']['g'].','.$clr['accent2_hover_rgb']['b'].', 0.8);
}


/* 10. Footer areas
-------------------------------------------------------------- */

/* Twitter and testimonials */
.testimonials_wrap_inner,
.twitter_wrap_inner {
  background-color: '.$clr['accent1'].';
}

/* Copyright */
.copyright_wrap_inner .menu_footer_nav li a:hover,
.scheme_dark .copyright_wrap_inner .menu_footer_nav li a:hover,
.scheme_light .copyright_wrap_inner .menu_footer_nav li a:hover {
    color: '.$clr['accent1'].';
}
.copyright_wrap_inner {
	background-color: '.$clr['accent3_hover'].';
}
.copyright_style_emailer .copyright_text a:hover {
    color: '.$clr['accent1'].';
}
.copyright_style_emailer .sc_emailer.sc_emailer_opened input {
	background-color: '.$clr['accent3_hover'].' !important;
}



/* 11. Utils
-------------------------------------------------------------- */

/* Scroll to top */
.scroll_to_top {
	background-color: '.$clr['accent1'].';
}
.scroll_to_top:hover {
	background-color: '.$clr['accent2_hover'].';
}
.custom_options #co_toggle {
	background-color: '.$clr['accent1_hover'].' !important;
}


/* 13.2 WooCommerce
------------------------------------------------------ */

/* Theme colors */
.woocommerce .woocommerce-message:before, .woocommerce-page .woocommerce-message:before,
//.woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover,
//.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover,
.woocommerce .quantity input[type="button"]:hover, .woocommerce #content input[type="button"]:hover, .woocommerce-page .quantity input[type="button"]:hover, .woocommerce-page #content .quantity input[type="button"]:hover,
.woocommerce ul.cart_list li > .amount, .woocommerce ul.product_list_widget li > .amount, .woocommerce-page ul.cart_list li > .amount, .woocommerce-page ul.product_list_widget li > .amount,
.woocommerce ul.cart_list li span .amount, .woocommerce ul.product_list_widget li span .amount, .woocommerce-page ul.cart_list li span .amount, .woocommerce-page ul.product_list_widget li span .amount,
.woocommerce ul.cart_list li ins .amount, .woocommerce ul.product_list_widget li ins .amount, .woocommerce-page ul.cart_list li ins .amount, .woocommerce-page ul.product_list_widget li ins .amount,
.woocommerce.widget_shopping_cart .total .amount, .woocommerce .widget_shopping_cart .total .amount, .woocommerce-page.widget_shopping_cart .total .amount, .woocommerce-page .widget_shopping_cart .total .amount,
.woocommerce a:hover h3, .woocommerce-page a:hover h3,
.woocommerce .cart-collaterals .order-total strong, .woocommerce-page .cart-collaterals .order-total strong,
.woocommerce .checkout #order_review .order-total .amount, .woocommerce-page .checkout #order_review .order-total .amount,
.widget_area_inner .widgetWrap ul > li .star-rating span, .woocommerce #review_form #respond .stars a, .woocommerce-page #review_form #respond .stars a
{
	color: '.$clr['accent1'].';
}

.woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price,.woocommerce ul.products li.product .price,.woocommerce-page ul.products li.product .price
.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before
{

}
.woocommerce .star-rating, .woocommerce-page .star-rating, .woocommerce .star-rating:before, .woocommerce-page .star-rating:before {
	color: '.$clr['accent1'].';
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range
{ 
	background-color: '.$clr['accent2'].';
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle
{
	border-color: '.$clr['accent2'].';
	background-color: '.$clr['accent3_hover'].';
}

.woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message,
.woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active,
.woocommerce a.button:active, .woocommerce button.button:active, .woocommerce input.button:active, .woocommerce #respond input#submit:active, .woocommerce #content input.button:active, .woocommerce-page a.button:active, .woocommerce-page button.button:active, .woocommerce-page input.button:active, .woocommerce-page #respond input#submit:active, .woocommerce-page #content input.button:active
{ 
	border-top-color: '.$clr['accent1'].';
}

/* Buttons */
//.woocommerce-page ul.products li.product .post_featured .post_thumb .add_to_cart_button:hover {
//    color: '.$clr['accent1'].';
//}
.woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce-account .addresses .title .edit, .woocommerce a.added_to_cart, .woocommerce-page a.added_to_cart, .woocommerce .quick_view_button, .woocommerce-page .quick_view_button {
	background-color: '.$clr['accent1'].';
}
.woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce-account .addresses .title .edit:hover, .woocommerce .quick_view_button:hover, .woocommerce-page .quick_view_button:hover, .woocommerce a.added_to_cart:hover, .woocommerce-page a.added_to_cart:hover {
	background-color: '.$clr['accent1_hover'].';
	background-color: '.$clr['accent2_hover'].';
}
.woocommerce a.button.checkout,
.woocommerce .price_slider_amount .button {
    background-color: '.$clr['accent2'].';
}
.woocommerce a.button.checkout:hover,
.woocommerce .price_slider_amount .button:hover {
    background-color: '.$clr['accent3'].';
}

/* Products stream */
.woocommerce span.new, .woocommerce-page span.new,
.woocommerce span.onsale, .woocommerce-page span.onsale {
	background-color: '.$clr['accent1'].';
}
.woocommerce ul.products li.product h3 a:hover, .woocommerce-page ul.products li.product h3 a:hover {
	color: '.$clr['accent2_hover'].';
}

//.woocommerce ul.products li.product .add_to_cart_button, .woocommerce-page ul.products li.product .add_to_cart_button {
//	background-color: '.$clr['accent1'].';
//}
//.woocommerce ul.products li.product .add_to_cart_button:hover, .woocommerce-page ul.products li.product .add_to_cart_button:hover {
//	background-color: '.$clr['accent1_hover'].';
//}
.woocommerce ul.products li.product .product-image:before, .woocommerce-page ul.products li.product .product-image:before,
.woocommerce ul.products li.product .product-image:before, .woocommerce-page ul.products li.product .product-image:before {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.25);
}

/* Pagination */
.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span.current {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current {
	color: '.$clr['accent1'].';
}

/* Cart */
.woocommerce table.cart thead th, .woocommerce #content table.cart thead th, .woocommerce-page table.cart thead th, .woocommerce-page #content table.cart thead th {
	background-color: '.$clr['accent1'].';
}

/* Widgets */
.textwidget .woocommerce ul.products li.product .post_featured .post_thumb:after {
	background-color: rgba('.$clr['accent2_hover_rgb']['r'].','.$clr['accent2_hover_rgb']['g'].','.$clr['accent2_hover_rgb']['b'].', 0.8);
}
.textwidget .woocommerce ul.products li.product .post_featured .quick_view_button:after {
	color: '.$clr['accent1'].';
}

/* 13.3 Tribe Events
------------------------------------------------------- */
.tribe-events-calendar thead th,
.tribe-events-sub-nav li a{
	background-color: '.$clr['accent1'].';
}

/* Buttons */
a.tribe-events-read-more,
.tribe-events-button,
.tribe-events-nav-previous a,
.tribe-events-nav-next a,
.tribe-events-widget-link a,
.tribe-events-viewmore a {
	background-color: '.$clr['accent1'].';
}
a.tribe-events-read-more:hover,
.tribe-events-button:hover,
.tribe-events-nav-previous a:hover,
.tribe-events-nav-next a:hover,
.tribe-events-widget-link a:hover,
.tribe-events-viewmore a:hover {
	background-color: '.$clr['accent1_hover'].';
}




/* 13.4 BB Press and Buddy Press
------------------------------------------------------- */

/* Buttons */
#bbpress-forums div.bbp-topic-content a,
#buddypress button, #buddypress a.button, #buddypress input[type="submit"], #buddypress input[type="button"], #buddypress input[type="reset"], #buddypress ul.button-nav li a, #buddypress div.generic-button a, #buddypress .comment-reply-link, a.bp-title-button, #buddypress div.item-list-tabs ul li.selected a {
	background: '.$clr['accent1'].';
}
#bbpress-forums div.bbp-topic-content a:hover,
#buddypress button:hover, #buddypress a.button:hover, #buddypress input[type="submit"]:hover, #buddypress input[type="button"]:hover, #buddypress input[type="reset"]:hover, #buddypress ul.button-nav li a:hover, #buddypress div.generic-button a:hover, #buddypress .comment-reply-link:hover, a.bp-title-button:hover, #buddypress div.item-list-tabs ul li.selected a:hover {
	background: '.$clr['accent1_hover'].';
}


/* 15. Shortcodes
-------------------------------------------------------------- */

/* Accordion */
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title.ui-state-active {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title.ui-state-active .sc_accordion_icon_opened,
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title .sc_accordion_icon_closed {
	background-color: '.$clr['accent1'].';
}
.sc_accordion.sc_accordion_style_1 .sc_accordion_item .sc_accordion_title:hover .sc_accordion_icon_opened {
	background-color: '.$clr['accent1_hover'].';
}




.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title.ui-state-active {
	color: '.$clr['accent1'].';
}
//.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title .sc_accordion_icon {
//	border-color: '.$clr['accent1'].';
//	background-color: '.$clr['accent1'].';
//}
.sc_accordion.sc_accordion_style_2 .sc_accordion_item .sc_accordion_title .sc_accordion_icon {
	color: '.$clr['accent1'].';
}


/* Audio */


/* Button */
input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.sc_button {
	'.organics_get_custom_font_css('button').';
}
input[type="submit"],
input[type="reset"],
input[type="button"],
button,
.sc_button.sc_button_style_filled {
	background-color: '.$clr['accent1'].';
}
.sc_button_style_filled.sc_button_scheme_dark {
	background-color: '.$clr['accent2_hover'].';
}
input[type="submit"]:hover,
input[type="reset"]:hover,
input[type="button"]:hover,
button:hover,
.sc_button,
.sc_button.sc_button_scheme_original:hover,
.sc_button.sc_button_scheme_orange:hover,
.sc_button.sc_button_scheme_crimson:hover {
	background-color: '.$clr['accent2_hover'].';
	border-color: '.$clr['accent2_hover'].';
}
.sc_button_style_filled.sc_button_scheme_dark:hover {
	background-color: '.$clr['accent1'].';
}
.sc_button.sc_button_style_border {
	border-color: '.$clr['accent1'].';
	color: '.$clr['accent1'].';
}
.sc_button.sc_button_style_border.sc_button_scheme_dark {
	border-color: '.$clr['accent2_hover'].';
	color: '.$clr['accent2_hover'].';
}
.sc_button.sc_button_style_border.sc_button_scheme_dark:hover {
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}


/* Blogger */
.sc_blogger.layout_date .sc_blogger_item .sc_blogger_date { 
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_plain_item:nth-child(even) .sc_plain_item_inner {
    background-color: '.$clr['accent1'].';
}

/* Call to Action */
.sc_call_to_action_accented {
	background-color: '.$clr['accent1'].';
}
.sc_call_to_action_accented .sc_item_button > a {
	color: '.$clr['accent1'].';
}
.sc_call_to_action_accented .sc_item_button > a:before {
	background-color: '.$clr['accent1'].';
}
.sc_call_to_action .sc_call_to_action_descr {
	color: '.$clr['accent3'].';
}

/* Chat */
.sc_chat_inner a {
  color: '.$clr['accent1'].';
}
.sc_chat_inner a:hover {
  color: '.$clr['accent1_hover'].';
}
.sc_chat .sc_chat_title a {
  color: '.$clr['accent2_hover'].';
}
.sc_chat .sc_chat_title a:hover {
  color: '.$clr['accent1'].';
}

/* Clients */
.sc_clients_style_clients-2 .sc_client_title a:hover {
	color: '.$clr['accent1'].';
}
.sc_clients_style_clients-2 .sc_client_description:before,
.sc_clients_style_clients-2 .sc_client_position {
	color: '.$clr['accent1'].';
}

/* Contact form */
.sc_form .sc_form_item.sc_form_button button {
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_form .sc_form_item.sc_form_button button:hover {
	background-color: '.$clr['accent1_hover'].';
	border-color: '.$clr['accent1_hover'].';
}
.sc_form_style_form_2 .sc_form_address_label {
	color: '.$clr['accent1'].';
}

/* Countdown Style 1 */
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits,
.sc_countdown.sc_countdown_style_1 .sc_countdown_separator {
	color: '.$clr['accent1'].';
}
.sc_countdown.sc_countdown_style_1 .sc_countdown_digits {
	border-color: '.$clr['accent1'].';
}

/* Countdown Style 2 */
.sc_countdown.sc_countdown_style_2 .sc_countdown_separator {
	color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8);
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_digits span {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.5);
}
.sc_countdown.sc_countdown_style_2 .sc_countdown_label {
	color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8);
}

/* Dropcaps */
.sc_dropcaps.sc_dropcaps_style_1 .sc_dropcaps_item {
	background-color: '.$clr['accent1'].';
}
.sc_dropcaps.sc_dropcaps_style_2 .sc_dropcaps_item {
	background-color: '.$clr['accent1'].';
} 
.sc_dropcaps.sc_dropcaps_style_3 .sc_dropcaps_item {
	background-color: '.$clr['accent2'].';
}
.sc_dropcaps.sc_dropcaps_style_4 .sc_dropcaps_item {
	background-color: '.$clr['accent1'].';
}


/* Emailer */
.sc_emailer .sc_emailer_button:hover {
	background-color: '.$clr['accent1'].';
}
.sc_emailer.sc_emailer_opened .sc_emailer_button:hover {
	border-color: '.$clr['accent1'].';
}



/* Events */
.sc_events_style_events-2 .sc_events_item_date {
	background-color: '.$clr['accent1'].';
}
.sc_events_style_events-2 .sc_events_item_time {
	color: '.$clr['accent3'].';
}

/* Quote */
.sc_quote {
	background-color: '.$clr['accent1'].';
}

/* Highlight */
.sc_highlight_style_1 {
	background-color: '.$clr['accent2_hover'].';
}
.sc_highlight_style_2 {
	background-color: '.$clr['accent1_hover'].';
}


/* Icon */
.sc_icon_hover:hover,
a:hover .sc_icon_hover {
	background-color: '.$clr['accent1'].' !important; 
}

.sc_icon_shape_round.sc_icon,
.sc_icon_shape_square.sc_icon {	
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}

.sc_icon_shape_round.sc_icon:hover,
.sc_icon_shape_square.sc_icon:hover,
a:hover .sc_icon_shape_round.sc_icon,
a:hover .sc_icon_shape_square.sc_icon {
	color: '.$clr['accent1'].';
}


/* Image */
figure figcaption,
.sc_image figcaption {
	background-color: '.$clr['accent3_hover'].';
	color: '.$clr['accent2'].';
	//background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6);
}
.sc_image  {
	//background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.6);
}
.sc_image a:hover:after {
	color: '.$clr['accent1'].';
}


/* Infobox */
.sc_infobox.sc_infobox_style_regular {
	background-color: '.$clr['accent3_hover'].';
	color: '.$clr['accent2_hover'].';
}


/* List */
.sc_list_style_iconed li:before,
.sc_list_style_iconed .sc_list_icon {
	color: '.$clr['accent1'].';
}
.sc_list_style_iconed li a:hover .sc_list_title {
	color: '.$clr['accent1_hover'].';
}


/* Popup */
.sc_popup:before {
	background-color: '.$clr['accent1'].';
}
.popup_wrap {
	background-color: '.$clr['accent3_hover'].';
	-webkit-box-shadow: 0px 0px 0px 1px '.$clr['accent1'].';
    -moz-box-shadow: 0px 0px 0px 1px '.$clr['accent1'].';
    box-shadow: 0px 0px 0px 1px '.$clr['accent1'].';
}
}


/* Price block */
.sc_price_block.sc_price_block_style_1 {
	background-color: '.$clr['accent1'].';
}
.sc_price_block.sc_price_block_style_2 {
	background-color: '.$clr['accent1_hover'].';
}
.sc_price_block.sc_price_block_style_3 {
	background-color: '.$clr['accent3_hover'].';
}
.sc_price_block.sc_price_block_style_3,
.sc_price_block_style_3 .sc_price_block_money * {
	color: '.$clr['accent3'].';
}
.sc_price_block.sc_price_block_style_3 .sc_price_block_link .sc_button:hover {
	background-color: '.$clr['accent3'].' !important;
}

/* Section */
.sc_services_item .sc_services_item_readmore span {
	color: '.$clr['accent1'].';
}
.sc_services_item .sc_services_item_readmore:hover,
.sc_services_item .sc_services_item_readmore:hover span {
	color: '.$clr['accent1_hover'].';
}


/* Services */
.sc_services_item .sc_services_item_readmore span {
  color: '.$clr['accent1'].';
}
.sc_services_item .sc_services_item_readmore:hover,
.sc_services_item .sc_services_item_readmore:hover span {
  color: '.$clr['accent1'].';
}
.sc_services_style_services-1 .sc_icon,
.sc_services_style_services-2 .sc_icon {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_services_style_services-1 .sc_icon:hover,
.sc_services_style_services-1 a:hover .sc_icon,
.sc_services_style_services-2 .sc_icon:hover,
.sc_services_style_services-2 a:hover .sc_icon {
	border-color: '.$clr['accent2_hover'].';
	background-color: '.$clr['accent2_hover'].';
}
.sc_services_style_services-3 a:hover .sc_icon,
.sc_services_style_services-3 .sc_icon:hover {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-3 a:hover .sc_services_item_title {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-4 .sc_icon {
	background-color: '.$clr['accent1'].';
}
.sc_services_style_services-4 a:hover .sc_icon,
.sc_services_style_services-4 .sc_icon:hover {
	background-color: '.$clr['accent1_hover'].';
}
.sc_services_style_services-4 a:hover .sc_services_item_title {
	color: '.$clr['accent1'].';
}
.sc_services_style_services-2 .sc_services_item .sc_services_item_description {
	color: '.$clr['accent3'].';
}


/* Scroll controls */
.sc_scroll_controls_wrap a {
	background-color: '.$clr['accent1'].';
}
.sc_scroll_controls_type_side .sc_scroll_controls_wrap a {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8);
}
.sc_scroll_controls_wrap a:hover {
//	background-color: '.$clr['accent1_hover'].';
}
.sc_scroll_bar .swiper-scrollbar-drag:before {
	background-color: '.$clr['accent1'].';
}

.sc_blogger .sc_scroll_controls_wrap a:hover {
	color: '.$clr['accent1'].';
}


/* Skills */
.sc_skills_counter .sc_skills_item .sc_skills_icon {
	color: '.$clr['accent1'].';
}
.sc_skills_counter .sc_skills_item:hover .sc_skills_icon {
	color: '.$clr['accent1_hover'].';
}
.sc_skills_bar .sc_skills_item .sc_skills_count {
	border-color: '.$clr['accent1'].';
}

.sc_skills_bar .sc_skills_info .sc_skills_label {
	color: '.$clr['accent2_hover'].';
}

.sc_skills_bar .sc_skills_item .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_3 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_count,
.sc_skills_counter .sc_skills_item.sc_skills_style_4 .sc_skills_info {
	background-color: '.$clr['accent1'].';
}

/* Slider */
.sc_slider_controls_wrap a:hover {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_slider_swiper .sc_slider_pagination_wrap .swiper-active-switch {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_slider_swiper .sc_slider_info {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8) !important;
}
.sc_slider_pagination_over .sc_slider_pagination_wrap span:hover,
.sc_slider_pagination_over .sc_slider_pagination_wrap .swiper-active-switch {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}

/* Slider */
.sc_slider_controls_wrap a {
	background-color: '.$clr['accent1'].';
}
.sc_slider .sc_slider_controls_wrap a:hover {
	background-color: '.$clr['accent1'].';
}
.sc_slider .sc_slider_controls_wrap a,
.sc_scroll_controls .sc_scroll_controls_wrap a,
.sc_slider_woocommerce .sc_scroll_controls_wrap a {
	color: '.$clr['accent1'].';
}
.sc_scroll_controls .sc_scroll_controls_wrap a:hover,
.sc_slider_woocommerce .sc_scroll_controls_wrap a:hover {
    color: '.$clr['accent2_hover'].';
}
.sc_slider_nocontrols.sc_slider_nopagination .sc_slider_controls_wrap a {
	color: '.$clr['accent1'].';
}
.sc_slider_nocontrols.sc_slider_nopagination .sc_slider_controls_wrap a:hover {
	color: '.$clr['accent2_hover'].';
}

/* Socials */
.sc_socials.sc_socials_type_icons a:hover,
.scheme_dark .sc_socials.sc_socials_shape_round a:hover,
.scheme_light .sc_socials.sc_socials_shape_round a:hover {
	color: '.$clr['accent3_hover'].';
	//border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.contacts_wrap_inner .sc_socials.sc_socials_shape_round a {
	color: '.$clr['accent1'].';
	background-color: '.$clr['accent3_hover'].';
}
.contacts_wrap_inner .sc_socials.sc_socials_shape_round a:hover {
	color: '.$clr['accent3_hover'].';
	background-color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}

.post_custom_fields .post-custom_field-key a {
	background-color: '.$clr['accent3_hover'].';
}
.post_custom_fields .post-custom_field-key a:hover {
	background-color: '.$clr['accent1'].';
}

/* Tabs */
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li a:hover,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover {
	color: '.$clr['accent1'].';
}
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a {
	color: '.$clr['accent1'].';
}
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a:hover,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a:hover {
	color: '.$clr['accent3'].';
}
.sc_tabs.sc_tabs_style_1 .sc_tabs_titles li.ui-state-active a:after {
	background-color: '.$clr['accent3_hover'].';
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a {
	color: '.$clr['accent3'].';
	border-color: '.$clr['accent3_hover'].';
	background-color: '.$clr['accent3_hover']. '!important' .';
}
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li a:hover,
.sc_tabs.sc_tabs_style_2 .sc_tabs_titles li.ui-state-active a {
	//color: '.$clr['accent2_hover'].';
	border-color: '.$clr['accent3_hover'].';
	background-color: '.$clr['accent3_hover']. '!important' .';
}



/* Team */
.sc_team_item .sc_team_item_info .sc_team_item_title a {
	color: '.$clr['accent2_hover'].';
}
.sc_team_item .sc_team_item_info .sc_team_item_title a:hover {
	color: '.$clr['accent1'].';
}
.sc_team_item .sc_team_item_info .sc_team_item_position {
	color: '.$clr['accent1'].';
}
.sc_team_style_team-1 .sc_team_item_info,
.sc_team_style_team-3 .sc_team_item_info {
	border-color: '.$clr['accent1'].';
}
.sc_team_style_team-1 .sc_team_item_avatar:before {
    background-color: '.$clr['accent1'].';
}
.sc_team_style_team-1 .sc_socials .social_icons {
    color: '.$clr['accent2'].';
}
.sc_team.sc_team_style_team-3 .sc_team_item_avatar .sc_team_item_hover {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.8);
}
.sc_team.sc_team_style_team-4 .sc_socials_item a:hover {
	border-color: '.$clr['accent1'].';
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_title a {
	color: '.$clr['accent1'].';
}
.sc_team_style_team-4 .sc_team_item_info .sc_team_item_title a:hover {
	color: '.$clr['accent1_hover'].';
}


/* Testimonials */
.sc_testimonials_style_testimonials-2 .sc_testimonial_author_name,
.sc_testimonials_style_testimonials-4 .sc_testimonial_author_name,
.sc_testimonials_style_testimonials-2 .sc_testimonial_author_position{
	color: '.$clr['accent2_hover'].';
}
.sc_testimonials_style_testimonials-3 .sc_testimonial_content p:first-child:before,
.sc_testimonials_style_testimonials-3 .sc_testimonial_author_position {
	color: '.$clr['accent1'].';
}
.sc_testimonials_style_testimonials-4 .sc_testimonial_content p:first-child:before,
.sc_testimonials_style_testimonials-4 .sc_testimonial_author_position {
	color: '.$clr['accent1'].';
}
.sc_testimonials_style_testimonials-4 .sc_testimonial_content {
	color: '.$clr['accent3'].';
}

/* Title */
.sc_title_icon {
	color: '.$clr['accent1'].';
}
.sc_title_underline::after {
    border-color: '.$clr['accent2'].';
}

/* Toggles */
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title.ui-state-active {
	color: '.$clr['accent1'].';
	border-color: '.$clr['accent1'].';
}
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon_opened {
	background-color: '.$clr['accent1'].';
}
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:hover {
	color: '.$clr['accent1_hover'].';
	border-color: '.$clr['accent1_hover'].';
}
.sc_toggles.sc_toggles_style_1 .sc_toggles_item .sc_toggles_title:hover .sc_toggles_icon_opened {
	background-color: '.$clr['accent1_hover'].';
}

.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title.ui-state-active {
	color: '.$clr['accent1'].';
}
.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title .sc_toggles_icon {
	border-color: '.$clr['accent1'].';
	background-color: '.$clr['accent1'].';
}
.sc_toggles.sc_toggles_style_2 .sc_toggles_item .sc_toggles_title.ui-state-active .sc_toggles_icon {
	color: '.$clr['accent1'].';
}


/* Tooltip */
.sc_tooltip_parent .sc_tooltip,
.sc_tooltip_parent .sc_tooltip:before {
	background-color: '.$clr['accent2_hover'].';
}

/* Common styles (title, subtitle and description for some shortcodes) */
.sc_item_subtitle {
	color: '.$clr['accent1'].';
}
.sc_item_button > a:before {
	color: '.$clr['accent1'].';
}
.sc_item_button > a:hover:before {
	color: '.$clr['accent1_hover'].';
}

/* Video */
.sc_video_player .sc_video_frame:before {
	color: '.$clr['accent1'].';
}


/* Plugins */

/* Content timeline */
.timeline.flatLine #t_line_left, .timeline.flatLine #t_line_right, #content .timeline.flatLine #t_line_left, #content .timeline.flatLine #t_line_right {
	color: '.$clr['accent1'].';
}
.timeline.flatNav .t_left, .timeline.flatNav .t_left:hover:active, #content .timeline.flatNav .t_left, #content .timeline.flatNav .t_left:hover:active,
.timeline.flatNav .t_left:hover, #content .timeline.flatNav .t_left:hover,
.timeline.flatNav .t_right, .timeline.flatNav .t_right:hover:active, #content .timeline.flatNav .t_right, #content .timeline.flatNav .t_right:hover:active,
.timeline.flatNav .t_right:hover, #content .timeline.flatNav .t_right:hover {
	background-color: rgba('.$clr['accent1_rgb']['r'].','.$clr['accent1_rgb']['g'].','.$clr['accent1_rgb']['b'].', 0.2);
}

/* Donation */
.donation .post_goal_title .post_goal_amount,
.post_type_donation.post_item_single.donation .post_sidebar .post_raised .post_raised_amount {
	color: '.$clr['accent1'].';
}
.post_type_donation .sc_donations_form_field_amount .sc_donations_form_label,
.post_type_donation .sc_donations_form_amount_label,
 .post_type_donation.donation .sc_socials_share .sc_socials_share_caption {
	color: '.$clr['accent2_hover'].';
}


/* HTML5 Player */
#myplayer .ttw-music-player .player .title {
	color: '.$clr['accent2_hover'].';
}
#myplayer .ttw-music-player li.playing,
#myplayer .ttw-music-player li:hover {
	color: '.$clr['accent2_hover'].' !important;
}

#myplayer .ttw-music-player .elapsed {
	background: '.$clr['accent1'].';
}

/* Essenrial Grid */
/* Gallery */
.organics .esg-filterbutton, .organics .esg-navigationbutton, .organics .esg-sortbutton, .organics .esg-cartbutton {
 	color: '.$clr['accent2'].';
}
.organics .esg-filterbutton,
.organics .esg-cartbutton,
.organics .esg-navigationbutton {
	background: '.$clr['accent3_hover'].' !important;
}
.organics .esg-filterbutton:hover,
.organics .esg-navigationbutton:hover,
.organics .esg-pagination .esg-navigationbutton.selected {
	//background: '.$clr['accent1'].' !important;
}
.organics .esg-filterbutton:hover, .organics .esg-sortbutton:hover,
.organics .esg-sortbutton-order:hover, .organics .esg-cartbutton-order:hover,
.organics .esg-filterbutton.selected {
 	//color: '.$clr['accent2'].' !important;
	background: '.$clr['accent1'].' !important;
}
.eg-organics-shop-wrapper .esg-starring .star-rating {
 	color: '.$clr['accent1'].' !important;
}

/* Tribe Events */
#booking_back_today a {
	color: '.$clr['accent2'].';
}
#tribe-bar-form,
.tribe-bar-views-inner,
#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a {
	background-color: '.$clr['accent3_hover'].';
}
#tribe-bar-form .tribe-bar-submit input[type=submit],
#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option.tribe-bar-active a:hover,
#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a:hover {
	background-color: '.$clr['accent1'].';
}
#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a {
	color: '.$clr['accent3'].';
}
#tribe-bar-form label {
	color: '.$clr['accent2_hover'].';
}
#tribe-bar-form input[type=text] {
	color: '.$clr['accent3'].';
}
.tribe-events-list-separator-month span,
.close_booking:hover {
	color: '.$clr['accent1'].';
}
#tribe-events .tribe-events-button, .tribe-events-button {
	background-color: '.$clr['accent2'].';
}
#tribe-events .tribe-events-button:hover, .tribe-events-button:hover {
	background-color: '.$clr['accent1_hover'].';
}

/* Revolution Slider */
.rev_slider .hermes .tp-bullet.selected,
.rev_slider .hermes .tp-bullet.selected:after,
.rev_slider .hermes .tp-bullet:hover {
	background-color: '.$clr['accent1'].';
}
';		
		return $custom_style.$css;	
	}
}

// Add skin responsive styles
if (!function_exists('organics_action_skin_add_responsive')) {
	//add_action('organics_action_add_responsive', 'organics_action_skin_add_responsive');
	function organics_action_skin_add_responsive() {
		$suffix = organics_param_is_off(organics_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(organics_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			organics_enqueue_style( 'theme-skin-responsive-style', organics_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('organics_filter_skin_add_responsive_inline')) {
	//add_filter('organics_filter_add_responsive_inline', 'organics_filter_skin_add_responsive_inline');
	function organics_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Remove list files for compilation
if (!function_exists('organics_filter_skin_compile_less')) {
	//add_filter('organics_filter_compile_less', 'organics_filter_skin_compile_less');
	function organics_filter_skin_compile_less($files) {
		return array();	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('organics_action_skin_add_scripts')) {
	//add_action('organics_action_add_scripts', 'organics_action_skin_add_scripts');
	function organics_action_skin_add_scripts() {
		if (file_exists(organics_get_file_dir('skin.js')))
			organics_enqueue_script( 'theme-skin-script', organics_get_file_url('skin.js'), array(), null );
		if (organics_get_theme_option('show_theme_customizer') == 'yes' && file_exists(organics_get_file_dir('skin.customizer.js')))
			organics_enqueue_script( 'theme-skin-customizer-script', organics_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('organics_action_skin_add_scripts_inline')) {
	//add_action('organics_action_add_scripts_inline', 'organics_action_skin_add_scripts_inline');
	function organics_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
		// Example:
		// echo '<script type="text/javascript">'
		//	. 'jQuery(document).ready(function() {'
		//	. "if (ORGANICS_GLOBALS['theme_font']=='') ORGANICS_GLOBALS['theme_font'] = '" . organics_get_custom_font_settings('p', 'font-family') . "';"
		//	. "ORGANICS_GLOBALS['theme_skin_color'] = '" . organics_get_scheme_color('accent1') . "';"
		//	. "});"
		//	. "< /script>";
	}
}
?>