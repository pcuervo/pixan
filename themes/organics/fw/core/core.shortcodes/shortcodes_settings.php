<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'organics_shortcodes_is_used' ) ) {
	function organics_shortcodes_is_used() {
		return organics_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
            || (function_exists('organics_vc_is_frontend') && organics_vc_is_frontend());		// VC Frontend editor mode														// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'organics_shortcodes_width' ) ) {
	function organics_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", "organics"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'organics_shortcodes_height' ) ) {
	function organics_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", "organics"),
			"desc" => esc_html__("Width (in pixels or percent) and height (only in pixels) of element", "organics"),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_shortcodes_settings_theme_setup' ) ) {
//	if ( organics_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'organics_action_before_init_theme', 'organics_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'organics_action_after_init_theme', 'organics_shortcodes_settings_theme_setup' );
	function organics_shortcodes_settings_theme_setup() {
		if (organics_shortcodes_is_used()) {
			global $ORGANICS_GLOBALS;

			// Prepare arrays 
			$ORGANICS_GLOBALS['sc_params'] = array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", "organics"),
					"desc" => esc_html__("ID for current element", "organics"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", "organics"),
					"desc" => esc_html__("CSS class for current element (optional)", "organics"),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", "organics"),
					"desc" => esc_html__("Any additional CSS rules (if need)", "organics"),
					"value" => "",
					"type" => "text"
				),
			
				// Margins params
				'top' => array(
					"title" => esc_html__("Top margin", "organics"),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				'bottom' => array(
					"title" => esc_html__("Bottom margin", "organics"),
					"value" => "",
					"type" => "text"
				),
			
				'left' => array(
					"title" => esc_html__("Left margin", "organics"),
					"value" => "",
					"type" => "text"
				),
			
				'right' => array(
					"title" => esc_html__("Right margin", "organics"),
					"desc" => esc_html__("Margins around list (in pixels).", "organics"),
					"value" => "",
					"type" => "text"
				),
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> __('Unordered', 'organics'),
					'ol'	=> __('Ordered', 'organics'),
					'iconed'=> __('Iconed', 'organics')
				),
				'yes_no'	=> organics_get_list_yesno(),
				'on_off'	=> organics_get_list_onoff(),
				'dir' 		=> organics_get_list_directions(),
				'align'		=> organics_get_list_alignments(),
				'float'		=> organics_get_list_floats(),
				'show_hide'	=> organics_get_list_showhide(),
				'sorting' 	=> organics_get_list_sortings(),
				'ordering' 	=> organics_get_list_orderings(),
				'shapes'	=> organics_get_list_shapes(),
				'sizes'		=> organics_get_list_sizes(),
				'sliders'	=> organics_get_list_sliders(),
				'revo_sliders' => organics_get_list_revo_sliders(),
				'categories'=> organics_get_list_categories(),
				'columns'	=> organics_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), organics_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), organics_get_list_icons()),
				'locations'	=> organics_get_list_dedicated_locations(),
				'filters'	=> organics_get_list_portfolio_filters(),
				'formats'	=> organics_get_list_post_formats_filters(),
				'hovers'	=> organics_get_list_hovers(true),
				'hovers_dir'=> organics_get_list_hovers_directions(true),
				'schemes'	=> organics_get_list_color_schemes(true),
				'animations'		=> organics_get_list_animations_in(),
				'blogger_styles'	=> organics_get_list_templates_blogger(),
				'forms'		=> organics_get_list_templates_forms(),
				'posts_types'		=> organics_get_list_posts_types(),
				'googlemap_styles'	=> organics_get_list_googlemap_styles(),
				'field_types'		=> organics_get_list_field_types(),
				'label_positions'	=> organics_get_list_label_positions()
			);

			$ORGANICS_GLOBALS['sc_params']['animation'] = array(
				"title" => esc_html__("Animation",  'organics'),
				"desc" => esc_html__('Select animation while object enter in the visible area of page',  'organics'),
				"value" => "none",
				"type" => "select",
				"options" => $ORGANICS_GLOBALS['sc_params']['animations']
			);
	
			// Shortcodes list
			//------------------------------------------------------------------
			$ORGANICS_GLOBALS['shortcodes'] = array(
			
				// Accordion
				"trx_accordion" => array(
					"title" => esc_html__("Accordion", "organics"),
					"desc" => esc_html__("Accordion items", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Accordion style", "organics"),
							"desc" => esc_html__("Select style for display accordion", "organics"),
							"value" => 1,
							"options" => organics_get_list_styles(1, 2),
							"type" => "radio"
						),
						"counter" => array(
							"title" => esc_html__("Counter", "organics"),
							"desc" => esc_html__("Display counter before each accordion title", "organics"),
							"value" => "off",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['on_off']
						),
						"initial" => array(
							"title" => esc_html__("Initially opened item", "organics"),
							"desc" => esc_html__("Number of initially opened item", "organics"),
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"icon_closed" => array(
							"title" => esc_html__("Icon while closed",  'organics'),
							"desc" => esc_html__('Select icon for the closed accordion item from Fontello icons set',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => esc_html__("Icon while opened",  'organics'),
							"desc" => esc_html__('Select icon for the opened accordion item from Fontello icons set',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_accordion_item",
						"title" => esc_html__("Item", "organics"),
						"desc" => esc_html__("Accordion item", "organics"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Accordion item title", "organics"),
								"desc" => esc_html__("Title for current accordion item", "organics"),
								"value" => "",
								"type" => "text"
							),
							"icon_closed" => array(
								"title" => esc_html__("Icon while closed",  'organics'),
								"desc" => esc_html__('Select icon for the closed accordion item from Fontello icons set',  'organics'),
								"value" => "",
								"type" => "icons",
								"options" => $ORGANICS_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => esc_html__("Icon while opened",  'organics'),
								"desc" => esc_html__('Select icon for the opened accordion item from Fontello icons set',  'organics'),
								"value" => "",
								"type" => "icons",
								"options" => $ORGANICS_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => esc_html__("Accordion item content", "organics"),
								"desc" => esc_html__("Current accordion item content", "organics"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Anchor
				"trx_anchor" => array(
					"title" => esc_html__("Anchor", "organics"),
					"desc" => esc_html__("Insert anchor for the TOC (table of content)", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => esc_html__("Anchor's icon",  'organics'),
							"desc" => esc_html__('Select icon for the anchor from Fontello icons set',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"title" => array(
							"title" => esc_html__("Short title", "organics"),
							"desc" => esc_html__("Short title of the anchor (for the table of content)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Long description", "organics"),
							"desc" => esc_html__("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", "organics"),
							"value" => "",
							"type" => "text"
						),
						"url" => array(
							"title" => esc_html__("External URL", "organics"),
							"desc" => esc_html__("External URL for this TOC item", "organics"),
							"value" => "",
							"type" => "text"
						),
						"separator" => array(
							"title" => esc_html__("Add separator", "organics"),
							"desc" => esc_html__("Add separator under item in the TOC", "organics"),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"id" => $ORGANICS_GLOBALS['sc_params']['id']
					)
				),
			
			
				// Audio
				"trx_audio" => array(
					"title" => esc_html__("Audio", "organics"),
					"desc" => esc_html__("Insert audio player", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for audio file", "organics"),
							"desc" => esc_html__("URL for audio file", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose audio', 'organics'),
								'action' => 'media_upload',
								'type' => 'audio',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose audio file', 'organics'),
									'update' => __('Select audio file', 'organics')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"image" => array(
							"title" => esc_html__("Cover image", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for audio cover", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Title of the audio file", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"author" => array(
							"title" => esc_html__("Author", "organics"),
							"desc" => esc_html__("Author of the audio file", "organics"),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => esc_html__("Show controls", "organics"),
							"desc" => esc_html__("Show controls in audio player", "organics"),
							"divider" => true,
							"size" => "medium",
							"value" => "show",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['show_hide']
						),
						"autoplay" => array(
							"title" => esc_html__("Autoplay audio", "organics"),
							"desc" => esc_html__("Autoplay audio on page load", "organics"),
							"value" => "off",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => esc_html__("Align", "organics"),
							"desc" => esc_html__("Select block alignment", "organics"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Block
				"trx_block" => array(
					"title" => esc_html__("Block container", "organics"),
					"desc" => esc_html__("Container for any block ([section] analog - to enable nesting)", "organics"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => esc_html__("Dedicated", "organics"),
							"desc" => esc_html__("Use this block as dedicated content - show it before post title on single page", "organics"),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Align", "organics"),
							"desc" => esc_html__("Select block alignment", "organics"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => esc_html__("Columns emulation", "organics"),
							"desc" => esc_html__("Select width for columns emulation", "organics"),
							"value" => "none",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => esc_html__("Use pan effect", "organics"),
							"desc" => esc_html__("Use pan effect to show section content", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "organics"),
							"desc" => esc_html__("Use scroller to show section content", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => esc_html__("Scroll direction", "organics"),
							"desc" => esc_html__("Scroll direction (if Use scroller = yes)", "organics"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => esc_html__("Scroll controls", "organics"),
							"desc" => esc_html__("Show scroll controls (if Use scroller = yes)", "organics"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => esc_html__("Select color scheme for this block", "organics"),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => esc_html__("Fore color", "organics"),
							"desc" => esc_html__("Any color for objects in this section", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "organics"),
							"desc" => esc_html__("Any background color for this section", "organics"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_tile" => array(
							"title" => esc_html__("Tile background image", "organics"),
							"desc" => esc_html__("Do you want tile background image or image cover whole block?", "organics"),
							"value" => "no",
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "organics"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "organics"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "organics"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "organics"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "organics"),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "organics"),
							"desc" => esc_html__("Font weight of the text", "organics"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'organics'),
								'300' => __('Light (300)', 'organics'),
								'400' => __('Normal (400)', 'organics'),
								'700' => __('Bold (700)', 'organics')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", "organics"),
							"desc" => esc_html__("Content for section container", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Blogger
				"trx_blogger" => array(
					"title" => esc_html__("Blogger", "organics"),
					"desc" => esc_html__("Insert posts (pages) in many styles from desired categories or directly from ids", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Title for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "organics"),
							"desc" => esc_html__("Subtitle for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "organics"),
							"desc" => esc_html__("Short description for the block", "organics"),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Posts output style", "organics"),
							"desc" => esc_html__("Select desired style for posts output", "organics"),
							"value" => "regular",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['blogger_styles']
						),
						"filters" => array(
							"title" => esc_html__("Show filters", "organics"),
							"desc" => esc_html__("Use post's tags or categories as filter buttons", "organics"),
							"value" => "no",
							"dir" => "horizontal",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['filters']
						),
						"hover" => array(
							"title" => esc_html__("Hover effect", "organics"),
							"desc" => esc_html__("Select hover effect (only if style=Portfolio)", "organics"),
							"dependency" => array(
								'style' => array('portfolio','grid','square','short','colored')
							),
							"value" => "",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['hovers']
						),
						"hover_dir" => array(
							"title" => esc_html__("Hover direction", "organics"),
							"desc" => esc_html__("Select hover direction (only if style=Portfolio and hover=Circle|Square)", "organics"),
							"dependency" => array(
								'style' => array('portfolio','grid','square','short','colored'),
								'hover' => array('square','circle')
							),
							"value" => "left_to_right",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['hovers_dir']
						),
						"dir" => array(
							"title" => esc_html__("Posts direction", "organics"),
							"desc" => esc_html__("Display posts in horizontal or vertical direction", "organics"),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['dir']
						),
						"post_type" => array(
							"title" => esc_html__("Post type", "organics"),
							"desc" => esc_html__("Select post type to show", "organics"),
							"value" => "post",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['posts_types']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "organics"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "organics"),
							"value" => "",
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories list", "organics"),
							"desc" => esc_html__("Select the desired categories. If not selected - show posts from any category or from IDs list", "organics"),
							"dependency" => array(
								'ids' => array('is_empty'),
								'post_type' => array('refresh')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => organics_array_merge(array(0 => __('- Select category -', 'organics')), $ORGANICS_GLOBALS['sc_params']['categories'])
						),
						"count" => array(
							"title" => esc_html__("Total posts to show", "organics"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "organics"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns number", "organics"),
							"desc" => esc_html__("How many columns used to show posts? If empty or 0 - equal to posts number", "organics"),
							"dependency" => array(
								'dir' => array('horizontal')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "organics"),
							"desc" => esc_html__("Skip posts before select next part.", "organics"),
							"dependency" => array(
								'ids' => array('is_empty')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "organics"),
							"desc" => esc_html__("Select desired posts sorting method", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "organics"),
							"desc" => esc_html__("Select desired posts order", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"only" => array(
							"title" => esc_html__("Select posts only", "organics"),
							"desc" => esc_html__("Select posts only with reviews, videos, audios, thumbs or galleries", "organics"),
							"value" => "no",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['formats']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "organics"),
							"desc" => esc_html__("Use scroller to show all posts", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Show slider controls", "organics"),
							"desc" => esc_html__("Show arrows to control scroll slider", "organics"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"location" => array(
							"title" => esc_html__("Dedicated content location", "organics"),
							"desc" => esc_html__("Select position for dedicated content (only for style=excerpt)", "organics"),
							"divider" => true,
							"dependency" => array(
								'style' => array('excerpt')
							),
							"value" => "default",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['locations']
						),
						"rating" => array(
							"title" => esc_html__("Show rating stars", "organics"),
							"desc" => esc_html__("Show rating stars under post's header", "organics"),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"info" => array(
							"title" => esc_html__("Show post info block", "organics"),
							"desc" => esc_html__("Show post info block (author, date, tags, etc.)", "organics"),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"links" => array(
							"title" => esc_html__("Allow links on the post", "organics"),
							"desc" => esc_html__("Allow links on the post from each blogger item", "organics"),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"descr" => array(
							"title" => esc_html__("Description length", "organics"),
							"desc" => esc_html__("How many characters are displayed from post excerpt? If 0 - don't show description", "organics"),
							"value" => 0,
							"min" => 0,
							"step" => 10,
							"type" => "spinner"
						),
						"readmore" => array(
							"title" => esc_html__("More link text", "organics"),
							"desc" => esc_html__("Read more link text. If empty - show 'More', else - used as link text", "organics"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "organics"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "organics"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Br
				"trx_br" => array(
					"title" => esc_html__("Break", "organics"),
					"desc" => esc_html__("Line break with clear floating (if need)", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"clear" => 	array(
							"title" => esc_html__("Clear floating", "organics"),
							"desc" => esc_html__("Clear floating (if need)", "organics"),
							"value" => "",
							"type" => "checklist",
							"options" => array(
								'none' => __('None', 'organics'),
								'left' => __('Left', 'organics'),
								'right' => __('Right', 'organics'),
								'both' => __('Both', 'organics')
							)
						)
					)
				),
			
			
			
			
				// Button
				"trx_button" => array(
					"title" => esc_html__("Button", "organics"),
					"desc" => esc_html__("Button with link", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Caption", "organics"),
							"desc" => esc_html__("Button caption", "organics"),
							"value" => "",
							"type" => "text"
						),
						"type" => array(
							"title" => esc_html__("Button's shape", "organics"),
							"desc" => esc_html__("Select button's shape", "organics"),
							"value" => "square",
							"size" => "medium",
							"options" => array(
								'square' => __('Square', 'organics'),
								'round' => __('Round', 'organics')
							),
							"type" => "switch"
						), 
						"style" => array(
							"title" => esc_html__("Button's style", "organics"),
							"desc" => esc_html__("Select button's style", "organics"),
							"value" => "default",
							"dir" => "horizontal",
							"options" => array(
								'filled' => __('Filled', 'organics'),
								'border' => __('Border', 'organics')
							),
							"type" => "checklist"
						), 
						"size" => array(
							"title" => esc_html__("Button's size", "organics"),
							"desc" => esc_html__("Select button's size", "organics"),
							"value" => "small",
							"dir" => "horizontal",
							"options" => array(
								'small' => __('Small', 'organics'),
								'medium' => __('Medium', 'organics'),
								'large' => __('Large', 'organics')
							),
							"type" => "checklist"
						), 
						"icon" => array(
							"title" => esc_html__("Button's icon",  'organics'),
							"desc" => esc_html__('Select icon for the title from Fontello icons set',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Button's text color", "organics"),
							"desc" => esc_html__("Any color for button's caption", "organics"),
							"std" => "",
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Button's backcolor", "organics"),
							"desc" => esc_html__("Any color for button's background", "organics"),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => esc_html__("Button's alignment", "organics"),
							"desc" => esc_html__("Align button to left, center or right", "organics"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => esc_html__("Link URL", "organics"),
							"desc" => esc_html__("URL for link on button click", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"target" => array(
							"title" => esc_html__("Link target", "organics"),
							"desc" => esc_html__("Target for link on button click", "organics"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"popup" => array(
							"title" => esc_html__("Open link in popup", "organics"),
							"desc" => esc_html__("Open link target in popup window", "organics"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						), 
						"rel" => array(
							"title" => esc_html__("Rel attribute", "organics"),
							"desc" => esc_html__("Rel attribute for button's link (if need)", "organics"),
							"dependency" => array(
								'link' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),




				// Call to Action block
				"trx_call_to_action" => array(
					"title" => esc_html__("Call to action", "organics"),
					"desc" => esc_html__("Insert call to action block in your page (post)", "organics"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Title for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "organics"),
							"desc" => esc_html__("Subtitle for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "organics"),
							"desc" => esc_html__("Short description for the block", "organics"),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Style", "organics"),
							"desc" => esc_html__("Select style to display block", "organics"),
							"value" => "1",
							"type" => "checklist",
							"options" => organics_get_list_styles(1, 2)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => esc_html__("Alignment elements in the block", "organics"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"accent" => array(
							"title" => esc_html__("Accented", "organics"),
							"desc" => esc_html__("Fill entire block with Accent1 color from current color scheme", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "organics"),
							"desc" => esc_html__("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"image" => array(
							"title" => esc_html__("Image", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site to include image into this block", "organics"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"video" => array(
							"title" => esc_html__("URL for video file", "organics"),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site to include video into this block", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'organics'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'organics'),
									'update' => __('Select video file', 'organics')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"link" => array(
							"title" => esc_html__("Button URL", "organics"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "organics"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"link2" => array(
							"title" => esc_html__("Button 2 URL", "organics"),
							"desc" => esc_html__("Link URL for the second button at the bottom of the block", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link2_caption" => array(
							"title" => esc_html__("Button 2 caption", "organics"),
							"desc" => esc_html__("Caption for the second button at the bottom of the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Chat
				"trx_chat" => array(
					"title" => esc_html__("Chat", "organics"),
					"desc" => esc_html__("Chat message", "organics"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Item title", "organics"),
							"desc" => esc_html__("Chat item title", "organics"),
							"value" => "",
							"type" => "text"
						),
						"photo" => array(
							"title" => esc_html__("Item photo", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the item photo (avatar)", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"link" => array(
							"title" => esc_html__("Item link", "organics"),
							"desc" => esc_html__("Chat item link", "organics"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Chat item content", "organics"),
							"desc" => esc_html__("Current chat item content", "organics"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Columns
				"trx_columns" => array(
					"title" => esc_html__("Columns", "organics"),
					"desc" => esc_html__("Insert up to 5 columns in your page (post)", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"fluid" => array(
							"title" => esc_html__("Fluid columns", "organics"),
							"desc" => esc_html__("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "organics"),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						), 
						"margins" => array(
							"title" => esc_html__("Margins between columns", "organics"),
							"desc" => esc_html__("Add margins between columns", "organics"),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"banner" => array(
							"title" => esc_html__("Banner Grid", "organics"),
							"desc" => esc_html__("Use Standard Grid", "organics"),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_column_item",
						"title" => esc_html__("Column", "organics"),
						"desc" => esc_html__("Column item", "organics"),
						"container" => true,
						"params" => array(
							"span" => array(
								"title" => esc_html__("Merge columns", "organics"),
								"desc" => esc_html__("Count merged columns from current", "organics"),
								"value" => "",
								"type" => "text"
							),
							"align" => array(
								"title" => esc_html__("Alignment", "organics"),
								"desc" => esc_html__("Alignment text in the column", "organics"),
								"value" => "",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $ORGANICS_GLOBALS['sc_params']['align']
							),
							"color" => array(
								"title" => esc_html__("Fore color", "organics"),
								"desc" => esc_html__("Any color for objects in this column", "organics"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => esc_html__("Background color", "organics"),
								"desc" => esc_html__("Any background color for this column", "organics"),
								"value" => "",
								"type" => "color"
							),
							"bg_image" => array(
								"title" => esc_html__("URL for background image file", "organics"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the background", "organics"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"bg_tile" => array(
								"title" => esc_html__("Tile background image", "organics"),
								"desc" => esc_html__("Do you want tile background image or image cover whole column?", "organics"),
								"value" => "no",
								"dependency" => array(
									'bg_image' => array('not_empty')
								),
								"type" => "switch",
								"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
							),
							"_content_" => array(
								"title" => esc_html__("Column item content", "organics"),
								"desc" => esc_html__("Current column item content", "organics"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Contact form
				"trx_form" => array(
					"title" => esc_html__("Form", "organics"),
					"desc" => esc_html__("Insert form with specified style or with set of custom fields", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Title for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "organics"),
							"desc" => esc_html__("Subtitle for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "organics"),
							"desc" => esc_html__("Short description for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("Style", "organics"),
							"desc" => esc_html__("Select style of the form (if 'style' is not equal 'custom' - all tabs 'Field NN' are ignored!", "organics"),
							"value" => 'form_custom',
							"options" => $ORGANICS_GLOBALS['sc_params']['forms'],
							"type" => "checklist"
						), 
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => esc_html__("Select color scheme for this block", "organics"),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"action" => array(
							"title" => esc_html__("Action", "organics"),
							"desc" => esc_html__("Contact form action (URL to handle form data). If empty - use internal action", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align", "organics"),
							"desc" => esc_html__("Select form alignment", "organics"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"width" => organics_shortcodes_width(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_form_item",
						"title" => esc_html__("Field", "organics"),
						"desc" => esc_html__("Custom field", "organics"),
						"container" => false,
						"params" => array(
							"type" => array(
								"title" => esc_html__("Type", "organics"),
								"desc" => esc_html__("Type of the custom field", "organics"),
								"value" => "text",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $ORGANICS_GLOBALS['sc_params']['field_types']
							), 
							"name" => array(
								"title" => esc_html__("Name", "organics"),
								"desc" => esc_html__("Name of the custom field", "organics"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => esc_html__("Default value", "organics"),
								"desc" => esc_html__("Default value of the custom field", "organics"),
								"value" => "",
								"type" => "text"
							),
							"options" => array(
								"title" => esc_html__("Options", "organics"),
								"desc" => esc_html__("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "organics"),
								"dependency" => array(
									'type' => array('radio', 'checkbox', 'select')
								),
								"value" => "",
								"type" => "text"
							),
							"label" => array(
								"title" => esc_html__("Label", "organics"),
								"desc" => esc_html__("Label for the custom field", "organics"),
								"value" => "",
								"type" => "text"
							),
							"label_position" => array(
								"title" => esc_html__("Label position", "organics"),
								"desc" => esc_html__("Label position relative to the field", "organics"),
								"value" => "top",
								"type" => "checklist",
								"dir" => "horizontal",
								"options" => $ORGANICS_GLOBALS['sc_params']['label_positions']
							), 
							"top" => $ORGANICS_GLOBALS['sc_params']['top'],
							"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
							"left" => $ORGANICS_GLOBALS['sc_params']['left'],
							"right" => $ORGANICS_GLOBALS['sc_params']['right'],
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Content block on fullscreen page
				"trx_content" => array(
					"title" => esc_html__("Content block", "organics"),
					"desc" => esc_html__("Container for main content block with desired class and style (use it only on fullscreen pages)", "organics"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => esc_html__("Select color scheme for this block", "organics"),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"_content_" => array(
							"title" => esc_html__("Container content", "organics"),
							"desc" => esc_html__("Content for section container", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Countdown
				"trx_countdown" => array(
					"title" => esc_html__("Countdown", "organics"),
					"desc" => esc_html__("Insert countdown object", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"date" => array(
							"title" => esc_html__("Date", "organics"),
							"desc" => esc_html__("Upcoming date (format: yyyy-mm-dd)", "organics"),
							"value" => "",
							"format" => "yy-mm-dd",
							"type" => "date"
						),
						"time" => array(
							"title" => esc_html__("Time", "organics"),
							"desc" => esc_html__("Upcoming time (format: HH:mm:ss)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("Style", "organics"),
							"desc" => esc_html__("Countdown style", "organics"),
							"value" => "1",
							"type" => "checklist",
							"options" => organics_get_list_styles(1, 2)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => esc_html__("Align counter to left, center or right", "organics"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						), 
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Dropcaps
				"trx_dropcaps" => array(
					"title" => esc_html__("Dropcaps", "organics"),
					"desc" => esc_html__("Make first letter as dropcaps", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "organics"),
							"desc" => esc_html__("Dropcaps style", "organics"),
							"value" => "1",
							"type" => "checklist",
							"options" => organics_get_list_styles(1, 4)
						),
						"_content_" => array(
							"title" => esc_html__("Paragraph content", "organics"),
							"desc" => esc_html__("Paragraph with dropcaps content", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Emailer
				"trx_emailer" => array(
					"title" => esc_html__("E-mail collector", "organics"),
					"desc" => esc_html__("Collect the e-mail address into specified group", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"group" => array(
							"title" => esc_html__("Group", "organics"),
							"desc" => esc_html__("The name of group to collect e-mail address", "organics"),
							"value" => "",
							"type" => "text"
						),
						"open" => array(
							"title" => esc_html__("Open", "organics"),
							"desc" => esc_html__("Initially open the input field on show object", "organics"),
							"divider" => true,
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => esc_html__("Align object to left, center or right", "organics"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						), 
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Gap
				"trx_gap" => array(
					"title" => esc_html__("Gap", "organics"),
					"desc" => esc_html__("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "organics"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Gap content", "organics"),
							"desc" => esc_html__("Gap inner content", "organics"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						)
					)
				),
			
			
			
			
			
				// Google map
				"trx_googlemap" => array(
					"title" => esc_html__("Google map", "organics"),
					"desc" => esc_html__("Insert Google map with specified markers", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"zoom" => array(
							"title" => esc_html__("Zoom", "organics"),
							"desc" => esc_html__("Map zoom factor", "organics"),
							"divider" => true,
							"value" => 16,
							"min" => 1,
							"max" => 20,
							"type" => "spinner"
						),
						"style" => array(
							"title" => esc_html__("Map style", "organics"),
							"desc" => esc_html__("Select map style", "organics"),
							"value" => "default",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['googlemap_styles']
						),
						"width" => organics_shortcodes_width('100%'),
						"height" => organics_shortcodes_height(240),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_googlemap_marker",
						"title" => esc_html__("Google map marker", "organics"),
						"desc" => esc_html__("Google map marker", "organics"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"address" => array(
								"title" => esc_html__("Address", "organics"),
								"desc" => esc_html__("Address of this marker", "organics"),
								"value" => "",
								"type" => "text"
							),
							"latlng" => array(
								"title" => esc_html__("Latitude and Longtitude", "organics"),
								"desc" => esc_html__("Comma separated marker's coorditanes (instead Address)", "organics"),
								"value" => "",
								"type" => "text"
							),
							"point" => array(
								"title" => esc_html__("URL for marker image file", "organics"),
								"desc" => esc_html__("Select or upload image or write URL from other site for this marker. If empty - use default marker", "organics"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"title" => array(
								"title" => esc_html__("Title", "organics"),
								"desc" => esc_html__("Title for this marker", "organics"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", "organics"),
								"desc" => esc_html__("Description for this marker", "organics"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id']
						)
					)
				),
			
			
			
				// Hide or show any block
				"trx_hide" => array(
					"title" => esc_html__("Hide/Show any block", "organics"),
					"desc" => esc_html__("Hide or Show any block with desired CSS-selector", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"selector" => array(
							"title" => esc_html__("Selector", "organics"),
							"desc" => esc_html__("Any block's CSS-selector", "organics"),
							"value" => "",
							"type" => "text"
						),
						"hide" => array(
							"title" => esc_html__("Hide or Show", "organics"),
							"desc" => esc_html__("New state for the block: hide or show", "organics"),
							"value" => "yes",
							"size" => "small",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						)
					)
				),
			
			
			
				// Highlght text
				"trx_highlight" => array(
					"title" => esc_html__("Highlight text", "organics"),
					"desc" => esc_html__("Highlight text with selected color, background color and other styles", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"type" => array(
							"title" => esc_html__("Type", "organics"),
							"desc" => esc_html__("Highlight type", "organics"),
							"value" => "1",
							"type" => "checklist",
							"options" => array(
								0 => __('Custom', 'organics'),
								1 => __('Type 1', 'organics'),
								2 => __('Type 2', 'organics'),
								3 => __('Type 3', 'organics')
							)
						),
						"color" => array(
							"title" => esc_html__("Color", "organics"),
							"desc" => esc_html__("Color for the highlighted text", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "organics"),
							"desc" => esc_html__("Background color for the highlighted text", "organics"),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "organics"),
							"desc" => esc_html__("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Highlighting content", "organics"),
							"desc" => esc_html__("Content for highlight", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Icon
				"trx_icon" => array(
					"title" => esc_html__("Icon", "organics"),
					"desc" => esc_html__("Insert icon", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"icon" => array(
							"title" => esc_html__('Icon',  'organics'),
							"desc" => esc_html__('Select font icon from the Fontello icons set',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Icon's color", "organics"),
							"desc" => esc_html__("Icon's color", "organics"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "color"
						),
						"bg_shape" => array(
							"title" => esc_html__("Background shape", "organics"),
							"desc" => esc_html__("Shape of the icon background", "organics"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "none",
							"type" => "radio",
							"options" => array(
								'none' => __('None', 'organics'),
								'round' => __('Round', 'organics'),
								'square' => __('Square', 'organics')
							)
						),
						"bg_color" => array(
							"title" => esc_html__("Icon's background color", "organics"),
							"desc" => esc_html__("Icon's background color", "organics"),
							"dependency" => array(
								'icon' => array('not_empty'),
								'background' => array('round','square')
							),
							"value" => "",
							"type" => "color"
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "organics"),
							"desc" => esc_html__("Icon's font size", "organics"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "spinner",
							"min" => 8,
							"max" => 240
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "organics"),
							"desc" => esc_html__("Icon font weight", "organics"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'organics'),
								'300' => __('Light (300)', 'organics'),
								'400' => __('Normal (400)', 'organics'),
								'700' => __('Bold (700)', 'organics')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => esc_html__("Icon text alignment", "organics"),
							"dependency" => array(
								'icon' => array('not_empty')
							),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						), 
						"link" => array(
							"title" => esc_html__("Link URL", "organics"),
							"desc" => esc_html__("Link URL from this icon (if not empty)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Image
				"trx_image" => array(
					"title" => esc_html__("Image", "organics"),
					"desc" => esc_html__("Insert image into your post (page)", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for image file", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
							)
						),
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Image title (if need)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => esc_html__("Icon before title",  'organics'),
							"desc" => esc_html__('Select icon for the title from Fontello icons set',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"align" => array(
							"title" => esc_html__("Float image", "organics"),
							"desc" => esc_html__("Float image to left or right side", "organics"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['float']
						), 
						"shape" => array(
							"title" => esc_html__("Image Shape", "organics"),
							"desc" => esc_html__("Shape of the image: square (rectangle) or round", "organics"),
							"value" => "square",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"square" => __('Square', 'organics'),
								"round" => __('Round', 'organics')
							)
						), 
						"link" => array(
							"title" => esc_html__("Link", "organics"),
							"desc" => esc_html__("The link URL from the image", "organics"),
							"value" => "",
							"type" => "text"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Infobox
				"trx_infobox" => array(
					"title" => esc_html__("Infobox", "organics"),
					"desc" => esc_html__("Insert infobox into your post (page)", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "organics"),
							"desc" => esc_html__("Infobox style", "organics"),
							"value" => "regular",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'regular' => __('Regular', 'organics'),
								'info' => __('Info', 'organics'),
								'success' => __('Success', 'organics'),
								'error' => __('Error', 'organics')
							)
						),
						"closeable" => array(
							"title" => esc_html__("Closeable box", "organics"),
							"desc" => esc_html__("Create closeable box (with close button)", "organics"),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"icon" => array(
							"title" => esc_html__("Custom icon",  'organics'),
							"desc" => esc_html__('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"color" => array(
							"title" => esc_html__("Text color", "organics"),
							"desc" => esc_html__("Any color for text and headers", "organics"),
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "organics"),
							"desc" => esc_html__("Any background color for this infobox", "organics"),
							"value" => "",
							"type" => "color"
						),
						"_content_" => array(
							"title" => esc_html__("Infobox content", "organics"),
							"desc" => esc_html__("Content for infobox", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Line
				"trx_line" => array(
					"title" => esc_html__("Line", "organics"),
					"desc" => esc_html__("Insert Line into your post (page)", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "organics"),
							"desc" => esc_html__("Line style", "organics"),
							"value" => "solid",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'solid' => __('Solid', 'organics'),
								'dashed' => __('Dashed', 'organics'),
								'dotted' => __('Dotted', 'organics'),
								'double' => __('Double', 'organics')
							)
						),
						"color" => array(
							"title" => esc_html__("Color", "organics"),
							"desc" => esc_html__("Line color", "organics"),
							"value" => "",
							"type" => "color"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// List
				"trx_list" => array(
					"title" => esc_html__("List", "organics"),
					"desc" => esc_html__("List items with specific bullets", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Bullet's style", "organics"),
							"desc" => esc_html__("Bullet's style for each list item", "organics"),
							"value" => "ul",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['list_styles']
						), 
						"color" => array(
							"title" => esc_html__("Color", "organics"),
							"desc" => esc_html__("List items color", "organics"),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => esc_html__('List icon',  'organics'),
							"desc" => esc_html__("Select list icon from Fontello icons set (only for style=Iconed)",  'organics'),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"icon_color" => array(
							"title" => esc_html__("Icon color", "organics"),
							"desc" => esc_html__("List icons color", "organics"),
							"value" => "",
							"dependency" => array(
								'style' => array('iconed')
							),
							"type" => "color"
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_list_item",
						"title" => esc_html__("Item", "organics"),
						"desc" => esc_html__("List item with specific bullet", "organics"),
						"decorate" => false,
						"container" => true,
						"params" => array(
							"_content_" => array(
								"title" => esc_html__("List item content", "organics"),
								"desc" => esc_html__("Current list item content", "organics"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"title" => array(
								"title" => esc_html__("List item title", "organics"),
								"desc" => esc_html__("Current list item title (show it as tooltip)", "organics"),
								"value" => "",
								"type" => "text"
							),
							"color" => array(
								"title" => esc_html__("Color", "organics"),
								"desc" => esc_html__("Text color for this item", "organics"),
								"value" => "",
								"type" => "color"
							),
							"icon" => array(
								"title" => esc_html__('List icon',  'organics'),
								"desc" => esc_html__("Select list item icon from Fontello icons set (only for style=Iconed)",  'organics'),
								"value" => "",
								"type" => "icons",
								"options" => $ORGANICS_GLOBALS['sc_params']['icons']
							),
							"icon_color" => array(
								"title" => esc_html__("Icon color", "organics"),
								"desc" => esc_html__("Icon color for this item", "organics"),
								"value" => "",
								"type" => "color"
							),
							"link" => array(
								"title" => esc_html__("Link URL", "organics"),
								"desc" => esc_html__("Link URL for the current list item", "organics"),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"target" => array(
								"title" => esc_html__("Link target", "organics"),
								"desc" => esc_html__("Link target for the current list item", "organics"),
								"value" => "",
								"type" => "text"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
				// Number
				"trx_number" => array(
					"title" => esc_html__("Number", "organics"),
					"desc" => esc_html__("Insert number or any word as set separate characters", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"value" => array(
							"title" => esc_html__("Value", "organics"),
							"desc" => esc_html__("Number or any word", "organics"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Align", "organics"),
							"desc" => esc_html__("Select block alignment", "organics"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Parallax
				"trx_parallax" => array(
					"title" => esc_html__("Parallax", "organics"),
					"desc" => esc_html__("Create the parallax container (with asinc background image)", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"gap" => array(
							"title" => esc_html__("Create gap", "organics"),
							"desc" => esc_html__("Create gap around parallax container", "organics"),
							"value" => "no",
							"size" => "small",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						), 
						"dir" => array(
							"title" => esc_html__("Dir", "organics"),
							"desc" => esc_html__("Scroll direction for the parallax background", "organics"),
							"value" => "up",
							"size" => "medium",
							"options" => array(
								'up' => __('Up', 'organics'),
								'down' => __('Down', 'organics')
							),
							"type" => "switch"
						), 
						"speed" => array(
							"title" => esc_html__("Speed", "organics"),
							"desc" => esc_html__("Image motion speed (from 0.0 to 1.0)", "organics"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0.3",
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => esc_html__("Select color scheme for this block", "organics"),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => esc_html__("Text color", "organics"),
							"desc" => esc_html__("Select color for text object inside parallax block", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "organics"),
							"desc" => esc_html__("Select color for parallax background", "organics"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the parallax background", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image_x" => array(
							"title" => esc_html__("Image X position", "organics"),
							"desc" => esc_html__("Image horizontal position (as background of the parallax block) - in percent", "organics"),
							"min" => "0",
							"max" => "100",
							"value" => "50",
							"type" => "spinner"
						),
						"bg_video" => array(
							"title" => esc_html__("Video background", "organics"),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site to show it as parallax background", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'organics'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'organics'),
									'update' => __('Select video file', 'organics')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"bg_video_ratio" => array(
							"title" => esc_html__("Video ratio", "organics"),
							"desc" => esc_html__("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "organics"),
							"value" => "16:9",
							"type" => "text"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "organics"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "organics"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "organics"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "organics"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"_content_" => array(
							"title" => esc_html__("Content", "organics"),
							"desc" => esc_html__("Content for the parallax container", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Popup
				"trx_popup" => array(
					"title" => esc_html__("Popup window", "organics"),
					"desc" => esc_html__("Container for any html-block with desired class and style for popup window", "organics"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Container content", "organics"),
							"desc" => esc_html__("Content for section container", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Price
				"trx_price" => array(
					"title" => esc_html__("Price", "organics"),
					"desc" => esc_html__("Insert price with decoration", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"money" => array(
							"title" => esc_html__("Money", "organics"),
							"desc" => esc_html__("Money value (dot or comma separated)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => esc_html__("Currency", "organics"),
							"desc" => esc_html__("Currency character", "organics"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => esc_html__("Period", "organics"),
							"desc" => esc_html__("Period text (if need). For example: monthly, daily, etc.", "organics"),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => esc_html__("Align price to left or right side", "organics"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['float']
						), 
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
				// Price block
				"trx_price_block" => array(
					"title" => esc_html__("Price block", "organics"),
					"desc" => esc_html__("Insert price block with title, price and description", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Block title", "organics"),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Link URL", "organics"),
							"desc" => esc_html__("URL for link from button (at bottom of the block)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"link_text" => array(
							"title" => esc_html__("Link text", "organics"),
							"desc" => esc_html__("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "organics"),
							"value" => "",
							"type" => "text"
						),
						"icon" => array(
							"title" => esc_html__("Icon",  'organics'),
							"desc" => esc_html__('Select icon from Fontello icons set (placed before/instead price)',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"money" => array(
							"title" => esc_html__("Money", "organics"),
							"desc" => esc_html__("Money value (dot or comma separated)", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"currency" => array(
							"title" => esc_html__("Currency", "organics"),
							"desc" => esc_html__("Currency character", "organics"),
							"value" => "$",
							"type" => "text"
						),
						"period" => array(
							"title" => esc_html__("Period", "organics"),
							"desc" => esc_html__("Period text (if need). For example: monthly, daily, etc.", "organics"),
							"value" => "",
							"type" => "text"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => esc_html__("Select color scheme for this block", "organics"),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => esc_html__("Align price to left or right side", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['float']
						), 
						"_content_" => array(
							"title" => esc_html__("Description", "organics"),
							"desc" => esc_html__("Description for this price block", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Quote
				"trx_quote" => array(
					"title" => esc_html__("Quote", "organics"),
					"desc" => esc_html__("Quote text", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"cite" => array(
							"title" => esc_html__("Quote cite", "organics"),
							"desc" => esc_html__("URL for quote cite", "organics"),
							"value" => "",
							"type" => "text"
						),
						"title" => array(
							"title" => esc_html__("Title (author)", "organics"),
							"desc" => esc_html__("Quote title (author name)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Quote content", "organics"),
							"desc" => esc_html__("Quote content", "organics"),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => organics_shortcodes_width(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Reviews
				"trx_reviews" => array(
					"title" => esc_html__("Reviews", "organics"),
					"desc" => esc_html__("Insert reviews block in the single post", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => esc_html__("Align counter to left, center or right", "organics"),
							"divider" => true,
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						), 
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Search
				"trx_search" => array(
					"title" => esc_html__("Search", "organics"),
					"desc" => esc_html__("Show search form", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Style", "organics"),
							"desc" => esc_html__("Select style to display search field", "organics"),
							"value" => "regular",
							"options" => array(
								"regular" => __('Regular', 'organics'),
								"rounded" => __('Rounded', 'organics')
							),
							"type" => "checklist"
						),
						"state" => array(
							"title" => esc_html__("State", "organics"),
							"desc" => esc_html__("Select search field initial state", "organics"),
							"value" => "fixed",
							"options" => array(
								"fixed"  => __('Fixed',  'organics'),
								"opened" => __('Opened', 'organics'),
								"closed" => __('Closed', 'organics')
							),
							"type" => "checklist"
						),
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Title (placeholder) for the search field", "organics"),
							"value" => __("Search &hellip;", 'organics'),
							"type" => "text"
						),
						"ajax" => array(
							"title" => esc_html__("AJAX", "organics"),
							"desc" => esc_html__("Search via AJAX or reload page", "organics"),
							"value" => "yes",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Section
				"trx_section" => array(
					"title" => esc_html__("Section container", "organics"),
					"desc" => esc_html__("Container for any block with desired class and style", "organics"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"dedicated" => array(
							"title" => esc_html__("Dedicated", "organics"),
							"desc" => esc_html__("Use this block as dedicated content - show it before post title on single page", "organics"),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Align", "organics"),
							"desc" => esc_html__("Select block alignment", "organics"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"columns" => array(
							"title" => esc_html__("Columns emulation", "organics"),
							"desc" => esc_html__("Select width for columns emulation", "organics"),
							"value" => "none",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['columns']
						), 
						"pan" => array(
							"title" => esc_html__("Use pan effect", "organics"),
							"desc" => esc_html__("Use pan effect to show section content", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "organics"),
							"desc" => esc_html__("Use scroller to show section content", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"scroll_dir" => array(
							"title" => esc_html__("Scroll and Pan direction", "organics"),
							"desc" => esc_html__("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", "organics"),
							"dependency" => array(
								'pan' => array('yes'),
								'scroll' => array('yes')
							),
							"value" => "horizontal",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['dir']
						),
						"scroll_controls" => array(
							"title" => esc_html__("Scroll controls", "organics"),
							"desc" => esc_html__("Show scroll controls (if Use scroller = yes)", "organics"),
							"dependency" => array(
								'scroll' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => esc_html__("Select color scheme for this block", "organics"),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"color" => array(
							"title" => esc_html__("Fore color", "organics"),
							"desc" => esc_html__("Any color for objects in this section", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "organics"),
							"desc" => esc_html__("Any background color for this section", "organics"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_tile" => array(
							"title" => esc_html__("Tile background image", "organics"),
							"desc" => esc_html__("Do you want tile background image or image cover whole block?", "organics"),
							"value" => "no",
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "organics"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "organics"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "organics"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "organics"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_padding" => array(
							"title" => esc_html__("Paddings around content", "organics"),
							"desc" => esc_html__("Add paddings around content in this section (only if bg_color or bg_image enabled).", "organics"),
							"value" => "yes",
							"dependency" => array(
								'compare' => 'or',
								'bg_color' => array('not_empty'),
								'bg_texture' => array('not_empty'),
								'bg_image' => array('not_empty')
							),
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"font_size" => array(
							"title" => esc_html__("Font size", "organics"),
							"desc" => esc_html__("Font size of the text (default - in pixels, allows any CSS units of measure)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "organics"),
							"desc" => esc_html__("Font weight of the text", "organics"),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'100' => __('Thin (100)', 'organics'),
								'300' => __('Light (300)', 'organics'),
								'400' => __('Normal (400)', 'organics'),
								'700' => __('Bold (700)', 'organics')
							)
						),
						"_content_" => array(
							"title" => esc_html__("Container content", "organics"),
							"desc" => esc_html__("Content for section container", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Skills
				"trx_skills" => array(
					"title" => esc_html__("Skills", "organics"),
					"desc" => esc_html__("Insert skills diagramm in your page (post)", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"max_value" => array(
							"title" => esc_html__("Max value", "organics"),
							"desc" => esc_html__("Max value for skills items", "organics"),
							"value" => 100,
							"min" => 1,
							"type" => "spinner"
						),
						"type" => array(
							"title" => esc_html__("Skills type", "organics"),
							"desc" => esc_html__("Select type of skills block", "organics"),
							"value" => "bar",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'bar' => __('Bar', 'organics'),
								'pie' => __('Pie chart', 'organics'),
								'counter' => __('Counter', 'organics'),
								'arc' => __('Arc', 'organics')
							)
						), 
						"layout" => array(
							"title" => esc_html__("Skills layout", "organics"),
							"desc" => esc_html__("Select layout of skills block", "organics"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "rows",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'rows' => __('Rows', 'organics'),
								'columns' => __('Columns', 'organics')
							)
						),
						"dir" => array(
							"title" => esc_html__("Direction", "organics"),
							"desc" => esc_html__("Select direction of skills block", "organics"),
							"dependency" => array(
								'type' => array('counter','pie','bar')
							),
							"value" => "horizontal",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['dir']
						), 
						"style" => array(
							"title" => esc_html__("Counters style", "organics"),
							"desc" => esc_html__("Select style of skills items (only for type=counter)", "organics"),
							"dependency" => array(
								'type' => array('counter')
							),
							"value" => 1,
							"options" => organics_get_list_styles(1, 4),
							"type" => "checklist"
						), 
						// "columns" - autodetect, not set manual
						"color" => array(
							"title" => esc_html__("Skills items color", "organics"),
							"desc" => esc_html__("Color for all skills items", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "color"
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "organics"),
							"desc" => esc_html__("Background color for all skills items (only for type=pie)", "organics"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"border_color" => array(
							"title" => esc_html__("Border color", "organics"),
							"desc" => esc_html__("Border color for all skills items (only for type=pie)", "organics"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "",
							"type" => "color"
						),
						"align" => array(
							"title" => esc_html__("Align skills block", "organics"),
							"desc" => esc_html__("Align skills block to left or right side", "organics"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['float']
						), 
						"arc_caption" => array(
							"title" => esc_html__("Arc Caption", "organics"),
							"desc" => esc_html__("Arc caption - text in the center of the diagram", "organics"),
							"dependency" => array(
								'type' => array('arc')
							),
							"value" => "",
							"type" => "text"
						),
						"pie_compact" => array(
							"title" => esc_html__("Pie compact", "organics"),
							"desc" => esc_html__("Show all skills in one diagram or as separate diagrams", "organics"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"pie_cutout" => array(
							"title" => esc_html__("Pie cutout", "organics"),
							"desc" => esc_html__("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "organics"),
							"dependency" => array(
								'type' => array('pie')
							),
							"value" => 0,
							"min" => 0,
							"max" => 99,
							"type" => "spinner"
						),
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Title for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "organics"),
							"desc" => esc_html__("Subtitle for the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "organics"),
							"desc" => esc_html__("Short description for the block", "organics"),
							"value" => "",
							"type" => "textarea"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "organics"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "organics"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "organics"),
							"value" => "",
							"type" => "text"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_skills_item",
						"title" => esc_html__("Skill", "organics"),
						"desc" => esc_html__("Skills item", "organics"),
						"container" => false,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", "organics"),
								"desc" => esc_html__("Current skills item title", "organics"),
								"value" => "",
								"type" => "text"
							),
							"value" => array(
								"title" => esc_html__("Value", "organics"),
								"desc" => esc_html__("Current skills level", "organics"),
								"value" => 50,
								"min" => 0,
								"step" => 1,
								"type" => "spinner"
							),
							"color" => array(
								"title" => esc_html__("Color", "organics"),
								"desc" => esc_html__("Current skills item color", "organics"),
								"value" => "",
								"type" => "color"
							),
							"bg_color" => array(
								"title" => esc_html__("Background color", "organics"),
								"desc" => esc_html__("Current skills item background color (only for type=pie)", "organics"),
								"value" => "",
								"type" => "color"
							),
							"border_color" => array(
								"title" => esc_html__("Border color", "organics"),
								"desc" => esc_html__("Current skills item border color (only for type=pie)", "organics"),
								"value" => "",
								"type" => "color"
							),
							"style" => array(
								"title" => esc_html__("Counter style", "organics"),
								"desc" => esc_html__("Select style for the current skills item (only for type=counter)", "organics"),
								"value" => 1,
								"options" => organics_get_list_styles(1, 4),
								"type" => "checklist"
							), 
							"icon" => array(
								"title" => esc_html__("Counter icon",  'organics'),
								"desc" => esc_html__('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'organics'),
								"value" => "",
								"type" => "icons",
								"options" => $ORGANICS_GLOBALS['sc_params']['icons']
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Slider
				"trx_slider" => array(
					"title" => esc_html__("Slider", "organics"),
					"desc" => esc_html__("Insert slider into your post (page)", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array_merge(array(
						"engine" => array(
							"title" => esc_html__("Slider engine", "organics"),
							"desc" => esc_html__("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", "organics"),
							"value" => "swiper",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['sliders']
						),
						"align" => array(
							"title" => esc_html__("Float slider", "organics"),
							"desc" => esc_html__("Float slider to left or right side", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['float']
						),
						"custom" => array(
							"title" => esc_html__("Custom slides", "organics"),
							"desc" => esc_html__("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						)
						),
						organics_exists_revslider() ? array(
						"alias" => array(
							"title" => esc_html__("Revolution slider alias", "organics"),
							"desc" => esc_html__("Select Revolution slider to display", "organics"),
							"dependency" => array(
								'engine' => array('revo')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['revo_sliders']
						)) : array(), array(
						"cat" => array(
							"title" => esc_html__("Swiper: Category list", "organics"),
							"desc" => esc_html__("Select category to show post's images. If empty - select posts from any category or from IDs list", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => organics_array_merge(array(0 => __('- Select category -', 'organics')), $ORGANICS_GLOBALS['sc_params']['categories'])
						),
						"count" => array(
							"title" => esc_html__("Swiper: Number of posts", "organics"),
							"desc" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Swiper: Offset before select posts", "organics"),
							"desc" => esc_html__("Skip posts before select next part.", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Swiper: Post order by", "organics"),
							"desc" => esc_html__("Select desired posts sorting method", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "date",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Swiper: Post order", "organics"),
							"desc" => esc_html__("Select desired posts order", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Swiper: Post IDs list", "organics"),
							"desc" => esc_html__("Comma separated list of posts ID. If set - parameters above are ignored!", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "",
							"type" => "text"
						),
						"controls" => array(
							"title" => esc_html__("Swiper: Show slider controls", "organics"),
							"desc" => esc_html__("Show arrows inside slider", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"pagination" => array(
							"title" => esc_html__("Swiper: Show slider pagination", "organics"),
							"desc" => esc_html__("Show bullets for switch slides", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								'no'   => __('None', 'organics'),
								'yes'  => __('Dots', 'organics'), 
								'full' => __('Side Titles', 'organics'),
								'over' => __('Over Titles', 'organics')
							)
						),
						"titles" => array(
							"title" => esc_html__("Swiper: Show titles section", "organics"),
							"desc" => esc_html__("Show section with post's title and short post's description", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"options" => array(
								"no"    => __('Not show', 'organics'),
								"slide" => __('Show/Hide info', 'organics'),
								"fixed" => __('Fixed info', 'organics')
							)
						),
						"descriptions" => array(
							"title" => esc_html__("Swiper: Post descriptions", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"desc" => esc_html__("Show post's excerpt max length (characters)", "organics"),
							"value" => 0,
							"min" => 0,
							"max" => 1000,
							"step" => 10,
							"type" => "spinner"
						),
						"links" => array(
							"title" => esc_html__("Swiper: Post's title as link", "organics"),
							"desc" => esc_html__("Make links from post's titles", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"crop" => array(
							"title" => esc_html__("Swiper: Crop images", "organics"),
							"desc" => esc_html__("Crop images in each slide or live it unchanged", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"autoheight" => array(
							"title" => esc_html__("Swiper: Autoheight", "organics"),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"slides_per_view" => array(
							"title" => esc_html__("Swiper: Slides per view", "organics"),
							"desc" => esc_html__("Slides per view showed in this slider", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slides_space" => array(
							"title" => esc_html__("Swiper: Space between slides", "organics"),
							"desc" => esc_html__("Size of space (in px) between slides", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Swiper: Slides change interval", "organics"),
							"desc" => esc_html__("Slides change interval (in milliseconds: 1000ms = 1s)", "organics"),
							"dependency" => array(
								'engine' => array('swiper')
							),
							"value" => 5000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)),
					"children" => array(
						"name" => "trx_slider_item",
						"title" => esc_html__("Slide", "organics"),
						"desc" => esc_html__("Slider item", "organics"),
						"container" => false,
						"params" => array(
							"src" => array(
								"title" => esc_html__("URL (source) for image file", "organics"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the current slide", "organics"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
				// Socials
				"trx_socials" => array(
					"title" => esc_html__("Social icons", "organics"),
					"desc" => esc_html__("List of social icons (with hovers)", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"type" => array(
							"title" => esc_html__("Icon's type", "organics"),
							"desc" => esc_html__("Type of the icons - images or font icons", "organics"),
							"value" => organics_get_theme_setting('socials_type'),
							"options" => array(
								'icons' => __('Icons', 'organics'),
								'images' => __('Images', 'organics')
							),
							"type" => "checklist"
						), 
						"size" => array(
							"title" => esc_html__("Icon's size", "organics"),
							"desc" => esc_html__("Size of the icons", "organics"),
							"value" => "small",
							"options" => $ORGANICS_GLOBALS['sc_params']['sizes'],
							"type" => "checklist"
						), 
						"shape" => array(
							"title" => esc_html__("Icon's shape", "organics"),
							"desc" => esc_html__("Shape of the icons", "organics"),
							"value" => "square",
							"options" => $ORGANICS_GLOBALS['sc_params']['shapes'],
							"type" => "checklist"
						), 
						"socials" => array(
							"title" => esc_html__("Manual socials list", "organics"),
							"desc" => esc_html__("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"custom" => array(
							"title" => esc_html__("Custom socials", "organics"),
							"desc" => esc_html__("Make custom icons from inner shortcodes (prepare it on tabs)", "organics"),
							"divider" => true,
							"value" => "no",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no'],
							"type" => "switch"
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_social_item",
						"title" => esc_html__("Custom social item", "organics"),
						"desc" => esc_html__("Custom social item: name, profile url and icon url", "organics"),
						"decorate" => false,
						"container" => false,
						"params" => array(
							"name" => array(
								"title" => esc_html__("Social name", "organics"),
								"desc" => esc_html__("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "organics"),
								"value" => "",
								"type" => "text"
							),
							"url" => array(
								"title" => esc_html__("Your profile URL", "organics"),
								"desc" => esc_html__("URL of your profile in specified social network", "organics"),
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("URL (source) for icon file", "organics"),
								"desc" => esc_html__("Select or upload image or write URL from other site for the current social icon", "organics"),
								"readonly" => false,
								"value" => "",
								"type" => "media"
							)
						)
					)
				),
			
			
			
			
				// Table
				"trx_table" => array(
					"title" => esc_html__("Table", "organics"),
					"desc" => esc_html__("Insert a table into post (page). ", "organics"),
					"decorate" => true,
					"container" => true,
					"params" => array(
						"align" => array(
							"title" => esc_html__("Content alignment", "organics"),
							"desc" => esc_html__("Select alignment for each table cell", "organics"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"_content_" => array(
							"title" => esc_html__("Table content", "organics"),
							"desc" => esc_html__("Content, created with any table-generator", "organics"),
							"divider" => true,
							"rows" => 8,
							"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
							"type" => "textarea"
						),
						"width" => organics_shortcodes_width(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Tabs
				"trx_tabs" => array(
					"title" => esc_html__("Tabs", "organics"),
					"desc" => esc_html__("Insert tabs in your page (post)", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Tabs style", "organics"),
							"desc" => esc_html__("Select style for tabs items", "organics"),
							"value" => 1,
							"options" => organics_get_list_styles(1, 2),
							"type" => "radio"
						),
						"initial" => array(
							"title" => esc_html__("Initially opened tab", "organics"),
							"desc" => esc_html__("Number of initially opened tab", "organics"),
							"divider" => true,
							"value" => 1,
							"min" => 0,
							"type" => "spinner"
						),
						"scroll" => array(
							"title" => esc_html__("Use scroller", "organics"),
							"desc" => esc_html__("Use scroller to show tab content (height parameter required)", "organics"),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_tab",
						"title" => esc_html__("Tab", "organics"),
						"desc" => esc_html__("Tab item", "organics"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Tab title", "organics"),
								"desc" => esc_html__("Current tab title", "organics"),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Tab content", "organics"),
								"desc" => esc_html__("Current tab content", "organics"),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				),
			


				
			
			
				// Title
				"trx_title" => array(
					"title" => esc_html__("Title", "organics"),
					"desc" => esc_html__("Create header tag (1-6 level) with many styles", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"_content_" => array(
							"title" => esc_html__("Title content", "organics"),
							"desc" => wp_kses( __("Title content", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"type" => array(
							"title" => esc_html__("Title type", "organics"),
							"desc" => wp_kses( __("Title type (header level)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "1",
							"type" => "select",
							"options" => array(
								'1' => esc_html__('Header 1', 'organics'),
								'2' => esc_html__('Header 2', 'organics'),
								'3' => esc_html__('Header 3', 'organics'),
								'4' => esc_html__('Header 4', 'organics'),
								'5' => esc_html__('Header 5', 'organics'),
								'6' => esc_html__('Header 6', 'organics'),
							)
						),
						"style" => array(
							"title" => esc_html__("Title style", "organics"),
							"desc" => wp_kses( __("Title style", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "regular",
							"type" => "select",
							"options" => array(
								'regular' => esc_html__('Regular', 'organics'),
								'underline' => esc_html__('Underline', 'organics'),
								'divider' => esc_html__('Divider', 'organics'),
								'iconed' => esc_html__('With icon (image)', 'organics')
							)
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => wp_kses( __("Title text alignment", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						), 
						"font_size" => array(
							"title" => esc_html__("Font_size", "organics"),
							"desc" => wp_kses( __("Custom font size. If empty - use theme default", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"font_weight" => array(
							"title" => esc_html__("Font weight", "organics"),
							"desc" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "select",
							"size" => "medium",
							"options" => array(
								'inherit' => esc_html__('Default', 'organics'),
								'100' => esc_html__('Thin (100)', 'organics'),
								'300' => esc_html__('Light (300)', 'organics'),
								'400' => esc_html__('Normal (400)', 'organics'),
								'600' => esc_html__('Semibold (600)', 'organics'),
								'700' => esc_html__('Bold (700)', 'organics'),
								'900' => esc_html__('Black (900)', 'organics')
							)
						),
						"color" => array(
							"title" => esc_html__("Title color", "organics"),
							"desc" => wp_kses( __("Select color for the title", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "color"
						),
						"icon" => array(
							"title" => esc_html__('Title font icon',  'organics'),
							"desc" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"image" => array(
							"title" => esc_html__('or image icon',  'organics'),
							"desc" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)",  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "",
							"type" => "images",
							"size" => "small",
							"options" => $ORGANICS_GLOBALS['sc_params']['images']
						),
						"picture" => array(
							"title" => esc_html__('or URL for image file', "organics"),
							"desc" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'style' => array('iconed')
							),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"image_size" => array(
							"title" => esc_html__('Image (picture) size', "organics"),
							"desc" => wp_kses( __("Select image (picture) size (if style='iconed')", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "small",
							"type" => "checklist",
							"options" => array(
								'small' => esc_html__('Small', 'organics'),
								'medium' => esc_html__('Medium', 'organics'),
								'large' => esc_html__('Large', 'organics')
							)
						),
						"position" => array(
							"title" => esc_html__('Icon (image) position', "organics"),
							"desc" => wp_kses( __("Select icon (image) position (if style=iconed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'style' => array('iconed')
							),
							"value" => "left",
							"type" => "checklist",
							"options" => array(
								'top' => esc_html__('Top', 'organics'),
								'left' => esc_html__('Left', 'organics')
							)
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
			
				// Toggles
				"trx_toggles" => array(
					"title" => esc_html__("Toggles", "organics"),
					"desc" => esc_html__("Toggles items", "organics"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"style" => array(
							"title" => esc_html__("Toggles style", "organics"),
							"desc" => esc_html__("Select style for display toggles", "organics"),
							"value" => 1,
							"options" => organics_get_list_styles(1, 2),
							"type" => "radio"
						),
						"counter" => array(
							"title" => esc_html__("Counter", "organics"),
							"desc" => esc_html__("Display counter before each toggles title", "organics"),
							"value" => "off",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['on_off']
						),
						"icon_closed" => array(
							"title" => esc_html__("Icon while closed",  'organics'),
							"desc" => esc_html__('Select icon for the closed toggles item from Fontello icons set',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"icon_opened" => array(
							"title" => esc_html__("Icon while opened",  'organics'),
							"desc" => esc_html__('Select icon for the opened toggles item from Fontello icons set',  'organics'),
							"value" => "",
							"type" => "icons",
							"options" => $ORGANICS_GLOBALS['sc_params']['icons']
						),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_toggles_item",
						"title" => esc_html__("Toggles item", "organics"),
						"desc" => esc_html__("Toggles item", "organics"),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Toggles item title", "organics"),
								"desc" => esc_html__("Title for current toggles item", "organics"),
								"value" => "",
								"type" => "text"
							),
							"open" => array(
								"title" => esc_html__("Open on show", "organics"),
								"desc" => esc_html__("Open current toggles item on show", "organics"),
								"value" => "no",
								"type" => "switch",
								"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
							),
							"icon_closed" => array(
								"title" => esc_html__("Icon while closed",  'organics'),
								"desc" => esc_html__('Select icon for the closed toggles item from Fontello icons set',  'organics'),
								"value" => "",
								"type" => "icons",
								"options" => $ORGANICS_GLOBALS['sc_params']['icons']
							),
							"icon_opened" => array(
								"title" => esc_html__("Icon while opened",  'organics'),
								"desc" => esc_html__('Select icon for the opened toggles item from Fontello icons set',  'organics'),
								"value" => "",
								"type" => "icons",
								"options" => $ORGANICS_GLOBALS['sc_params']['icons']
							),
							"_content_" => array(
								"title" => esc_html__("Toggles item content", "organics"),
								"desc" => esc_html__("Current toggles item content", "organics"),
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $ORGANICS_GLOBALS['sc_params']['id'],
							"class" => $ORGANICS_GLOBALS['sc_params']['class'],
							"css" => $ORGANICS_GLOBALS['sc_params']['css']
						)
					)
				),
			
			
			
			
			
				// Tooltip
				"trx_tooltip" => array(
					"title" => esc_html__("Tooltip", "organics"),
					"desc" => esc_html__("Create tooltip for selected text", "organics"),
					"decorate" => false,
					"container" => true,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => esc_html__("Tooltip title (required)", "organics"),
							"value" => "",
							"type" => "text"
						),
						"_content_" => array(
							"title" => esc_html__("Tipped content", "organics"),
							"desc" => esc_html__("Highlighted content with tooltip", "organics"),
							"divider" => true,
							"rows" => 4,
							"value" => "",
							"type" => "textarea"
						),
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Twitter
				"trx_twitter" => array(
					"title" => esc_html__("Twitter", "organics"),
					"desc" => esc_html__("Insert twitter feed into post (page)", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"user" => array(
							"title" => esc_html__("Twitter Username", "organics"),
							"desc" => esc_html__("Your username in the twitter account. If empty - get it from Theme Options.", "organics"),
							"value" => "",
							"type" => "text"
						),
						"consumer_key" => array(
							"title" => esc_html__("Consumer Key", "organics"),
							"desc" => esc_html__("Consumer Key from the twitter account", "organics"),
							"value" => "",
							"type" => "text"
						),
						"consumer_secret" => array(
							"title" => esc_html__("Consumer Secret", "organics"),
							"desc" => esc_html__("Consumer Secret from the twitter account", "organics"),
							"value" => "",
							"type" => "text"
						),
						"token_key" => array(
							"title" => esc_html__("Token Key", "organics"),
							"desc" => esc_html__("Token Key from the twitter account", "organics"),
							"value" => "",
							"type" => "text"
						),
						"token_secret" => array(
							"title" => esc_html__("Token Secret", "organics"),
							"desc" => esc_html__("Token Secret from the twitter account", "organics"),
							"value" => "",
							"type" => "text"
						),
						"count" => array(
							"title" => esc_html__("Tweets number", "organics"),
							"desc" => esc_html__("Tweets number to show", "organics"),
							"divider" => true,
							"value" => 3,
							"max" => 20,
							"min" => 1,
							"type" => "spinner"
						),
						"controls" => array(
							"title" => esc_html__("Show arrows", "organics"),
							"desc" => esc_html__("Show control buttons", "organics"),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"interval" => array(
							"title" => esc_html__("Tweets change interval", "organics"),
							"desc" => esc_html__("Tweets change interval (in milliseconds: 1000ms = 1s)", "organics"),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => esc_html__("Alignment of the tweets block", "organics"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "organics"),
							"desc" => esc_html__("Change whole slider's height (make it equal current slide's height)", "organics"),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => esc_html__("Select color scheme for this block", "organics"),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "organics"),
							"desc" => esc_html__("Any background color for this section", "organics"),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for the background", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "organics"),
							"desc" => esc_html__("Overlay color opacity (from 0.0 to 1.0)", "organics"),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "organics"),
							"desc" => esc_html__("Predefined texture style from 1 to 11. 0 - without texture.", "organics"),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
				// Video
				"trx_video" => array(
					"title" => esc_html__("Video", "organics"),
					"desc" => esc_html__("Insert video player", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"url" => array(
							"title" => esc_html__("URL for video file", "organics"),
							"desc" => esc_html__("Select video from media library or paste URL for video file from other site", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media",
							"before" => array(
								'title' => __('Choose video', 'organics'),
								'action' => 'media_upload',
								'type' => 'video',
								'multiple' => false,
								'linked_field' => '',
								'captions' => array( 	
									'choose' => __('Choose video file', 'organics'),
									'update' => __('Select video file', 'organics')
								)
							),
							"after" => array(
								'icon' => 'icon-cancel',
								'action' => 'media_reset'
							)
						),
						"ratio" => array(
							"title" => esc_html__("Ratio", "organics"),
							"desc" => esc_html__("Ratio of the video", "organics"),
							"value" => "16:9",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								"16:9" => __("16:9", 'organics'),
								"4:3" => __("4:3", 'organics')
							)
						),
						"autoplay" => array(
							"title" => esc_html__("Autoplay video", "organics"),
							"desc" => esc_html__("Autoplay video on page load", "organics"),
							"value" => "off",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['on_off']
						),
						"align" => array(
							"title" => esc_html__("Align", "organics"),
							"desc" => esc_html__("Select block alignment", "organics"),
							"value" => "none",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"image" => array(
							"title" => esc_html__("Cover image", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for video preview", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "organics"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => esc_html__("Top offset", "organics"),
							"desc" => esc_html__("Top offset (padding) inside background image to video block (in percent). For example: 3%", "organics"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => esc_html__("Bottom offset", "organics"),
							"desc" => esc_html__("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", "organics"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => esc_html__("Left offset", "organics"),
							"desc" => esc_html__("Left offset (padding) inside background image to video block (in percent). For example: 20%", "organics"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => esc_html__("Right offset", "organics"),
							"desc" => esc_html__("Right offset (padding) inside background image to video block (in percent). For example: 12%", "organics"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				),
			
			
			
			
				// Zoom
				"trx_zoom" => array(
					"title" => esc_html__("Zoom", "organics"),
					"desc" => esc_html__("Insert the image with zoom/lens effect", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"effect" => array(
							"title" => esc_html__("Effect", "organics"),
							"desc" => esc_html__("Select effect to display overlapping image", "organics"),
							"value" => "lens",
							"size" => "medium",
							"type" => "switch",
							"options" => array(
								"lens" => __('Lens', 'organics'),
								"zoom" => __('Zoom', 'organics')
							)
						),
						"url" => array(
							"title" => esc_html__("Main image", "organics"),
							"desc" => esc_html__("Select or upload main image", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"over" => array(
							"title" => esc_html__("Overlaping image", "organics"),
							"desc" => esc_html__("Select or upload overlaping image", "organics"),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"align" => array(
							"title" => esc_html__("Float zoom", "organics"),
							"desc" => esc_html__("Float zoom to left or right side", "organics"),
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['float']
						), 
						"bg_image" => array(
							"title" => esc_html__("Background image", "organics"),
							"desc" => esc_html__("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", "organics"),
							"divider" => true,
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_top" => array(
							"title" => esc_html__("Top offset", "organics"),
							"desc" => esc_html__("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", "organics"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_bottom" => array(
							"title" => esc_html__("Bottom offset", "organics"),
							"desc" => esc_html__("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", "organics"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_left" => array(
							"title" => esc_html__("Left offset", "organics"),
							"desc" => esc_html__("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", "organics"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"bg_right" => array(
							"title" => esc_html__("Right offset", "organics"),
							"desc" => esc_html__("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", "organics"),
							"dependency" => array(
								'bg_image' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => organics_shortcodes_width(),
						"height" => organics_shortcodes_height(),
						"top" => $ORGANICS_GLOBALS['sc_params']['top'],
						"bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
						"left" => $ORGANICS_GLOBALS['sc_params']['left'],
						"right" => $ORGANICS_GLOBALS['sc_params']['right'],
						"id" => $ORGANICS_GLOBALS['sc_params']['id'],
						"class" => $ORGANICS_GLOBALS['sc_params']['class'],
						"animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
						"css" => $ORGANICS_GLOBALS['sc_params']['css']
					)
				)
			);
	
			// Woocommerce Shortcodes list
			//------------------------------------------------------------------
			if (organics_exists_woocommerce()) {
				
				// WooCommerce - Cart
				$ORGANICS_GLOBALS['shortcodes']["woocommerce_cart"] = array(
					"title" => esc_html__("Woocommerce: Cart", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show Cart page", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Checkout
				$ORGANICS_GLOBALS['shortcodes']["woocommerce_checkout"] = array(
					"title" => esc_html__("Woocommerce: Checkout", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show Checkout page", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - My Account
				$ORGANICS_GLOBALS['shortcodes']["woocommerce_my_account"] = array(
					"title" => esc_html__("Woocommerce: My Account", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show My Account page", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Order Tracking
				$ORGANICS_GLOBALS['shortcodes']["woocommerce_order_tracking"] = array(
					"title" => esc_html__("Woocommerce: Order Tracking", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show Order Tracking page", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Shop Messages
				$ORGANICS_GLOBALS['shortcodes']["shop_messages"] = array(
					"title" => esc_html__("Woocommerce: Shop Messages", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show shop messages", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array()
				);
				
				// WooCommerce - Product Page
				$ORGANICS_GLOBALS['shortcodes']["product_page"] = array(
					"title" => esc_html__("Woocommerce: Product Page", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: display single product page", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => esc_html__("SKU", "organics"),
							"desc" => esc_html__("SKU code of displayed product", "organics"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => esc_html__("ID", "organics"),
							"desc" => esc_html__("ID of displayed product", "organics"),
							"value" => "",
							"type" => "text"
						),
						"posts_per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => "1",
							"min" => 1,
							"type" => "spinner"
						),
						"post_type" => array(
							"title" => esc_html__("Post type", "organics"),
							"desc" => esc_html__("Post type for the WP query (leave 'product')", "organics"),
							"value" => "product",
							"type" => "text"
						),
						"post_status" => array(
							"title" => esc_html__("Post status", "organics"),
							"desc" => esc_html__("Display posts only with this status", "organics"),
							"value" => "publish",
							"type" => "select",
							"options" => array(
								"publish" => __('Publish', 'organics'),
								"protected" => __('Protected', 'organics'),
								"private" => __('Private', 'organics'),
								"pending" => __('Pending', 'organics'),
								"draft" => __('Draft', 'organics')
							)
						)
					)
				);
				
				// WooCommerce - Product
				$ORGANICS_GLOBALS['shortcodes']["product"] = array(
					"title" => esc_html__("Woocommerce: Product", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: display one product", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"sku" => array(
							"title" => esc_html__("SKU", "organics"),
							"desc" => esc_html__("SKU code of displayed product", "organics"),
							"value" => "",
							"type" => "text"
						),
						"id" => array(
							"title" => esc_html__("ID", "organics"),
							"desc" => esc_html__("ID of displayed product", "organics"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Best Selling Products
				$ORGANICS_GLOBALS['shortcodes']["best_selling_products"] = array(
					"title" => esc_html__("Woocommerce: Best Selling Products", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show best selling products", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						)
					)
				);
				
				// WooCommerce - Recent Products
				$ORGANICS_GLOBALS['shortcodes']["recent_products"] = array(
					"title" => esc_html__("Woocommerce: Recent Products", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show recent products", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Related Products
				$ORGANICS_GLOBALS['shortcodes']["related_products"] = array(
					"title" => esc_html__("Woocommerce: Related Products", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show related products", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"posts_per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						)
					)
				);
				
				// WooCommerce - Featured Products
				$ORGANICS_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Featured Products", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show featured products", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Top Rated Products
				$ORGANICS_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Top Rated Products", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show top rated products", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Sale Products
				$ORGANICS_GLOBALS['shortcodes']["featured_products"] = array(
					"title" => esc_html__("Woocommerce: Sale Products", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: list products on sale", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product Category
				$ORGANICS_GLOBALS['shortcodes']["product_category"] = array(
					"title" => esc_html__("Woocommerce: Products from category", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: list products in specified category(-ies)", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"category" => array(
							"title" => esc_html__("Categories", "organics"),
							"desc" => esc_html__("Comma separated category slugs", "organics"),
							"value" => '',
							"type" => "text"
						),
						"operator" => array(
							"title" => esc_html__("Operator", "organics"),
							"desc" => esc_html__("Categories operator", "organics"),
							"value" => "IN",
							"type" => "checklist",
							"size" => "medium",
							"options" => array(
								"IN" => __('IN', 'organics'),
								"NOT IN" => __('NOT IN', 'organics'),
								"AND" => __('AND', 'organics')
							)
						)
					)
				);
				
				// WooCommerce - Products
				$ORGANICS_GLOBALS['shortcodes']["products"] = array(
					"title" => esc_html__("Woocommerce: Products", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: list all products", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"skus" => array(
							"title" => esc_html__("SKUs", "organics"),
							"desc" => esc_html__("Comma separated SKU codes of products", "organics"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => esc_html__("IDs", "organics"),
							"desc" => esc_html__("Comma separated ID of products", "organics"),
							"value" => "",
							"type" => "text"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						)
					)
				);
				
				// WooCommerce - Product attribute
				$ORGANICS_GLOBALS['shortcodes']["product_attribute"] = array(
					"title" => esc_html__("Woocommerce: Products by Attribute", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show products with specified attribute", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"per_page" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many products showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for products output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"attribute" => array(
							"title" => esc_html__("Attribute", "organics"),
							"desc" => esc_html__("Attribute name", "organics"),
							"value" => "",
							"type" => "text"
						),
						"filter" => array(
							"title" => esc_html__("Filter", "organics"),
							"desc" => esc_html__("Attribute value", "organics"),
							"value" => "",
							"type" => "text"
						)
					)
				);
				
				// WooCommerce - Products Categories
				$ORGANICS_GLOBALS['shortcodes']["product_categories"] = array(
					"title" => esc_html__("Woocommerce: Product Categories", "organics"),
					"desc" => esc_html__("WooCommerce shortcode: show categories with products", "organics"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"number" => array(
							"title" => esc_html__("Number", "organics"),
							"desc" => esc_html__("How many categories showed", "organics"),
							"value" => 4,
							"min" => 1,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => esc_html__("How many columns per row use for categories output", "organics"),
							"value" => 4,
							"min" => 2,
							"max" => 4,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Order by", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "date",
							"type" => "select",
							"options" => array(
								"date" => __('Date', 'organics'),
								"title" => esc_html__('Title', 'organics')
							)
						),
						"order" => array(
							"title" => esc_html__("Order", "organics"),
							"desc" => esc_html__("Sorting order for products output", "organics"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"parent" => array(
							"title" => esc_html__("Parent", "organics"),
							"desc" => esc_html__("Parent category slug", "organics"),
							"value" => "",
							"type" => "text"
						),
						"ids" => array(
							"title" => esc_html__("IDs", "organics"),
							"desc" => esc_html__("Comma separated ID of products", "organics"),
							"value" => "",
							"type" => "text"
						),
						"hide_empty" => array(
							"title" => esc_html__("Hide empty", "organics"),
							"desc" => esc_html__("Hide empty categories", "organics"),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						)
					)
				);

			}
			
			do_action('organics_action_shortcodes_list');

		}
	}
}
?>