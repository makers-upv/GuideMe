<?php
$my_file = fopen("status_map.txt", "r");
$status_map = fread($my_file, filesize("status_map.txt"));

echo $status_map;
?>
