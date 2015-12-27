<?php
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

	$checkCookie = 0;//для проверки
	while ($compare = $stmt->fetch(PDO::FETCH_LAZY)){
		if (isset($_COOKIE[$compare->login])){
			$checkCookie = 1;
			$cookieName = $compare->login;//для разлогинивания
			break;
		}
	}
	if ($checkCookie === 0){
		//перенаправляем на главную
		header('Location: http://arrow.ru');
	}
	else{
		// удаляем cookie
		setcookie($cookieName, '', 0, '/');
		// перенаправляем на главную
		header('Location: http://arrow.ru');
	}	
?>