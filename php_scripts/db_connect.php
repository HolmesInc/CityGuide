<?php
	$db_name  = 'arrow_db';
	$hostname = '127.0.0.1';
	$db_username = 'holmes';
	$db_password = '123';
	// подключаемся к базе данных
	$dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $db_username, $db_password);
?>