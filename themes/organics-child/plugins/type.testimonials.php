<?php
/**
 * AxiomThemes Framework: Testimonial post type settings
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Theme init
if (!function_exists('organics_testimonial_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_testimonial_theme_setup' );
	function organics_testimonial_theme_setup() {

		// Add item in the admin menu
		add_action('admin_menu',			'organics_testimonial_add_meta_box');

		// Save data from meta box
		add_action('save_post',				'organics_testimonial_save_data');

		// Add shortcodes [trx_testimonials] and [trx_testimonials_item]
		add_action('organics_action_shortcodes_list',		'organics_testimonials_reg_shortcodes');
		add_action('organics_action_shortcodes_list_vc',	'organics_testimonials_reg_shortcodes_vc');

		// Meta box fields
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['testimonial_meta_box'] = array(
			'id' => 'testimonial-meta-box',
			'title' => esc_html__('Testimonial Details', 'organics'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'organics'),
					"desc" => wp_kses( __("Name of the testimonial's author", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'organics'),
					"desc" => wp_kses( __("Position of the testimonial's author", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'organics'),
					"desc" => wp_kses( __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'organics'),
					"desc" => wp_kses( __("URL of the testimonial source or author profile page", 'organics'), $ORGANICS_GLOBALS['allowed_tags'] ),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
			)
		);

		if (function_exists('organics_require_data')) {
			// Prepare type "Testimonial"
			organics_require_data( 'post_type', 'testimonial', array(
				'label'               => esc_html__( 'Testimonial', 'organics' ),
				'description'         => esc_html__( 'Testimonial Description', 'organics' ),
				'labels'              => array(
					'name'                => esc_html__( 'Testimonials', 'organics' ),
					'singular_name'       => esc_html__( 'Testimonial', 'organics' ),
					'menu_name'           => esc_html__( 'Testimonials', 'organics' ),
					'parent_item_colon'   => esc_html__( 'Parent Item:', 'organics' ),
					'all_items'           => esc_html__( 'All Testimonials', 'organics' ),
					'view_item'           => esc_html__( 'View Item', 'organics' ),
					'add_new_item'        => esc_html__( 'Add New Testimonial', 'organics' ),
					'add_new'             => esc_html__( 'Add New', 'organics' ),
					'edit_item'           => esc_html__( 'Edit Item', 'organics' ),
					'update_item'         => esc_html__( 'Update Item', 'organics' ),
					'search_items'        => esc_html__( 'Search Item', 'organics' ),
					'not_found'           => esc_html__( 'Not found', 'organics' ),
					'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'organics' ),
				),
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'menu_icon'			  => 'dashicons-cloud',
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => '52.4',
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
				)
			);

			// Prepare taxonomy for testimonial
			organics_require_data( 'taxonomy', 'testimonial_group', array(
				'post_type'			=> array( 'testimonial' ),
				'hierarchical'      => true,
				'labels'            => array(
					'name'              => esc_html__( 'Testimonials Group', 'organics' ),
					'singular_name'     => esc_html__( 'Group', 'organics' ),
					'search_items'      => esc_html__( 'Search Groups', 'organics' ),
					'all_items'         => esc_html__( 'All Groups', 'organics' ),
					'parent_item'       => esc_html__( 'Parent Group', 'organics' ),
					'parent_item_colon' => esc_html__( 'Parent Group:', 'organics' ),
					'edit_item'         => esc_html__( 'Edit Group', 'organics' ),
					'update_item'       => esc_html__( 'Update Group', 'organics' ),
					'add_new_item'      => esc_html__( 'Add New Group', 'organics' ),
					'new_item_name'     => esc_html__( 'New Group Name', 'organics' ),
					'menu_name'         => esc_html__( 'Testimonial Group', 'organics' ),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'testimonial_group' ),
				)
			);
		}
	}
}


// Add meta box
if (!function_exists('organics_testimonial_add_meta_box')) {
	//add_action('admin_menu', 'organics_testimonial_add_meta_box');
	function organics_testimonial_add_meta_box() {
		global $ORGANICS_GLOBALS;
		$mb = $ORGANICS_GLOBALS['testimonial_meta_box'];
		add_meta_box($mb['id'], $mb['title'], 'organics_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('organics_testimonial_show_meta_box')) {
	function organics_testimonial_show_meta_box() {
		global $post, $ORGANICS_GLOBALS;

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_testimonial_nonce" value="', esc_attr($ORGANICS_GLOBALS['admin_nonce']), '" />';

		$data = get_post_meta($post->ID, 'testimonial_data', true);

		$fields = $ORGANICS_GLOBALS['testimonial_meta_box']['fields'];
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('organics_testimonial_save_data')) {
	//add_action('save_post', 'organics_testimonial_save_data');
	function organics_testimonial_save_data($post_id) {
		global $ORGANICS_GLOBALS;
		// verify nonce
		if (!isset($_POST['meta_box_testimonial_nonce']) || !wp_verify_nonce($_POST['meta_box_testimonial_nonce'], $ORGANICS_GLOBALS['admin_url'])) {
			return $post_id;
		}

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = $ORGANICS_GLOBALS['testimonial_meta_box']['fields'];

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
				if (isset($_POST[$id]))
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, 'testimonial_data', $data);
	}
}






// ---------------------------------- [trx_testimonials] ---------------------------------------

/*
[trx_testimonials id="unique_id" style="1|2|3"]
	[trx_testimonials_item user="user_login"]Testimonials text[/trx_testimonials_item]
	[trx_testimonials_item email="" name="" position="" photo="photo_url"]Testimonials text[/trx_testimonials]
[/trx_testimonials]
*/

