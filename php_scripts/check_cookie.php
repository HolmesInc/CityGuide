<?php
	function CheckCookies(){
		// задаём переменные для подключения к базе данных
		$db_name  = 'arrow_db';
		$hostname = '127.0.0.1';
		$db_username = 'holmes';
		$db_password = '123';

		// подключаемся к базе данных
		$dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $db_username, $db_password);

		// делаем запрос на получение данных
		$sql = 'SELECT login FROM users';
		    
		$stmt = $dbh->prepare( $sql );
		// запускаем запрос
		$stmt->execute();

		$checkCookie = 0;
		while ($compare = $stmt->fetch(PDO::FETCH_LAZY)){
			if (isset($_COOKIE[$compare->login])){
				$checkCookie = 1;
				break;
			}
		}
		return $checkCookie;
	}
?>