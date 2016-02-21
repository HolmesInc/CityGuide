<?php 
	//определяем переменные для подключения к БД
	$db_name  = 'arrow_db';
	$hostname = '127.0.0.1';
	$username = 'holmes';
	$password = '123';

	//подключаемся к базе
	$dbConnect = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
	//получаем нужные нам данные
	$querry = 'SELECT * FROM institutions WHERE category = "Pizza" ';
	$stmt = $dbConnect->prepare($querry);
	$stmt->execute();
	//представляем результат в виде массива
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//конвертируем в JSON
	$json = json_encode($result);
	//отправляем для Angular результат
	echo $json;
?>