if (!function_exists('organics_sc_testimonials')) {
	function organics_sc_testimonials($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "testimonials-4",
			"columns" => 3,
			"slider" => "yes",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
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

		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && organics_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}

		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = organics_get_scheme_color('bg');
			$rgb = organics_hex2rgb($bg_color);
		}

		$ms = organics_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = organics_get_css_position_from_values('', '', '', '', $width);
		$hs = organics_get_css_position_from_values('', '', '', '', '', $height);
		$css .= ($ms) . ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (organics_param_is_off($custom) && $count < $columns) $columns = $count;

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_testimonials_id'] = $id;
		$ORGANICS_GLOBALS['sc_testimonials_style'] = $style;
		$ORGANICS_GLOBALS['sc_testimonials_columns'] = $columns;
		$ORGANICS_GLOBALS['sc_testimonials_counter'] = 0;
		$ORGANICS_GLOBALS['sc_testimonials_slider'] = $slider;
		$ORGANICS_GLOBALS['sc_testimonials_css_wh'] = $ws . $hs;

		if (organics_param_is_on($slider)) organics_enqueue_slider('swiper');

		$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || organics_strlen($bg_texture)>2 || ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme))
					? '<div class="sc_testimonials_wrap sc_section'
							. ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '')
							. '"'
						.' style="'
							. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
							. '"'
						. (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
						. '>'
						. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
								. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
									. (organics_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
									. '"'
									. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
									. '>'
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
 					. ' ' . esc_attr(organics_get_template_property($style, 'container_classes'))
 					. (organics_param_is_on($slider)
						? ' sc_slider_swiper swiper-slider-container'
							. ' ' . esc_attr(organics_get_slider_controls_classes($controls))
							//. ' sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom'
							. (organics_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
							. ($hs ? ' sc_slider_height_fixed' : '')
						: '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. '"'
				. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
				. (!empty($width) && organics_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && organics_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
                . ' data-slides-min-width="250"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
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
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);

			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}

			$args = organics_query_add_sort_order($args, $orderby, $order);
			$args = organics_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');

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
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = organics_get_post_data($args);
				$post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
				$post_meta = get_post_meta($post_data['post_id'], 'testimonial_data', true);
				$thumb_sizes = organics_get_thumb_sizes(array('layout' => $style));
				$args['author'] = $post_meta['testimonial_author'];
				$args['position'] = $post_meta['testimonial_position'];
				$args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';	//$post_data['post_link'];
				$args['email'] = $post_meta['testimonial_email'];
				$args['photo'] = $post_data['post_thumb'];
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*min(2, max(1, organics_get_theme_option("retina_ready"))));
				$output .= organics_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}

		if (organics_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || organics_strlen($bg_texture)>2
						?  '</div></div>'
						: '');

		// Add template specific scripts and styles
		do_action('organics_action_blog_scripts', $style);

		return apply_filters('organics_shortcode_output', $output, 'trx_testimonials', $atts, $content);
	}
	if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_testimonials', 'organics_sc_testimonials');
}


