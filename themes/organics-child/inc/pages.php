<?php


// CUSTOM PAGES //////////////////////////////////////////////////////////////////////

add_action('init', function(){

	//Directorio
	if( ! get_page_by_path('faqs') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Preguntas frecuentes',
			'post_name'   => 'faqs',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}

});
