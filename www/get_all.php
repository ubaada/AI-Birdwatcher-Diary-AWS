<?php

function clean($string) {
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

$db_host   = '192.168.2.12:3306';
$db_name   = 'fvision';
$db_user   = 'webuser';
$db_passwd = 'insecure_db_pw';

$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

$q = $pdo->query("SELECT * FROM birds");

while($row = $q->fetch()){
	$s ='"';
	echo "<div class='card hoverable' onclick='show_dialog("
	. $s . $row["id"] . $s . ',' 
	. $s . clean($row["scientific_name"]) . $s . ',' 
	. $s . clean($row["common_name"]) . $s . ',' 
	. $s . clean($row["sighting_time"]) . $s . ',' 
	. $s . clean($row["location"]) . $s  .
	")'>
	<div class='row'><div class='col s8'>
	<div class ='truncate grey-text text-darken-1' style='padding-left:10px;padding-top: 14px;'>"
	. htmlentities($row['common_name']) . 
	"</div></div><div class='col s4 right-align'>
	<img style ='max-width:50px;height:50px;margin:auto;' src='birdimages/". $row['id'] .".jpg'/>
	</div>
	</div>
	</div>";
}

?>