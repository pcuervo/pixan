<?php

function my_superawesome_function() {
	

$current_user = wp_get_current_user();
$customer_id = $current_user->ID;

// $args = array(
// 'numberposts' => -1,
// 'meta_key' => '_customer_user',
// 'meta_value'	=> $customer_id,
// 'post_type' => 'shop_order',
// 'post_status' => 'publish'
// );
// $customer_orders = get_posts($args);
$customer_orders = get_posts( array(
    'numberposts' => -1,
    'meta_key'    => '_customer_user',
    'meta_value'  => get_current_user_id(),
    'post_type'   => wc_get_order_types(),
    'post_status' => array_keys( wc_get_order_statuses() ),
) );

//var_dump( $customer_orders );
if ($customer_orders) :
	error_log('nigga has orders');
foreach ($customer_orders as $customer_order) :
$order = new WC_Order();

$order->populate( $customer_order );

// Get the coupon array
$couponR = $order->order_custom_fields['coupons'];

foreach ($couponR as $singleCoupon) {
echo $singleCoupon.'
';
}

endforeach;

else :
//no Coupon then...
endif;

}
//add_action('woocommerce_init', 'my_superawesome_function');


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

/**
 * Apply coupon "bienvenido" to new users.
 */
function px_apply_new_customer_coupon(){
	if( WC()->cart->has_discount('bienvenido') ) return;
	WC()->cart->add_discount( 'bienvenido' );
}

/**
 * Create coupon "bienvenido"
 */
function px_create_new_customer_coupon(){
	return;
	$coupon_code = 'bienvenido'; 
	$amount = '50';
	$discount_type = 'fixed_cart';

	$coupon = array(
	    'post_title' 	=> $coupon_code,
	    'post_content' 	=> '',
	    'post_status' 	=> 'publish',
	    'post_author' 	=> 1,
	    'post_type'     => 'shop_coupon'
	);    
	$new_coupon_id = wp_insert_post( $coupon );

	update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
	update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
	update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
	update_post_meta( $new_coupon_id, 'product_ids', '' );
	update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
	update_post_meta( $new_coupon_id, 'usage_limit', '1' );
	update_post_meta( $new_coupon_id, 'expiry_date', '' );
	update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
	update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
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
function show_metabox_producto($post){
	
	$tipo_unidad = get_post_meta($post->ID, 'unidadmedida', true);
	$temperatura = get_post_meta($post->ID, 'temperatura', true);
	$meta = get_post_meta($post->ID);
	
	wp_nonce_field(__FILE__, '_unidadmedida');
	wp_nonce_field(__FILE__, '_temperatura');

	echo '<h5>Tipo de Unidad</h5>';
	echo 'Kilo <input type="radio" name="unidadmedida" id="radio_kilo" value="Kilo" ';checked( $tipo_unidad, 'Kilo' ); echo ' />';
	echo 'Pieza <input type="radio" name="unidadmedida" id="radio_pieza" value="Pieza" ';checked( $tipo_unidad, 'Pieza' ); echo ' />';
	echo 'Manojo <input type="radio" name="unidadmedida" id="radio_manojo" value="Manojo" ';checked( $tipo_unidad, 'Manojo' ); echo ' />';
	echo 'Caja <input type="radio" name="unidadmedida" id="radio_caja" value="Caja" ';checked( $tipo_unidad, 'Caja' ); echo ' />';
	echo 'Canasta <input type="radio" name="unidadmedida" id="radio_canasta" value="Canasta" ';checked( $tipo_unidad, 'Canasta' ); echo ' />';

	echo '<h5>Temperatura</h5>';
	echo 'Fresco <input type="radio" name="temperatura" id="radio_fresco" value="Fresco" ';checked( $temperatura, 'Fresco' ); echo ' />';
	echo 'Congelado <input type="radio" name="temperatura" id="radio_congelado" value="Congelado" ';checked( $temperatura, 'Congelado' ); echo ' />';

}

function meta_box_producto(){
	global $post;
	add_meta_box( 'meta-box-producto', 'Información Adicional', 'show_metabox_producto', 'product');
};

/**
* Save the metaboxes for post type "Puntos de Recolección"
* */
add_action( 'save_post', function ( $post_id ){
	if ( isset($_POST['unidadmedida']) and check_admin_referer(__FILE__, '_unidadmedida') ){
		update_post_meta($post_id, 'unidadmedida', $_POST['unidadmedida']);
	}
	if ( isset($_POST['temperatura']) and check_admin_referer(__FILE__, '_temperatura') ){
		update_post_meta($post_id, 'temperatura', $_POST['temperatura']);
	}
});// save_meta_boxes_puntos_recoleccion

/*=====  End of #METABOXES  ======*/
