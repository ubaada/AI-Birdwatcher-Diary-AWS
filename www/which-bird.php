<?php

if ( 0 < $_FILES['birdfile']['error'] ) {
	echo 'Error: ' . $_FILES['birdfile']['error'] . '<br>';
}
else {

	if (function_exists('curl_file_create')) { // php 5.5+
	  $file = curl_file_create($_FILES['file']['tmp_name'],'image/jpeg','test_name');
	} else { // 
	  $file = '@' . realpath($_FILES['file']['tmp_name']);
	}

	$data['file'] = $file;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)");

	curl_setopt($ch, CURLOPT_URL, 'http://192.168.2.13:5000/');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$result=curl_exec($ch);
	echo $result;
}


?>