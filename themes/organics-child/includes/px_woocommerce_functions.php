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
	if( WC()->cart->has_discount('bienvenido') || px_has_used_coupon( 'bienvenido' ) ) return;
	WC()->cart->add_discount( 'bienvenido' );
}

/**
 * Create coupon "bienvenido"
 */
function px_create_new_customer_coupon(){
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

/**
 * Check if user has applied a coupon before
 *
 * @param string $coupon_name
 * @return boolean
 */
function px_has_used_coupon( $coupon_name ) {
	// Exlude orders that have been cancelled or failed
	$valid_order_statuses = array_diff( array_keys( wc_get_order_statuses() ), array( 'wc-cancelled', 'wc-failed' ));
	$customer_orders = get_posts( array(
	    'numberposts' => -1,
	    'meta_key'    => '_customer_user',
	    'meta_value'  => get_current_user_id(),
	    'post_type'   => array('shop_order'),
	    'post_status' => $valid_order_statuses,
	) );
	if( ! $customer_orders ) return 0;

	foreach ( $customer_orders as $customer_order ) {
		$order = new WC_Order( $customer_order->ID );
		if( px_order_has_coupon( $order, $coupon_name ) ) return 1;
	}
	return 0;
}

/**
 * Check if an order has a coupon
 *
 * @param WC_Order $order
 * @param string $coupon_name
 * @return boolean
 */
function px_order_has_coupon( WC_Order $order, $coupon_name ) {
	if( ! $order->get_used_coupons() ) return 0;

	if( in_array( $coupon_name, $order->get_used_coupons() ) ) return 1;
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
	$vigencia = get_post_meta($post->ID, 'vigencia', true);
	
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
	echo '<h5>Vigencia</h5>';
	echo '<input type="date" id="vigencia" name="vigencia" value="'.$vigencia.'" class="example-datepicker" />';

	//$meta = get_post_meta($post->ID);
	//var_dump($meta);
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


//MODIFY ORDERS LIST FILTERS
add_filter( 'manage_edit-shop_order_columns', 'MY_COLUMNS_FUNCTION' );
function MY_COLUMNS_FUNCTION($columns){
    $new_columns = (is_array($columns)) ? $columns : array();
    unset( $new_columns['order_actions'] );

    //edit this for you column(s)
    //all of your columns will be added before the actions column
    //$new_columns['temperatura'] = 'Tempreratura';
    $new_columns['_temperaturas_orden'] = 'Tempreraturas';
    $new_columns['_billing_regalo'] = 'Regalo';
    //stop editing

    $new_columns['order_actions'] = $columns['order_actions'];
    return $new_columns;
}

add_action( 'restrict_manage_posts' , 'modify_orders_filters'  );

function modify_orders_filters()
{
    // Only apply the filter to our specific post type
    global $wpdb;
	
    global $typenow;
    if( $typenow == 'shop_order' )
    {
    	
    	$temperaturas = $wpdb->get_results(
		"SELECT distinct(meta_value) FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'temperatura'"
		);
		if (count($temperaturas[0]) > 0) {
			echo '<select id="_temperaturas_orden" name="_temperaturas_orden" data-parsley-error-message="Todas las temperaturas" >';
	 		echo '<option class="" value="" selected>Todas las temperaturas</option>';
			foreach ( $temperaturas as $temperatura )
			{
				if(isset($_GET['_temperaturas_orden'])) { $selected = $temperatura->meta_value == $_GET['_temperaturas_orden'] ? ' selected ' : ''; }
				else { $selected = ''; }
				echo '<option value="'.$temperatura->meta_value.'" '.$selected.'>'.$temperatura->meta_value.'</option>';
			}
			echo '</select>';
		}
		//BOTON PARA IMPRIMIR
    	echo '<a href="#" class="button" id="btnImprimir">IMPRIMIR</a>';
    }
}

add_filter( 'parse_query', 'modify_filter_orders' );

function modify_filter_orders( $query )
{
    global $typenow;
    global $pagenow;
    
    /*
    if( isset($_GET['temperatura']) && $_GET['temperatura'] != '') {
	    if( $pagenow == 'edit.php' && $typenow == 'shop_order' && $_GET['temperatura'] )
	    {
	    	//echo '****************'.$_GET['temperatura'];
	        $query->query_vars[ 'meta_key' ] = 'temperatura';
	        $query->query_vars[ 'meta_value' ] = $_GET['temperatura'];
	    }
	}
	*/
	if( isset($_GET['_temperaturas_orden']) && $_GET['_temperaturas_orden'] != '') {
	    if( $pagenow == 'edit.php' && $typenow == 'shop_order' && $_GET['_temperaturas_orden'] )
	    {
	    	//echo '****************'.$_GET['_temperaturas_orden'];
	        $query->query_vars[ 'meta_key' ] = '_temperaturas_orden';
	        $query->query_vars[ 'meta_value' ] = $_GET['_temperaturas_orden'];
	        $query->query_vars[ 'meta_compare' ] = 'LIKE';
	    }
	}
		
	//var_dump($query);
}

add_action( 'manage_shop_order_posts_custom_column', 'my_manage_shop_order_columns', 10, 2 );

function my_manage_shop_order_columns( $column, $post_id ) {
	global $post, $the_order;
	$tempe = array();
	$t = '';

	if ( empty( $the_order ) || $the_order->id != $post_id ) {
		$the_order = wc_get_order( $post_id );
	}

	//var_dump($the_order);

	switch( $column ) {

		/* If displaying the '_ciudad_meta' column. */
		/*
		case 'temperatura' :
			foreach ( $the_order->get_items() as $item ) {
				//echo $item;
				$product        = apply_filters( 'woocommerce_order_item_product', $the_order->get_product_from_item( $item ), $item );
				//var_dump($product->id);
				/* Get the post meta. * /
				$temperatura = get_post_meta( $product->id , 'temperatura', true );
				//echo '['.$temperatura.']';
				if ( !empty( $temperatura ) && !in_array($temperatura, $tempe)) { array_push($tempe, $temperatura); }
			}
			for ($i = 0; $i<count($tempe); $i++) {
				$t .= $tempe[$i].'<br />';
			}
			echo $t;
			break;
		*/
		case '_temperaturas_orden' :
			/* Get the post meta. */
			$_temperaturas_orden = get_post_meta( $post_id, '_temperaturas_orden', true );

			/* If no _temperaturas_orden is found, output a default message. */
			if ( !empty( $_temperaturas_orden ) ) { echo $_temperaturas_orden; }
			//else { echo 'NADA'; }
			break;	
		case '_billing_regalo' :
			/* Get the post meta. */
			$_billing_regalo = get_post_meta( $post_id, '_billing_regalo', true );

			/* If no _billing_regalo is found, output a default message. */
			if ( !empty( $_billing_regalo ) && $_billing_regalo == '1' ) { echo '<strong style="color:#CDDC39;">SI</strong>'; }
			else { echo 'NO'; }
			break;		
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}


/*=====  End of #METABOXES  ======*/
