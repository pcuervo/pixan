<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
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
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php
	//CALCULAR EL DIA DE ENTREGA
	//var_dump($order);
	$semana = array(
				'Lunes' 	=> 'monday',
				'Martes' 	=> 'tuesday',
				'Miercoles' => 'wednesday',
				'Jueves' 	=> 'thursday',
				'Viernes' 	=> 'friday',
				'Sabado' 	=> 'saturday',
				'Domingo' 	=> 'sunday',
				);
	$meses = array(
				'January' => 'Enero',
			    'February' => 'Febrero',
			    'March' => 'Marzo',
			    'April' => 'Abril',
			    'May' => 'Mayo',
			    'June' => 'Junio',
			    'July ' => 'Julio',
			    'August' => 'Agosto',
			    'September' => 'Septiembre',
			    'October' => 'Octubre',
			    'November' => 'Noviembre',
			    'December' => 'Diciembre'
				);
	$meta_orden = get_post_meta($order->id);
	//var_dump($meta_orden);
	$meta = get_post_meta($meta_orden["_billing_area_entrega"][0]);
	//var_dump($meta);
	$dias = '';
	if(isset($meta['_dia1'])) { $dias .= $meta['_dia1'][0].', '; }
	if(isset($meta['_dia2'])) { $dias .= $meta['_dia2'][0].', '; }
	if(isset($meta['_dia3'])) { $dias .= $meta['_dia3'][0].', '; }
	if(isset($meta['_dia4'])) { $dias .= $meta['_dia4'][0].', '; }
	if(isset($meta['_dia5'])) { $dias .= $meta['_dia5'][0].', '; }
	if(isset($meta['_dia6'])) { $dias .= $meta['_dia6'][0].', '; }
	if(isset($meta['_dia7'])) { $dias .= $meta['_dia7'][0].', '; }


	$dias = substr($dias, 0, -2);
	$d = explode(',', $dias);
	//OBTENER FECHA DE PROXIMA ENTREGA
	$timestamp = strtotime('+1 day');

	$dia = date('d', strtotime("next ".$semana[$d[0]] . date('H:i:s', $timestamp), $timestamp));
	$m = date('F', strtotime("next ".$semana[$d[0]] . date('H:i:s', $timestamp), $timestamp));
	$a = date('Y', strtotime("next ".$semana[$d[0]] . date('H:i:s', $timestamp), $timestamp));
	$p = $d[0].' '.$dia.' de '.$meses[$m].', '.$a;

	$timestamp_c = strtotime('-2 days');
	$dia_c = date('d', strtotime("next ".$semana[$d[0]] . " -1 day", $timestamp));
	$m_c = date('F', strtotime("next ".$semana[$d[0]] . " -1 day", $timestamp));
	$a_c = date('Y', strtotime("next ".$semana[$d[0]] . " -1 day", $timestamp));
	$cance = $dia_c.' de '.$meses[$m_c].', '.$a_c;
	setlocale(LC_ALL,"es_ES");
?>

<p><?php _e( "Gracias por comprar en Pixan. Tu pedido se ha recibido y se estará entregando el día <strong>".$p."</strong>. Recuerda que la fecha máxima para la cancelación de tu pedido es el día <strong>".$cance."</strong>. Los detalles del pedido son los siguientes:", 'woocommerce' ); ?></p>

<?php

/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Emails::order_schema_markup() Adds Schema.org markup.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
