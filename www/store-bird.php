<?php

# Form data
$scientific_name = $_POST["scientific_name"];
$common_name = $_POST["common_name"];
$sighting_time = $_POST["sighting_time"];
$location = $_POST["location"];

//check if any field is empty
$img_val =false;
if(exif_imagetype($_FILES["file"]["tmp_name"])) {
	$img_val =true;
}
if (empty($scientific_name) or empty($common_name) or empty($sighting_time) or empty($location) or !$img_val) {
	echo '422';
} else {
	try {
		include __DIR__ . '/../.config/db-config.php';
		
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
			$b_dir = '/etc/birdimages/';
			if ( ! is_dir($b_dir)) {
			    mkdir($b_dir);
			}
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $b_dir . $last_id . '.jpg')==false) {
				//image couldn't be uploaded
				echo "500";
				return;

			}
			echo 200;
		} else {
			echo "500";
		}

	} catch(PDOException $e) {
		echo "500";
	}
}
?>