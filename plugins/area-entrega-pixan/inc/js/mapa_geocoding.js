$ = jQuery.noConflict();
//zona = [];

zonaarray = new Array();
$(document).ready(function(){
    
    mapGeocoding();

    $("#puntos_recoleccion").change(function() {
        resetInfo();
        $(".area_e").removeAttr('selected', false);
        //console.log('LAT-'+$("#puntos_recoleccion option:selected").data('lat'));
        $("#billing_puntos_recoleccion").val($("#puntos_recoleccion").val());
        $("#billing_lat").val($("#puntos_recoleccion option:selected").data('lat'));
        $("#billing_long").val($("#puntos_recoleccion option:selected").data('long'));
        $("#nombrePunto").html($("#puntos_recoleccion option:selected").html());
        $("#responsable").html($("#puntos_recoleccion option:selected").data('responsable'));
        $("#telefono").html($("#puntos_recoleccion option:selected").data('tel'));
        $("#ubicacion").html($("#puntos_recoleccion option:selected").data('ubicacion'));
    });

    $("#area_entrega").change(function() {
        console.log($("#area_entrega").val());
        $("#billing_area_entrega").val($("#area_entrega").val());
        $("#lblNombrePunto").html($("#area_entrega option:selected").html());
        $("#lblDiasEntrega").html($("#area_entrega option:selected").data('dias'));
        $("#lblHorarioEntrega").html($("#area_entrega option:selected").data('hora'));
    });
    
});

var resetInfo = function() {
    //setMapOnAll(null);
    //markers = [];
    $("#billing_address_1").val('');
    $("#billing_address_2").val('');
    $("#billing_city").val('');
    $("#billing_postcode").val('');
    $("#billing_formated_address").val('');
    $("#billing_lat").val('');
    $("#billing_long").val('');
}

var limpiarLabels = function() {
    //setMapOnAll(null);
    //markers = [];
    $("#lblNombrePunto, #lblDiasEntrega, #lblHorarioEntrega, #nombrePunto, #responsable, #telefono, #ubicacion").html('');
}

