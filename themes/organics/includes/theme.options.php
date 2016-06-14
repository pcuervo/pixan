<?php

/* Theme setup section
-------------------------------------------------------------------- */


// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings
if ( !function_exists( 'organics_framework_settings' ) ) {
	function organics_framework_settings() {
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['settings'] = array(

			'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																		// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																		// less  - slow, but support all features
			'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
			'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
			'less_separator'	=> '',	//'/*---LESS_SEPARATOR---*/',	// string - separator inside .less file to split it when compiling to reduce memory usage
																		// (compilation speed gets a bit slow)
			'less_map'			=> 'no',								// no|internal|external - Generate map for .less files. 
																		// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)

			'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

			'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

			'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
			'slides_type'		=> 'bg'								    // images|bg - Use image as slide's content or as slide's background

		);
	}
}
organics_framework_settings();



// Default Theme Options
if ( !function_exists( 'organics_options_settings_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_options_settings_theme_setup', 2 );	// Priority 1 for add organics_filter handlers

	function organics_options_settings_theme_setup() {
		global $ORGANICS_GLOBALS;

        // Clear all saved Theme Options on first theme run
        add_action('after_switch_theme', 'organics_options_reset');

		// Settings 
		$socials_type = organics_get_theme_setting('socials_type');
				
		// Prepare arrays 
		$ORGANICS_GLOBALS['options_params'] = array(
			'list_fonts'		=> array('$organics_get_list_fonts' => ''),
			'list_fonts_styles'	=> array('$organics_get_list_fonts_styles' => ''),
			'list_socials' 		=> array('$organics_get_list_socials' => ''),
			'list_icons' 		=> array('$organics_get_list_icons' => ''),
			'list_posts_types' 	=> array('$organics_get_list_posts_types' => ''),
			'list_categories' 	=> array('$organics_get_list_categories' => ''),
			'list_menus'		=> array('$organics_get_list_menus' => ''),
			'list_sidebars'		=> array('$organics_get_list_sidebars' => ''),
			'list_positions' 	=> array('$organics_get_list_sidebars_positions' => ''),
			'list_skins'		=> array('$organics_get_list_skins' => ''),
			'list_color_schemes'=> array('$organics_get_list_color_schemes' => ''),
			'list_bg_tints'		=> array('$organics_get_list_bg_tints' => ''),
			'list_body_styles'	=> array('$organics_get_list_body_styles' => ''),
			'list_header_styles'=> array('$organics_get_list_templates_header' => ''),
			'list_blog_styles'	=> array('$organics_get_list_templates_blog' => ''),
			'list_single_styles'=> array('$organics_get_list_templates_single' => ''),
			'list_article_styles'=> array('$organics_get_list_article_styles' => ''),
			'list_blog_counters' => array('$organics_get_list_blog_counters' => ''),
			'list_animations_in' => array('$organics_get_list_animations_in' => ''),
			'list_animations_out'=> array('$organics_get_list_animations_out' => ''),
			'list_filters'		=> array('$organics_get_list_portfolio_filters' => ''),
			'list_hovers'		=> array('$organics_get_list_hovers' => ''),
			'list_hovers_dir'	=> array('$organics_get_list_hovers_directions' => ''),
			'list_alter_sizes'	=> array('$organics_get_list_alter_sizes' => ''),
			'list_sliders' 		=> array('$organics_get_list_sliders' => ''),
			'list_revo_sliders'	=> array('$organics_get_list_revo_sliders' => ''),
			'list_bg_image_positions' => array('$organics_get_list_bg_image_positions' => ''),
			'list_popups' 		=> array('$organics_get_list_popup_engines' => ''),
			'list_gmap_styles' 	=> array('$organics_get_list_googlemap_styles' => ''),
			'list_yes_no' 		=> array('$organics_get_list_yesno' => ''),
			'list_on_off' 		=> array('$organics_get_list_onoff' => ''),
			'list_show_hide' 	=> array('$organics_get_list_showhide' => ''),
			'list_sorting' 		=> array('$organics_get_list_sortings' => ''),
			'list_ordering' 	=> array('$organics_get_list_orderings' => ''),
			'list_locations' 	=> array('$organics_get_list_dedicated_locations' => '')
			);


		// Theme options array
		$ORGANICS_GLOBALS['options'] = array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'organics'),
					"start" => "partitions",
					"override" => "category,services_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'organics'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'organics'),
					"desc" => wp_kses( __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),
					
		'body_style' => array(
					"title" => esc_html__('Body style', 'organics'),
					"desc" => wp_kses( __('Select body style:', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] )
								. ' <br>' 
								. wp_kses( __('<b>boxed</b> - if you want use background color and/or image', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] )
								. ',<br>'
								. wp_kses( __('<b>wide</b> - page fill whole window with centered content', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] )
								. (organics_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] )
									: '')
								. (organics_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] )
									: ''),
					"override" => "category,services_group,post,page",
					"std" => "wide",
					"options" => $ORGANICS_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'organics'),
					"desc" => wp_kses( __('Add paddings above and below the page content', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'organics'),
					"desc" => wp_kses( __('Select skin for the theme decoration', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "trex2_no_less",
					"options" => $ORGANICS_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'organics'),
					"desc" => wp_kses( __('Select predefined color scheme for the entire page', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $ORGANICS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'organics'),
					"desc" => wp_kses( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'organics'),
					"desc" => wp_kses( __('Color and image for the site background', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'organics'),
					"desc" => wp_kses( __("Use custom color and/or image as the site background", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'organics'),
					"desc" => wp_kses( __('Body background color',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'organics'),
					"desc" => wp_kses( __('Select theme background pattern (first case - without pattern)',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => organics_get_file_url('images/spacer.png'),
						1 => organics_get_file_url('images/bg/pattern_1.jpg'),
						2 => organics_get_file_url('images/bg/pattern_2.jpg'),
						3 => organics_get_file_url('images/bg/pattern_3.jpg'),
						4 => organics_get_file_url('images/bg/pattern_4.jpg'),
						5 => organics_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'organics'),
					"desc" => wp_kses( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'organics'),
					"desc" => wp_kses( __('Select theme background image (first case - without image)',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => organics_get_file_url('images/spacer.png'),
						1 => organics_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => organics_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => organics_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'organics'),
					"desc" => wp_kses( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'organics'),
					"desc" => wp_kses( __('Select custom image position',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'organics'),
					"desc" => wp_kses( __('Always load background images or only for boxed body style', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'organics'),
						'always' => esc_html__('Always', 'organics')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'organics'),
					"desc" => wp_kses( __('Parameters of the video, used as site background', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'organics'),
					"desc" => wp_kses( __("Show video as the site background", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'organics'),
					"desc" => wp_kses( __("Youtube code of video", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'organics'),
					"desc" => wp_kses( __("URL to video-file (uploaded on your site)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"readonly" =>false,
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'organics'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'organics'),
															'update' => esc_html__( 'Select Video', 'organics')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'organics'),
					"desc" => wp_kses( __('Use overlay texture for the video background', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'organics'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'organics'),
					"desc" => wp_kses( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'organics'),
					"desc" => wp_kses( __('Select desired style of the page header', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "header_5",
					"options" => $ORGANICS_GLOBALS['options_params']['list_header_styles'],
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'organics'),
					"desc" => wp_kses( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'organics'),
					"desc" => wp_kses( __('Select position for the top panel with logo and main menu', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'organics'),
						'above' => esc_html__('Above slider', 'organics'),
						'below' => esc_html__('Below slider', 'organics'),
						'over'  => esc_html__('Over slider', 'organics')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'organics'),
					"desc" => wp_kses( __('Select predefined color scheme for the top panel', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $ORGANICS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

        'bg_image_custom_page' => array(
                    "title" => esc_html__('Background custom image',  'organics'),
                    "desc" => wp_kses( __('Select or upload background custom image)',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
                    "override" => "category,services_group,post,page",
                    "dependency" => array(
                        'header_bg_custom' => array('yes')
                    ),
                    "std" => "",
                    "type" => "media"
                    ),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'organics'),
					"desc" => wp_kses( __('Show post/page/category title', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'organics'),
					"desc" => wp_kses( __('Show path to current category (post, page)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'organics'),
					"desc" => wp_kses( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'organics'),
					"desc" => wp_kses( __('Select the Main menu style and position', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'organics'),
					"desc" => wp_kses( __('Select main menu for the current page',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $ORGANICS_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'organics'),
					"desc" => wp_kses( __('Attach main menu to top of window then page scroll down', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'organics'), 
						"none"=>esc_html__("Don't fix menu position", 'organics')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'organics'),
					"desc" => wp_kses( __('Use slider background for main menu items', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"type" => "switch",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'organics'),
					"desc" => wp_kses( __('Select animation to show submenu ', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "bounceIn",
					"type" => "select",
					"options" => $ORGANICS_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'organics'),
					"desc" => wp_kses( __('Select animation to hide submenu ', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => $ORGANICS_GLOBALS['options_params']['list_animations_out']),
	/*	
		"menu_relayout" => array( 
					"title" => esc_html__('Main menu relayout', 'organics'),
					"desc" => wp_kses( __('Allow relayout main menu if window width less then this value', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => esc_html__('Main menu responsive', 'organics'),
					"desc" => wp_kses( __('Allow responsive version for the main menu if window width less then this value', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),*/
					
		"menu_mobile" => array( 
					"title" => esc_html__('Main menu responsive', 'themerex'),
					"desc" => wp_kses( __('Allow responsive version for the main menu if window width less then this value', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => 1023,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'organics'),
					"desc" => wp_kses( __('Width for dropdown menus in main menu', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'organics'),
					"desc" => wp_kses( __("Select parts for the user's menu area", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'organics'),
					"desc" => wp_kses( __('Show user menu area on top of page', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'organics'),
					"desc" => wp_kses( __('Select user menu for the current page',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => $ORGANICS_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'organics'),
					"desc" => wp_kses( __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'organics'),
					"desc" => wp_kses( __('Show Login and Logout buttons in the user menu area', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'organics'),
					"desc" => wp_kses( __('Show bookmarks selector in the user menu', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'organics'),
					"desc" => wp_kses( __('Show Social icons in the mobile menu and Top Panel Style "Socials Ecommerce"', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		

		
//		"info_header_4" => array( 
//					"title" => esc_html__("Table of Contents (TOC)", 'organics'),
//					"desc" => wp_kses( __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,page,post",
//					"type" => "info"),
//		
//		"menu_toc" => array( 
//					"title" => esc_html__('TOC position', 'organics'),
//					"desc" => wp_kses( __('Show TOC for the current page', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,post,page",
//					"std" => "float",
//					"options" => array(
//						'hide'  => esc_html__('Hide', 'organics'),
//						'fixed' => esc_html__('Fixed', 'organics'),
//						'float' => esc_html__('Float', 'organics')
//					),
//					"type" => "checklist"),
//		
//		"menu_toc_home" => array(
//					"title" => esc_html__('Add "Home" into TOC', 'organics'),
//					"desc" => wp_kses( __('Automatically add "Home" item into table of contents - return to home page of the site', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,post,page",
//					"dependency" => array(
//						'menu_toc' => array('fixed','float')
//					),
//					"std" => "yes",
//					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
//					"type" => "switch"),
//		
//		"menu_toc_top" => array( 
//					"title" => esc_html__('Add "To Top" into TOC', 'organics'),
//					"desc" => wp_kses( __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,post,page",
//					"dependency" => array(
//						'menu_toc' => array('fixed','float')
//					),
//					"std" => "yes",
//					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
//					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'organics'),
					"desc" => wp_kses( __("Select or upload logos for the site's header and select it position", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'favicon' => array(
					"title" => esc_html__('Favicon', 'organics'),
					"desc" => wp_kses( __("Upload a 16px x 16px image that will represent your website's favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (<a href='http://www.favicon.cc/'>www.favicon.cc</a>)</em>", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"type" => "media"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'organics'),
					"desc" => wp_kses( __('Main logo image', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo mobile image', 'organics'),
					"desc" => wp_kses( __('Logo image for the header (if menu is mobile)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'organics'),
					"desc" => wp_kses( __('Logo text - display it after logo image', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => esc_html__('Logo slogan', 'organics'),
					"desc" => wp_kses( __('Logo slogan - display it under logo image (instead the site tagline)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'organics'),
					"desc" => wp_kses( __('Height for the logo in the header area', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'organics'),
					"desc" => wp_kses( __('Top offset for the logo in the header area', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'organics'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'organics'),
					"desc" => wp_kses( __('Select parameters for main slider (you can override it in each category and page)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'organics'),
					"desc" => wp_kses( __('Do you want to show slider on each page (post)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'organics'),
					"desc" => wp_kses( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'organics'),
						"fullwide"=>esc_html__("Fullwide", 'organics'),
						"fullscreen"=>esc_html__("Fullscreen", 'organics')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'organics'),
					"desc" => wp_kses( __("Slider height (in pixels) - only if slider display with fixed height.", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'organics'),
					"desc" => wp_kses( __('What engine use to show slider?', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "revo",
					"options" => $ORGANICS_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_alias" => array(
					"title" => esc_html__('Revolution Slider: Select slider',  'organics'),
					"desc" => wp_kses( __("Select slider to show (if engine=revo in the field above)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('revo')
					),
					"std" => "",
					"options" => $ORGANICS_GLOBALS['options_params']['list_revo_sliders'],
					"type" => "select"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'organics'),
					"desc" => wp_kses( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => organics_array_merge(array(0 => esc_html__('- Select category -', 'organics')), $ORGANICS_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'organics'),
					"desc" => wp_kses( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'organics'),
					"desc" => wp_kses( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => $ORGANICS_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'organics'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => $ORGANICS_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'organics'),
					"desc" => wp_kses( __("Interval (in ms) for slides change in slider", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'organics'),
					"desc" => wp_kses( __("Choose pagination style for the slider", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'organics'),
						'yes'  => esc_html__('Dots', 'organics'), 
						'over' => esc_html__('Titles', 'organics')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'organics'),
					"desc" => wp_kses( __("Do you want to show post's title, reviews rating and description on slides in slider", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'organics'),
						'slide' => esc_html__('Slide', 'organics'), 
						'fixed' => esc_html__('Fixed', 'organics')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'organics'),
					"desc" => wp_kses( __("Do you want to show post's category on slides in slider", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'organics'),
					"desc" => wp_kses( __("Do you want to show post's reviews rating on slides in slider", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'organics'),
					"desc" => wp_kses( __("How many characters show in the post's description in slider. 0 - no descriptions", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'organics'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'organics'),
					"desc" => wp_kses( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'organics'),
					"desc" => wp_kses( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'organics'),
					"desc" => wp_kses( __('Show / Hide and select main sidebar', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'organics'),
					"desc" => wp_kses( __('Select position for the main sidebar or hide it',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "right",
					"options" => $ORGANICS_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'organics'),
					"desc" => wp_kses( __('Select predefined color scheme for the main sidebar', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $ORGANICS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'organics'),
					"desc" => wp_kses( __('Select main sidebar content',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => $ORGANICS_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),

		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'organics'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'organics'),
					"desc" => wp_kses( __("Select components of the footer, set style and put the content for the user's footer area", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'organics'),
					"desc" => wp_kses( __('Select style for the footer sidebar or hide it', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'organics'),
					"desc" => wp_kses( __('Select predefined color scheme for the footer', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $ORGANICS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'organics'),
					"desc" => wp_kses( __('Select footer sidebar for the blog page',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => $ORGANICS_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'organics'),
					"desc" => wp_kses( __('Select columns number for the footer sidebar',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),


		"info_footer_2" => array(
					"title" => esc_html__('Shortcode area in Footer', 'organics'),
					"desc" => wp_kses( __('Add shortcode for the Footer', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_footer_shortcode_area" => array(
					"title" => esc_html__('Show shortcode area in footer', 'organics'),
					"desc" => wp_kses( __('You can add shortcode for the Footer', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
        ),

		"footer_shortcode_area_scheme" => array(
					"title" => esc_html__("Color scheme", 'organics'),
					"desc" => wp_kses( __('Select predefined color scheme for the footer shortcode area', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_footer_shortcode_area' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $ORGANICS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

        "footer_shortcode_area" => array(
                    "title" => esc_html__('Footer shortcode area',  'organics'),
                    "desc" => wp_kses( __("Add shortcode or simple text for the Footer", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
                    "override" => "category,services_group,page,post",
                    "dependency" => array(
                        'show_footer_shortcode_area' => array('yes')
                    ),
                    "std" => "yes",
                    "rows" => "10",
                    "type" => "editor"),


		
//		"info_footer_3" => array(
//					"title" => esc_html__('Twitter in Footer', 'organics'),
//					"desc" => wp_kses( __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,page,post",
//					"type" => "info"),
//
//		"show_twitter_in_footer" => array(
//					"title" => esc_html__('Show Twitter in footer', 'organics'),
//					"desc" => wp_kses( __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,post,page",
//					"std" => "no",
//					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
//					"type" => "switch"),
//
//		"twitter_scheme" => array(
//					"title" => esc_html__("Color scheme", 'organics'),
//					"desc" => wp_kses( __('Select predefined color scheme for the twitter area', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,post,page",
//					"dependency" => array(
//						'show_twitter_in_footer' => array('yes')
//					),
//					"std" => "original",
//					"dir" => "horizontal",
//					"options" => $ORGANICS_GLOBALS['options_params']['list_color_schemes'],
//					"type" => "checklist"),
//
//		"twitter_count" => array( 
//					"title" => esc_html__('Twitter count', 'organics'),
//					"desc" => wp_kses( __('Number twitter to show', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,post,page",
//					"dependency" => array(
//						'show_twitter_in_footer' => array('yes')
//					),
//					"std" => 3,
//					"step" => 1,
//					"min" => 1,
//					"max" => 10,
//					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'organics'),
					"desc" => wp_kses( __('Select parameters for Google map (you can override it in each category and page)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'organics'),
					"desc" => wp_kses( __('Do you want to show Google map on each page (post)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'organics'),
					"desc" => wp_kses( __("Map height (default - in pixels, allows any CSS units of measure)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'organics'),
					"desc" => wp_kses( __("Enter address to show on map center", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longtitude to show on map',  'organics'),
					"desc" => wp_kses( __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'organics'),
					"desc" => wp_kses( __("Enter title to show on map center", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'organics'),
					"desc" => wp_kses( __("Enter description to show on map center", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'organics'),
					"desc" => wp_kses( __("Enter desired initial zoom for Google map", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'organics'),
					"desc" => wp_kses( __("Select style to show Google map", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => $ORGANICS_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'organics'),
					"desc" => wp_kses( __("Select or upload png-image with Google map marker", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'organics'),
					"desc" => wp_kses( __("Show/Hide contacts area in the footer", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'organics'),
					"desc" => wp_kses( __('Show contact information area in footer: site logo, contact info and large social icons', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'organics'),
					"desc" => wp_kses( __('Select predefined color scheme for the contacts area', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $ORGANICS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

//		'logo_footer' => array(
//					"title" => esc_html__('Logo image for footer', 'organics'),
//					"desc" => wp_kses( __('Logo image in the footer (in the contacts area)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,post,page",
//					"dependency" => array(
//						'show_contacts_in_footer' => array('yes')
//					),
//					"std" => "",
//					"type" => "media"
//					),
//		
//		'logo_footer_height' => array(
//					"title" => esc_html__('Logo height', 'organics'),
//					"desc" => wp_kses( __('Height for the logo in the footer area (in the contacts area)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
//					"override" => "category,services_group,post,page",
//					"dependency" => array(
//						'show_contacts_in_footer' => array('yes')
//					),
//					"step" => 1,
//					"std" => 30,
//					"min" => 10,
//					"max" => 300,
//					"mask" => "?999",
//					"type" => "spinner"
//					),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'organics'),
					"desc" => wp_kses( __("Show/Hide copyright area in the footer", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'organics'),
					"desc" => wp_kses( __('Show area with copyright information, footer menu and small social icons in footer', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'organics'),
						'text' => esc_html__('Text', 'organics'),
						'emailer' => esc_html__('Text and emailer', 'organics'),
						'menu' => esc_html__('Text and menu', 'organics'),
						'socials' => esc_html__('Text and Social icons', 'organics')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'organics'),
					"desc" => wp_kses( __('Select predefined color scheme for the copyright area', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials', 'emailer')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $ORGANICS_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'organics'),
					"desc" => wp_kses( __('Select footer menu for the current page',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => $ORGANICS_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'organics'),
					"desc" => wp_kses( __("Copyright text to show in footer area (bottom of site)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials', 'emailer')
					),
					"std" => "Organics &copy; 2014 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'organics'),
					"override" => "category,services_group,page,post",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'organics'),
					"desc" => wp_kses( __('Animation parameters and responsive layouts for the small screens', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'organics'),
					"desc" => wp_kses( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'organics'),
					"desc" => wp_kses( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'organics'),
					"desc" => wp_kses( __('Do you want use extended animations effects on your site?', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'organics'),
					"desc" => wp_kses( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'organics'),
					"desc" => wp_kses( __('Do you want use responsive layouts on small screen or still use main layout?', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		


		'info_other_2' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'organics'),
					"desc" => wp_kses( __('Put here your custom CSS and JS code', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'organics'),
					"desc" => wp_kses( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'organics'),
					"desc" => wp_kses( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'organics'),
					"desc" => wp_kses( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'organics'),
					"desc" => wp_kses( __('Put here your invisible html/js code: Google analitics, counters, etc',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'organics'),
					"desc" => wp_kses( __('Put here your css code to correct main theme styles',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'organics'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'organics'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'organics'),
					"desc" => wp_kses( __('Select desired blog streampage parameters (you can override it in each category)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'organics'),
					"desc" => wp_kses( __('Select desired blog style', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "excerpt",
					"options" => $ORGANICS_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'organics'),
					"desc" => wp_kses( __('Select desired hover style (only for Blog style = Portfolio)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => $ORGANICS_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'organics'),
					"desc" => wp_kses( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => $ORGANICS_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'organics'),
					"desc" => wp_kses( __('Select article display method: boxed or stretch', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "stretch",
					"options" => $ORGANICS_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'organics'),
					"desc" => wp_kses( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => $ORGANICS_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'organics'),
					"desc" => wp_kses( __('What taxonomy use for filter buttons', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => $ORGANICS_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'organics'),
					"desc" => wp_kses( __('Select the desired sorting method for posts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "date",
					"options" => $ORGANICS_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'organics'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "desc",
					"options" => $ORGANICS_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'organics'),
					"desc" => wp_kses( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'organics'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'organics'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'organics'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'organics'),
					"desc" => wp_kses( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'organics'),
					"desc" => wp_kses( __('Select desired style for single page', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "single-standard",
					"options" => $ORGANICS_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'organics'),
					"desc" => wp_kses( __('Select icon for output before post/category name in some layouts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "services_group,page,post",
					"std" => "",
					"options" => $ORGANICS_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),
		
		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'organics'),
					"override" => "page,post",
					"desc" => wp_kses( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => $ORGANICS_GLOBALS['options_params']['list_alter_sizes']
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'organics'),
					"desc" => wp_kses( __("Show featured image (if selected) before post content on single pages", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'organics'),
					"desc" => wp_kses( __('Show area with post title on single pages', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'organics'),
					"desc" => wp_kses( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'organics'),
					"desc" => wp_kses( __('Show area with post info on single pages', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'organics'),
					"desc" => wp_kses( __('Show text before "Read more" tag on single pages', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'organics'),
					"desc" => wp_kses( __("Show post author information block on single post page", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'organics'),
					"desc" => wp_kses( __("Show tags block on single post page", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'organics'),
					"desc" => wp_kses( __("Show related posts block on single post page", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'organics'),
					"desc" => wp_kses( __("How many related posts showed on single post page", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'organics'),
					"desc" => wp_kses( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'organics'),
					"desc" => wp_kses( __('Select the desired sorting method for related posts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => $ORGANICS_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'organics'),
					"desc" => wp_kses( __('Select the desired ordering method for related posts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => $ORGANICS_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'organics'),
					"desc" => wp_kses( __("Show comments block on single post page", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'organics'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'organics'),
					"desc" => wp_kses( __('Select excluded categories, substitute parameters, etc.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'organics'),
					"desc" => wp_kses( __('Select categories, which posts are exclude from blog page', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"options" => $ORGANICS_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'organics'),
					"desc" => wp_kses( __('Select type of the pagination on blog streampages', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "pages",
					"override" => "category,services_group,page",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'organics'),
						'slider'   => esc_html__('Slider with page numbers', 'organics'),
						'viewmore' => esc_html__('"View more" button', 'organics'),
						'infinite' => esc_html__('Infinite scroll', 'organics')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'organics'),
					"desc" => wp_kses( __('Select counters, displayed near the post title', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "views",
					"options" => $ORGANICS_GLOBALS['options_params']['list_blog_counters'],
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'organics'),
					"desc" => wp_kses( __('What category display in announce block (over posts thumb) - original or nearest parental', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'organics'),
						'original' => esc_html__("Original post's category", 'organics')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'organics'),
					"desc" => wp_kses( __('Show post date after N days (before - show post age)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'organics'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'organics'),
					"desc" => wp_kses( __('Set up list of reviews criterias. You can override it in any category.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'organics'),
					"desc" => wp_kses( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'organics'),
					"desc" => wp_kses( __("Maximum level for reviews marks", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'organics'), 
						'10'=>esc_html__('10 stars', 'organics'), 
						'100'=>esc_html__('100%', 'organics')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'organics'),
					"desc" => wp_kses( __("Show rating marks as text or as stars/progress bars.", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'organics'), 
						'stars' => esc_html__('As stars or bars', 'organics')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'organics'),
					"desc" => wp_kses( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => esc_html__("bad,poor,normal,good,great", 'organics'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'organics'),
					"desc" => wp_kses( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'organics'),
						'users' => esc_html__('By visitors', 'organics')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'organics'),
					"desc" => wp_kses( __("Do you want hide second reviews tab in widgets and single posts?", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "show",
					"options" => $ORGANICS_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'organics'),
					"desc" => wp_kses( __("What visitors can vote: all or only registered", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'organics'), 
						'registered'=>esc_html__('Only registered', 'organics')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'organics'),
					"desc" => wp_kses( __('Add default reviews criterias.',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'organics'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'organics'),
					"desc" => wp_kses( __('Set up parameters to show images, galleries, audio and video posts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'organics'),
					"desc" => wp_kses( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'organics'), 
						"2" => esc_html__("Retina", 'organics')
					),
					"type" => "switch"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'organics'),
					"desc" => wp_kses( __('Substitute standard Wordpress gallery with our slider on the single pages', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'organics'),
					"desc" => wp_kses( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'organics'),
					"desc" => wp_kses( __('Maximum images number from gallery into slider', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'organics'),
					"desc" => wp_kses( __('Select engine to show popup windows with images and galleries', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "magnific",
					"options" => $ORGANICS_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'organics'),
					"desc" => wp_kses( __('Substitute audio tag with source from soundcloud to embed player', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'organics'),
					"desc" => wp_kses( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'organics'),
					"desc" => wp_kses( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'organics'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'organics'),
					"desc" => wp_kses( __("Social networks list for site footer and Social widget", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'organics'),
					"desc" => wp_kses( __('Select icon and write URL to your profile in desired social networks.',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $ORGANICS_GLOBALS['options_params']['list_socials'] : $ORGANICS_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'organics'),
					"desc" => wp_kses( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'organics'),
					"desc" => wp_kses( __("Show social share buttons block", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'organics'),
						'vertical'	=> esc_html__('Vertical', 'organics'),
						'horizontal'=> esc_html__('Horizontal', 'organics')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'organics'),
					"desc" => wp_kses( __("Show share counters after social buttons", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'organics'),
					"desc" => wp_kses( __('Caption for the block with social share buttons',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'organics'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'organics'),
					"desc" => wp_kses( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $ORGANICS_GLOBALS['options_params']['list_socials'] : $ORGANICS_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'organics'),
					"desc" => wp_kses( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'organics'),
					"desc" => wp_kses( __('Your login (username) in Twitter',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'organics'),
					"desc" => wp_kses( __('Twitter API Consumer key',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'organics'),
					"desc" => wp_kses( __('Twitter API Consumer secret',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'organics'),
					"desc" => wp_kses( __('Twitter API Token key',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'organics'),
					"desc" => wp_kses( __('Twitter API Token secret',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'organics'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'organics'),
					"desc" => wp_kses( __('Company address, phones and e-mail', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'organics'),
					"desc" => wp_kses( __('String with contact info in the left side of the site header', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header & Contact form 2', 'organics'),
					"desc" => wp_kses( __('String with open hours in the site header', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'organics'),
					"desc" => wp_kses( __('E-mail for send contact form and user registration data', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'organics'),
					"desc" => wp_kses( __('Company country, post code and city', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'organics'),
					"desc" => wp_kses( __('Street and house number', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'organics'),
					"desc" => wp_kses( __('Phone number', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'organics'),
					"desc" => wp_kses( __('Fax number', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'organics'),
					"desc" => wp_kses( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'organics'),
					"desc" => wp_kses( __("Message's maxlength in the contact form shortcode", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'organics'),
					"desc" => wp_kses( __("Message's maxlength in the comments form", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'organics'),
					"desc" => wp_kses( __('What function you want to use for sending mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'organics'),
					"desc" => wp_kses( __("What function you want to use for sending mail?", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'organics'),
						'mail' => esc_html__('PHP mail', 'organics')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'organics'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'organics'),
					"desc" => wp_kses( __('Enable/disable AJAX search and output settings for it', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'organics'),
					"desc" => wp_kses( __('Show search field in the top area and side menus', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'organics'),
					"desc" => wp_kses( __('Use incremental AJAX search for the search field in top of page', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'organics'),
					"desc" => wp_kses( __('The minimum length of the search string',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'organics'),
					"desc" => wp_kses( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'organics'),
					"desc" => wp_kses( __('Select post types, what will be include in search results. If not selected - use all types.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => $ORGANICS_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'organics'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __('Number of the posts to show in search results',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'organics'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's thumbnail in the search results", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'organics'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's publish date in the search results", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'organics'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's author in the search results", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'organics'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's counters (views, comments, likes) in the search results", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'organics'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'organics'),
					"desc" => wp_kses( __('Basic theme functionality settings', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => esc_html__('Notify about new registration', 'organics'),
					"desc" => wp_kses( __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => esc_html__('No', 'organics'),
						'both'  => esc_html__('Both', 'organics'),
						'admin' => esc_html__('Admin', 'organics'),
						'user'  => esc_html__('User', 'organics')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'organics'),
					"desc" => wp_kses( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'organics'),
					"desc" => wp_kses( __("Allow authors to edit their posts in frontend area)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'organics'),
					"desc" => wp_kses( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'organics'),
					"desc" => wp_kses( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'organics'),
					"desc" => wp_kses( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'organics'),
					"desc" => wp_kses( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'organics'),
					"desc" => wp_kses( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'organics'),
					"desc" => wp_kses( __('Allow to use Organics Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => esc_html__('Enable PO Composer in the admin panel', 'organics'),
					"desc" => wp_kses( __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'organics'),
					"desc" => wp_kses( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utility <b>yuicompressor-x.y.z.jar</b>', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $ORGANICS_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		"info_service_2" => array(
					"title" => esc_html__('Clear Wordpress cache', 'organics'),
					"desc" => wp_kses( __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"clear_cache" => array(
					"title" => esc_html__('Clear cache', 'organics'),
					"desc" => wp_kses( __('Clear Wordpress cache data', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button")
		);



		
		
		
		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		$ORGANICS_GLOBALS['options']["custom_stylesheet_file"] = array(
			"title" => esc_html__('Custom stylesheet file', 'organics'),
			"desc" => wp_kses( __('Path to the custom stylesheet (stored in the uploads folder)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"std" => "",
			"type" => "hidden");
		
		$ORGANICS_GLOBALS['options']["custom_stylesheet_url"] = array(
			"title" => esc_html__('Custom stylesheet url', 'organics'),
			"desc" => wp_kses( __('URL to the custom stylesheet (stored in the uploads folder)', 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
			"std" => "",
			"type" => "hidden");
		*/

		
		
	}
}


// Update all temporary vars (start with $organics_) in the Theme Options with actual lists
if ( !function_exists( 'organics_options_settings_theme_setup2' ) ) {
	add_action( 'organics_action_after_init_theme', 'organics_options_settings_theme_setup2', 1 );
	function organics_options_settings_theme_setup2() {
		if (organics_options_is_used()) {
			global $ORGANICS_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			if (count($ORGANICS_GLOBALS['options']) > 0) {
				foreach ($ORGANICS_GLOBALS['options'] as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (organics_substr($k1, 0, 10) == '$organics_' || organics_substr($v1, 0, 10) == '$organics_') {
								$list_func = organics_substr(organics_substr($k1, 0, 10) == '$organics_' ? $k1 : $v1, 1);
								unset($ORGANICS_GLOBALS['options'][$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$ORGANICS_GLOBALS['options'][$k]['options'] = organics_array_merge($ORGANICS_GLOBALS['options'][$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$ORGANICS_GLOBALS['options'][$k]['options'] = $lists[$list_func] = organics_array_merge($ORGANICS_GLOBALS['options'][$k]['options'], $list_func == 'organics_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		echo sprintf(esc_html__('Wrong function name %s in the theme options array', 'organics'), $list_func);
								}
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'organics_options_reset' ) ) {
	function organics_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(organics_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'organics_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "organics_%"');
				// Add Templates Options
				if (file_exists(organics_get_file_dir('demo/templates_options.txt'))) {
					$txt = organics_fgc(organics_get_file_dir('demo/templates_options.txt'));
					$data = organics_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = organics_replace_uploads_url(organics_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>