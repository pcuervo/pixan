<?php echo sprintf ( _n( '%d PRODUCTO', '%d PRODUCTOS', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>
<span> - <?php echo WC()->cart->get_cart_subtotal(); ?></span>
