<?php
/**
 * WooCommerce Subscriptions User Change Status Handler Class
 *
 * @author      Prospress
 * @since       2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WCS_User_Change_Status_Handler {

	public static function init() {
		// Check if a user is requesting to cancel their subscription
		add_action( 'wp_loaded', __CLASS__ . '::maybe_change_users_subscription', 100 );
	}

	/**
	 * Checks if the current request is by a user to change the status of their subscription, and if it is,
	 * validate the request and proceed to change to the subscription.
	 *
	 * @since 2.0
	 */
	public static function maybe_change_users_subscription() {

		if ( isset( $_GET['change_subscription_to'] ) && isset( $_GET['subscription_id'] ) && isset( $_GET['_wpnonce'] )  ) {

			$user_id      = get_current_user_id();
			$subscription = wcs_get_subscription( $_GET['subscription_id'] );
			$new_status   = $_GET['change_subscription_to'];

			if ( self::validate_request( $user_id, $subscription, $new_status, $_GET['_wpnonce'] ) ) {
				self::change_users_subscription( $subscription, $new_status );

				wp_safe_redirect( $subscription->get_view_order_url() );
				exit;
			}
		}
	}

	/**
	 * Change the status of a subscription and show a notice to the user if there was an issue.
	 *
	 * @since 2.0
	 */
	public static function change_users_subscription( $subscription, $new_status ) {
		$subscription = ( ! is_object( $subscription ) ) ? wcs_get_subscription( $subscription ) : $subscription;
		$changed = false;

		switch ( $new_status ) {
			case 'active' :
				if ( ! $subscription->needs_payment() ) {
					$subscription->update_status( $new_status );
					$subscription->add_order_note( _x( 'Suscripción reactivada por el abondono de cuenta.', 'order note left on subscription after user action', 'woocommerce-subscriptions' ) );
					WC_Subscriptions::add_notice( _x( 'Tu suscripción ha sido reactivada.', 'Notice displayed to user confirming their action.', 'woocommerce-subscriptions' ), 'success' );
					$changed = true;
				} else {
					WC_Subscriptions::add_notice( __( 'No se puede volver a activar la suscripción hasta el pago de renovación. Por favor, póngase en contacto con nosotros si necesita ayuda.', 'woocommerce-subscriptions' ), 'error' );
				}
				break;
			case 'on-hold' :
				if ( wcs_can_user_put_subscription_on_hold( $subscription ) ) {
					$subscription->update_status( $new_status );
					$subscription->add_order_note( _x( 'Suscripción retenida por el abandono de cuenta.', 'order note left on subscription after user action', 'woocommerce-subscriptions' ) );
					WC_Subscriptions::add_notice( _x( 'Su suscripción se ha puesto en espera.', 'Notice displayed to user confirming their action.', 'woocommerce-subscriptions' ), 'success' );
					$changed = true;
				} else {
					WC_Subscriptions::add_notice( __( 'No se puede suspender la suscripción - se ha alcanzado el límite de suspensión. Por favor, póngase en contacto con nosotros si necesita ayuda.', 'woocommerce-subscriptions' ), 'error' );
				}
				break;
			case 'cancelled' :
				$subscription->cancel_order();
				$subscription->add_order_note( _x( 'Suscripción cancelada por el suscriptor de su página de cuenta.', 'order note left on subscription after user action', 'woocommerce-subscriptions' ) );
				WC_Subscriptions::add_notice( _x( 'Tu suscripción ha sido cancelada.', 'Notice displayed to user confirming their action.', 'woocommerce-subscriptions' ), 'success' );
				$changed = true;
				break;
		}

		if ( $changed ) {
			do_action( 'woocommerce_customer_changed_subscription_to_' . $new_status, $subscription );
		}
	}

	/**
	 * Checks if the user's current request to change the status of their subscription is valid.
	 *
	 * @since 2.0
	 */
	public static function validate_request( $user_id, $subscription, $new_status, $wpnonce = '' ) {
		$subscription = ( ! is_object( $subscription ) ) ? wcs_get_subscription( $subscription ) : $subscription;

		if ( ! wcs_is_subscription( $subscription ) ) {
			WC_Subscriptions::add_notice( __( 'La suscripción no existe. Por favor, póngase en contacto con nosotros si necesita ayuda.', 'woocommerce-subscriptions' ), 'error' );
			return false;

		} elseif ( ! empty( $wpnonce ) && wp_verify_nonce( $wpnonce, $subscription->id . $subscription->get_status() ) === false ) {
			WC_Subscriptions::add_notice( __( 'Error de seguridad. Por favor, póngase en contacto con nosotros si necesita ayuda.', 'woocommerce-subscriptions' ), 'error' );
			return false;

		} elseif ( ! user_can( $user_id, 'edit_shop_subscription_status', $subscription->id ) ) {
			WC_Subscriptions::add_notice( __( 'Eso no es una de sus suscripciones .', 'woocommerce-subscriptions' ), 'error' );
			return false;

		} elseif ( ! $subscription->can_be_updated_to( $new_status ) ) {
			// translators: placeholder is subscription's new status, translated
			WC_Subscriptions::add_notice( sprintf( __( 'La suscripción no se puede cambiar a %s. Póngase en contacto con nosotros si necesita ayuda.', 'woocommerce-subscriptions' ), wcs_get_subscription_status_name( $new_status ) ), 'error' );
			return false;
		}

		return true;
	}
}
WCS_User_Change_Status_Handler::init();
