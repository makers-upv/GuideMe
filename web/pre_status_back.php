<?php
$status_map_antiguo = $_GET['status_map_antiguo'];

echo $status_map_antiguo;
$my_file = fopen("status_map.txt", "w") or die ("no va");
$writing = fwrite($my_file, $status_map_antiguo);
fclose($my_file);

?>
