$ = jQuery.noConflict();

$(document).ready(function(){
    if ( $( "#fechaPedido" ).length ) {
        $( "#fechaPedido" ).datepicker();
    }
    if ( $( "#gmap_admin_orders" ).length ) {
        mapAdminOrders();
    }
    
});

var mapAdminOrders = function () {
    var origen = [19.4016653,-99.1743618];
    var color = ["#FFFF00", "#0174DF", "#5FB404", "#AC58FA", "#FF8000", "#FFFF00", "#0174DF", "#5FB404", "#AC58FA", "#FF8000"];
    var map = new GMaps({
        div: '#gmap_admin_orders',
        lat: 19.4389263,
        lng: -99.1278322,
        zoom: 11
    });

    $(".orderMap").each(function() {
        map.addMarker({
            lat: $(this).data('lat'),
            lng: $(this).data('long'),
            title: '# '+$(this).data("num"),
            infoWindow: {
                content: '<span style="color:#000">'+$(this).html()+'<br>'+$(this).data('dir')+'</span>'
            }
        });
    });

    $('#gmap_admin_orders_start').click(function (e) {
        e.preventDefault();
        $(".orderMap").each(function(index) {
            $('#gmap_admin_orders_instructions').append('<h5>' +$(this).html()+'<br>'+$(this).data('dir')+ '</h5>');
            var destino = [$(this).data('lat'),$(this).data('long')];
            map.travelRoute({
                origin: origen,
                destination: destino,
                travelMode: 'driving',
                step: function (e) {
                    $('#gmap_admin_orders_instructions').append('<li>' + e.instructions + '</li>');
                    $('#gmap_admin_orders_instructions li:eq(' + e.step_number + ')').delay(800 * e.step_number).fadeIn(500, function () {
                        map.setCenter(e.end_location.lat(), e.end_location.lng());
                        map.drawPolyline({
                            path: e.path,
                            strokeColor: color[index],
                            strokeOpacity: 0.6,
                            strokeWeight: 6
                        });
                    });
                }
            });
            origen = destino;
        });
    });
}