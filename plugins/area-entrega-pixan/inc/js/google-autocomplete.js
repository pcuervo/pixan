(function($){

	"use strict";

	$(function(){
		if (document.getElementById("gmap_geocoding_address")) {
			var autocomplete = new google.maps.places.Autocomplete($("#gmap_geocoding_address")[0], {});

	        google.maps.event.addListener(autocomplete, 'place_changed', function() {
	       	 	var place = autocomplete.getPlace();
	        });

		};
	});

})(jQuery);