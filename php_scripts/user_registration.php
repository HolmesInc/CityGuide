<?php
	//получаем данные из формы регистрации
	$data = json_decode(file_get_contents("php://input"));
	$userName = mysql_real_escape_string($data->name);
	$userEmail = mysql_real_escape_string($data->email);
	$userPassword = mysql_real_escape_string($data->password);
	//подготавливаем их к записи в БД
	$userLogin = str_replace('.', '', $userEmail);
	$userPassword = md5($userPassword);
	//определяем переменные для подключения к БД
	$db_name  = 'arrow_db';
	$hostname = '127.0.0.1';
	$username = 'holmes';
	$password = '123';
	//подключаемся к базе
	$dbConnect = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
	$querry = 'INSERT INTO users (name,email,login,password) values ("'.$userName.'","'.$userEmail.'","'.$userLogin.'","'.$userPassword.'")';
	$stm = $dbConnect->prepare($querry);
	$stm->execute($values);
?>