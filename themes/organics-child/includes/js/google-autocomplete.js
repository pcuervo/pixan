(function($){

	"use strict";
	$("#ubicacion_punto").keypress(function(e) {
		if(e.which == 13) {
	        e.preventDefault();
	    }
	});
	
	$(function(){
		if (document.getElementById("ubicacion_punto")) {
			var autocomplete = new google.maps.places.Autocomplete($("#ubicacion_punto")[0], {});

	        google.maps.event.addListener(autocomplete, 'place_changed', function() {
	       	 	var place = autocomplete.getPlace();

	       	 	$('#latitud_punto').val( place.geometry.location.lat() );
	       	 	$('#longitud_punto').val( place.geometry.location.lng() );

	       	 	var iframe = '<iframe width="100%" height="170" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q='+place.geometry.location.lat()+','+place.geometry.location.lng()+'&hl=es;z=14&amp;output=embed"></iframe>';
	       	 	$('.iframe-cont').empty().append(iframe);

	        });

		};

	});

})(jQuery);