<?php

require ('config.php');

try {

	$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);

}catch (PDOException $e) {

	echo $e->getMessage();
	die;
}


