<?php
/**
Template Name: Preguntas Frecuentes
 */
get_header();
echo '<h2> Preguntas Fecuentes </h2>';

$query_args = array(
	'post_type'      => 'preguntas-frecuentes',
	'orderby'        => 'pots_title',
	'no_found_rows'  => true,
	'cache_results'  => false,
	'numberposts' => -1,
);

$posts = new WP_Query( $query_args );

if ( $posts->have_posts() ) {
	while ( $posts->have_posts() ) {
		$posts->the_post();
		echo '<h3>'.$posts->post->post_title.'</h3>';
		echo '<p>'.$posts->post->post_content.'</p>';
	}
}

get_footer();
?>