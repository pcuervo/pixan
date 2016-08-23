<?php

define( 'THEMEPATH', get_stylesheet_directory_uri() . '/' );

add_action( 'admin_enqueue_scripts', 'load_maps_js');
function load_maps_js(){

	if(get_post_type() == 'puntos-recoleccion')
	{
		// scripts
		wp_enqueue_script( 'api-google-punto', 'https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyABZ4eSBYBsLi5WQ7WdXZpivNq6n4wQZPA&language=es-ES', array('jquery'), '1.0', true );
		wp_enqueue_script( 'google-function-autocomplete', THEMEPATH. 'includes/js/google-autocomplete.js', array('api-google-punto'), '1.0', true );
	}

}

function px_woocommerce_functions() {
	// https://developer.wordpress.org/reference/functions/locate_template/
	// locate_template( $nombres_de_plantilla, $cargar, $requerir_una_vez ) 
	// Con esta función se carga un archivo y se sobreescribe para el child y el parent theme.
	locate_template( array( 'includes/px_woocommerce_functions.php' ), TRUE, TRUE );
	locate_template( array( 'includes/px_woocommerce_hooks.php' ), TRUE, TRUE );
	locate_template( array( 'includes/post-types.php' ), TRUE, TRUE );
}
add_action( 'after_setup_theme', 'px_woocommerce_functions' );





?>
