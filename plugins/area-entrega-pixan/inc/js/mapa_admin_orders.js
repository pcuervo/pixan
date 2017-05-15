$ = jQuery.noConflict();

$(document).ready(function(){
    if($("#gmap_admin_orders").length > 0) {
        map = new GMaps({
            div: '#gmap_admin_orders',
            lat: 19.4389263,
            lng: -99.1278322,
            zoom: 11
        });
        $('#gmap_admin_orders_instructions').hide();
        if ( $( "#fechaPedido" ).length ) {
            $( "#fechaPedido" ).datepicker();
        }
        if ( $( "#gmap_admin_orders" ).length ) {
            mapAdminOrders();
        }

        $(".orderMap").click(function() {
            mapAdminOrders();
        });

        $("#btnImprimir").click(function(e) {
            e.preventDefault();
            //calcularRuta(map,true);
            printMaps();
           
        });

        $('#gmap_admin_orders_start').click(function (e) {
            e.preventDefault();
            calcularRuta(map,false);
        });
    }
    
});

var imprSelec = function ()
{
  var ficha = document.getElementById("divImprimir");
  var mapa = document.getElementById("gmap_admin_orders");
  var izquierda = (screen.width-800)/2;
  var ventimp = window.open(' ', 'popimpr','width=800,height=800,left='+izquierda+'scrollbars=NO');
  ventimp.document.write( mapa.innerHTML+'<div style="margin-top:400px">'+ ficha.innerHTML+'</div>');
  //ventimp.document.write(  );
  ventimp.document.close();
  ventimp.print( );
  ventimp.close();
    
}

function printMaps() {
    $( ".orderMap:not(:checked)").parent().hide();
    var body               = $('body');
    var mapContainer       = $('#gmap_admin_orders');
    var ficha = $("#divImprimir");
    var mapContainerParent = mapContainer.parent();
    var printContainer     = $('<div>');

    printContainer
        .addClass('print-container')
        .css('position', 'relative')
        .height(mapContainer.height())
        .append(mapContainer)
        .prependTo(body);

    printContainer.append( ficha.html() );

    var content = body
        .children()
        .not('script')
        .not(printContainer)
        .detach();

    /*
     * Needed for those who use Bootstrap 3.x, because some of
     * its `@media print` styles ain't play nicely when printing.
     */
    var patchedStyle = $('<style>')
        .attr('media', 'print')
        .text('img { max-width: none !important; }' +
              'a[href]:after { content: ""; }')
        .appendTo('head');

    window.print();

    body.prepend(content);
    mapContainerParent.prepend(mapContainer);
    

    printContainer.remove();
    patchedStyle.remove();
    $( ".orderMap:not(:checked)").parent().show();
}

var calcularRuta = function(map,imprimir) {

    $('#gmap_admin_orders_instructions').html('');
    var origen = [19.4016653,-99.1743618];
    //var origen = [18.994597,-99.481275];
    var color = ["#FFFF00", "#0174DF", "#5FB404", "#AC58FA", "#FF8000", "#FFFF00", "#0174DF", "#5FB404", "#AC58FA", "#FF8000"];
    $(".orderMap:checked").each(function(index) {
        //$('#gmap_admin_orders_instructions').append('<h5>' +$(this).html()+'<br>'+$(this).data('dir')+ '</h5>');
        //console.log('RUTA -> '+$(this).data('info'));
        var destino = [$(this).data('lat'),$(this).data('long')];
        map.travelRoute({
            origin: origen,
            destination: destino,
            travelMode: 'driving',
            step: function (e) {
                $('#gmap_admin_orders_instructions').append('<li>' + e.instructions + '</li>');
                $('#gmap_admin_orders_instructions li:eq(' + e.step_number + ')').delay(800 * e.step_number).fadeIn(500, function () {
                    map.setCenter(e.end_location.lat(), e.end_location.lng());
                    //console.log(e.end_location.lat()+' '+e.end_location.lng());
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
    
    $("#btnImprimir").removeAttr('disabled');
    if(imprimir) {
         //setTimeout('printMaps();', 4000);
    }
}

var mapAdminOrders = function () {
    $('#gmap_admin_orders_instructions').html('');
    var bounds = new google.maps.LatLngBounds();
    
    map.removeMarkers();

    $(".orderMap:checked").each(function() {
        //console.log($(this).data('info'));
        var pedido = new google.maps.LatLng($(this).data('lat'), $(this).data('long'));
        map.addMarker({
            lat: $(this).data('lat'),
            lng: $(this).data('long'),
            title: '# '+$(this).data("num"),
            infoWindow: {
                content: '<span style="color:#000">'+$(this).data('info')+'<br>'+$(this).data('dir')+'</span>'
            }
        });
        bounds.extend(pedido);
    });
    map.fitBounds(bounds);
}

