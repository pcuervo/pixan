// source --> http://localhost:8888/pixan/wp-content/plugins/wp-facebook-login/public/js/facebook-login.js?ver=1.1.4 
(function( $ ) {
	'use strict';
    // Thanks to Zane === zM Ajax Login & Register === for this bit
    $( document ).on( 'click', '.js-fbl', function( e ) {
        e.preventDefault();
        window.fbl_button    = $(this);
        window.fbl_button.addClass('fbl-loading');
        $('.fbl_error').remove();
        if( navigator.userAgent.match('CriOS') ) {
            $('<p class="fbl_error">'+fbl.l18n.chrome_ios_alert+'</p>').insertAfter( window.fbl_button);
            FB.getLoginStatus( handleResponse );
        } else {
            try {
                FB.login(handleResponse, {
                    scope: fbl.scopes,
                    return_scopes: true,
                    auth_type: 'rerequest'
                });
            } catch (err) {
                window.fbl_button.removeClass('fbl-loading');
                alert('Facebook Init is not loaded. Check that you are not running any blocking software or that you have tracking protection turned off if you use Firefox');
            }
        }
    });

    var handleResponse = function( response ) {
        var $form_obj       = window.fbl_button.parents('form') || false,
            $redirect_to    = $form_obj.find('input[name="redirect_to"]').val() || window.fbl_button.data('redirect');
        /**
         * If we get a successful authorization response we handle it
         */
        if (response.status == 'connected') {

            var fb_response = response;

            /**
             * Send the obtained token to server side for extra checks and user data retrieval
             */
            $.ajax({
                data: {
                    action: "fbl_facebook_login",
                    fb_response: fb_response,
                    security: window.fbl_button.data('fb_nonce')
                },
                global: false,
                type: "POST",
                url: fbl.ajaxurl,
                success: function (data) {
                    if (data && data.success) {
                        if( data.redirect && data.redirect.length ) {
                            location.href = data.redirect.length;
                        } else if ( $redirect_to.length ) {
                            location.href = $redirect_to;
                        } else {
                            location.href = fbl.site_url;
                        }
                    } else if (data && data.error) {
                        window.fbl_button.removeClass('fbl-loading');
                        if ($form_obj.length) {
                            $form_obj.append('<p class="fbl_error">' + data.error + '</p>');
                        } else {
                            // we just have a button
                            $('<p class="fbl_error">' + data.error + '</p>').insertAfter(window.fbl_button);
                        }
                    }
                },
                error: function (data) {
                    window.fbl_button.removeClass('fbl-loading');
                    $form_obj.append('<p class="fbl_error">' + data + '</p>');
                }
            });

        } else {
            window.fbl_button.removeClass('fbl-loading');
            if( navigator.userAgent.match('CriOS') )
                window.open('https://www.facebook.com/dialog/oauth?client_id=' + fbl.appId + '&redirect_uri=' + document.location.href + '&scope=email,public_profile', '', null);
        }
    };
})( jQuery );
// source --> http://localhost:8888/pixan/wp-content/plugins/js_composer/assets/js/vendors/woocommerce-add-to-cart.js?ver=4.11.2.1 
window.jQuery( document ).ready( function ( $ ) {
	$( 'body' ).on( 'adding_to_cart', function ( event, $button, data ) {
		$button && $button.hasClass( 'vc_gitem-link' ) && $button
			.addClass( 'vc-gitem-add-to-cart-loading-btn' )
			.parents( '.vc_grid-item-mini' )
			.addClass( 'vc-woocommerce-add-to-cart-loading' )
			.append( $( '<div class="vc_wc-load-add-to-loader-wrapper"><div class="vc_wc-load-add-to-loader"></div></div>' ) );
	} ).on( 'added_to_cart', function ( event, fragments, cart_hash, $button ) {
		if ( 'undefined' === typeof($button) ) {
			$button = $( '.vc-gitem-add-to-cart-loading-btn' );
		}
		$button && $button.hasClass( 'vc_gitem-link' ) && $button
			.removeClass( 'vc-gitem-add-to-cart-loading-btn' )
			.parents( '.vc_grid-item-mini' )
			.removeClass( 'vc-woocommerce-add-to-cart-loading' )
			.find( '.vc_wc-load-add-to-loader-wrapper' ).remove();
	} );
} );