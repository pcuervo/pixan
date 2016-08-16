<?php

/**
 * On checkout page add basket product to order, in case of first order. 
 */
add_action( 'woocommerce_cart_calculate_fees', 'px_add_basket_on_first_order' );

/**
 * Create coupon for new customers
 */
add_action( 'woocommerce_init', 'px_create_new_customer_coupon' );

/**
 * On first order, apply coupon to cart.
 */
add_action( 'woocommerce_before_cart', 'px_apply_new_customer_coupon' );

/**
 * Set free shipping on orders above $500
 */
add_filter( 'woocommerce_package_rates', 'px_free_shipping_above_500', 100 );

/**
 * Add metaboxes for products
 */
add_action('add_meta_boxes', 'meta_box_unidad_medida');
