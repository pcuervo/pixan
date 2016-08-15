<?php

// CUSTOM METABOXES //////////////////////////////////////////////////////////////////

// PRUEBA DE PLUGIN



function meta_box_unidad_medida(){

	add_meta_box( 'meta-box-unidad_medida', 'Unidad Medida', 'show_metabox_unidad_medida', 'unidades');
	
}

function show_metabox_unidad_medida($post){
	
	wp_nonce_field(__FILE__, '_unidad_medida_nonce');

	echo "<label for='tiempo_preparacion' class='label-paquetes'>Tiempo de preparación: </label>";
	echo "<input type='text' class='widefat' id='tiempo_preparacion' name='tiempo_preparacion' value='$tiempo_preparacion'/>";

	echo "<br><br><label for='numero_personas' class='label-paquetes'>Número de personas: </label>";
	echo "<input type='text' class='widefat' id='numero_personas' name='numero_personas' value='$numero_personas'/>";

	echo "<br><br><label for='nivel_de_preparacion' class='label-paquetes'>Nivel de preparación: </label>";
	echo "<input type='text' class='widefat' id='nivel_de_preparacion' name='nivel_de_preparacion' value='$nivel_de_preparacion'/>";

	echo "<br><br><label for='pasos_preparacion' class='label-paquetes'>Pasos para preparar: </label>";
}

/*==========================================
=            #GENERAL FUNCTIONS            =
==========================================*/

/**
 * Create a product name "Canasta"
 */
function px_create_product_basket(){

	$basket = get_page_by_title( 'Canasta', ARRAY_A, 'product' );
	if( $basket ) return;

	$post = array(
	    'post_author'	=> get_current_user_id(),
	    'post_status' 	=> "publish",
	    'post_title' 	=> 'Canasta',
	    'post_type' 	=> "product",
	);
	wp_insert_post( $post, $wp_error );
}

/*=====  End of #GENERAL FUNCTIONS  ======*/


/*==========================================
=            #GET/SET FUNCTIONS            =
==========================================*/

/**
 * Get the number of orders made by current customer.
 * @return boolean
 */
function px_get_num_orders(){
	$customer_orders = get_posts( 
		array(
	        'numberposts' => -1,
	        'meta_key'    => '_customer_user',
	        'meta_value'  => get_current_user_id(),
	        'post_type'   => wc_get_order_types(),
	        'post_status' => array_keys( wc_get_order_statuses() ),
    	) 
	);
	var_dump( $customer_orders );
	return count( $customer_orders );
}

/*=====  End of #GET/SET FUNCTIONS  ======*/


/**
 * Adds basket product to order if the user has 
 * never bought anything before. 
 */
function px_add_basket_on_first_order(){
	if( 0 == px_get_num_orders() ) return;

}


/**
 * Adds basket product to order if the user has 
 * never bought anything before. 
 */
function px_add_basket_to_order(){
	
}