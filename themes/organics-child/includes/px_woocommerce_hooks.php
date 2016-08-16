<?php
	
add_action('add_meta_boxes', 'meta_box_unidad_medida');


/**
 * On checkout page add basket product to order, in case of first order. 
 */
add_action( 'woocommerce_cart_calculate_fees', 'px_add_basket_on_first_order' );


add_filter( 'woocommerce_package_rates', 'px_free_shipping_above_500', 100 );

