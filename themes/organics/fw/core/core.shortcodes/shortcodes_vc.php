<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
	require_once organics_get_file_dir('core/core.shortcodes/shortcodes_vc_classes.php');
}

// Width and height params
if ( !function_exists( 'organics_vc_width' ) ) {
	function organics_vc_width($w='') {
		global $ORGANICS_GLOBALS;
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", "organics"),
			"description" => wp_kses( __("Width (in pixels or percent) of the current element", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
			"group" => esc_html__('Size &amp; Margins', 'organics'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'organics_vc_height' ) ) {
	function organics_vc_height($h='') {
		global $ORGANICS_GLOBALS;
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", "organics"),
			"description" => wp_kses( __("Height (only in pixels) of the current element", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
			"group" => esc_html__('Size &amp; Margins', 'organics'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'organics_shortcodes_vc_scripts_admin' ) ) {
	//add_action( 'admin_enqueue_scripts', 'organics_shortcodes_vc_scripts_admin' );
	function organics_shortcodes_vc_scripts_admin() {
		// Include CSS 
		organics_enqueue_style ( 'shortcodes_vc-style', organics_get_file_url('core/core.shortcodes/shortcodes_vc_admin.css'), array(), null );
		// Include JS
		organics_enqueue_script( 'shortcodes_vc-script', organics_get_file_url('core/core.shortcodes/shortcodes_vc_admin.js'), array(), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'organics_shortcodes_vc_scripts_front' ) ) {
	//add_action( 'wp_enqueue_scripts', 'organics_shortcodes_vc_scripts_front' );
	function organics_shortcodes_vc_scripts_front() {
		if (organics_vc_is_frontend()) {
			// Include CSS 
			organics_enqueue_style ( 'shortcodes_vc-style', organics_get_file_url('core/core.shortcodes/shortcodes_vc_front.css'), array(), null );
			// Include JS
			organics_enqueue_script( 'shortcodes_vc-script', organics_get_file_url('core/core.shortcodes/shortcodes_vc_front.js'), array(), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'organics_shortcodes_vc_add_init_script' ) ) {
	//add_filter('organics_shortcode_output', 'organics_shortcodes_vc_add_init_script', 10, 4);
	function organics_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (organics_strpos($output, 'organics_vc_init_shortcodes')===false) {
				$id = "organics_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				$output .= '
					<script id="'.esc_attr($id).'">
						try {
							organics_init_post_formats();
							organics_init_shortcodes(jQuery("body").eq(0));
							organics_scroll_actions();
						} catch (e) { };
					</script>
				';
			}
		}
		return $output;
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_shortcodes_vc_theme_setup' ) ) {
	//if ( organics_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'organics_action_before_init_theme', 'organics_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'organics_action_after_init_theme', 'organics_shortcodes_vc_theme_setup' );
	function organics_shortcodes_vc_theme_setup() {
		global $ORGANICS_GLOBALS;
	
		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( organics_get_folder_dir('core/core.shortcodes/vc_shortcodes' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "organics"),
					"description" => wp_kses( __("Select color scheme for this block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Color scheme', 'organics'),
					"class" => "",
					"value" => array_flip(organics_get_list_color_schemes(true)),
					"type" => "dropdown"
		));

		if (organics_shortcodes_is_used()) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Disable frontend editor
			//vc_disable_frontend();

			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'organics_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'organics_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('organics_shortcode_output', 'organics_shortcodes_vc_add_init_script', 10, 4);

			// Remove standard VC shortcodes
			vc_remove_element("vc_button");
			vc_remove_element("vc_posts_slider");
			vc_remove_element("vc_gmaps");
			vc_remove_element("vc_teaser_grid");
			vc_remove_element("vc_progress_bar");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_tweetmeme");
			vc_remove_element("vc_googleplus");
			vc_remove_element("vc_facebook");
			vc_remove_element("vc_pinterest");
			vc_remove_element("vc_message");
			vc_remove_element("vc_posts_grid");
			vc_remove_element("vc_carousel");
			vc_remove_element("vc_flickr");
			vc_remove_element("vc_tour");
			vc_remove_element("vc_separator");
			vc_remove_element("vc_single_image");
			vc_remove_element("vc_cta_button");
//			vc_remove_element("vc_accordion");
//			vc_remove_element("vc_accordion_tab");
			vc_remove_element("vc_toggle");
			vc_remove_element("vc_tabs");
			vc_remove_element("vc_tab");
			vc_remove_element("vc_images_carousel");
			
			// Remove standard WP widgets
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_rss");
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_text");
			
			global $ORGANICS_GLOBALS;
			
			$ORGANICS_GLOBALS['vc_params'] = array(
				
				// Common arrays and strings
				'category' => esc_html__("Organics shortcodes", "organics"),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", "organics"),
					"description" => wp_kses( __("ID for current element", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('ID &amp; Class', 'organics'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", "organics"),
					"description" => wp_kses( __("CSS class for current element", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('ID &amp; Class', 'organics'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", "organics"),
					"description" => wp_kses( __("Select animation while object enter in the visible area of page", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('ID &amp; Class', 'organics'),
					"class" => "",
					"value" => array_flip($ORGANICS_GLOBALS['sc_params']['animations']),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", "organics"),
					"description" => wp_kses( __("Any additional CSS rules (if need)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('ID &amp; Class', 'organics'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", "organics"),
					"description" => wp_kses( __("Top margin (in pixels).", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Size &amp; Margins', 'organics'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", "organics"),
					"description" => wp_kses( __("Bottom margin (in pixels).", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Size &amp; Margins', 'organics'),
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", "organics"),
					"description" => wp_kses( __("Left margin (in pixels).", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Size &amp; Margins', 'organics'),
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", "organics"),
					"description" => wp_kses( __("Right margin (in pixels).", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Size &amp; Margins', 'organics'),
					"value" => "",
					"type" => "textfield"
				)
			);
	
	
	
			// Accordion
			//-------------------------------------------------------------------------------------
			vc_map( array(
				"base" => "trx_accordion",
				"name" => esc_html__("Accordion", "organics"),
				"description" => esc_html__("Accordion items", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_accordion',
				"class" => "trx_sc_collection trx_sc_accordion",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Accordion style", "organics"),
						"description" => esc_html__("Select style for display accordion", "organics"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(organics_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => esc_html__("Counter", "organics"),
						"description" => esc_html__("Display counter before each accordion title", "organics"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "initial",
						"heading" => esc_html__("Initially opened item", "organics"),
						"description" => esc_html__("Number of initially opened item", "organics"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "organics"),
						"description" => esc_html__("Select icon for the closed accordion item from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "organics"),
						"description" => esc_html__("Select icon for the opened accordion item from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'organics' ) . '"][/trx_accordion_item]
					[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'organics' ) . '"][/trx_accordion_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.esc_html__("Add item", "organics").'">'.esc_html__("Add item", "organics").'</button>
					</div>
				',
				'js_view' => 'VcTrxAccordionView'
			) );
			
			
			vc_map( array(
				"base" => "trx_accordion_item",
				"name" => esc_html__("Accordion item", "organics"),
				"description" => esc_html__("Inner accordion item", "organics"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_accordion_item',
				"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_accordion'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title for current accordion item", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "organics"),
						"description" => esc_html__("Select icon for the closed accordion item from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "organics"),
						"description" => esc_html__("Select icon for the opened accordion item from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxAccordionTabView'
			) );

			class WPBakeryShortCode_Trx_Accordion extends ORGANICS_VC_ShortCodeAccordion {}
			class WPBakeryShortCode_Trx_Accordion_Item extends ORGANICS_VC_ShortCodeAccordionItem {}
			
			
			
			
			
			
			// Anchor
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_anchor",
				"name" => esc_html__("Anchor", "organics"),
				"description" => esc_html__("Insert anchor for the TOC (table of content)", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_anchor',
				"class" => "trx_sc_single trx_sc_anchor",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Anchor's icon", "organics"),
						"description" => esc_html__("Select icon for the anchor from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Short title", "organics"),
						"description" => esc_html__("Short title of the anchor (for the table of content)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Long description", "organics"),
						"description" => esc_html__("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("External URL", "organics"),
						"description" => esc_html__("External URL for this TOC item", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "separator",
						"heading" => esc_html__("Add separator", "organics"),
						"description" => esc_html__("Add separator under item in the TOC", "organics"),
						"class" => "",
						"value" => array("Add separator" => "yes" ),
						"type" => "checkbox"
					),
					$ORGANICS_GLOBALS['vc_params']['id']
				),
			) );
			
			class WPBakeryShortCode_Trx_Anchor extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Audio
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_audio",
				"name" => esc_html__("Audio", "organics"),
				"description" => esc_html__("Insert audio player", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_audio',
				"class" => "trx_sc_single trx_sc_audio",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("URL for audio file", "organics"),
						"description" => esc_html__("Put here URL for audio file", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Cover image", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for audio cover", "organics"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title of the audio file", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", "organics"),
						"description" => esc_html__("Author of the audio file", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", "organics"),
						"description" => esc_html__("Show/hide controls", "organics"),
						"class" => "",
						"value" => array("Hide controls" => "hide" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoplay",
						"heading" => esc_html__("Autoplay", "organics"),
						"description" => esc_html__("Autoplay audio on page load", "organics"),
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Select block alignment", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Audio extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_block",
				"name" => esc_html__("Block container", "organics"),
				"description" => esc_html__("Container for any block ([section] analog - to enable nesting)", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_block',
				"class" => "trx_sc_collection trx_sc_block",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => esc_html__("Dedicated", "organics"),
						"description" => esc_html__("Use this block as dedicated content - show it before post title on single page", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Select block alignment", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns emulation", "organics"),
						"description" => esc_html__("Select width for columns emulation", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => esc_html__("Use pan effect", "organics"),
						"description" => esc_html__("Use pan effect to show section content", "organics"),
						"group" => esc_html__('Scroll', 'organics'),
						"class" => "",
						"value" => array(__('Content scroller', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "organics"),
						"description" => esc_html__("Use scroller to show section content", "organics"),
						"group" => esc_html__('Scroll', 'organics'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => esc_html__("Scroll direction", "organics"),
						"description" => esc_html__("Scroll direction (if Use scroller = yes)", "organics"),
						"admin_label" => true,
						"class" => "",
						"group" => esc_html__('Scroll', 'organics'),
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['dir']),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => esc_html__("Scroll controls", "organics"),
						"description" => esc_html__("Show scroll controls (if Use scroller = yes)", "organics"),
						"class" => "",
						"group" => esc_html__('Scroll', 'organics'),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"value" => array(__('Show scroll controls', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => esc_html__("Select color scheme for this block", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "organics"),
						"description" => esc_html__("Any color for objects in this section", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Any background color for this section", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "organics"),
						"description" => esc_html__("Select background image from library for this section", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_tile",
						"heading" => esc_html__("Tile background image", "organics"),
						"description" => esc_html__("Do you want tile background image or image cover whole block?", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						'dependency' => array(
							'element' => 'bg_image',
							'not_empty' => true
						),
						"std" => "no",
						"value" => array(__('Tile background image', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "organics"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "organics"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "organics"),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "organics"),
						"description" => esc_html__("Font weight of the text", "organics"),
						"class" => "",
						"value" => array(
							__('Default', 'organics') => 'inherit',
							__('Thin (100)', 'organics') => '100',
							__('Light (300)', 'organics') => '300',
							__('Normal (400)', 'organics') => '400',
							__('Bold (700)', 'organics') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "organics"),
						"description" => esc_html__("Content for section container", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Block extends ORGANICS_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Blogger
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_blogger",
				"name" => esc_html__("Blogger", "organics"),
				"description" => esc_html__("Insert posts (pages) in many styles from desired categories or directly from ids", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_blogger',
				"class" => "trx_sc_single trx_sc_blogger",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Output style", "organics"),
						"description" => esc_html__("Select desired style for posts output", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['blogger_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "filters",
						"heading" => esc_html__("Show filters", "organics"),
						"description" => esc_html__("Use post's tags or categories as filter buttons", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['filters']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover",
						"heading" => esc_html__("Hover effect", "organics"),
						"description" => esc_html__("Select hover effect (only if style=Portfolio)", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['hovers']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "hover_dir",
						"heading" => esc_html__("Hover direction", "organics"),
						"description" => esc_html__("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['hovers_dir']),
						'dependency' => array(
							'element' => 'style',
							'value' => array('portfolio_2','portfolio_3','portfolio_4','grid_2','grid_3','grid_4','square_2','square_3','square_4','short_2','short_3','short_4','colored_2','colored_3','colored_4')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "location",
						"heading" => esc_html__("Dedicated content location", "organics"),
						"description" => esc_html__("Select position for dedicated content (only for style=excerpt)", "organics"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('excerpt')
						),
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['locations']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Posts direction", "organics"),
						"description" => esc_html__("Display posts in horizontal or vertical direction", "organics"),
						"admin_label" => true,
						"class" => "",
						"std" => "horizontal",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns number", "organics"),
						"description" => esc_html__("How many columns used to display posts?", "organics"),
						'dependency' => array(
							'element' => 'dir',
							'value' => 'horizontal'
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "rating",
						"heading" => esc_html__("Show rating stars", "organics"),
						"description" => esc_html__("Show rating stars under post's header", "organics"),
						"group" => esc_html__('Details', 'organics'),
						"class" => "",
						"value" => array(__('Show rating', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "info",
						"heading" => esc_html__("Show post info block", "organics"),
						"description" => esc_html__("Show post info block (author, date, tags, etc.)", "organics"),
						"class" => "",
						"std" => 'yes',
						"value" => array(__('Show info', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "descr",
						"heading" => esc_html__("Description length", "organics"),
						"description" => esc_html__("How many characters are displayed from post excerpt? If 0 - don't show description", "organics"),
						"group" => esc_html__('Details', 'organics'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Allow links to the post", "organics"),
						"description" => esc_html__("Allow links to the post from each blogger item", "organics"),
						"group" => esc_html__('Details', 'organics'),
						"class" => "",
						"std" => 'yes',
						"value" => array(__('Allow links', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("More link text", "organics"),
						"description" => esc_html__("Read more link text. If empty - show 'More', else - used as link text", "organics"),
						"group" => esc_html__('Details', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title for the block", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "organics"),
						"description" => esc_html__("Subtitle for the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "organics"),
						"description" => esc_html__("Description for the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "organics"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "organics"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", "organics"),
						"description" => esc_html__("Select post type to show", "organics"),
						"group" => esc_html__('Query', 'organics'),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['posts_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", "organics"),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "organics"),
						"group" => esc_html__('Query', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories list", "organics"),
						"description" => esc_html__("Select category. If empty - show posts from any category or from IDs list", "organics"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => esc_html__('Query', 'organics'),
						"class" => "",
						"value" => array_flip(organics_array_merge(array(0 => __('- Select category -', 'organics')), $ORGANICS_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Total posts to show", "organics"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "organics"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"admin_label" => true,
						"group" => esc_html__('Query', 'organics'),
						"class" => "",
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "organics"),
						"description" => esc_html__("Skip posts before select next part.", "organics"),
						'dependency' => array(
							'element' => 'ids',
							'is_empty' => true
						),
						"group" => esc_html__('Query', 'organics'),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post order by", "organics"),
						"description" => esc_html__("Select desired posts sorting method", "organics"),
						"class" => "",
						"group" => esc_html__('Query', 'organics'),
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "organics"),
						"description" => esc_html__("Select desired posts order", "organics"),
						"class" => "",
						"group" => esc_html__('Query', 'organics'),
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "only",
						"heading" => esc_html__("Select posts only", "organics"),
						"description" => esc_html__("Select posts only with reviews, videos, audios, thumbs or galleries", "organics"),
						"class" => "",
						"group" => esc_html__('Query', 'organics'),
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['formats']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "organics"),
						"description" => esc_html__("Use scroller to show all posts", "organics"),
						"group" => esc_html__('Scroll', 'organics'),
						"class" => "",
						"value" => array(__('Use scroller', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show slider controls", "organics"),
						"description" => esc_html__("Show arrows to control scroll slider", "organics"),
						"group" => esc_html__('Scroll', 'organics'),
						"class" => "",
						"value" => array(__('Show controls', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Blogger extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Br
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_br",
				"name" => esc_html__("Line break", "organics"),
				"description" => esc_html__("Line break or Clear Floating", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_br',
				"class" => "trx_sc_single trx_sc_br",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "clear",
						"heading" => esc_html__("Clear floating", "organics"),
						"description" => esc_html__("Select clear side (if need)", "organics"),
						"class" => "",
						"value" => "",
						"value" => array(
							__('None', 'organics') => 'none',
							__('Left', 'organics') => 'left',
							__('Right', 'organics') => 'right',
							__('Both', 'organics') => 'both'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Br extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Button
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_button",
				"name" => esc_html__("Button", "organics"),
				"description" => esc_html__("Button with link", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_button',
				"class" => "trx_sc_single trx_sc_button",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Caption", "organics"),
						"description" => esc_html__("Button caption", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Button's shape", "organics"),
						"description" => esc_html__("Select button's shape", "organics"),
						"class" => "",
						"value" => array(
							__('Square', 'organics') => 'square',
							__('Round', 'organics') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Button's style", "organics"),
						"description" => esc_html__("Select button's style", "organics"),
						"class" => "",
						"value" => array(
							__('Filled', 'organics') => 'filled',
							__('Border', 'organics') => 'border'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => esc_html__("Button's size", "organics"),
						"description" => esc_html__("Select button's size", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Small', 'organics') => 'small',
							__('Medium', 'organics') => 'medium',
							__('Large', 'organics') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Button's icon", "organics"),
						"description" => esc_html__("Select icon for the title from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Button's text color", "organics"),
						"description" => esc_html__("Any color for button's caption", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Button's backcolor", "organics"),
						"description" => esc_html__("Any color for button's background", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Button's alignment", "organics"),
						"description" => esc_html__("Align button to left, center or right", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "organics"),
						"description" => esc_html__("URL for the link on button click", "organics"),
						"class" => "",
						"group" => esc_html__('Link', 'organics'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => esc_html__("Link target", "organics"),
						"description" => esc_html__("Target for the link on button click", "organics"),
						"class" => "",
						"group" => esc_html__('Link', 'organics'),
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "popup",
						"heading" => esc_html__("Open link in popup", "organics"),
						"description" => esc_html__("Open link target in popup window", "organics"),
						"class" => "",
						"group" => esc_html__('Link', 'organics'),
						"value" => array(__('Open in popup', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "rel",
						"heading" => esc_html__("Rel attribute", "organics"),
						"description" => esc_html__("Rel attribute for the button's link (if need", "organics"),
						"class" => "",
						"group" => esc_html__('Link', 'organics'),
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Button extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Call to Action block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_call_to_action",
				"name" => esc_html__("Call to Action", "organics"),
				"description" => esc_html__("Insert call to action block in your page (post)", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_call_to_action',
				"class" => "trx_sc_collection trx_sc_call_to_action",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Block's style", "organics"),
						"description" => esc_html__("Select style to display this block", "organics"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(organics_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Select block alignment", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "accent",
						"heading" => esc_html__("Accent", "organics"),
						"description" => esc_html__("Fill entire block with Accent1 color from current color scheme", "organics"),
						"class" => "",
						"value" => array("Fill with Accent1 color" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "organics"),
						"description" => esc_html__("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", "organics"),
						"class" => "",
						"value" => array("Custom content" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", "organics"),
						"description" => esc_html__("Image to display inside block", "organics"),
				        'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "video",
						"heading" => esc_html__("URL for video file", "organics"),
						"description" => esc_html__("Paste URL for video file to display inside block", "organics"),
				        'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title for the block", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "organics"),
						"description" => esc_html__("Subtitle for the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "organics"),
						"description" => esc_html__("Description for the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "organics"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "organics"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link2",
						"heading" => esc_html__("Button 2 URL", "organics"),
						"description" => esc_html__("Link URL for the second button at the bottom of the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link2_caption",
						"heading" => esc_html__("Button 2 caption", "organics"),
						"description" => esc_html__("Caption for the second button at the bottom of the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Call_To_Action extends ORGANICS_VC_ShortCodeCollection {}


			
			
			
			
			// Chat
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_chat",
				"name" => esc_html__("Chat", "organics"),
				"description" => esc_html__("Chat message", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_chat',
				"class" => "trx_sc_container trx_sc_chat",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Item title", "organics"),
						"description" => esc_html__("Title for current chat item", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Item photo", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for the item photo (avatar)", "organics"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "organics"),
						"description" => esc_html__("URL for the link on chat title click", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Chat item content", "organics"),
						"description" => esc_html__("Current chat item content", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			
			) );
			
			class WPBakeryShortCode_Trx_Chat extends ORGANICS_VC_ShortCodeContainer {}
			
			
			
			
			
			
			// Columns
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_columns",
				"name" => esc_html__("Columns", "organics"),
				"description" => esc_html__("Insert columns with margins", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_columns',
				"class" => "trx_sc_columns",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_column_item'),
				"params" => array(
					array(
						"param_name" => "count",
						"heading" => esc_html__("Columns count", "organics"),
						"description" => esc_html__("Number of the columns in the container.", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "2",
						"type" => "textfield"
					),
					array(
						"param_name" => "fluid",
						"heading" => esc_html__("Fluid columns", "organics"),
						"description" => esc_html__("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "organics"),
						"class" => "",
						"value" => array(__('Fluid columns', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "margins",
						"heading" => esc_html__("Margins between columns", "organics"),
						"description" => esc_html__("Add margins between columns", "organics"),
						"class" => "",
						"std" => "yes",
						"value" => array(__('Disable margins between columns', 'organics') => 'no'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "banner",
						"heading" => esc_html__("Banner Grid", "organics"),
						"description" => esc_html__("Use Standard Grid", "organics"),
						"class" => "",
						"std" => "yes",
						"value" => array(__('Use Banner Grid', 'organics') => 'no'),
						"type" => "checkbox"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_column_item][/trx_column_item]
					[trx_column_item][/trx_column_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_column_item",
				"name" => esc_html__("Column", "organics"),
				"description" => esc_html__("Column item", "organics"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_column_item',
				"as_child" => array('only' => 'trx_columns'),
				"as_parent" => array('except' => 'trx_columns'),
				"params" => array(
					array(
						"param_name" => "span",
						"heading" => esc_html__("Merge columns", "organics"),
						"description" => esc_html__("Count merged columns from current", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Alignment text in the column", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "organics"),
						"description" => esc_html__("Any color for objects in this column", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Any background color for this column", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("URL for background image file", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for the background", "organics"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_tile",
						"heading" => esc_html__("Tile background image", "organics"),
						"description" => esc_html__("Do you want tile background image or image cover whole column?", "organics"),
						"class" => "",
						'dependency' => array(
							'element' => 'bg_image',
							'not_empty' => true
						),
						"std" => "no",
						"value" => array(__('Tile background image', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Column's content", "organics"),
						"description" => esc_html__("Content of the current column", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
			class WPBakeryShortCode_Trx_Columns extends ORGANICS_VC_ShortCodeColumns {}
			class WPBakeryShortCode_Trx_Column_Item extends ORGANICS_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Contact form
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_form",
				"name" => esc_html__("Form", "organics"),
				"description" => esc_html__("Insert form with specefied style of with set of custom fields", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_form',
				"class" => "trx_sc_collection trx_sc_form",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('except' => 'trx_form'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "organics"),
						"description" => esc_html__("Select style of the form (if 'style' is not equal 'custom' - all tabs 'Field NN' are ignored!", "organics"),
						"admin_label" => true,
						"class" => "",
						"std" => "form_custom",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['forms']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => esc_html__("Select color scheme for this block", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "action",
						"heading" => esc_html__("Action", "organics"),
						"description" => esc_html__("Contact form action (URL to handle form data). If empty - use internal action", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Select form alignment", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title for the block", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "organics"),
						"description" => esc_html__("Subtitle for the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "organics"),
						"description" => esc_html__("Description for the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_form_item",
				"name" => esc_html__("Form item (custom field)", "organics"),
				"description" => esc_html__("Custom field for the contact form", "organics"),
				"class" => "trx_sc_item trx_sc_form_item",
				'icon' => 'icon_trx_form_item',
				//"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_form,trx_column_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", "organics"),
						"description" => esc_html__("Select type of the custom field", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['field_types']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", "organics"),
						"description" => esc_html__("Name of the custom field", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => esc_html__("Default value", "organics"),
						"description" => esc_html__("Default value of the custom field", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "options",
						"heading" => esc_html__("Options", "organics"),
						"description" => esc_html__("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "organics"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('radio','checkbox','select')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label",
						"heading" => esc_html__("Label", "organics"),
						"description" => esc_html__("Label for the custom field", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "label_position",
						"heading" => esc_html__("Label position", "organics"),
						"description" => esc_html__("Label position relative to the field", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['label_positions']),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Form extends ORGANICS_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Form_Item extends ORGANICS_VC_ShortCodeItem {}
			
			
			
			
			
			
			
			// Content block on fullscreen page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_content",
				"name" => esc_html__("Content block", "organics"),
				"description" => esc_html__("Container for main content block (use it only on fullscreen pages)", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_content',
				"class" => "trx_sc_collection trx_sc_content",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => esc_html__("Select color scheme for this block", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "organics"),
						"description" => esc_html__("Content for section container", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom']
				)
			) );
			
			class WPBakeryShortCode_Trx_Content extends ORGANICS_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Countdown
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_countdown",
				"name" => esc_html__("Countdown", "organics"),
				"description" => esc_html__("Insert countdown object", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_countdown',
				"class" => "trx_sc_single trx_sc_countdown",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "date",
						"heading" => esc_html__("Date", "organics"),
						"description" => esc_html__("Upcoming date (format: yyyy-mm-dd)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "time",
						"heading" => esc_html__("Time", "organics"),
						"description" => esc_html__("Upcoming time (format: HH:mm:ss)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "organics"),
						"description" => esc_html__("Countdown style", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Align counter to left, center or right", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Countdown extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Dropcaps
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_dropcaps",
				"name" => esc_html__("Dropcaps", "organics"),
				"description" => esc_html__("Make first letter of the text as dropcaps", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_dropcaps',
				"class" => "trx_sc_single trx_sc_dropcaps",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "organics"),
						"description" => esc_html__("Dropcaps style", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_list_styles(1, 4)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Paragraph text", "organics"),
						"description" => esc_html__("Paragraph with dropcaps content", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_Dropcaps extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Emailer
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_emailer",
				"name" => esc_html__("E-mail collector", "organics"),
				"description" => esc_html__("Collect e-mails into specified group", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_emailer',
				"class" => "trx_sc_single trx_sc_emailer",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "group",
						"heading" => esc_html__("Group", "organics"),
						"description" => esc_html__("The name of group to collect e-mail address", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => esc_html__("Opened", "organics"),
						"description" => esc_html__("Initially open the input field on show object", "organics"),
						"class" => "",
						"value" => array(__('Initially opened', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Align field to left, center or right", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Emailer extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Gap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_gap",
				"name" => esc_html__("Gap", "organics"),
				"description" => esc_html__("Insert gap (fullwidth area) in the post content", "organics"),
				"category" => esc_html__('Structure', 'js_composer'),
				'icon' => 'icon_trx_gap',
				"class" => "trx_sc_collection trx_sc_gap",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Gap content", "organics"),
						"description" => esc_html__("Gap inner content", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					)
					*/
				)
			) );
			
			class WPBakeryShortCode_Trx_Gap extends ORGANICS_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Googlemap
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_googlemap",
				"name" => esc_html__("Google map", "organics"),
				"description" => esc_html__("Insert Google map with desired address or coordinates", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_googlemap',
				"class" => "trx_sc_collection trx_sc_googlemap",
				"content_element" => true,
				"is_container" => true,
				"as_parent" => array('only' => 'trx_googlemap_marker'),
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "zoom",
						"heading" => esc_html__("Zoom", "organics"),
						"description" => esc_html__("Map zoom factor", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "16",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "organics"),
						"description" => esc_html__("Map custom style", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['googlemap_styles']),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width('100%'),
					organics_vc_height(240),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			vc_map( array(
				"base" => "trx_googlemap_marker",
				"name" => esc_html__("Googlemap marker", "organics"),
				"description" => esc_html__("Insert new marker into Google map", "organics"),
				"class" => "trx_sc_collection trx_sc_googlemap_marker",
				'icon' => 'icon_trx_googlemap_marker',
				//"allowed_container_element" => 'vc_row',
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"params" => array(
					array(
						"param_name" => "address",
						"heading" => esc_html__("Address", "organics"),
						"description" => esc_html__("Address of this marker", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "latlng",
						"heading" => esc_html__("Latitude and Longtitude", "organics"),
						"description" => esc_html__("Comma separated marker's coorditanes (instead Address)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title for this marker", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "point",
						"heading" => esc_html__("URL for marker image file", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for this marker. If empty - use default marker", "organics"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$ORGANICS_GLOBALS['vc_params']['id']
				)
			) );
			
			class WPBakeryShortCode_Trx_Googlemap extends ORGANICS_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Googlemap_Marker extends ORGANICS_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			
			
			// Highlight
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_highlight",
				"name" => esc_html__("Highlight text", "organics"),
				"description" => esc_html__("Highlight text with selected color, background color and other styles", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_highlight',
				"class" => "trx_sc_single trx_sc_highlight",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Type", "organics"),
						"description" => esc_html__("Highlight type", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Custom', 'organics') => 0,
								__('Type 1', 'organics') => 1,
								__('Type 2', 'organics') => 2,
								__('Type 3', 'organics') => 3
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "organics"),
						"description" => esc_html__("Color for the highlighted text", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Background color for the highlighted text", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "organics"),
						"description" => esc_html__("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Highlight text", "organics"),
						"description" => esc_html__("Content for highlight", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Highlight extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			// Icon
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_icon",
				"name" => esc_html__("Icon", "organics"),
				"description" => esc_html__("Insert the icon", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_icon',
				"class" => "trx_sc_single trx_sc_icon",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", "organics"),
						"description" => esc_html__("Select icon class from Fontello icons set", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "organics"),
						"description" => esc_html__("Icon's color", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Background color for the icon", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_shape",
						"heading" => esc_html__("Background shape", "organics"),
						"description" => esc_html__("Shape of the icon background", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('None', 'organics') => 'none',
							__('Round', 'organics') => 'round',
							__('Square', 'organics') => 'square'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "organics"),
						"description" => esc_html__("Icon's font size", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "organics"),
						"description" => esc_html__("Icon's font weight", "organics"),
						"class" => "",
						"value" => array(
							__('Default', 'organics') => 'inherit',
							__('Thin (100)', 'organics') => '100',
							__('Light (300)', 'organics') => '300',
							__('Normal (400)', 'organics') => '400',
							__('Bold (700)', 'organics') => '700'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Icon's alignment", "organics"),
						"description" => esc_html__("Align icon to left, center or right", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "organics"),
						"description" => esc_html__("Link URL from this icon (if not empty)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Icon extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Image
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_image",
				"name" => esc_html__("Image", "organics"),
				"description" => esc_html__("Insert image", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_image',
				"class" => "trx_sc_single trx_sc_image",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("Select image", "organics"),
						"description" => esc_html__("Select image from library", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Image alignment", "organics"),
						"description" => esc_html__("Align image to left or right side", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => esc_html__("Image shape", "organics"),
						"description" => esc_html__("Shape of the image: square or round", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Square', 'organics') => 'square',
							__('Round', 'organics') => 'round'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Image's title", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Title's icon", "organics"),
						"description" => esc_html__("Select icon for the title from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "organics"),
						"description" => esc_html__("The link URL from the image", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Image extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Infobox
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_infobox",
				"name" => esc_html__("Infobox", "organics"),
				"description" => esc_html__("Box with info or error message", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_infobox',
				"class" => "trx_sc_container trx_sc_infobox",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "organics"),
						"description" => esc_html__("Infobox style", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Regular', 'organics') => 'regular',
								__('Info', 'organics') => 'info',
								__('Success', 'organics') => 'success',
								__('Error', 'organics') => 'error',
								__('Result', 'organics') => 'result'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "closeable",
						"heading" => esc_html__("Closeable", "organics"),
						"description" => esc_html__("Create closeable box (with close button)", "organics"),
						"class" => "",
						"value" => array(__('Close button', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Custom icon", "organics"),
						"description" => esc_html__("Select icon for the infobox from Fontello icons set. If empty - use default icon", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "organics"),
						"description" => esc_html__("Any color for the text and headers", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Any background color for this infobox", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Message text", "organics"),
						"description" => esc_html__("Message for the infobox", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Infobox extends ORGANICS_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Line
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_line",
				"name" => esc_html__("Line", "organics"),
				"description" => esc_html__("Insert line (delimiter)", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				"class" => "trx_sc_single trx_sc_line",
				'icon' => 'icon_trx_line',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "organics"),
						"description" => esc_html__("Line style", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Solid', 'organics') => 'solid',
								__('Dashed', 'organics') => 'dashed',
								__('Dotted', 'organics') => 'dotted',
								__('Double', 'organics') => 'double',
								__('Shadow', 'organics') => 'shadow'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Line color", "organics"),
						"description" => esc_html__("Line color", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Line extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// List
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_list",
				"name" => esc_html__("List", "organics"),
				"description" => esc_html__("List items with specific bullets", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				"class" => "trx_sc_collection trx_sc_list",
				'icon' => 'icon_trx_list',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_list_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Bullet's style", "organics"),
						"description" => esc_html__("Bullet's style for each list item", "organics"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['list_styles']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "organics"),
						"description" => esc_html__("List items color", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("List icon", "organics"),
						"description" => esc_html__("Select list icon from Fontello icons set (only for style=Iconed)", "organics"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => esc_html__("Icon color", "organics"),
						"description" => esc_html__("List icons color", "organics"),
						"class" => "",
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => "",
						"type" => "colorpicker"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_list_item]' . __( 'Item 1', 'organics' ) . '[/trx_list_item]
					[trx_list_item]' . __( 'Item 2', 'organics' ) . '[/trx_list_item]
				'
			) );
			
			
			vc_map( array(
				"base" => "trx_list_item",
				"name" => esc_html__("List item", "organics"),
				"description" => esc_html__("List item with specific bullet", "organics"),
				"class" => "trx_sc_single trx_sc_list_item",
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_list_item',
				"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
				"as_parent" => array('except' => 'trx_list'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("List item title", "organics"),
						"description" => esc_html__("Title for the current list item (show it as tooltip)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "organics"),
						"description" => esc_html__("Link URL for the current list item", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Link', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "target",
						"heading" => esc_html__("Link target", "organics"),
						"description" => esc_html__("Link target for the current list item", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Link', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "organics"),
						"description" => esc_html__("Text color for this item", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("List item icon", "organics"),
						"description" => esc_html__("Select list item icon from Fontello icons set (only for style=Iconed)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_color",
						"heading" => esc_html__("Icon color", "organics"),
						"description" => esc_html__("Icon color for this item", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("List item text", "organics"),
						"description" => esc_html__("Current list item content", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTextView'
			
			) );
			
			class WPBakeryShortCode_Trx_List extends ORGANICS_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_List_Item extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			
			
			// Number
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_number",
				"name" => esc_html__("Number", "organics"),
				"description" => esc_html__("Insert number or any word as set of separated characters", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				"class" => "trx_sc_single trx_sc_number",
				'icon' => 'icon_trx_number',
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "value",
						"heading" => esc_html__("Value", "organics"),
						"description" => esc_html__("Number or any word to separate", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Select block alignment", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Number extends ORGANICS_VC_ShortCodeSingle {}


			
			
			
			
			
			// Parallax
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_parallax",
				"name" => esc_html__("Parallax", "organics"),
				"description" => esc_html__("Create the parallax container (with asinc background image)", "organics"),
				"category" => esc_html__('Structure', 'js_composer'),
				'icon' => 'icon_trx_parallax',
				"class" => "trx_sc_collection trx_sc_parallax",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "gap",
						"heading" => esc_html__("Create gap", "organics"),
						"description" => esc_html__("Create gap around parallax container (not need in fullscreen pages)", "organics"),
						"class" => "",
						"value" => array(__('Create gap', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Direction", "organics"),
						"description" => esc_html__("Scroll direction for the parallax background", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
								__('Up', 'organics') => 'up',
								__('Down', 'organics') => 'down'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "speed",
						"heading" => esc_html__("Speed", "organics"),
						"description" => esc_html__("Parallax background motion speed (from 0.0 to 1.0)", "organics"),
						"class" => "",
						"value" => "0.3",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => esc_html__("Select color scheme for this block", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Text color", "organics"),
						"description" => esc_html__("Select color for text object inside parallax block", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Backgroud color", "organics"),
						"description" => esc_html__("Select color for parallax background", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for the parallax background", "organics"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image_x",
						"heading" => esc_html__("Image X position", "organics"),
						"description" => esc_html__("Parallax background X position (in percents)", "organics"),
						"class" => "",
						"value" => "50%",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video",
						"heading" => esc_html__("Video background", "organics"),
						"description" => esc_html__("Paste URL for video file to show it as parallax background", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_video_ratio",
						"heading" => esc_html__("Video ratio", "organics"),
						"description" => esc_html__("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "organics"),
						"class" => "",
						"value" => "16:9",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "organics"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "organics"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Content", "organics"),
						"description" => esc_html__("Content for the parallax container", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Parallax extends ORGANICS_VC_ShortCodeCollection {}
			
			
			
			
			
			
			// Popup
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_popup",
				"name" => esc_html__("Popup window", "organics"),
				"description" => esc_html__("Container for any html-block with desired class and style for popup window", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_popup',
				"class" => "trx_sc_collection trx_sc_popup",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "organics"),
						"description" => esc_html__("Content for popup container", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Popup extends ORGANICS_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Price
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price",
				"name" => esc_html__("Price", "organics"),
				"description" => esc_html__("Insert price with decoration", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_price',
				"class" => "trx_sc_single trx_sc_price",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "money",
						"heading" => esc_html__("Money", "organics"),
						"description" => esc_html__("Money value (dot or comma separated)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency symbol", "organics"),
						"description" => esc_html__("Currency character", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => esc_html__("Period", "organics"),
						"description" => esc_html__("Period text (if need). For example: monthly, daily, etc.", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Align price to left or right side", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Price extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Price block
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_price_block",
				"name" => esc_html__("Price block", "organics"),
				"description" => esc_html__("Insert price block with title, price and description", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_price_block',
				"class" => "trx_sc_single trx_sc_price_block",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Block title", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link URL", "organics"),
						"description" => esc_html__("URL for link from button (at bottom of the block)", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_text",
						"heading" => esc_html__("Link text", "organics"),
						"description" => esc_html__("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", "organics"),
						"description" => esc_html__("Select icon from Fontello icons set (placed before/instead price)", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "money",
						"heading" => esc_html__("Money", "organics"),
						"description" => esc_html__("Money value (dot or comma separated)", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Money', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency symbol", "organics"),
						"description" => esc_html__("Currency character", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Money', 'organics'),
						"class" => "",
						"value" => "$",
						"type" => "textfield"
					),
					array(
						"param_name" => "period",
						"heading" => esc_html__("Period", "organics"),
						"description" => esc_html__("Period text (if need). For example: monthly, daily, etc.", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Money', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => esc_html__("Select color scheme for this block", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Align price to left or right side", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Description", "organics"),
						"description" => esc_html__("Description for this price block", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_PriceBlock extends ORGANICS_VC_ShortCodeSingle {}

			
			
			
			
			// Quote
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_quote",
				"name" => esc_html__("Quote", "organics"),
				"description" => esc_html__("Quote text", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_quote',
				"class" => "trx_sc_single trx_sc_quote",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "cite",
						"heading" => esc_html__("Quote cite", "organics"),
						"description" => esc_html__("URL for the quote cite link", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title (author)", "organics"),
						"description" => esc_html__("Quote title (author name)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Quote content", "organics"),
						"description" => esc_html__("Quote content", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Quote extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Reviews
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_reviews",
				"name" => esc_html__("Reviews", "organics"),
				"description" => esc_html__("Insert reviews block in the single post", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_reviews',
				"class" => "trx_sc_single trx_sc_reviews",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Align counter to left, center or right", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Reviews extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Search
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_search",
				"name" => esc_html__("Search form", "organics"),
				"description" => esc_html__("Insert search form", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_search',
				"class" => "trx_sc_single trx_sc_search",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Style", "organics"),
						"description" => esc_html__("Select style to display search field", "organics"),
						"class" => "",
						"value" => array(
							__('Regular', 'organics') => "regular",
							__('Flat', 'organics') => "flat"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "state",
						"heading" => esc_html__("State", "organics"),
						"description" => esc_html__("Select search field initial state", "organics"),
						"class" => "",
						"value" => array(
							__('Fixed', 'organics')  => "fixed",
							__('Opened', 'organics') => "opened",
							__('Closed', 'organics') => "closed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title (placeholder) for the search field", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => __("Search &hellip;", 'organics'),
						"type" => "textfield"
					),
					array(
						"param_name" => "ajax",
						"heading" => esc_html__("AJAX", "organics"),
						"description" => esc_html__("Search via AJAX or reload page", "organics"),
						"class" => "",
						"value" => array(__('Use AJAX search', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Search extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Section
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_section",
				"name" => esc_html__("Section container", "organics"),
				"description" => esc_html__("Container for any block ([block] analog - to enable nesting)", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				"class" => "trx_sc_collection trx_sc_section",
				'icon' => 'icon_trx_block',
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "dedicated",
						"heading" => esc_html__("Dedicated", "organics"),
						"description" => esc_html__("Use this block as dedicated content - show it before post title on single page", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Use as dedicated content', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Select block alignment", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns emulation", "organics"),
						"description" => esc_html__("Select width for columns emulation", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['columns']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "pan",
						"heading" => esc_html__("Use pan effect", "organics"),
						"description" => esc_html__("Use pan effect to show section content", "organics"),
						"group" => esc_html__('Scroll', 'organics'),
						"class" => "",
						"value" => array(__('Content scroller', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Use scroller", "organics"),
						"description" => esc_html__("Use scroller to show section content", "organics"),
						"group" => esc_html__('Scroll', 'organics'),
						"admin_label" => true,
						"class" => "",
						"value" => array(__('Content scroller', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scroll_dir",
						"heading" => esc_html__("Scroll and Pan direction", "organics"),
						"description" => esc_html__("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "organics"),
						"admin_label" => true,
						"class" => "",
						"group" => esc_html__('Scroll', 'organics'),
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scroll_controls",
						"heading" => esc_html__("Scroll controls", "organics"),
						"description" => esc_html__("Show scroll controls (if Use scroller = yes)", "organics"),
						"class" => "",
						"group" => esc_html__('Scroll', 'organics'),
						'dependency' => array(
							'element' => 'scroll',
							'not_empty' => true
						),
						"value" => array(__('Show scroll controls', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => esc_html__("Select color scheme for this block", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Fore color", "organics"),
						"description" => esc_html__("Any color for objects in this section", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Any background color for this section", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "organics"),
						"description" => esc_html__("Select background image from library for this section", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_tile",
						"heading" => esc_html__("Tile background image", "organics"),
						"description" => esc_html__("Do you want tile background image or image cover whole block?", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						'dependency' => array(
							'element' => 'bg_image',
							'not_empty' => true
						),
						"std" => "no",
						"value" => array(__('Tile background image', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "organics"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "organics"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_padding",
						"heading" => esc_html__("Paddings around content", "organics"),
						"description" => esc_html__("Add paddings around content in this section (only if bg_color or bg_image enabled).", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						'dependency' => array(
							'element' => array('bg_color','bg_texture','bg_image'),
							'not_empty' => true
						),
						"std" => "yes",
						"value" => array(__('Disable padding around content in this block', 'organics') => 'no'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "organics"),
						"description" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "organics"),
						"description" => esc_html__("Font weight of the text", "organics"),
						"class" => "",
						"value" => array(
							__('Default', 'organics') => 'inherit',
							__('Thin (100)', 'organics') => '100',
							__('Light (300)', 'organics') => '300',
							__('Normal (400)', 'organics') => '400',
							__('Bold (700)', 'organics') => '700'
						),
						"type" => "dropdown"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Container content", "organics"),
						"description" => esc_html__("Content for section container", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Section extends ORGANICS_VC_ShortCodeCollection {}
			
			
			
			
			
			
			
			// Skills
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_skills",
				"name" => esc_html__("Skills", "organics"),
				"description" => esc_html__("Insert skills diagramm", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_skills',
				"class" => "trx_sc_collection trx_sc_skills",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_skills_item'),
				"params" => array(
					array(
						"param_name" => "max_value",
						"heading" => esc_html__("Max value", "organics"),
						"description" => esc_html__("Max value for skills items", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "100",
						"type" => "textfield"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Skills type", "organics"),
						"description" => esc_html__("Select type of skills block", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							__('Bar', 'organics') => 'bar',
							__('Pie chart', 'organics') => 'pie',
							__('Counter', 'organics') => 'counter',
							__('Arc', 'organics') => 'arc'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "layout",
						"heading" => esc_html__("Skills layout", "organics"),
						"description" => esc_html__("Select layout of skills block", "organics"),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter','bar','pie')
						),
						"class" => "",
						"value" => array(
							__('Rows', 'organics') => 'rows',
							__('Columns', 'organics') => 'columns'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "dir",
						"heading" => esc_html__("Direction", "organics"),
						"description" => esc_html__("Select direction of skills block", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['dir']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Counters style", "organics"),
						"description" => esc_html__("Select style of skills items (only for type=counter)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_list_styles(1, 4)),
						'dependency' => array(
							'element' => 'type',
							'value' => array('counter')
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns count", "organics"),
						"description" => esc_html__("Skills columns count (required)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "organics"),
						"description" => esc_html__("Color for all skills items", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Background color for all skills items (only for type=pie)", "organics"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => esc_html__("Border color", "organics"),
						"description" => esc_html__("Border color for all skills items (only for type=pie)", "organics"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Align skills block to left or right side", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "arc_caption",
						"heading" => esc_html__("Arc caption", "organics"),
						"description" => esc_html__("Arc caption - text in the center of the diagram", "organics"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('arc')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "pie_compact",
						"heading" => esc_html__("Pie compact", "organics"),
						"description" => esc_html__("Show all skills in one diagram or as separate diagrams", "organics"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => array(__('Show all skills in one diagram', 'organics') => 'on'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pie_cutout",
						"heading" => esc_html__("Pie cutout", "organics"),
						"description" => esc_html__("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "organics"),
						'dependency' => array(
							'element' => 'type',
							'value' => array('pie')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title for the block", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "organics"),
						"description" => esc_html__("Subtitle for the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "organics"),
						"description" => esc_html__("Description for the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "organics"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "organics"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "organics"),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			
			vc_map( array(
				"base" => "trx_skills_item",
				"name" => esc_html__("Skill", "organics"),
				"description" => esc_html__("Skills item", "organics"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_single trx_sc_skills_item",
				"content_element" => true,
				"is_container" => false,
				"as_child" => array('only' => 'trx_skills'),
				"as_parent" => array('except' => 'trx_skills'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title for the current skills item", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "value",
						"heading" => esc_html__("Value", "organics"),
						"description" => esc_html__("Value for the current skills item", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Color", "organics"),
						"description" => esc_html__("Color for current skills item", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Background color for current skills item (only for type=pie)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "border_color",
						"heading" => esc_html__("Border color", "organics"),
						"description" => esc_html__("Border color for current skills item (only for type=pie)", "organics"),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Counter style", "organics"),
						"description" => esc_html__("Select style for the current skills item (only for type=counter)", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_list_styles(1, 4)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Counter icon", "organics"),
						"description" => esc_html__("Select icon from Fontello icons set, placed before counter (only for type=counter)", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Skills extends ORGANICS_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Skills_Item extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Slider
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_slider",
				"name" => esc_html__("Slider", "organics"),
				"description" => esc_html__("Insert slider", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_slider',
				"class" => "trx_sc_collection trx_sc_slider",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_slider_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "engine",
						"heading" => esc_html__("Engine", "organics"),
						"description" => esc_html__("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sliders']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Float slider", "organics"),
						"description" => esc_html__("Float slider to left or right side", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom slides", "organics"),
						"description" => esc_html__("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "organics"),
						"class" => "",
						"value" => array(__('Custom slides', 'organics') => 'yes'),
						"type" => "checkbox"
					)
					),
					organics_exists_revslider() ? array(
					array(
						"param_name" => "alias",
						"heading" => esc_html__("Revolution slider alias", "organics"),
						"description" => esc_html__("Select Revolution slider to display", "organics"),
						"admin_label" => true,
						"class" => "",
						'dependency' => array(
							'element' => 'engine',
							'value' => array('revo')
						),
						"value" => array_flip(organics_array_merge(array('none' => __('- Select slider -', 'organics')), $ORGANICS_GLOBALS['sc_params']['revo_sliders'])),
						"type" => "dropdown"
					)) : array(), array(
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories list", "organics"),
						"description" => esc_html__("Select category. If empty - show posts from any category or from IDs list", "organics"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip(organics_array_merge(array(0 => __('- Select category -', 'organics')), $ORGANICS_GLOBALS['sc_params']['categories'])),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Swiper: Number of posts", "organics"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "organics"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Swiper: Offset before select posts", "organics"),
						"description" => esc_html__("Skip posts before select next part.", "organics"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Swiper: Post sorting", "organics"),
						"description" => esc_html__("Select desired posts sorting method", "organics"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Swiper: Post order", "organics"),
						"description" => esc_html__("Select desired posts order", "organics"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Swiper: Post IDs list", "organics"),
						"description" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "organics"),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Swiper: Show slider controls", "organics"),
						"description" => esc_html__("Show arrows inside slider", "organics"),
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Show controls', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "pagination",
						"heading" => esc_html__("Swiper: Show slider pagination", "organics"),
						"description" => esc_html__("Show bullets or titles to switch slides", "organics"),
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"std" => "no",
						"value" => array(
								__('None', 'organics') => 'no',
								__('Dots', 'organics') => 'yes', 
								__('Side Titles', 'organics') => 'full',
								__('Over Titles', 'organics') => 'over'
							),
						"type" => "dropdown"
					),
					array(
						"param_name" => "titles",
						"heading" => esc_html__("Swiper: Show titles section", "organics"),
						"description" => esc_html__("Show section with post's title and short post's description", "organics"),
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(
								__('Not show', 'organics') => "no",
								__('Show/Hide info', 'organics') => "slide",
								__('Fixed info', 'organics') => "fixed"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descriptions",
						"heading" => esc_html__("Swiper: Post descriptions", "organics"),
						"description" => esc_html__("Show post's excerpt max length (characters)", "organics"),
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "links",
						"heading" => esc_html__("Swiper: Post's title as link", "organics"),
						"description" => esc_html__("Make links from post's titles", "organics"),
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Titles as a links', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "crop",
						"heading" => esc_html__("Swiper: Crop images", "organics"),
						"description" => esc_html__("Crop images in each slide or live it unchanged", "organics"),
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Crop images', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Swiper: Autoheight", "organics"),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "organics"),
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => array(__('Autoheight', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					array(
						"param_name" => "slides_per_view",
						"heading" => esc_html__("Swiper: Slides per view", "organics"),
						"description" => esc_html__("Slides per view showed in this slider", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Swiper: Space between slides", "organics"),
						"description" => esc_html__("Size of space (in px) between slides", "organics"),
						"admin_label" => true,
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Swiper: Slides change interval", "organics"),
						"description" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "organics"),
						"group" => esc_html__('Details', 'organics'),
						'dependency' => array(
							'element' => 'engine',
							'value' => array('swiper')
						),
						"class" => "",
						"value" => "5000",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_slider_item",
				"name" => esc_html__("Slide", "organics"),
				"description" => esc_html__("Slider item - single slide", "organics"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_slider_item',
				"as_child" => array('only' => 'trx_slider'),
				"as_parent" => array('except' => 'trx_slider'),
				"params" => array(
					array(
						"param_name" => "src",
						"heading" => esc_html__("URL (source) for image file", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for the current slide", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css']
				)
			) );
			
			class WPBakeryShortCode_Trx_Slider extends ORGANICS_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Slider_Item extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Socials
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_socials",
				"name" => esc_html__("Social icons", "organics"),
				"description" => esc_html__("Custom social icons", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_socials',
				"class" => "trx_sc_collection trx_sc_socials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_social_item'),
				"params" => array_merge(array(
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", "organics"),
						"description" => esc_html__("Type of the icons - images or font icons", "organics"),
						"class" => "",
						"std" => organics_get_theme_setting('socials_type'),
						"value" => array(
							__('Icons', 'organics') => 'icons',
							__('Images', 'organics') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "size",
						"heading" => esc_html__("Icon's size", "organics"),
						"description" => esc_html__("Size of the icons", "organics"),
						"class" => "",
						"std" => "small",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sizes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "shape",
						"heading" => esc_html__("Icon's shape", "organics"),
						"description" => esc_html__("Shape of the icons", "organics"),
						"class" => "",
						"std" => "square",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['shapes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Manual socials list", "organics"),
						"description" => esc_html__("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebooc.com/my_profile. If empty - use socials from Theme options.", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom socials", "organics"),
						"description" => esc_html__("Make custom icons from inner shortcodes (prepare it on tabs)", "organics"),
						"class" => "",
						"value" => array(__('Custom socials', 'organics') => 'yes'),
						"type" => "checkbox"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				))
			) );
			
			
			vc_map( array(
				"base" => "trx_social_item",
				"name" => esc_html__("Custom social item", "organics"),
				"description" => esc_html__("Custom social item: name, profile url and icon url", "organics"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => false,
				'icon' => 'icon_trx_social_item',
				"as_child" => array('only' => 'trx_socials'),
				"as_parent" => array('except' => 'trx_socials'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => esc_html__("Social name", "organics"),
						"description" => esc_html__("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("Your profile URL", "organics"),
						"description" => esc_html__("URL of your profile in specified social network", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("URL (source) for icon file", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for the current social icon", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					)
				)
			) );
			
			class WPBakeryShortCode_Trx_Socials extends ORGANICS_VC_ShortCodeCollection {}
			class WPBakeryShortCode_Trx_Social_Item extends ORGANICS_VC_ShortCodeSingle {}
			

			
			
			
			
			
			// Table
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_table",
				"name" => esc_html__("Table", "organics"),
				"description" => esc_html__("Insert a table", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_table',
				"class" => "trx_sc_container trx_sc_table",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "align",
						"heading" => esc_html__("Cells content alignment", "organics"),
						"description" => esc_html__("Select alignment for each table cell", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "content",
						"heading" => esc_html__("Table content", "organics"),
						"description" => esc_html__("Content, created with any table-generator", "organics"),
						"class" => "",
						"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
						"type" => "textarea_html"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextContainerView'
			) );
			
			class WPBakeryShortCode_Trx_Table extends ORGANICS_VC_ShortCodeContainer {}
			
			
			
			
			
			
			
			// Tabs
			//-------------------------------------------------------------------------------------
			
			$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
			$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
			vc_map( array(
				"base" => "trx_tabs",
				"name" => esc_html__("Tabs", "organics"),
				"description" => esc_html__("Tabs", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_tabs',
				"class" => "trx_sc_collection trx_sc_tabs",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_tab'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Tabs style", "organics"),
						"description" => esc_html__("Select style of tabs items", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(organics_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "initial",
						"heading" => esc_html__("Initially opened tab", "organics"),
						"description" => esc_html__("Number of initially opened tab", "organics"),
						"class" => "",
						"value" => 1,
						"type" => "textfield"
					),
					array(
						"param_name" => "scroll",
						"heading" => esc_html__("Scroller", "organics"),
						"description" => esc_html__("Use scroller to show tab content (height parameter required)", "organics"),
						"class" => "",
						"value" => array("Use scroller" => "yes" ),
						"type" => "checkbox"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_tab title="' . __( 'Tab 1', 'organics' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
					[trx_tab title="' . __( 'Tab 2', 'organics' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
				',
				"custom_markup" => '
					<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
						<ul class="tabs_controls">
						</ul>
						%content%
					</div>
				',
				'js_view' => 'VcTrxTabsView'
			) );
			
			
			vc_map( array(
				"base" => "trx_tab",
				"name" => esc_html__("Tab item", "organics"),
				"description" => esc_html__("Single tab item", "organics"),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_tab",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_tab',
				"as_child" => array('only' => 'trx_tabs'),
				"as_parent" => array('except' => 'trx_tabs'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Tab title", "organics"),
						"description" => esc_html__("Title for current tab", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "tab_id",
						"heading" => esc_html__("Tab ID", "organics"),
						"description" => esc_html__("ID for current tab (required). Please, start it from letter.", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
			  'js_view' => 'VcTrxTabView'
			) );
			class WPBakeryShortCode_Trx_Tabs extends ORGANICS_VC_ShortCodeTabs {}
			class WPBakeryShortCode_Trx_Tab extends ORGANICS_VC_ShortCodeTab {}
			
			
			
			
			
			
			
			// Title
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_title",
				"name" => esc_html__("Title", "organics"),
				"description" => wp_kses( __("Create header tag (1-6 level) with many styles", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_title',
				"class" => "trx_sc_single trx_sc_title",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "content",
						"heading" => esc_html__("Title content", "organics"),
						"description" => wp_kses( __("Title content", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Title type", "organics"),
						"description" => wp_kses( __("Title type (header level)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Header 1', 'organics') => '1',
							esc_html__('Header 2', 'organics') => '2',
							esc_html__('Header 3', 'organics') => '3',
							esc_html__('Header 4', 'organics') => '4',
							esc_html__('Header 5', 'organics') => '5',
							esc_html__('Header 6', 'organics') => '6'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("Title style", "organics"),
						"description" => wp_kses( __("Title style: only text (regular) or with icon/image (iconed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Regular', 'organics') => 'regular',
							esc_html__('Underline', 'organics') => 'underline',
							esc_html__('Divider', 'organics') => 'divider',
							esc_html__('With icon (image)', 'organics') => 'iconed'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => wp_kses( __("Title text alignment", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "font_size",
						"heading" => esc_html__("Font size", "organics"),
						"description" => wp_kses( __("Custom font size. If empty - use theme default", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "font_weight",
						"heading" => esc_html__("Font weight", "organics"),
						"description" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array(
							esc_html__('Default', 'organics') => 'inherit',
							esc_html__('Thin (100)', 'organics') => '100',
							esc_html__('Light (300)', 'organics') => '300',
							esc_html__('Normal (400)', 'organics') => '400',
							esc_html__('Semibold (600)', 'organics') => '600',
							esc_html__('Bold (700)', 'organics') => '700',
							esc_html__('Black (900)', 'organics') => '900'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "color",
						"heading" => esc_html__("Title color", "organics"),
						"description" => wp_kses( __("Select color for the title", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Title font icon", "organics"),
						"description" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'organics'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("or image icon", "organics"),
						"description" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"group" => esc_html__('Icon &amp; Image', 'organics'),
						'dependency' => array(
							'element' => 'style',
							'value' => array('iconed')
						),
						"value" => $ORGANICS_GLOBALS['sc_params']['images'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "picture",
						"heading" => esc_html__("or select uploaded image", "organics"),
						"description" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Icon &amp; Image', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_size",
						"heading" => esc_html__("Image (picture) size", "organics"),
						"description" => wp_kses( __("Select image (picture) size (if style=iconed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Icon &amp; Image', 'organics'),
						"class" => "",
						"value" => array(
							esc_html__('Small', 'organics') => 'small',
							esc_html__('Medium', 'organics') => 'medium',
							esc_html__('Large', 'organics') => 'large'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Icon (image) position", "organics"),
						"description" => wp_kses( __("Select icon (image) position (if style=iconed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Icon &amp; Image', 'organics'),
						"class" => "",
						"value" => array(
							esc_html__('Top', 'organics') => 'top',
							esc_html__('Left', 'organics') => 'left'
						),
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'js_view' => 'VcTrxTextView'
			) );
			
			class WPBakeryShortCode_Trx_Title extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Toggles
			//-------------------------------------------------------------------------------------
				
			vc_map( array(
				"base" => "trx_toggles",
				"name" => esc_html__("Toggles", "organics"),
				"description" => esc_html__("Toggles items", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_toggles',
				"class" => "trx_sc_collection trx_sc_toggles",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => false,
				"as_parent" => array('only' => 'trx_toggles_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Toggles style", "organics"),
						"description" => esc_html__("Select style for display toggles", "organics"),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip(organics_get_list_styles(1, 2)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "counter",
						"heading" => esc_html__("Counter", "organics"),
						"description" => esc_html__("Display counter before each toggles title", "organics"),
						"class" => "",
						"value" => array("Add item numbers before each element" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "organics"),
						"description" => esc_html__("Select icon for the closed toggles item from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "organics"),
						"description" => esc_html__("Select icon for the opened toggles item from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
				'default_content' => '
					[trx_toggles_item title="' . __( 'Item 1 title', 'organics' ) . '"][/trx_toggles_item]
					[trx_toggles_item title="' . __( 'Item 2 title', 'organics' ) . '"][/trx_toggles_item]
				',
				"custom_markup" => '
					<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
						%content%
					</div>
					<div class="tab_controls">
						<button class="add_tab" title="'.__("Add item", "organics").'">'.__("Add item", "organics").'</button>
					</div>
				',
				'js_view' => 'VcTrxTogglesView'
			) );
			
			
			vc_map( array(
				"base" => "trx_toggles_item",
				"name" => esc_html__("Toggles item", "organics"),
				"description" => esc_html__("Single toggles item", "organics"),
				"show_settings_on_create" => true,
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_toggles_item',
				"as_child" => array('only' => 'trx_toggles'),
				"as_parent" => array('except' => 'trx_toggles'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => esc_html__("Title for current toggles item", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "open",
						"heading" => esc_html__("Open on show", "organics"),
						"description" => esc_html__("Open current toggle item on show", "organics"),
						"class" => "",
						"value" => array("Opened" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "icon_closed",
						"heading" => esc_html__("Icon while closed", "organics"),
						"description" => esc_html__("Select icon for the closed toggles item from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "icon_opened",
						"heading" => esc_html__("Icon while opened", "organics"),
						"description" => esc_html__("Select icon for the opened toggles item from Fontello icons set", "organics"),
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxTogglesTabView'
			) );
			class WPBakeryShortCode_Trx_Toggles extends ORGANICS_VC_ShortCodeToggles {}
			class WPBakeryShortCode_Trx_Toggles_Item extends ORGANICS_VC_ShortCodeTogglesItem {}
			
			
			
			
			
			
			// Twitter
			//-------------------------------------------------------------------------------------

			vc_map( array(
				"base" => "trx_twitter",
				"name" => esc_html__("Twitter", "organics"),
				"description" => esc_html__("Insert twitter feed into post (page)", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_twitter',
				"class" => "trx_sc_single trx_sc_twitter",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Twitter Username", "organics"),
						"description" => esc_html__("Your username in the twitter account. If empty - get it from Theme Options.", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_key",
						"heading" => esc_html__("Consumer Key", "organics"),
						"description" => esc_html__("Consumer Key from the twitter account", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "consumer_secret",
						"heading" => esc_html__("Consumer Secret", "organics"),
						"description" => esc_html__("Consumer Secret from the twitter account", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_key",
						"heading" => esc_html__("Token Key", "organics"),
						"description" => esc_html__("Token Key from the twitter account", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "token_secret",
						"heading" => esc_html__("Token Secret", "organics"),
						"description" => esc_html__("Token Secret from the twitter account", "organics"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Tweets number", "organics"),
						"description" => esc_html__("Number tweets to show", "organics"),
						"class" => "",
						"divider" => true,
						"value" => 3,
						"type" => "textfield"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Show arrows", "organics"),
						"description" => esc_html__("Show control buttons", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Tweets change interval", "organics"),
						"description" => esc_html__("Tweets change interval (in milliseconds: 1000ms = 1s)", "organics"),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Alignment of the tweets block", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "organics"),
						"description" => esc_html__("Change whole slider's height (make it equal current slide's height)", "organics"),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => esc_html__("Select color scheme for this block", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => esc_html__("Any background color for this section", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "organics"),
						"description" => esc_html__("Select background image from library for this section", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "organics"),
						"description" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "organics"),
						"description" => esc_html__("Texture style from 1 to 11. Empty or 0 - without texture.", "organics"),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				),
			) );
			
			class WPBakeryShortCode_Trx_Twitter extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
			
			
			
			// Video
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_video",
				"name" => esc_html__("Video", "organics"),
				"description" => esc_html__("Insert video player", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_video',
				"class" => "trx_sc_single trx_sc_video",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "url",
						"heading" => esc_html__("URL for video file", "organics"),
						"description" => esc_html__("Paste URL for video file", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ratio",
						"heading" => esc_html__("Ratio", "organics"),
						"description" => esc_html__("Select ratio for display video", "organics"),
						"class" => "",
						"value" => array(
							__('16:9', 'organics') => "16:9",
							__('4:3', 'organics') => "4:3"
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "autoplay",
						"heading" => esc_html__("Autoplay video", "organics"),
						"description" => esc_html__("Autoplay video on page load", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array("Autoplay" => "on" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Select block alignment", "organics"),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Cover image", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for video preview", "organics"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => esc_html__("Top offset", "organics"),
						"description" => esc_html__("Top offset (padding) from background image to video block (in percent). For example: 3%", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => esc_html__("Bottom offset", "organics"),
						"description" => esc_html__("Bottom offset (padding) from background image to video block (in percent). For example: 3%", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => esc_html__("Left offset", "organics"),
						"description" => esc_html__("Left offset (padding) from background image to video block (in percent). For example: 20%", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => esc_html__("Right offset", "organics"),
						"description" => esc_html__("Right offset (padding) from background image to video block (in percent). For example: 12%", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Video extends ORGANICS_VC_ShortCodeSingle {}



            // Zoom
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "trx_zoom",
				"name" => esc_html__("Zoom", "organics"),
				"description" => esc_html__("Insert the image with zoom/lens effect", "organics"),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_zoom',
				"class" => "trx_sc_single trx_sc_zoom",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "effect",
						"heading" => esc_html__("Effect", "organics"),
						"description" => esc_html__("Select effect to display overlapping image", "organics"),
						"admin_label" => true,
						"class" => "",
						"std" => "zoom",
						"value" => array(
							__('Lens', 'organics') => 'lens',
							__('Zoom', 'organics') => 'zoom'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "url",
						"heading" => esc_html__("Main image", "organics"),
						"description" => esc_html__("Select or upload main image", "organics"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "over",
						"heading" => esc_html__("Overlaping image", "organics"),
						"description" => esc_html__("Select or upload overlaping image", "organics"),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => esc_html__("Float zoom to left or right side", "organics"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['float']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image", "organics"),
						"description" => esc_html__("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_top",
						"heading" => esc_html__("Top offset", "organics"),
						"description" => esc_html__("Top offset (padding) from background image to zoom block (in percent). For example: 3%", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_bottom",
						"heading" => esc_html__("Bottom offset", "organics"),
						"description" => esc_html__("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_left",
						"heading" => esc_html__("Left offset", "organics"),
						"description" => esc_html__("Left offset (padding) from background image to zoom block (in percent). For example: 20%", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_right",
						"heading" => esc_html__("Right offset", "organics"),
						"description" => esc_html__("Right offset (padding) from background image to zoom block (in percent). For example: 12%", "organics"),
						"group" => esc_html__('Background', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css'],
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right']
				)
			) );
			
			class WPBakeryShortCode_Trx_Zoom extends ORGANICS_VC_ShortCodeSingle {}
			

			do_action('organics_action_shortcodes_list_vc');
			
			
			if (false && organics_exists_woocommerce()) {
			
				// WooCommerce - Cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_cart",
					"name" => esc_html__("Cart", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show cart page", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_cart',
					"class" => "trx_sc_alone trx_sc_woocommerce_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => esc_html__("Dummy data", "organics"),
							"description" => esc_html__("Dummy data - not used in shortcodes", "organics"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Cart extends ORGANICS_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Checkout
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_checkout",
					"name" => esc_html__("Checkout", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show checkout page", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_checkout',
					"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => esc_html__("Dummy data", "organics"),
							"description" => esc_html__("Dummy data - not used in shortcodes", "organics"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Checkout extends ORGANICS_VC_ShortCodeAlone {}
			
			
				// WooCommerce - My Account
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_my_account",
					"name" => esc_html__("My Account", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show my account page", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_my_account',
					"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => esc_html__("Dummy data", "organics"),
							"description" => esc_html__("Dummy data - not used in shortcodes", "organics"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_My_Account extends ORGANICS_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Order Tracking
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "woocommerce_order_tracking",
					"name" => esc_html__("Order Tracking", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show order tracking page", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_order_tracking',
					"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => esc_html__("Dummy data", "organics"),
							"description" => esc_html__("Dummy data - not used in shortcodes", "organics"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Woocommerce_Order_Tracking extends ORGANICS_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Shop Messages
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "shop_messages",
					"name" => esc_html__("Shop Messages", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show shop messages", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_wooc_shop_messages',
					"class" => "trx_sc_alone trx_sc_shop_messages",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => false,
					"params" => array(
						array(
							"param_name" => "dummy",
							"heading" => esc_html__("Dummy data", "organics"),
							"description" => esc_html__("Dummy data - not used in shortcodes", "organics"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Shop_Messages extends ORGANICS_VC_ShortCodeAlone {}
			
			
				// WooCommerce - Product Page
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_page",
					"name" => esc_html__("Product Page", "organics"),
					"description" => esc_html__("WooCommerce shortcode: display single product page", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_page',
					"class" => "trx_sc_single trx_sc_product_page",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", "organics"),
							"description" => esc_html__("SKU code of displayed product", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", "organics"),
							"description" => esc_html__("ID of displayed product", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "posts_per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_type",
							"heading" => esc_html__("Post type", "organics"),
							"description" => esc_html__("Post type for the WP query (leave 'product')", "organics"),
							"class" => "",
							"value" => "product",
							"type" => "textfield"
						),
						array(
							"param_name" => "post_status",
							"heading" => esc_html__("Post status", "organics"),
							"description" => esc_html__("Display posts only with this status", "organics"),
							"class" => "",
							"value" => array(
								__('Publish', 'organics') => 'publish',
								__('Protected', 'organics') => 'protected',
								__('Private', 'organics') => 'private',
								__('Pending', 'organics') => 'pending',
								__('Draft', 'organics') => 'draft'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Page extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product",
					"name" => esc_html__("Product", "organics"),
					"description" => esc_html__("WooCommerce shortcode: display one product", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product',
					"class" => "trx_sc_single trx_sc_product",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", "organics"),
							"description" => esc_html__("Product's SKU code", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", "organics"),
							"description" => esc_html__("Product's ID", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product extends ORGANICS_VC_ShortCodeSingle {}
			
			
				// WooCommerce - Best Selling Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "best_selling_products",
					"name" => esc_html__("Best Selling Products", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show best selling products", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_best_selling_products',
					"class" => "trx_sc_single trx_sc_best_selling_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Best_Selling_Products extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Recent Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "recent_products",
					"name" => esc_html__("Recent Products", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show recent products", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_recent_products',
					"class" => "trx_sc_single trx_sc_recent_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Recent_Products extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Related Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "related_products",
					"name" => esc_html__("Related Products", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show related products", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_related_products',
					"class" => "trx_sc_single trx_sc_related_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "posts_per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Related_Products extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Featured Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "featured_products",
					"name" => esc_html__("Featured Products", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show featured products", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_featured_products',
					"class" => "trx_sc_single trx_sc_featured_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Featured_Products extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Top Rated Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "top_rated_products",
					"name" => esc_html__("Top Rated Products", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show top rated products", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_top_rated_products',
					"class" => "trx_sc_single trx_sc_top_rated_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Top_Rated_Products extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Sale Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "sale_products",
					"name" => esc_html__("Sale Products", "organics"),
					"description" => esc_html__("WooCommerce shortcode: list products on sale", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_sale_products',
					"class" => "trx_sc_single trx_sc_sale_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Sale_Products extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Product Category
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_category",
					"name" => esc_html__("Products from category", "organics"),
					"description" => esc_html__("WooCommerce shortcode: list products in specified category(-ies)", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_category',
					"class" => "trx_sc_single trx_sc_product_category",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "category",
							"heading" => esc_html__("Categories", "organics"),
							"description" => esc_html__("Comma separated category slugs", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "operator",
							"heading" => esc_html__("Operator", "organics"),
							"description" => esc_html__("Categories operator", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('IN', 'organics') => 'IN',
								__('NOT IN', 'organics') => 'NOT IN',
								__('AND', 'organics') => 'AND'
							),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Category extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "products",
					"name" => esc_html__("Products", "organics"),
					"description" => esc_html__("WooCommerce shortcode: list all products", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_products',
					"class" => "trx_sc_single trx_sc_products",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "skus",
							"heading" => esc_html__("SKUs", "organics"),
							"description" => esc_html__("Comma separated SKU codes of products", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => esc_html__("IDs", "organics"),
							"description" => esc_html__("Comma separated ID of products", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						)
					)
				) );
				
				class WPBakeryShortCode_Products extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
			
				// WooCommerce - Product Attribute
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_attribute",
					"name" => esc_html__("Products by Attribute", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show products with specified attribute", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_attribute',
					"class" => "trx_sc_single trx_sc_product_attribute",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "per_page",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many products showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "attribute",
							"heading" => esc_html__("Attribute", "organics"),
							"description" => esc_html__("Attribute name", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "filter",
							"heading" => esc_html__("Filter", "organics"),
							"description" => esc_html__("Attribute value", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Product_Attribute extends ORGANICS_VC_ShortCodeSingle {}
			
			
			
				// WooCommerce - Products Categories
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "product_categories",
					"name" => esc_html__("Product Categories", "organics"),
					"description" => esc_html__("WooCommerce shortcode: show categories with products", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_product_categories',
					"class" => "trx_sc_single trx_sc_product_categories",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "number",
							"heading" => esc_html__("Number", "organics"),
							"description" => esc_html__("How many categories showed", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "4",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Columns", "organics"),
							"description" => esc_html__("How many columns per row use for categories output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "orderby",
							"heading" => esc_html__("Order by", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array(
								__('Date', 'organics') => 'date',
								__('Title', 'organics') => 'title'
							),
							"type" => "dropdown"
						),
						array(
							"param_name" => "order",
							"heading" => esc_html__("Order", "organics"),
							"description" => esc_html__("Sorting order for products output", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
							"type" => "dropdown"
						),
						array(
							"param_name" => "parent",
							"heading" => esc_html__("Parent", "organics"),
							"description" => esc_html__("Parent category slug", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "date",
							"type" => "textfield"
						),
						array(
							"param_name" => "ids",
							"heading" => esc_html__("IDs", "organics"),
							"description" => esc_html__("Comma separated ID of products", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "hide_empty",
							"heading" => esc_html__("Hide empty", "organics"),
							"description" => esc_html__("Hide empty categories", "organics"),
							"class" => "",
							"value" => array("Hide empty" => "1" ),
							"type" => "checkbox"
						)
					)
				) );
				
				class WPBakeryShortCode_Products_Categories extends ORGANICS_VC_ShortCodeSingle {}
			
				/*
			
				// WooCommerce - Add to cart
				//-------------------------------------------------------------------------------------
				
				vc_map( array(
					"base" => "add_to_cart",
					"name" => esc_html__("Add to cart", "organics"),
					"description" => esc_html__("WooCommerce shortcode: Display a single product price + cart button", "organics"),
					"category" => esc_html__('WooCommerce', 'js_composer'),
					'icon' => 'icon_trx_add_to_cart',
					"class" => "trx_sc_single trx_sc_add_to_cart",
					"content_element" => true,
					"is_container" => false,
					"show_settings_on_create" => true,
					"params" => array(
						array(
							"param_name" => "id",
							"heading" => esc_html__("ID", "organics"),
							"description" => esc_html__("Product's ID", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "sku",
							"heading" => esc_html__("SKU", "organics"),
							"description" => esc_html__("Product's SKU code", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "quantity",
							"heading" => esc_html__("Quantity", "organics"),
							"description" => esc_html__("How many item add", "organics"),
							"admin_label" => true,
							"class" => "",
							"value" => "1",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_price",
							"heading" => esc_html__("Show price", "organics"),
							"description" => esc_html__("Show price near button", "organics"),
							"class" => "",
							"value" => array("Show price" => "true" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "class",
							"heading" => esc_html__("Class", "organics"),
							"description" => esc_html__("CSS class", "organics"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						),
						array(
							"param_name" => "style",
							"heading" => esc_html__("CSS style", "organics"),
							"description" => esc_html__("CSS style for additional decoration", "organics"),
							"class" => "",
							"value" => "",
							"type" => "textfield"
						)
					)
				) );
				
				class WPBakeryShortCode_Add_To_Cart extends ORGANICS_VC_ShortCodeSingle {}
				*/
			}

		}
	}
}
?>