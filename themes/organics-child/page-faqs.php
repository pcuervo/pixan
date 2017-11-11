<?php
get_header();

$query_args = array(
	'post_type'      => 'preguntas-frecuentes',
	'orderby'        => 'DESC',
	'posts_per_page' => -1,
);

$posts = new WP_Query( $query_args );

if ( $posts->have_posts() ) {
	while ( $posts->have_posts() ) {
		$posts->the_post(); ?>
		<h3><?php the_title(); ?></h3>
		<p><?php the_content(); ?></p>
	<?php }
} ?>

<p>Si tu pregunta no está en esta sección te pedimos enviarnos un correo a <a href="mailto:ventas@isana.com.mx">ventas@isana.com.mx</a> para contestarte a la brevedad.</p>
<p>Gracias</p>

<?php get_footer(); ?>


