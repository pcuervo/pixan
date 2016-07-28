<?php
/**
 * Organics Shortcodes
*/
// [trx_blogger]
// [trx_button]
// [trx_image]
// [trx_search]
// [trx_services]
// [trx_axiomthemes_..._products]



// [trx_blogger] ---------------------------------------

/*
[trx_blogger id="unique_id" ids="comma_separated_list" cat="id|slug" orderby="date|views|comments" order="asc|desc" count="5" descr="0" dir="horizontal|vertical" style="regular|date|image_large|image_medium|image_small|accordion|list" border="0"]
*/
if (!function_exists('organics_blogger_busy_set')) {
	function organics_blogger_busy_set() {
		global $ORGANICS_GLOBALS;
		$ORGANICS_GLOBALS['sc_blogger_busy'] = false;
	}
}
organics_blogger_busy_set();

if (!function_exists('organics_sc_blogger')) {
    function organics_sc_blogger($atts, $content=null){
        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            // Individual params
            "style" => "accordion-1",
            "filters" => "no",
            "post_type" => "post",
            "ids" => "",
            "cat" => "",
            "count" => "3",
            "columns" => "",
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "only" => "no",
            "descr" => "",
            "readmore" => "",
            "loadmore" => "no",
            "location" => "default",
            "dir" => "horizontal",
            "hover" => organics_get_theme_option('hover_style'),
            "hover_dir" => organics_get_theme_option('hover_dir'),
            "scroll" => "no",
            "controls" => "no",
            "rating" => "no",
            "info" => "yes",
            "links" => "yes",
            "date_format" => "",
            "title" => "",
            "subtitle" => "",
            "description" => "",
            "link" => '',
            "link_caption" => __('Learn more', 'organics'),
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "animation" => "",
            "width" => "",
            "height" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));

        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
        $width  = organics_prepare_css_value($width);
        $height = organics_prepare_css_value($height);

        global $post, $ORGANICS_GLOBALS;

        $ORGANICS_GLOBALS['sc_blogger_busy'] = true;
        $ORGANICS_GLOBALS['sc_blogger_counter'] = 0;

        if (empty($id)) $id = "sc_blogger_".str_replace('.', '', mt_rand());

        if ($style=='date' && empty($date_format)) $date_format = 'd.m+Y';

        if (!empty($ids)) {
            $posts = explode(',', str_replace(' ', '', $ids));
            $count = count($posts);
        }

        if ($descr == '') $descr = organics_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : ''));

        if (!organics_param_is_off($scroll)) {
            organics_enqueue_slider();
            if (empty($id)) $id = 'sc_blogger_'.str_replace('.', '', mt_rand());
        }

        $class = apply_filters('organics_filter_blog_class',
            'sc_blogger'
            . ' layout_'.esc_attr($style)
            . ' template_'.esc_attr(organics_get_template_name($style))
            . (!empty($class) ? ' '.esc_attr($class) : '')
            . ' ' . esc_attr(organics_get_template_property($style, 'container_classes'))
            . ' sc_blogger_' . ($dir=='vertical' ? 'vertical' : 'horizontal')
            . (organics_param_is_on($scroll) && organics_param_is_on($controls) ? ' sc_scroll_controls sc_scroll_controls_type_top sc_scroll_controls_'.esc_attr($dir) : '')
            . ($descr == 0 ? ' no_description' : ''),
            array('style'=>$style, 'dir'=>$dir, 'descr'=>$descr)
        );

        $container = apply_filters('organics_filter_blog_container', organics_get_template_property($style, 'container'), array('style'=>$style, 'dir'=>$dir));
        $container_start = $container_end = '';
        if (!empty($container)) {
            $container = explode('%s', $container);
            $container_start = !empty($container[0]) ? $container[0] : '';
            $container_end = !empty($container[1]) ? $container[1] : '';
        }

        $output = '<div'
            . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="'.($style=='list' ? 'sc_list sc_list_style_iconed ' : '') . esc_attr($class).'"'
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
            . '>'
            . ($container_start)
            . (!empty($subtitle) ? '<h6 class="sc_blogger_subtitle sc_item_subtitle">' . trim(organics_strmacros($subtitle)) . '</h6>' : '')
            . (!empty($title) ? '<h2 class="sc_blogger_title sc_item_title">' . trim(organics_strmacros($title)) . '</h2>' : '')
            . (!empty($description) ? '<div class="sc_blogger_descr sc_item_descr">' . trim(organics_strmacros($description)) . '</div>' : '')
            . ($style=='list' ? '<ul class="sc_list sc_list_style_iconed">' : '')
            . ($dir=='horizontal' && $columns > 1 && organics_get_template_property($style, 'need_columns') ? '<div class="columns_wrap">' : '')
            . (organics_param_is_on($scroll)
                ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($dir).' sc_slider_noresize swiper-slider-container scroll-container"'
                . ' style="'.($dir=='vertical' ? 'height:'.($height != '' ? $height : "230px").';' : 'width:'.($width != '' ? $width.';' : "100%;")).'"'
                . '>'
                . '<div class="sc_scroll_wrapper swiper-wrapper">'
                . '<div class="sc_scroll_slide swiper-slide">'
                : '');

        if (organics_get_template_property($style, 'need_isotope')) {
            if (!organics_param_is_off($filters))
                $output .= '<div class="isotope_filters"></div>';
            if ($columns<1) $columns = organics_substr($style, -1);
            $output .= '<div class="isotope_wrap" data-columns="'.max(1, min(12, $columns)).'">';
        }

        $args = array(
            'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
            'posts_per_page' => $count,
            'ignore_sticky_posts' => true,
            'order' => $order=='asc' ? 'asc' : 'desc',
            'orderby' => 'date',
        );

        if ($offset > 0 && empty($ids)) {
            $args['offset'] = $offset;
        }

        $args = organics_query_add_sort_order($args, $orderby, $order);
        if (!organics_param_is_off($only)) $args = organics_query_add_filters($args, $only);
        $args = organics_query_add_posts_and_cats($args, $ids, $post_type, $cat);

        $query = new WP_Query( $args );

        $flt_ids = array();

        while ( $query->have_posts() ) { $query->the_post();

            $ORGANICS_GLOBALS['sc_blogger_counter']++;

            $args = array(
                'layout' => $style,
                'show' => false,
                'number' => $ORGANICS_GLOBALS['sc_blogger_counter'],
                'add_view_more' => false,
                'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
                // Additional options to layout generator
                "location" => $location,
                "descr" => $descr,
                "readmore" => $readmore,
                "loadmore" => $loadmore,
                "reviews" => organics_param_is_on($rating),
                "dir" => $dir,
                "scroll" => organics_param_is_on($scroll),
                "info" => organics_param_is_on($info),
                "links" => organics_param_is_on($links),
                "orderby" => $orderby,
                "columns_count" => $columns,
                "date_format" => $date_format,
                // Get post data
                'strip_teaser' => false,
                'content' => organics_get_template_property($style, 'need_content'),
                'terms_list' => !organics_param_is_off($filters) || organics_get_template_property($style, 'need_terms'),
                'filters' => organics_param_is_off($filters) ? '' : $filters,
                'hover' => $hover,
                'hover_dir' => $hover_dir
            );
            $post_data = organics_get_post_data($args);
            $output .= organics_show_post_layout($args, $post_data);

            if (!organics_param_is_off($filters)) {
                if ($filters == 'tags') {			// Use tags as filter items
                    if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
                        foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
                            $flt_ids[$tag->term_id] = $tag->name;
                        }
                    }
                }
            }

        }

        wp_reset_postdata();

        // Close isotope wrapper
        if (organics_get_template_property($style, 'need_isotope'))
            $output .= '</div>';

        // Isotope filters list
        if (!organics_param_is_off($filters)) {
            $filters_list = '';
            if ($filters == 'categories') {			// Use categories as filter items
                $taxonomy = organics_get_taxonomy_categories_by_post_type($post_type);
                $portfolio_parent = $cat ? max(0, organics_get_parent_taxonomy_by_property($cat, 'show_filters', 'yes', true, $taxonomy)) : 0;
                $args2 = array(
                    'type'			=> $post_type,
                    'child_of'		=> $portfolio_parent,
                    'orderby'		=> 'name',
                    'order'			=> 'ASC',
                    'hide_empty'	=> 1,
                    'hierarchical'	=> 0,
                    'exclude'		=> '',
                    'include'		=> '',
                    'number'		=> '',
                    'taxonomy'		=> $taxonomy,
                    'pad_counts'	=> false
                );
                $portfolio_list = get_categories($args2);
                if (is_array($portfolio_list) && count($portfolio_list) > 0) {
                    $filters_list .= '<a href="#" data-filter="*" class="theme_button active">'.__('All', 'organics').'</a>';
                    foreach ($portfolio_list as $cat) {
                        $filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($cat->term_id).'" class="theme_button">'.($cat->name).'</a>';
                    }
                }
            } else {								// Use tags as filter items
                if (is_array($flt_ids) && count($flt_ids) > 0) {
                    $filters_list .= '<a href="#" data-filter="*" class="theme_button active">'.__('All', 'organics').'</a>';
                    foreach ($flt_ids as $flt_id=>$flt_name) {
                        $filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($flt_id).'" class="theme_button">'.($flt_name).'</a>';
                    }
                }
            }
            if ($filters_list) {
                $output .= '<script type="text/javascript">'
                    . 'jQuery(document).ready(function () {'
                    . 'jQuery("#'.esc_attr($id).' .isotope_filters").append("'.addslashes($filters_list).'");'
                    . '});'
                    . '</script>';
            }
        }
        $output	.= (organics_param_is_on($scroll)
                ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                . (!organics_param_is_off($controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                : '')
            . ($dir=='horizontal' && $columns > 1 && organics_get_template_property($style, 'need_columns') ? '</div>' : '')
            . ($style == 'list' ? '</ul>' : '')
            . (!empty($link) ? '<div class="sc_blogger_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" ]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
            . ($container_end)
            . '</div>';

        // Add template specific scripts and styles
        do_action('organics_action_blog_scripts', $style);

        $ORGANICS_GLOBALS['sc_blogger_busy'] = false;

        return apply_filters('organics_shortcode_output', $output, 'trx_blogger', $atts, $content);
    }
    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_blogger', 'organics_sc_blogger');
}
// ---------------------------------- [/trx_blogger] ---------------------------------------









// ---------------------------------- [trx_axiomthemes_recent_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_recent_products')) {
    function organics_sc_axiomthemes_recent_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
	     "width" => "",
            "height" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
                    . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_recent_products'
                    . ($class ? ' ' . esc_attr($class) : '')
                    . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
                    . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
                    . '"'
                    .'>'
                        . (organics_param_is_on($scroll)
                            ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                            . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                            . '>'
                            . '<div class="sc_scroll_wrapper swiper-wrapper">'
                            . '<div class="sc_scroll_slide swiper-slide">'
                            : '')

                        . do_shortcode('[recent_products per_page=" '. $per_page .' " columns="' . $columns .'" orderby="' . $orderby .'" order="' . $order .'"]')

                        . (organics_param_is_on($scroll)
                            ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                            . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                            : '')
                    . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_recent_products', $atts, $content);
    }

    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_axiomthemes_recent_products', 'organics_sc_axiomthemes_recent_products');

}
// ---------------------------------- [/trx_axiomthemes_recent_products] ---------------------------------------




