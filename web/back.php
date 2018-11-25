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
$origin1 = explode(',', $origin);
$destination1 = explode(',', $destination);

for($j = 0; $j < $datanumber; $j++){
  if($status == ($j+1)){
    $steps[$j];
    $origin1 = explode(',', $steps[$j]);
    $destination1= explode(',', $steps[1]);
  }
}

if(isset($status)){
  if($status == 1){
    $steps[0];
    $origin1 = explode(',', $steps[0]);
    $destination1= explode(',', $steps[1]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  } else if($status == 2){
    $origin1 = explode(',', $steps[1]);
    $destination1= explode(',', $steps[2]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  }else if($status == 3){
    $origin1 = explode(',', $steps[2]);
    $destination1= explode(',', $steps[3]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  }else if($status == 4){
    $origin1 = explode(',', $steps[3]);
    $destination1= explode(',', $steps[4]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  }else if($status == 5){
    $origin1 = explode(',', $steps[4]);
    $destination1= explode(',', $steps[5]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  }else if($status == 6){
    $origin1 = explode(',', $steps[5]);
    $destination1= explode(',', $steps[6]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  }else if($status == 7){
    $origin1 = explode(',', $steps[6]);
    $destination1= explode(',', $steps[7]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  }else if($status == 8){
    $origin1 = explode(',', $steps[7]);
    $destination1= explode(',', $steps[8]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  }else if($status == 9){
    $origin1 = explode(',', $steps[8]);
    $destination1= explode(',', $steps[9]);
    echo getCompassDirection($origin1[0],$origin1[1], $destination1[0], $destination1[1]);
  }
  $my_file = fopen("status.txt", "w") or die ("no va");
  $sobre_escribiendo = fwrite($my_file, $status);
  fclose($my_file);
}

if(!isset($status)){
  $my_file = fopen("status_guide.txt", "r") or die ("no va");
  $contenido = fread($my_file, filesize("status_guide.txt"));
  echo $contenido;
  fclose($my_file);
}

function getCompassDirection($lat1,$lon1, $lat2, $lon2) {
  $bearing = (rad2deg(atan2(sin(deg2rad($lon2) - deg2rad($lon1)) * cos(deg2rad($lat2)), cos(deg2rad($lat1)) * sin(deg2rad($lat2)) - sin(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon2) - deg2rad($lon1)))) + 360) % 360;
   if($bearing>=0 && $bearing<22.5) $direction="NN"; 
   else if($bearing>=22.5 && $bearing<67.5) $direction="NE"; 
   else if($bearing>=67.5 && $bearing<112.5) $direction="EE"; 
   else if($bearing>=112.5 && $bearing<157.5) $direction="SE"; 
   else if($bearing>=157.5 && $bearing<202.5) $direction="SS"; 
   else if($bearing>=202.5 && $bearing<247.5) $direction="SW"; 
   else if($bearing>=247.5 && $bearing<292.5) $direction="WW"; 
   else if($bearing>=292.5 && $bearing<337.5) $direction="NW"; 
   else $direction="NN";
   return $direction;
}
 
?>
