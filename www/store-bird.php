<?php

# Form data
$scientific_name = $_POST["scientific_name"];
$common_name = $_POST["common_name"];
$sighting_time = $_POST["sighting_time"];
$location = $_POST["location"];

//check if any field is empty
if (empty($scientific_name) or empty($common_name) or empty($sighting_time) or empty($location)) {
	echo '422';
} else {
	try {
		$db_host   = '192.168.2.12:3306';
		$db_name   = 'fvision';
		$db_user   = 'webuser';
		$db_passwd = 'insecure_db_pw';

		$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

		$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);


		$stmt = $pdo->prepare("INSERT INTO birds (scientific_name, common_name, sighting_time, location)
			VALUES (:scientific_name, :common_name, :sighting_time, :location)");
		$stmt->bindParam(':scientific_name', $scientific_name);
		$stmt->bindParam(':common_name', $common_name);
		$stmt->bindParam(':sighting_time', $sighting_time);
		$stmt->bindParam(':location', $location);

		if ($stmt->execute()=== TRUE) {
		# Successful data insertion
			$last_id =  $pdo->lastInsertId();
			//make dir if doesn't exist
			$b_dir = '/vagrant/www/birdimages/';
			if ( ! is_dir($b_dir)) {
			    mkdir($b_dir);
			}
			move_uploaded_file($_FILES["file"]["tmp_name"], $b_dir . $last_id . '.jpg');
			echo 200;
		} else {
			echo 500;
		}

	} catch(PDOException $e) {
		echo "500";
	}
}
?>