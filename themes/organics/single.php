<?php
/**
Template Name: Single post
 */
get_header();

$single_style = organics_storage_get('single_style');
if (empty($single_style)) $single_style = organics_get_custom_option('single_style');

while ( have_posts() ) { the_post();

	// Move organics_set_post_views to the javascript - counter will work under cache system
	if (organics_get_custom_option('use_ajax_views_counter')=='no') {
		organics_set_post_views(get_the_ID());
	}

	organics_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !organics_param_is_off(organics_get_custom_option('show_sidebar_main')),
			'content' => organics_get_template_property($single_style, 'need_content'),
			'terms_list' => organics_get_template_property($single_style, 'need_terms')
		)
	);

}

get_footer();
?>