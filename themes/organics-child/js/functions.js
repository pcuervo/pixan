var $=jQuery.noConflict();

(function($){
    "use strict";
    $(function(){


        /*------------------------------------*\
            #GLOBAL
        \*------------------------------------*/
        $(window).ready(function(){
            remplaceText();
            setTimeout(function() {
                remplaceText();
            }, 3500);
        });

        $(".btn-zonas").click(function() {
		    $('html, body').animate({
		        scrollTop: $("#zonas-entrega").offset().top -100
		    }, 2000);
		});

    });
})(jQuery);

function remplaceText(){
    $('span.traslate').each(function() {
        var text = $(this).text();
        $(this).text(text.replace('every 2 weeks', 'cada 2 semanas'));
    });
    $('span.traslate').each(function() {
        var text = $(this).text();
        $(this).text(text.replace('/ week', '/ semana'));
    });
    $('span.traslate').each(function() {
        var text = $(this).text();
        $(this).text(text.replace('/ month', '/ mes'));
    });
    $('.subscription-details').each(function() {
        var text = $(this).text();
        $(this).text(text.replace('/ month', '/ mes'));
    });
    $('.subscription-details').each(function() {
        var text = $(this).text();
        $(this).text(text.replace(' / month', '/ mes'));
    });
    $('.subscription-details').each(function() {
        var text = $(this).text();
        $(this).text(text.replace('/ week', '/ semana'));
    });
    $('.subscription-details').each(function() {
        var text = $(this).text();
        $(this).text(text.replace(' / week', '/ semana'));
    });
    $('a').each(function() {
        var text = $(this).text();
        $(this).text(text.replace('your account', 'tu cuenta'));
    });
    $('a').each(function() {
        var text = $(this).text();
        $(this).text(text.replace('suspend', 'suspender'));
    });
}