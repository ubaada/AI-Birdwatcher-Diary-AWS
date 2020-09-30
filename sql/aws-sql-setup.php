<?php

try {
	# Load db config variables from file
	include __DIR__ . '/../.config/db-config.php';

	# Execute table setup sql file from this php file
	$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

	$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
	$sql = file_get_contents(__DIR__ .'/setup-database.sql');
	$qr = $pdo->exec($sql);

	# Print to shell
	print("Birds table created!");
	print("Sample data added!");
} catch(PDOException $e) {
	echo $e;
}
?>