// ---------------------------------- [trx_axiomthemes_featured_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_featured_products')) {
    function organics_sc_axiomthemes_featured_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
                    . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_featured_products'
                    . ($class ? ' ' . esc_attr($class) : '')
                    . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
                    . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
                    . '"'
                    .'>'
                        . (organics_param_is_on($scroll)
                            ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                            . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                            . '>'
                            . '<div class="sc_scroll_wrapper swiper-wrapper">'
                            . '<div class="sc_scroll_slide swiper-slide">'
                            : '')

                        . do_shortcode('[featured_products per_page=" '. $per_page .' " columns="' . $columns .'" orderby="' . $orderby .'" order="' . $order .'"]')

                        . (organics_param_is_on($scroll)
                            ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                            . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                            : '')
                    . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_featured_products', $atts, $content);
    }

    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_axiomthemes_featured_products', 'organics_sc_axiomthemes_featured_products');

}
// ---------------------------------- [/trx_axiomthemes_featured_products] ---------------------------------------




// ---------------------------------- [trx_axiomthemes_best_selling_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_best_selling_products')) {
    function organics_sc_axiomthemes_best_selling_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
                    . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_best_selling_products'
                    . ($class ? ' ' . esc_attr($class) : '')
                    . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
                    . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
                    . '"'
                    .'>'
                        . (organics_param_is_on($scroll)
                            ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                            . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                            . '>'
                            . '<div class="sc_scroll_wrapper swiper-wrapper">'
                            . '<div class="sc_scroll_slide swiper-slide">'
                            : '')

                        . do_shortcode('[best_selling_products per_page=" '. $per_page .' " columns="' . $columns .'" orderby="' . $orderby .'" order="' . $order .'"]')

                        . (organics_param_is_on($scroll)
                            ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                            . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                            : '')
                    . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_best_selling_products', $atts, $content);
    }

    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_axiomthemes_best_selling_products', 'organics_sc_axiomthemes_best_selling_products');

}
// ---------------------------------- [/trx_axiomthemes_best_selling_products] ---------------------------------------





