$ = jQuery.noConflict();
$(document).ready(function(){
    if(document.getElementById("coordenadas").value != '') {
      pintarMapa();
    }
    else {
      initMap();
    }

    $('#clearPolygon').click(function(e){
        e.preventDefault();
        document.getElementById("coordenadas").value = '';
        zona.setMap(null);
    })
});


function initMap() {
    console.log('iniciando mapa...');
  var mapOptions = {
    center: new google.maps.LatLng(19.4389263,-99.1278322),
    zoom: 12
  };

  var map = new google.maps.Map(document.getElementById('gmap_geo'),
    mapOptions);

  var drawingManager = new google.maps.drawing.DrawingManager({
    //drawingMode: google.maps.drawing.OverlayType.POLYGON,
    drawingControl: true,
    drawingControlOptions: {
        position: google.maps.ControlPosition.TOP_CENTER,
        drawingModes: [google.maps.drawing.OverlayType.POLYGON]
    },
    polygonOptions: {
        fillColor: '#ffff00',
        fillOpacity: 0.4,
        strokeWeight: 3,
        clickable: true,
        zIndex: 1,
        editable: false
    }
   });


   google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
        var coordinates = (polygon.getPath().getArray());
        console.log(coordinates);
        console.log('jQuery no esta funcionando');
        //$("#coordenadas").val(coordinates);
        document.getElementById("coordenadas").value = coordinates;
    });

  drawingManager.setMap(map);

}

function pintarMapa() {
  var mapOptions = {
    zoom: 12,
    center: new google.maps.LatLng(19.4389263,-99.1278322),
    mapTypeId: google.maps.MapTypeId.STREET
  };

  
  var map = new google.maps.Map(document.getElementById('gmap_geo'),
      mapOptions);

  var drawingManager = new google.maps.drawing.DrawingManager({
    //drawingMode: google.maps.drawing.OverlayType.POLYGON,
    drawingControl: true,
    drawingControlOptions: {
        position: google.maps.ControlPosition.TOP_CENTER,
        drawingModes: [google.maps.drawing.OverlayType.POLYGON]
    },
    polygonOptions: {
        fillColor: '#ffff00',
        fillOpacity: 0.4,
        strokeWeight: 3,
        clickable: true,
        zIndex: 1,
        editable: false
    }
   });
  google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
        var coordinates = (polygon.getPath().getArray());
        console.log(coordinates);
        console.log('jQuery no esta funcionando');
        //$("#coordenadas").val(coordinates);
        document.getElementById("coordenadas").value = coordinates;
    });
  drawingManager.setMap(map);

  // Define the LatLng coordinates for the polygon's path. Note that there's
  // no need to specify the final coordinates to complete the polygon, because
  // The Google Maps JavaScript API will automatically draw the closing side.
  
  color = ["#FFFF00", "#0174DF", "#5FB404", "#AC58FA", "#FF8000"];
  c = 0;
  
  var coor = document.getElementById("coordenadas").value;
  var favcolor = document.getElementById("favcolor").value;
  console.log(favcolor);
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
    strokeColor: favcolor,
    strokeOpacity: 0.55,
    strokeWeight: 1,
    fillColor: favcolor,
    fillOpacity: 0.35
  });
  //c++;
  zona.setMap(map);

  
    
}



