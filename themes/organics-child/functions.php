<?php

define( 'THEMEPATH', get_stylesheet_directory_uri() . '/' );

/*------------------------------------*\
	#INCLUDES
\*------------------------------------*/
require_once( 'inc/pages.php' );

add_action('woocommerce_before_checkout_form','show_cart_summary',9);

// gets the cart template and outputs it before the form
function show_cart_summary( ) {
  wc_get_template_part( 'cart/cart-mail' );
}

add_action( 'admin_enqueue_scripts', 'load_js');
function load_js(){

	if(get_post_type() == 'puntos-recoleccion')
	{
		// scripts
		wp_enqueue_script( 'api-google-punto', 'https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyABZ4eSBYBsLi5WQ7WdXZpivNq6n4wQZPA&language=es-ES', array('jquery'), '1.0', true );
		wp_enqueue_script( 'google-function-autocomplete', THEMEPATH. 'includes/js/google-autocomplete.js', array('api-google-punto'), '1.0', true );
	}
	if(get_post_type() == 'shop_order')
	{
		wp_enqueue_script( 'imprimir-lista-ordenes', THEMEPATH. 'includes/js/print-list.js', array('jquery'), '1.0', true );
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


/**
* Enqueue frontend scripts and styles
**/
add_action( 'wp_enqueue_scripts', function(){

	wp_enqueue_script( 'functions-user', THEMEPATH. 'js/functions.js', array('jquery'), '1.0', true );
	//wp_enqueue_script( 'wc-password-strength-meter-index', THEMEPATH . 'js/password-strength-meter-index.js', array( 'jquery' ), true );

});

/**
* Campo términos y condiciones login
**/
function wooc_extra_register_fields() {?>
       <p class="form-row form-row-wide">
			<label for="registration_agree">
				<input type="checkbox" value="agree" id="registration_agree" name="registration_agree" class="[ width--20 ][ vertical-align--middle ]" required="">
				<?php esc_html_e('Estoy de acuerdo con los ', 'organics'); ?><a href="#"><?php esc_html_e(' Términos y condiciones', 'organics'); ?></a>
			</label>
       </p>
       <?php
 }
 add_action( 'woocommerce_register_form', 'wooc_extra_register_fields' );

//Cambiar orden o quitar elementos de content-single-product
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 25 );


function my_woocommerce_add_error( $error ) {
    return str_replace('Facturación ','',$error);
}
add_filter( 'woocommerce_add_error', 'my_woocommerce_add_error' );