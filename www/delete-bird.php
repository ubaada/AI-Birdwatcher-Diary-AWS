<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try{
	$id = $_GET["id"];
	include __DIR__ . '/../.config/db-config.php';

	$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

	$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);

	$stmt = $pdo->prepare("DELETE FROM birds WHERE id=:id");
	$stmt->bindParam(':id', $id);
	if ($stmt->execute()=== TRUE) {
		//successfully deleted row
		//Now delete its image.
		unlink('/etc/birdimages/' . $id . '.jpg');
		echo "200";
	} else {
		echo "500";
	}
} catch(PDOException $e) {
	echo $e;
}

?>