<?php
	
add_action('add_meta_boxes', 'meta_box_unidad_medida');

/**
 * Create product "Canasta" after WooCommerce is loaded, 
 * or ignore if it already exists.
 *
 * TODO: Borrar cuando ya se tenga en producción o todos 
 * los involucrados tenga el producto creado en su ambiente local. 
 */
add_action( 'init', 'px_create_product_basket' );

/**
 * On checkout page add basket product to order, in case of first order. 
 */
// add_action( 'woocommerce_before_checkout_form', 'px_add_basket_on_first_order' );

