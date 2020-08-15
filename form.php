<?php

require ('src/connection.php');


$email = $_POST['email'];
$street = $_POST['street'];
$house = $_POST['home'];
$building = $_POST['part'];
$flat = $_POST['appt'];
$floor = $_POST['floor'];
$orderDate = date('y.m.d h:i');
$message = "Спасибо, ваш заказ будет доставлен по адресу: улица $street, дом $house/$building, кв $flat <br>";


$result = $pdo->prepare("SELECT * FROM clients WHERE `email` = :email ");
$result->execute([':email'=> $email]);
$user = $result->fetchAll(PDO::FETCH_ASSOC);



if ($user) {

	$updatedOrders = '';
	$thisUserId = '';

	foreach($user as $key => $value) {

		$thisUserId = $value['id'];
		$thisOrders = $value['orders'];
		$updatedOrders = $thisOrders + 1;
	}

	$add_order_query = $pdo->prepare("UPDATE `clients` SET `orders` = :orders WHERE `email` = :email");
	$add_order_query->bindParam (':email', $email);
	$add_order_query->bindParam (':orders', $updatedOrders);
	$add_order_query->execute();


	$new_order_query = $pdo->prepare("INSERT INTO orders (`order_date`,`street`, `house`,
			`building`, `flat`, `floor`, `client_id`)
				VALUES (:orderDate, :street, :house, :building, :flat, :floor, :clientId)");


	$new_order_query->bindParam (':orderDate', $orderDate);
	$new_order_query->bindParam (':street', $street);
	$new_order_query->bindParam (':house', $house);
	$new_order_query->bindParam (':building', $building);
	$new_order_query->bindParam (':flat', $flat);
	$new_order_query->bindParam (':floor', $floor);
	$new_order_query->bindParam (':clientId', $thisUserId);


	if (!$new_order_query->execute()){
		echo "There is some problems";
	}

	$resultOrder = $pdo->prepare("SELECT * FROM orders WHERE `client_id` = :clientId ");
	$resultOrder->bindParam (':clientId', $thisUserId);
	$resultOrder->execute([':clientId'=> $thisUserId]);
	$numberOrder = $resultOrder->fetchAll(PDO::FETCH_ASSOC);
	$orderNumber = '';

	foreach($numberOrder as $key => $value) {

		$orderNumber = $value['id'];
	}

	echo $message;
	echo "Номер вашего заказа: $orderNumber <br>";
	echo "Это ваш $updatedOrders заказ";

}else {

	$new_user_query = $pdo->prepare("INSERT INTO clients (`email`) VALUES (:email)");
	$new_user_query->bindParam (':email', $email);
	$new_user_query->execute();

	$newResult = $pdo->prepare("SELECT * FROM clients WHERE `email` = :email ");
	$newResult->execute([':email'=> $email]);
	$newUser = $newResult->fetchAll(PDO::FETCH_ASSOC);
	$newUserId = '';

	foreach($newUser as $key => $value) {

		$newUserId = $value['id'];
	}

	$first_order_query = $pdo->prepare("INSERT INTO orders (`order_date`,`street`, `house`, 
		`building`, `flat`, `floor`, `client_id`) 
			VALUES (:orderDate, :street, :house, :building, :flat, :floor, :clientId)");


	$first_order_query->bindParam (':orderDate', $orderDate);
	$first_order_query->bindParam (':street', $street);
	$first_order_query->bindParam (':house', $house);
	$first_order_query->bindParam (':building', $building);
	$first_order_query->bindParam (':flat', $flat);
	$first_order_query->bindParam (':floor', $floor);
	$first_order_query->bindParam (':clientId', $newUserId);

	if (!$first_order_query->execute()){
		echo "There is some problems";
	}

	$resultOrder = $pdo->prepare("SELECT * FROM orders WHERE `client_id` = :clientId ");
	$resultOrder->bindParam (':clientId', $newUserId);
	$resultOrder->execute([':clientId'=> $newUserId]);
	$numberOrder = $resultOrder->fetchAll(PDO::FETCH_ASSOC);
	$orderNumber = '';

	foreach($numberOrder as $key => $value) {

		$orderNumber = $value['id'];
	}

	echo $message;
	echo "Номер вашего заказа: $orderNumber <br>";
	echo "Это ваш первый заказ";

}

