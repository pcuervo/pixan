<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();
wc_print_notice( __( 'Password reset email has been sent.', 'woocommerce' ) );
?>

<p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Se ha enviado un correo electrónico de restablecimiento de contraseña a la dirección de correo electrónico de tu cuenta, esto puede llevar algunos minutos antes de que aparezca en tu bandeja de entrada. Por favor, espera al menos 10 minutos antes de intentar otro restablecimiento.', 'woocommerce' ) ); ?></p>
