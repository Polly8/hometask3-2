<?php

if (empty($_POST)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

require ('src/connection.php');
require ('src/functions.php');


$email = $_POST['email'];
$street = $_POST['street'];
$house = $_POST['home'];
$building = $_POST['part'];
$flat = $_POST['appt'];
$floor = $_POST['floor'];



if ($email){

	selectByEmail($email);

}else{
	echo "Укажите свой e-mail";
	exit;
}



if ($user) {

	$userId = $user['id'];

	createOrder($street, $house, $building, $flat, $floor, $userId);

	selectById($userId);

	$count = $pdo->prepare("SELECT COUNT(*) as count FROM `orders` WHERE `client_id` = :userId");
	$count->execute([':userId' => $userId]);
	$countResult = $count->fetch(PDO::FETCH_ASSOC);
	$countOrder = $countResult['count'];

	typeMessage($street, $house, $building, $flat);

	echo "Это ваш $countOrder заказ";

}else {

	createUser($email);

	selectByEmail($email);

	$userId = $user['id'];

	createOrder($street, $house, $building, $flat, $floor, $userId);

	selectById($userId);

	typeMessage($street, $house, $building, $flat);

	echo "Это ваш первый заказ";

}