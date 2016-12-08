<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( __( 'Bienvenido a PIXAN,<span style="display:none;">%s</span> gracias por tu interés en nuestros productos. PIXAN es una empresa que cultiva sus propios productos y busca alianzas a través de proveedores afines a nuestra misión para entregarte una honesta experiencia de sabor.  Tu usuario es <strong>%s</strong>. ', 'woocommerce' ), esc_html( $blogname ), esc_html( $user_login ) ); ?><?php printf( __( 'You can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), make_clickable( esc_url( wc_get_page_permalink( 'myaccount' ) ) ) ); ?></p>


<?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>

	<p><?php printf( __( 'Your password has been automatically generated: <strong>%s</strong>', 'woocommerce' ), esc_html( $user_pass ) ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_email_footer', $email );
