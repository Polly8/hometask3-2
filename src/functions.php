<?php

require ('src/connection.php');


function createOrder($street, $house, $building, $flat, $floor, $userId){

	global $pdo;

	$new_order_query = $pdo->prepare("INSERT INTO orders (`street`, `house`,
			`building`, `flat`, `floor`, `client_id`)
				VALUES (:street, :house, :building, :flat, :floor, :clientId)");


	if (!$new_order_query->execute([':street' => $street, ':house' => $house,
		':building' => $building, ':flat' => $flat, ':floor' => $floor, ':clientId' => $userId])){

		echo "There is some problems";
	}
}


function createUser($email){

	global $pdo;

	$new_user_query = $pdo->prepare("INSERT INTO clients (`email`) VALUES (:email)");
	$new_user_query->execute([':email' => $email]);

}


function selectByEmail($email){

	global $pdo;
	global $user;

	$result = $pdo->prepare("SELECT * FROM clients WHERE `email` = :email ");
	$result->execute([':email'=> $email]);
	$user = $result->fetch(PDO::FETCH_ASSOC);
}


function selectById($userId){

	global $pdo;
	global $orderNumber;

	$resultOrder = $pdo->prepare("SELECT * FROM orders WHERE `client_id` = :clientId ");
	$resultOrder->execute([':clientId'=> $userId]);
	$numberOrder = $resultOrder->fetchAll(PDO::FETCH_ASSOC);


	$orderNumber = '';

	foreach($numberOrder as $key => $value) {

		$orderNumber = $value['id'];
	}
}


function typeMessage($street, $house, $building, $flat){

	global $orderNumber;
	$message = "Спасибо, ваш заказ будет доставлен по адресу: улица $street, дом $house/$building, кв $flat <br>";

	echo $message;
	echo "Номер вашего заказа: $orderNumber <br>";

}