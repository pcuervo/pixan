<?php

/*==========================================
=            #GENERAL FUNCTIONS            =
==========================================*/

/**
 * Adds basket product ass fee to order if the user has 
 * never bought anything before. 
 */
function px_add_basket_on_first_order(){
	if( 0 < px_get_num_orders() ) return;
	WC()->cart->add_fee( 'Canasta primera vez', 100 );
}

/**
 * Adds basket product to order if the user has 
 * never bought anything before. 
 */
function px_add_basket_to_order(){
	
}

/**
 * Show free shipping for orders above $500 and show
 * flat rate shipping on orders below $500.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function px_free_shipping_above_500( $rates ) {
	// Replace currency symbol with empty string
	$total_without_currency_symbol = preg_replace( '/&#36/', '', WC()->cart->get_cart_total() );
	// Replace everything that is not a number, comma or perioed with empty string
	$current_cart_totals = floatval( preg_replace( '#[^\d.]#', '', $total_without_currency_symbol ) );

	if( '500' > $current_cart_totals ){
		unset( $rates['free_shipping:2'] );
		return $rates;
	} 

	unset( $rates['flat_rate:1'] );
	return $rates;
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
	//var_dump( $customer_orders );
	return count( $customer_orders );
}

/*=====  End of #GET/SET FUNCTIONS  ======*/


/*==================================
=            #METABOXES            =
==================================*/

function meta_box_unidad_medida(){
	add_meta_box( 'meta-box-unidad_medida', 'Unidad Medida', 'show_metabox_unidad_medida', 'unidades');
}

function show_metabox_unidad_medida( $post ){
	wp_nonce_field(__FILE__, '_unidad_medida_nonce');

	echo "<label for='tiempo_preparacion' class='label-paquetes'>Tiempo de preparación: </label>";
	echo "<input type='text' class='widefat' id='tiempo_preparacion' name='tiempo_preparacion' value='$tiempo_preparacion'/>";

	echo "<br><br><label for='numero_personas' class='label-paquetes'>Número de personas: </label>";
	echo "<input type='text' class='widefat' id='numero_personas' name='numero_personas' value='$numero_personas'/>";

	echo "<br><br><label for='nivel_de_preparacion' class='label-paquetes'>Nivel de preparación: </label>";
	echo "<input type='text' class='widefat' id='nivel_de_preparacion' name='nivel_de_preparacion' value='$nivel_de_preparacion'/>";

	echo "<br><br><label for='pasos_preparacion' class='label-paquetes'>Pasos para preparar: </label>";
}


/*=====  End of #METABOXES  ======*/


