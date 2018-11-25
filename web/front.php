<?php
$status = $_GET['status'];

$origin =  "61.506936,23.7982224";
$destination = "61.5056461,23.812458"; 

$request="https://maps.googleapis.com/maps/api/directions/json?origin=";
$request.=$origin;
$request.="&destination=";
$request.=$destination;
$request.="&mode=walking&key=KEY_GOOGLE_XXXXXXXXXXXXXXXXXXXXXXXXXXXX";

$jsondata = file_get_contents($request);
$response_a = json_decode($jsondata);
$datanumber =  sizeof($response_a->routes[0]->legs[0]->steps);

for ($i = 0; $i < $datanumber; $i++) {
  $steps[$i] = (string)$response_a->routes[0]->legs[0]->steps[$i]->start_location->lat;
  $steps[$i] .= ",";
  $steps[$i] .= (string)$response_a->routes[0]->legs[0]->steps[$i]->start_location->lng;
}
$steps[$datanumber] = $destination;
?>

<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=KEY_GOOGLE_XXXXXXXXXXXXXXXXXXXXXXXXXXXX"></script>
<div align="center">
  <img style="margin-left: 33.3%;" src="image/logotampere.png" height="14%" width="28%" align="left" >
</div>
<br><br>
<div id="map_canvas" style="height:78%;width: 100%"></div>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<div align="right">
  <img style=" margin-top: 25px; margin-left: 15%;" src="image/junction_logo_black.png" height="3%" width="15%" align="left" >
</div>
<div align="center">
  <img style="margin-top: 10px; margin-left: 230px;" src="image/makers.png" height="6%" width="9%" align="left" >
</div>
<div align="left">
<img style="margin-top: -155px; margin-left: 200px; margin-bottom: -155px;" src="image/logoguideme.png" height="35%" width="20%" align="left" >
</div> 
<br><br>
<script>
var map;
var bounds;
var polyline;
var valor_baston= 0;
var markers = [];
var markers2 = [];
var markers3 = [];
var latitud_makers = [];
var longitud_makers = [];
var posicion_general = 0;
var posicion_inicial=0;
var posicion_final=0;
var posicion_general_no_tocar=0;
var file=0;

bounds = new google.maps.LatLngBounds(null);
var jArray = <?php echo json_encode($steps); ?>;
var status_map_antiguo = 0;

for(var i=0; i<jArray.length; i++){
  var aux = jArray[i];
  var str = aux.split(',');
  var lat = parseFloat(str[0]);
  latitud_makers[i] = lat;
  var long = parseFloat(str[1]);
  longitud_makers[i] = long;
  markers[i] = new google.maps.LatLng(latitud_makers[i], longitud_makers[i]); 
  bounds.extend(markers[i]);
}
var punto_final_no_tocar = {lat: latitud_makers[jArray.length-1], lng: longitud_makers[jArray.length-1]};
var punto_inicial_no_tocar = {lat: latitud_makers[0], lng: longitud_makers[0]};

init();
 
(function worker() {
   $.ajax({
    url: 'http://localhost:8888/GuideMe/back.php', 
    success: function(datos_leidos) {    
      valor_baston= parseInt(datos_leidos);
      posicion_general = valor_baston-1;
      
      markers2[0] = new google.maps.LatLng(latitud_makers[posicion_general], longitud_makers[posicion_general]);
      markers2[1] = new google.maps.LatLng(latitud_makers[posicion_general+1], longitud_makers[posicion_general+1]);

      for(var p=0; p<=posicion_general; p++){
          markers3[p] = new google.maps.LatLng(latitud_makers[p], longitud_makers[p]);
        }
       posicion_general_no_tocar = {lat: latitud_makers[posicion_general], lng: longitud_makers[posicion_general]};

       $.get( "http://localhost:8888/GuideMe/pre_status_front.php", function(ss){
        sss= parseInt(ss);
        status_map_antiguo = sss;
        });
   
       if(status_map_antiguo != valor_baston){  
        $.get( "http://localhost:8888/GuideMe/pre_status_back.php?status_map_antiguo="+valor_baston, function(data_leida){
        });
            console.log("status_map_antiguo: "+status_map_antiguo);
            console.log("valor_baston: "+valor_baston);

        init();
       }else{}
    },
    complete: function() {
      // Schedule the next request when the current one's complete
      setTimeout(worker, 2000);
    }
  });
 })();

