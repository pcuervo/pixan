<?php
/**
 * AxiomThemes Framework: Services post type settings
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Theme init
if (!function_exists('organics_services_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_services_theme_setup' );
	function organics_services_theme_setup() {

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('organics_filter_get_blog_type',			'organics_services_get_blog_type', 9, 2);
		add_filter('organics_filter_get_blog_title',		'organics_services_get_blog_title', 9, 2);
		add_filter('organics_filter_get_current_taxonomy',	'organics_services_get_current_taxonomy', 9, 2);
		add_filter('organics_filter_is_taxonomy',			'organics_services_is_taxonomy', 9, 2);
		add_filter('organics_filter_get_stream_page_title',	'organics_services_get_stream_page_title', 9, 2);
		add_filter('organics_filter_get_stream_page_link',	'organics_services_get_stream_page_link', 9, 2);
		add_filter('organics_filter_get_stream_page_id',	'organics_services_get_stream_page_id', 9, 2);
		add_filter('organics_filter_query_add_filters',		'organics_services_query_add_filters', 9, 2);
		add_filter('organics_filter_detect_inheritance_key','organics_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (organics_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'organics_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'organics_post_fill_options_column', 9, 2);
		}

		// Add shortcodes [trx_services] and [trx_services_item]
		add_action('organics_action_shortcodes_list',		'organics_services_reg_shortcodes');
		add_action('organics_action_shortcodes_list_vc',	'organics_services_reg_shortcodes_vc');

		if (function_exists('organics_require_data')) {
			// Prepare type "Team"
			organics_require_data( 'post_type', 'services', array(
				'label'               => esc_html__( 'Service item', 'organics' ),
				'description'         => esc_html__( 'Service Description', 'organics' ),
				'labels'              => array(
					'name'                => esc_html__( 'Services', 'organics' ),
					'singular_name'       => esc_html__( 'Service item', 'organics' ),
					'menu_name'           => esc_html__( 'Services', 'organics' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'organics' ),
					'all_items'           => esc_html__( 'All Services', 'organics' ),
					'view_item'           => esc_html__( 'View Item', 'organics' ),
					'add_new_item'        => esc_html__( 'Add New Service', 'organics' ),
					'add_new'             => esc_html__( 'Add New', 'organics' ),
					'edit_item'           => esc_html__( 'Edit Item', 'organics' ),
					'update_item'         => esc_html__( 'Update Item', 'organics' ),
					'search_items'        => esc_html__( 'Search Item', 'organics' ),
					'not_found'           => esc_html__( 'Not found', 'organics' ),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'organics' ),
				),
				'supports'            => array( 'title', 'excerpt', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields'),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-info',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => '52.2',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'query_var'           => true,
				'capability_type'     => 'page',
				'rewrite'             => true
				)
			);

			// Prepare taxonomy for team
			organics_require_data( 'taxonomy', 'services_group', array(
				'post_type'			=> array( 'services' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html__( 'Services Group', 'organics' ),
					'singular_name'     => esc_html__( 'Group', 'organics' ),
					'search_items'      => esc_html__( 'Search Groups', 'organics' ),
					'all_items'         => esc_html__( 'All Groups', 'organics' ),
					'parent_item'       => esc_html__( 'Parent Group', 'organics' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'organics' ),
					'edit_item'         => esc_html__( 'Edit Group', 'organics' ),
					'update_item'       => esc_html__( 'Update Group', 'organics' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'organics' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'organics' ),
					'menu_name'         => esc_html__( 'Services Group', 'organics' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'services_group' ),
				)
			);
		}
	}
}

if ( !function_exists( 'organics_services_settings_theme_setup2' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_services_settings_theme_setup2', 3 );
	function organics_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		organics_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-service',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'page'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'organics_is_services_page' ) ) {
	function organics_is_services_page() {
		global $ORGANICS_GLOBALS;
		$is = in_array($ORGANICS_GLOBALS['page_template'], array('blog-services', 'single-service'));
		if (!$is) {
			if (!empty($ORGANICS_GLOBALS['pre_query']))
				$is = $ORGANICS_GLOBALS['pre_query']->get('post_type')=='services'
						|| $ORGANICS_GLOBALS['pre_query']->is_tax('services_group')
						|| ($ORGANICS_GLOBALS['pre_query']->is_page()
								&& ($id=organics_get_template_page_id('blog-services')) > 0
								&& $id==(isset($ORGANICS_GLOBALS['pre_query']->queried_object_id)
											? $ORGANICS_GLOBALS['pre_query']->queried_object_id
											: 0)
						);
			else
				$is = get_query_var('post_type')=='services'
						|| is_tax('services_group')
						|| (is_page() && ($id=organics_get_template_page_id('blog-services')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'organics_services_detect_inheritance_key' ) ) {
	//add_filter('organics_filter_detect_inheritance_key',	'organics_services_detect_inheritance_key', 9, 1);
	function organics_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return organics_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'organics_services_get_blog_type' ) ) {
	//add_filter('organics_filter_get_blog_type',	'organics_services_get_blog_type', 9, 2);
	function organics_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'organics_services_get_blog_title' ) ) {
	//add_filter('organics_filter_get_blog_title',	'organics_services_get_blog_title', 9, 2);
	function organics_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( organics_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = organics_get_post_title();
			} else {
				$title = esc_html__('All services', 'organics');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'organics_services_get_stream_page_title' ) ) {
	//add_filter('organics_filter_get_stream_page_title',	'organics_services_get_stream_page_title', 9, 2);
	function organics_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (organics_strpos($page, 'services')!==false) {
			if (($page_id = organics_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = organics_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'organics');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'organics_services_get_stream_page_id' ) ) {
	//add_filter('organics_filter_get_stream_page_id',	'organics_services_get_stream_page_id', 9, 2);
	function organics_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (organics_strpos($page, 'services')!==false) $id = organics_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'organics_services_get_stream_page_link' ) ) {
	//add_filter('organics_filter_get_stream_page_link',	'organics_services_get_stream_page_link', 9, 2);
	function organics_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (organics_strpos($page, 'services')!==false) {
			$id = organics_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'organics_services_get_current_taxonomy' ) ) {
	//add_filter('organics_filter_get_current_taxonomy',	'organics_services_get_current_taxonomy', 9, 2);
	function organics_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( organics_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'organics_services_is_taxonomy' ) ) {
	//add_filter('organics_filter_is_taxonomy',	'organics_services_is_taxonomy', 9, 2);
	function organics_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'organics_services_query_add_filters' ) ) {
	//add_filter('organics_filter_query_add_filters',	'organics_services_query_add_filters', 9, 2);
	function organics_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

/*
[trx_services id="unique_id" columns="4" count="4" style="services-1|services-2|..." title="Block title" subtitle="xxx" description="xxxxxx"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
[/trx_services]
*/
if ( !function_exists( 'organics_sc_services' ) ) {
	function organics_sc_services($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => esc_html__('Learn more', 'organics'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'organics'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && organics_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$ms = organics_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = organics_get_css_position_from_values('', '', '', '', $width);
		$hs = organics_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (organics_param_is_off($custom) && $count < $columns) $columns = $count;

		if (organics_param_is_on($slider)) organics_enqueue_slider('swiper');

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_services_id'] = $id;
		$ORGANICS_GLOBALS['sc_services_style'] = $style;
		$ORGANICS_GLOBALS['sc_services_columns'] = $columns;
		$ORGANICS_GLOBALS['sc_services_counter'] = 0;
		$ORGANICS_GLOBALS['sc_services_slider'] = $slider;
		$ORGANICS_GLOBALS['sc_services_css_wh'] = $ws . $hs;
		$ORGANICS_GLOBALS['sc_services_readmore'] = $readmore;

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
						. ' class="sc_services_wrap'
						. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(organics_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(organics_get_slider_controls_classes($controls))
							. (organics_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (organics_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
						. (!empty($width) && organics_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && organics_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
                        . ' data-slides-min-width="250"'
						. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
					. (organics_param_is_on($slider)
						? '<div class="slides swiper-wrapper">'
						: ($columns > 1
							? '<div class="sc_columns columns_wrap">'
							: '')
						);

		$content = do_shortcode($content);

		if (organics_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;

			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}

			$args = array(
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);

			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}

			$args = organics_query_add_sort_order($args, $orderby, $order);
			$args = organics_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
			$query = new WP_Query( $args );

			$post_number = 0;

			while ( $query->have_posts() ) {
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => organics_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= organics_show_post_layout($args);
			}
			wp_reset_postdata();
		}

		if (organics_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.organics_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';

		// Add template specific scripts and styles
		do_action('organics_action_blog_scripts', $style);

		return apply_filters('organics_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_services', 'organics_sc_services');
}


if ( !function_exists( 'organics_sc_services_item' ) ) {
	function organics_sc_services_item($atts, $content=null) {
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_services_counter']++;

		$id = $id ? $id : ($ORGANICS_GLOBALS['sc_services_id'] ? $ORGANICS_GLOBALS['sc_services_id'] . '_' . $ORGANICS_GLOBALS['sc_services_counter'] : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? $ORGANICS_GLOBALS['sc_services_readmore'] : $readmore;

		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = organics_get_thumb_sizes(array('layout' => $ORGANICS_GLOBALS['sc_services_style']));
			$image = organics_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => $ORGANICS_GLOBALS['sc_services_style'],
			'number' => $ORGANICS_GLOBALS['sc_services_counter'],
			'columns_count' => $ORGANICS_GLOBALS['sc_services_columns'],
			'slider' => $ORGANICS_GLOBALS['sc_services_slider'],
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $ORGANICS_GLOBALS['sc_services_css_wh']
		);
		$output = organics_show_post_layout($args, $post_data);
		return apply_filters('organics_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_services_item', 'organics_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('organics_services_reg_shortcodes')) {
	//add_filter('organics_action_shortcodes_list',	'organics_services_reg_shortcodes');
	function organics_services_reg_shortcodes() {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['shortcodes'])) {

			$services_groups = organics_get_list_terms(false, 'services_group');
			$services_styles = organics_get_list_templates('services');
			$controls 		 = organics_get_list_slider_controls();

			organics_array_insert_after($ORGANICS_GLOBALS['shortcodes'], 'trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", "organics"),
					"desc" => wp_kses( __("Insert services list in your page (post)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "organics"),
							"desc" => wp_kses( __("Title for the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "organics"),
							"desc" => wp_kses( __("Subtitle for the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "organics"),
							"desc" => wp_kses( __("Short description for the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Services style", "organics"),
							"desc" => wp_kses( __("Select style to display services list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"type" => array(
							"title" => esc_html__("Icon's type", "organics"),
							"desc" => wp_kses( __("Select type of icons: font icon or image", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'organics'),
								'images' => esc_html__('Images', 'organics')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => wp_kses( __("How many columns use to show services list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => wp_kses( __("Select color scheme for this block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"slider" => array(
							"title" => esc_html__("Slider", "organics"),
							"desc" => wp_kses( __("Use slider to show services", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Controls", "organics"),
							"desc" => wp_kses( __("Slider controls style and position", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", "organics"),
							"desc" => wp_kses( __("Size of space (in px) between slides", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", "organics"),
							"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "organics"),
							"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "organics"),
							"desc" => wp_kses( __("Alignment of the services block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "organics"),
							"desc" => wp_kses( __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "organics"),
							"desc" => wp_kses( __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => organics_array_merge(array(0 => esc_html__('- Select category -', 'organics')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "organics"),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "organics"),
							"desc" => wp_kses( __("Skip posts before select next part.", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "organics"),
							"desc" => wp_kses( __("Select desired posts sorting method", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "organics"),
							"desc" => wp_kses( __("Select desired posts order", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $ORGANICS_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "organics"),
							"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"readmore" => array(
							"title" => esc_html__("Read more", "organics"),
							"desc" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "organics"),
							"desc" => wp_kses( __("Link URL for the button at the bottom of the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "organics"),
							"desc" => wp_kses( __("Caption for the button at the bottom of the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
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
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", "organics"),
						"desc" => wp_kses( __("Service item", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", "organics"),
								"desc" => wp_kses( __("Item's title", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon",  'organics'),
								"desc" => wp_kses( __('Select icon for the item from Fontello icons set',  'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "icons",
								"options" => $ORGANICS_GLOBALS['sc_params']['icons']
							),
							"image" => array(
								"title" => esc_html__("Item's image", "organics"),
								"desc" => wp_kses( __("Item's image (if icon not selected)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", "organics"),
								"desc" => wp_kses( __("Link on service's item page", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", "organics"),
								"desc" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", "organics"),
								"desc" => wp_kses( __("Item's short description", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
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
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('organics_services_reg_shortcodes_vc')) {
	//add_filter('organics_action_shortcodes_list_vc',	'organics_services_reg_shortcodes_vc');
	function organics_services_reg_shortcodes_vc() {
		global $ORGANICS_GLOBALS;

		$services_groups = organics_get_list_terms(false, 'services_group');
		$services_styles = organics_get_list_templates('services');
		$controls		 = organics_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => esc_html__("Services", "organics"),
				"description" => wp_kses( __("Insert services list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
				"category" => esc_html__('Content', 'js_composer'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Services style", "organics"),
						"description" => wp_kses( __("Select style to display services list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", "organics"),
						"description" => wp_kses( __("Select type of icons: font icon or image", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Icons', 'organics') => 'icons',
							esc_html__('Images', 'organics') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => wp_kses( __("Select color scheme for this block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", "organics"),
						"description" => wp_kses( __("Use slider to show services", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'organics'),
						"class" => "",
						"std" => "no",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", "organics"),
						"description" => wp_kses( __("Slider controls style and position", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'organics'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", "organics"),
						"description" => wp_kses( __("Size of space (in px) between slides", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'organics'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", "organics"),
						"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Slider', 'organics'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "organics"),
						"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Slider', 'organics'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "organics"),
						"description" => wp_kses( __("Alignment of the services block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "organics"),
						"description" => wp_kses( __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => wp_kses( __("Title for the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "organics"),
						"description" => wp_kses( __("Subtitle for the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "organics"),
						"description" => wp_kses( __("Description for the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "organics"),
						"description" => wp_kses( __("Select category to show services. If empty - select services from any category (group) or from IDs list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(organics_array_merge(array(0 => esc_html__('- Select category -', 'organics')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses( __("How many columns use to show services list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "organics"),
						"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Query', 'organics'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "organics"),
						"description" => wp_kses( __("Skip posts before select next part.", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "organics"),
						"description" => wp_kses( __("Select desired posts sorting method", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "organics"),
						"description" => wp_kses( __("Select desired posts order", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Service's IDs list", "organics"),
						"description" => wp_kses( __("Comma separated list of service's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", "organics"),
						"description" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "organics"),
						"description" => wp_kses( __("Link URL for the button at the bottom of the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "organics"),
						"description" => wp_kses( __("Caption for the button at the bottom of the block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Captions', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					organics_vc_width(),
					organics_vc_height(),
					$ORGANICS_GLOBALS['vc_params']['margin_top'],
					$ORGANICS_GLOBALS['vc_params']['margin_bottom'],
					$ORGANICS_GLOBALS['vc_params']['margin_left'],
					$ORGANICS_GLOBALS['vc_params']['margin_right'],
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'organics' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'organics' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'organics' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'organics' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );


		vc_map( array(
				"base" => "trx_services_item",
				"name" => esc_html__("Services item", "organics"),
				"description" => wp_kses( __("Custom services item - all data pull out from shortcode parameters", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "organics"),
						"description" => wp_kses( __("Item's title", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", "organics"),
						"description" => wp_kses( __("Select icon for the item from Fontello icons set", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => $ORGANICS_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", "organics"),
						"description" => wp_kses( __("Item's image (if icon is empty)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "organics"),
						"description" => wp_kses( __("Link on item's page", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", "organics"),
						"description" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['animation'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );

		class WPBakeryShortCode_Trx_Services extends ORGANICS_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends ORGANICS_VC_ShortCodeCollection {}

	}
}
?>