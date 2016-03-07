<?php
	//получаем данные из формы регистрации

	$data = json_decode(file_get_contents("php://input"));
	$userLogin = mysql_real_escape_string($data->userLogin);
	$placeId = (int)mysql_real_escape_string($data->placeId);
	$placeName = mysql_real_escape_string($data->placeName);
	$vote = mysql_real_escape_string($data->vote);
	if ($vote == true) {
		$userVote = 'true';
	}
	else if ($vote == false) {
		$userVote = 'false';
	}
	//определяем переменные для подключения к БД
	require '../../db_connect.php';
	try{
		$query = 'INSERT INTO users_votes (userLogin, placeId, placeName, vote) values ("'.$userLogin.'", "'.$placeId.'", "'.$placeName.'", "'.$userVote.'")';
		$stm = $dbh->prepare($query);
		$stm->execute();

		if ($vote == true) {
			$query = 'SELECT positive_votes FROM propose_institutions  WHERE name = "'.$placeName.'" AND id = "'.$placeId.'"';
			$stmt = $dbh->prepare($query);
			$stmt->execute();
			//представляем результат в виде массива
			$result = $stmt->fetch(PDO::FETCH_LAZY);
			$newValue = $result[0];
			$newValue += 1;
			$query = 'UPDATE propose_institutions SET positive_votes = "'.$newValue.'" WHERE name = "'.$placeName.'" AND id = "'.$placeId.'"';
			$stmt = $dbh->prepare($query);
			$stmt->execute();
		}
		else if ($vote == false) {
			$query = 'SELECT negative_votes FROM propose_institutions  WHERE name = "'.$placeName.'" AND id = "'.$placeId.'"';
			$stmt = $dbh->prepare($query);
			$stmt->execute();
			//представляем результат в виде массива
			$result = $stmt->fetch(PDO::FETCH_LAZY);
			$newValue = $result[0];
			$newValue += 1;
			$query = 'UPDATE propose_institutions SET negative_votes = "'.$newValue.'" WHERE name = "'.$placeName.'" AND id = "'.$placeId.'"';
			$stmt = $dbh->prepare($query);
			$stmt->execute();
		}
	}
	catch(PDOException $e) {  
		echo $e->getMessage();
	}
/*
	require '../../db_connect.php';
	$placeId = 1;
	$placeName = 'Kronverk';
	$vote = true;
	$query = 'SELECT positive_votes FROM propose_institutions  WHERE name = "'.$placeName.'" AND id = "'.$placeId.'"';
	$stmt = $dbh->prepare($query);
	$stmt->execute();
	//представляем результат в виде массива
	$result = $stmt->fetch(PDO::FETCH_LAZY);
	print_r($result[0]);
*/
?>