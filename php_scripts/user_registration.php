<?php
	//получаем данные из формы регистрации
	$data = json_decode(file_get_contents("php://input"));
	$userName = mysql_real_escape_string($data->name);
	$userEmail = mysql_real_escape_string($data->email);
	$userPassword = mysql_real_escape_string($data->password);
	//подготавливаем их к записи в БД
	$userLogin = str_replace('.', '', $userEmail);
	$userPassword = md5($userPassword);
	require 'db_connect.php';
	$query = 'INSERT INTO users (name,email,login,password) values ("'.$userName.'","'.$userEmail.'","'.$userLogin.'","'.$userPassword.'")';
	$stm = $dbh->prepare($query);
	$stm->execute($values);
?>