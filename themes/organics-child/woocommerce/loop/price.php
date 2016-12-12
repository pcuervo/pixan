<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>

	<span class="price"><?php echo $price_html; ?> x
		<!-- unidad de medida -->
		<?php $tipo_unidad = get_post_meta($post->ID, 'unidadmedida', true); ?>
		<span class="[ color-red font-weight--500 ]"><?php echo $tipo_unidad; ?></span>
	</span>

<?php endif; ?>

<?php if ( !$product->is_in_stock() ) {?>
	<p class="[ stock out-of-stock ]">AGOTADO</p>
<?php } ?>