var mapGeocoding = function () {
    /*
    var map = new GMaps({
        div: '#gmap_geocoding',
        lat: 19.4389263,
        lng: -99.1278322
    });
*/
    

    var mapOptions = {
        center: new google.maps.LatLng(19.4389263,-99.1278322),
        zoom: 12
      };

    var map = new google.maps.Map(document.getElementById('gmap_geocoding'),
        mapOptions);
    var markers = [];
    
    var c = 0;

    $(".area_e").each(function(index) {
        var coor = $(this).data("coor");
        color = ["#FFFF00", "#0174DF", "#5FB404", "#AC58FA", "#FF8000", "#FFFF00", "#0174DF", "#5FB404", "#AC58FA", "#FF8000"];
        ////console.log(coor);
        coor = coor.substring(1, coor.length-1);
        coor = coor.split("),(");
        var zonacoor = new Array();
        for(var i=0; i<coor.length; i++)
        {
            c = coor[i].split(",");
            zonacoor.push(new google.maps.LatLng(c[0], c[1]));
        }

        zona = new google.maps.Polygon({
            paths: zonacoor,
            strokeColor: color[index],
            strokeOpacity: 0.5,
            strokeWeight: 1,
            fillColor: color[index],
            fillOpacity: 0.35,
            indexID: 'poly-'+$(this).attr('id')
          });
        //console.log(zona);
        zonaarray.push(zona);
        zona.setMap(map);
        c++;
    });

    var handleAction = function () {
        var text = $.trim($('#gmap_geocoding_address').val());
        GMaps.geocode({
            address: text,
            callback: function (results, status) {
                $("#searchErrorText").slideUp();
                resetInfo();
                ////console.log(results);
                if (status == 'OK') {
                    //console.log(results.length);
                    if(results.length == 1) {
                        //Loop in addres_components to get required values and set in their respectives inputs
                        for (var i = 0; i < results[0].address_components.length; i++) {    
                            ////console.log(results[0].address_components[i]);
                            ////console.log('size -> '+results[0].address_components[i].types.length);
                            for (var n = 0; n < results[0].address_components[i].types.length; n++ )
                            {
                                ////console.log('Types -> '+results[0].address_components[i].types);
                                switch(results[0].address_components[i].types[n]) {
                                    case 'street_number':
                                        $("#billing_address_1").val(results[0].address_components[i].long_name+' '+$("#billing_address_1").val());
                                        break;
                                    case 'route':
                                        $("#billing_address_1").val(results[0].address_components[i].long_name+' '+$("#billing_address_1").val());
                                        break;                                        
                                    case 'sublocality':
                                        $("#billing_address_2").val(results[0].address_components[i].long_name);
                                        break;
                                    case 'administrative_area_level_3':
                                        $("#billing_city").val(results[0].address_components[i].long_name);
                                        break;
                                    case 'postal_code':
                                        $("#billing_postcode").val(results[0].address_components[i].long_name);
                                        break;
                                    default:
                                        //console.log('No usado -> '+results[0].address_components[i].types[n]);
                                }
                            }
                        }

                        var latlng = results[0].geometry.location;
                        $("#billing_formated_address").val(results[0].formatted_address);
                        $("#billing_lat").val(latlng.lat());
                        $("#billing_long").val(latlng.lng());
                        //map.setCenter(latlng.lat(), latlng.lng());
                        /*
                        map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng()
                        });
                        */
                       
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(latlng.lat(), latlng.lng()),
                            map: map
                          });
                        markers.push(map);

                        for (var i = 0; i<zonaarray.length; i++) {
                            //console.log(zonaarray[i].indexID+' - '+zonaarray[i]);
                            var esta = google.maps.geometry.poly.containsLocation(latlng, zonaarray[i]) ? true : false;
                            if(esta) {
                                var opti = zonaarray[i].indexID.split('-');
                                $(".punto_r").removeAttr('selected', false);
                                $("#"+opti[1]).attr('selected', true);
                                //console.log('-->'+$("#area_entrega").val());
                                $("#billing_area_entrega").val($("#area_entrega").val());
                                $("#lblNombrePunto").html($("#"+opti[1]).html());
                                $("#lblDiasEntrega").html($("#"+opti[1]).data('dias'));
                                $("#lblHorarioEntrega").html($("#"+opti[1]).data('hora'));
                                $("#divInfoAreaEntrega").slideDown();
                                $("#puntos_recoleccion, #divInfoPunto, #areaInfo").slideUp();

                                return;
                            }
                            else {
                                $(".area_e").removeAttr('selected', false);
                                $("#divInfoAreaEntrega").slideUp();
                                $("#areaInfo, #divInfoPunto").slideDown();
                                limpiarLabels();
                            }
                        }
                    }
                    else {
                        $("#searchErrorText").slideDown();
                        $(".punto_r").removeAttr('selected', false);
                        $(".area_e").removeAttr('selected', false);
                        $("#divInfoAreaEntrega").slideUp();
                        $("#divInfoPunto, #areaInfo").slideUp();
                        limpiarLabels();
                    }
                }
                else {
                    $("#searchErrorText").slideDown();
                    $(".punto_r").removeAttr('selected', false);
                    $(".area_e").removeAttr('selected', false);
                    $("#divInfoAreaEntrega").slideUp();
                    $("#puntos_recoleccion, #divInfoPunto, #areaInfo").slideUp();
                    limpiarLabels();
                }
            }
        });
    }

    // Sets the map on all markers in the array.
    var setMapOnAll = function(map) {
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
      }
    }

    // Removes the markers from the map, but keeps them in the array.
    var clearMarkers = function() {
      setMapOnAll(null);
    }

    // Shows any markers currently in the array.
    var showMarkers = function() {
      setMapOnAll(map);
    }

    // Deletes all markers in the array by removing references to them.
    var deleteMarkers = function() {
      clearMarkers();
      markers = [];
    }

    $('#gmap_geocoding_btn').click(function (e) {
        e.preventDefault();
        handleAction();
    });

    $("#gmap_geocoding_address").keypress(function (e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == '13') {
            e.preventDefault();
            handleAction();
        }
    });

}