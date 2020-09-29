<?php

try {
	include __DIR__ . '/../.config/db-config.php';

	$pdo_dsn = "mysql:host=$db_host;dbname=$db_name";

	$pdo = new PDO($pdo_dsn, $db_user, $db_passwd);
	$sql = file_get_contents(__DIR__ .'/setup-database.sql');
	$qr = $pdo->exec($sql);
	print("Birds table created!");
	print("Sample data added!");
} catch(PDOException $e) {
	echo $e;
}
?>