// ---------------------------------- [trx_axiomthemes_sale_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_sale_products')) {
    function organics_sc_axiomthemes_sale_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
                    . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_sale_products'
                    . ($class ? ' ' . esc_attr($class) : '')
                    . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
                    . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
                    . '"'
                    .'>'
                        . (organics_param_is_on($scroll)
                            ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                            . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                            . '>'
                            . '<div class="sc_scroll_wrapper swiper-wrapper">'
                            . '<div class="sc_scroll_slide swiper-slide">'
                            : '')

                        . do_shortcode('[sale_products per_page=" '. $per_page .' " columns="' . $columns .'"]')

                        . (organics_param_is_on($scroll)
                            ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                            . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                            : '')
                    . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_sale_products', $atts, $content);
    }

    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_axiomthemes_sale_products', 'organics_sc_axiomthemes_sale_products');

}
// ---------------------------------- [/trx_axiomthemes_sale_products] ---------------------------------------





// ---------------------------------- [trx_axiomthemes_top_rated_products] ---------------------------------------
if (!function_exists('organics_sc_axiomthemes_top_rated_products')) {
    function organics_sc_axiomthemes_top_rated_products($atts, $content = null) {

        if (organics_in_shortcode_blogger(true)) return '';
        extract(organics_html_decode(shortcode_atts(array(
            "scroll" => "yes",
            "scroll_dir" => "horizontal",
            "scroll_controls" => "yes",
            "per_page" => "6",
            "columns" => "5",
            "offset" => "",
            "orderby" => "date",
            "order" => "desc",
            "only" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);

        if ((!organics_param_is_off($scroll) || !organics_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());

        if (!organics_param_is_off($scroll)) organics_enqueue_slider();


        $output =   '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
                    . ' class="sc_scroll_controls_type_top sc_slider_woocommerce sc_slider_top_rated_products'
                    . ($class ? ' ' . esc_attr($class) : '')
                    . (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '')
                    . (organics_param_is_on($scroll) && !organics_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
                    . '"'
                    .'>'
                        . (organics_param_is_on($scroll)
                            ? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
                            . ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
                            . '>'
                            . '<div class="sc_scroll_wrapper swiper-wrapper">'
                            . '<div class="sc_scroll_slide swiper-slide">'
                            : '')

                        . do_shortcode('[top_rated_products per_page=" '. $per_page .' " columns="' . $columns .'" orderby="' . $orderby .'" order="' . $order .'"]')

                        . (organics_param_is_on($scroll)
                            ? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
                            . (!organics_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
                            : '')
                    . '</div>';

        return apply_filters('organics_shortcode_output', $output, 'trx_axiomthemes_top_rated_products', $atts, $content);
    }

    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_axiomthemes_top_rated_products', 'organics_sc_axiomthemes_top_rated_products');

}
// ---------------------------------- [/trx_axiomthemes_top_rated_products] ---------------------------------------









// ---------------------------------- [trx_button] ---------------------------------------

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('organics_sc_button')) {
    function organics_sc_button($atts, $content=null){
        if (organics_in_shortcode_blogger()) return '';
        extract(organics_html_decode(shortcode_atts(array(
            // Individual params
            "type" => "round",
            "style" => "filled",
            "scheme" => "original",
            "size" => "small",
            "icon" => "",
            "color" => "",
            "bg_color" => "",
            "link" => "",
            "target" => "",
            "align" => "",
            "rel" => "",
            "popup" => "no",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "animation" => "",
            "width" => "",
            "height" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left, $width, $height)
            . ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
            . ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
        if (organics_param_is_on($popup)) organics_enqueue_popup('magnific');
        $output = '<a href="' . (empty($link) ? '#' : $link) . '"'
            . (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
            . (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
            . (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
            . ' class="sc_button sc_button_' . esc_attr($type)
            . ' sc_button_style_' . esc_attr($style)
            . ' sc_button_scheme_' . esc_attr($scheme)
            . ' sc_button_size_' . esc_attr($size)
            . ($align && $align!='none' ? ' align'.esc_attr($align) : '')
            . (!empty($class) ? ' '.esc_attr($class) : '')
            . ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '')
            . (organics_param_is_on($popup) ? ' sc_popup_link' : '')
            . '"'
            . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . '>'
            . do_shortcode($content)
            . '</a>';
        return apply_filters('organics_shortcode_output', $output, 'trx_button', $atts, $content);
    }
    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_button', 'organics_sc_button');
}
// ---------------------------------- [/trx_button] ---------------------------------------



// ---------------------------------- [trx_image] ---------------------------------------

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('organics_sc_image')) {
    function organics_sc_image($atts, $content=null){
        if (organics_in_shortcode_blogger()) return '';
        extract(organics_html_decode(shortcode_atts(array(
            // Individual params
            "title" => "",
            "align" => "",
            "shape" => "square",
            "src" => "",
            "url" => "",
            "icon" => "",
            "link" => "",
            // Common params
            "id" => "",
            "class" => "",
            "animation" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => "",
            "width" => "",
            "height" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values('!'.($top), '!'.($right), '!'.($bottom), '!'.($left), $width, $height);
        $src = $src!='' ? $src : $url;
        if ($src > 0) {
            $attach = wp_get_attachment_image_src( $src, 'full' );
            if (isset($attach[0]) && $attach[0]!='')
                $src = $attach[0];
        }
        if (!empty($width) || !empty($height)) {
            $w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
            $h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
            if ($w || $h) $src = organics_get_resized_image_url($src, $w, $h);
        }
        if (trim($link)) organics_enqueue_popup();
        $output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
            . (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . '>'
            . (trim($link) ? '<a href="'.esc_url($link).'">' .(trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'>' : '') : '')
            . '<img src="'.esc_url($src).'" alt="" />'
            . (trim($link) ? '</span> ' . ($title) . '</figcaption>' . '</a>' : '')
            . ((trim(!$link) && trim($title)) || ((trim(!$link) && trim($icon))) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
            . '</figure>');
        return apply_filters('organics_shortcode_output', $output, 'trx_image', $atts, $content);
    }
    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_image', 'organics_sc_image');
}
// ---------------------------------- [/trx_image] ---------------------------------------


// ---------------------------------- [trx_search] ---------------------------------------

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('organics_sc_search')) {
    function organics_sc_search($atts, $content=null){
        if (organics_in_shortcode_blogger()) return '';
        extract(organics_html_decode(shortcode_atts(array(
            // Individual params
            "style" => "regular",
            "state" => "fixed",
            "scheme" => "original",
            "ajax" => "",
            "title" => __('Search', 'organics'),
            // Common params
            "id" => "",
            "class" => "",
            "animation" => "",
            "css" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $css .= organics_get_css_position_from_values($top, $right, $bottom, $left);
        if (empty($ajax)) $ajax = organics_get_theme_option('use_ajax_search');
        // Load core messages
        organics_enqueue_messages();
        $output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
            . (organics_param_is_on($ajax) ? ' search_ajax' : '')
            . ($class ? ' '.esc_attr($class) : '')
            . '"'
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . (!organics_param_is_off($animation) ? ' data-animation="'.esc_attr(organics_get_animation_classes($animation)).'"' : '')
            . '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url( home_url( '/' ) ) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? __('Open search', 'organics') : __('Start search', 'organics')) . '"></button>
                                <label class="[ hidden ]" for="s">search</label>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" id="s" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !organics_param_is_off($scheme) && !organics_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
        return apply_filters('organics_shortcode_output', $output, 'trx_search', $atts, $content);
    }
    if (function_exists('organics_require_shortcode')) organics_require_shortcode('trx_search', 'organics_sc_search');
}
// ---------------------------------- [/trx_search] ---------------------------------------



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
            "readmore" => __('Learn more', 'organics'),
            "title" => "",
            "subtitle" => "",
            "description" => "",
            "link_caption" => __('Learn more', 'organics'),
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
            . ' data-slides-min-width="200"'
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

        $output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.organics_do_shortcode('[trx_button link="'.esc_url($link).']'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
            . '</div><!-- /.sc_services -->'
            . '</div><!-- /.sc_services_wrap -->';

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
            'descr'  => 0,
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