if (!function_exists('organics_sc_testimonials_item')) {
	function organics_sc_testimonials_item($atts, $content=null){
		if (organics_in_shortcode_blogger()) return '';
		extract(organics_html_decode(shortcode_atts(array(
			// Individual params
			"author" => "",
			"position" => "",
			"link" => "",
			"photo" => "",
			"email" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
		), $atts)));

		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_testimonials_counter']++;

		$id = $id ? $id : ($ORGANICS_GLOBALS['sc_testimonials_id'] ? $ORGANICS_GLOBALS['sc_testimonials_id'] . '_' . $ORGANICS_GLOBALS['sc_testimonials_counter'] : '');

		$thumb_sizes = organics_get_thumb_sizes(array('layout' => $ORGANICS_GLOBALS['sc_testimonials_style']));

		if (empty($photo)) {
			if (!empty($email))
				$photo = get_avatar($email, $thumb_sizes['w']*min(2, max(1, organics_get_theme_option("retina_ready"))));
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = organics_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_content' => do_shortcode($content)
		);
		$args = array(
			'layout' => $ORGANICS_GLOBALS['sc_testimonials_style'],
			'number' => $ORGANICS_GLOBALS['sc_testimonials_counter'],
			'columns_count' => $ORGANICS_GLOBALS['sc_testimonials_columns'],
			'slider' => $ORGANICS_GLOBALS['sc_testimonials_slider'],
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => '',
			'tag_css' => $css,
			'tag_css_wh' => $ORGANICS_GLOBALS['sc_testimonials_css_wh'],
			'author' => $author,
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo
		);
		$output = organics_show_post_layout($args, $post_data);

		return apply_filters('organics_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
	}
	if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_testimonials_item', 'organics_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------



// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('organics_testimonials_reg_shortcodes')) {
	//add_filter('organics_action_shortcodes_list',	'organics_testimonials_reg_shortcodes');
	function organics_testimonials_reg_shortcodes() {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['shortcodes'])) {

			$testimonials_groups = organics_get_list_terms(false, 'testimonial_group');
			$testimonials_styles = organics_get_list_templates('testimonials');
			$controls = organics_get_list_slider_controls();

			organics_array_insert_before($ORGANICS_GLOBALS['shortcodes'], 'trx_title', array(

				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", "organics"),
					"desc" => wp_kses( __("Insert testimonials into post (page)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
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
							"title" => esc_html__("Testimonials style", "organics"),
							"desc" => wp_kses( __("Select style to display testimonials", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "testimonials-1",
							"type" => "select",
							"options" => $testimonials_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", "organics"),
							"desc" => wp_kses( __("How many columns use to show testimonials", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slider" => array(
							"title" => esc_html__("Slider", "organics"),
							"desc" => wp_kses( __("Use slider to show testimonials", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "yes",
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
							"desc" => wp_kses( __("Alignment of the testimonials block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $ORGANICS_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "organics"),
							"desc" => wp_kses( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "organics"),
							"desc" => wp_kses( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => organics_array_merge(array(0 => esc_html__('- Select category -', 'organics')), $testimonials_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "organics"),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
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
							"value" => "date",
							"type" => "select",
							"options" => $ORGANICS_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "organics"),
							"desc" => wp_kses( __("Select desired posts order", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
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
						"scheme" => array(
							"title" => esc_html__("Color scheme", "organics"),
							"desc" => wp_kses( __("Select color scheme for this block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $ORGANICS_GLOBALS['sc_params']['schemes']
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", "organics"),
							"desc" => wp_kses( __("Any background color for this section", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", "organics"),
							"desc" => wp_kses( __("Select or upload image or write URL from other site for the background", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", "organics"),
							"desc" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", "organics"),
							"desc" => wp_kses( __("Predefined texture style from 1 to 11. 0 - without texture.", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
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
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", "organics"),
						"desc" => wp_kses( __("Testimonials item (custom parameters)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", "organics"),
								"desc" => wp_kses( __("Name of the testimonmials author", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", "organics"),
								"desc" => wp_kses( __("Link URL to the testimonmials author page", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", "organics"),
								"desc" => wp_kses( __("E-mail of the testimonmials author (to get gravatar)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", "organics"),
								"desc" => wp_kses( __("Select or upload photo of testimonmials author or write URL of photo from other site", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", "organics"),
								"desc" => wp_kses( __("Current testimonials text", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
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
				)

			));
		}
	}
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('organics_testimonials_reg_shortcodes_vc')) {
	//add_filter('organics_action_shortcodes_list_vc',	'organics_testimonials_reg_shortcodes_vc');
	function organics_testimonials_reg_shortcodes_vc() {
		global $ORGANICS_GLOBALS;

		$testimonials_groups = organics_get_list_terms(false, 'testimonial_group');
		$testimonials_styles = organics_get_list_templates('testimonials');
		$controls			 = organics_get_list_slider_controls();

		// Testimonials
		vc_map( array(
				"base" => "trx_testimonials",
				"name" => esc_html__("Testimonials", "organics"),
				"description" => wp_kses( __("Insert testimonials slider", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
				"category" => esc_html__('Content', 'js_composer'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_collection trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Testimonials style", "organics"),
						"description" => wp_kses( __("Select style to display testimonials", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($testimonials_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", "organics"),
						"description" => wp_kses( __("Use slider to show testimonials", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'organics'),
						"class" => "",
						"std" => "yes",
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
						"description" => wp_kses( __("Alignment of the testimonials block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "organics"),
						"description" => wp_kses( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
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
						"description" => wp_kses( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(organics_array_merge(array(0 => esc_html__('- Select category -', 'organics')), $testimonials_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "organics"),
						"description" => wp_kses( __("How many columns use to show testimonials", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "organics"),
						"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'organics'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
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
						"heading" => esc_html__("Post IDs list", "organics"),
						"description" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
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
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "organics"),
						"description" => wp_kses( __("Select color scheme for this block", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => array_flip($ORGANICS_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", "organics"),
						"description" => wp_kses( __("Any background color for this section", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", "organics"),
						"description" => wp_kses( __("Select background image from library for this section", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", "organics"),
						"description" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'organics'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", "organics"),
						"description" => wp_kses( __("Texture style from 1 to 11. Empty or 0 - without texture.", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Colors and Images', 'organics'),
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
				)
		) );


		vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => esc_html__("Testimonial", "organics"),
				"description" => wp_kses( __("Single testimonials item", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", "organics"),
						"description" => wp_kses( __("Name of the testimonmials author", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "organics"),
						"description" => wp_kses( __("Link URL to the testimonmials author page", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", "organics"),
						"description" => wp_kses( __("E-mail of the testimonmials author", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Photo", "organics"),
						"description" => wp_kses( __("Select or upload photo of testimonmials author or write URL of photo from other site", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Testimonials text", "organics"),
						"description" => wp_kses( __("Current testimonials text", "organics"), $ORGANICS_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					$ORGANICS_GLOBALS['vc_params']['id'],
					$ORGANICS_GLOBALS['vc_params']['class'],
					$ORGANICS_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
		) );

		class WPBakeryShortCode_Trx_Testimonials extends ORGANICS_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Testimonials_Item extends ORGANICS_VC_ShortCodeCollection {}

	}
}
?>