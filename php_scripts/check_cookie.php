<?php
	function CheckCookies(){
		require 'db_connect.php';
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