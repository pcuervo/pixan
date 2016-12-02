var $=jQuery.noConflict();

(function($){
    "use strict";
    $(function(){


        /*------------------------------------*\
            #GLOBAL
        \*------------------------------------*/
        // $(window).ready(function(){
        //     alert('hola');
        // });

        $(".btn-zonas").click(function() {
		    $('html, body').animate({
		        scrollTop: $("#zonas-entrega").offset().top -100
		    }, 2000);
		});

    });
})(jQuery);