function init() {
  var moptions = {
    center: new google.maps.LatLng(latitud_makers[posicion_general],longitud_makers[posicion_general]),
    zoom: 16,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }

  map = new google.maps.Map(document.getElementById("map_canvas"), moptions);
  map.fitBounds(bounds);

  var iconsetngs = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
   };

  var polylineoptns = {
    strokeOpacity: 0.8,
    strokeWeight: 3,
        strokeColor: "#088da5",
    map: map,
    icons: [{
        repeat: '70px', //CHANGE THIS VALUE TO CHANGE THE DISTANCE BETWEEN ARROWS
        icon: iconsetngs,
        offset: '100%'}]
  };

  var polylineoptns2 = {
    strokeOpacity: 0.8,
    strokeWeight: 3,
        strokeColor: "#ff3633",
    map: map,
    icons: [{
        repeat: '20px', //CHANGE THIS VALUE TO CHANGE THE DISTANCE BETWEEN ARROWS
        icon: iconsetngs,
        offset: '100%'}]
  };

  var polylineoptns3 = {
    strokeOpacity: 0.8,
    strokeWeight: 3,
        strokeColor: "#aaaaaa",
    map: map,
    
  };

  polyline = new google.maps.Polyline(polylineoptns);
  polyline2 = new google.maps.Polyline(polylineoptns2);
  polyline3 = new google.maps.Polyline(polylineoptns3);

  var z = 0;
  var z2 = 1;          
  var z3 = 2;
  var path = [];
  path[z2] = polyline2.getPath();
  path[z3] = polyline3.getPath();
  path[z] = polyline.getPath();
       
  var markador = new google.maps.Marker({
    position: punto_final_no_tocar,
    icon: { url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"},
    map: map
  });
  var markador = new google.maps.Marker({
    position: punto_inicial_no_tocar,
    icon: { url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"},
    map: map
  });
  var markador = new google.maps.Marker({
    position: posicion_general_no_tocar,
    map: map
  });

  for (var i =markers3.length ; i < markers.length; i++){ //LOOP TO DISPLAY THE MARKERS
    var pos = markers[i];
    var marker = new google.maps.Marker({
      position: pos,             
      icon: { url: "http://maps.google.com/mapfiles/ms/icons/none.png"},
      map: map
    });
    xpath[z].push(marker.getPosition()); //PUSH THE NEWLY CREATED MARKER'S POSITION TO THE PATH ARRAY
  }

  for (var i = 0; i < markers2.length; i++){ //LOOP TO DISPLAY THE MARKERS
      var posicion_general_marker = markers2[i];
      var marker2 = new google.maps.Marker({
        position: posicion_general_marker,             
        icon: { url: "http://maps.google.com/mapfiles/ms/icons/none.png"},
        map: map
      });
      path[z2].push(marker2.getPosition()); //PUSH THE NEWLY CREATED MARKER'S POSITION TO THE PATH ARRAY
  }

  for (var i = 0; i < markers3.length; i++){ //LOOP TO DISPLAY THE MARKERS
    var posicion_general_marker = markers3[i];
    var marker3 = new google.maps.Marker({
      position: posicion_general_marker,             
      icon: { url: "http://maps.google.com/mapfiles/ms/icons/none.png"},
      map: map
    });
    path[z3].push(marker3.getPosition()); //PUSH THE NEWLY CREATED MARKER'S POSITION TO THE PATH ARRAY
  }
}
window.onload = init;
</script>






