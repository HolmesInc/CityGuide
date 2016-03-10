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
	try {
		/*Записываем данные о проголосовавшем и его голосе*/
		$query = 'INSERT INTO users_votes (userLogin, placeId, placeName, vote) values ("'.$userLogin.'", "'.$placeId.'", "'.$placeName.'", "'.$userVote.'")';
		$stm = $dbh->prepare($query);
		$stm->execute();
		/*Определяем тип голоса и обновляем данные о голосах в БД*/
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
			// представляем результат в виде массива
			$result = $stmt->fetch(PDO::FETCH_LAZY);
			$newValue = $result[0];
			$newValue += 1;
			$query = 'UPDATE propose_institutions SET negative_votes = "'.$newValue.'" WHERE name = "'.$placeName.'" AND id = "'.$placeId.'"';
			$stmt = $dbh->prepare($query);
			$stmt->execute();
		}
		/*Пересчитываем значения индекса валидности заведения*/
		$query = 'SELECT positive_votes, negative_votes, id FROM propose_institutions'; // получам значения положительных и отрицательных голосов
		$stmt = $dbh->prepare($query);
		$stmt->execute(); // выпоняем запрос
		$i = 0; // значение счётчика для циклов
		$positiveVotes = []; // массив значений положительных голосов
		$negativeVotes = []; // массив значений отрицательных голосов
		$dbPlaceId = []; // массив значений id заведений
		$rating = []; // массив значений рейтинга для заведений
		$totalVotes = [];
		while ($result = $stmt->fetch(PDO::FETCH_LAZY)) { // получаем зачения положительных и отрицательных голосов из запроса
			$positiveVotes[$i] = $result[0];
			$negativeVotes[$i] = $result[1];
			$dbPlaceId[$i] = $result[2];
			$i += 1;
		}

		for ($i = 0; $i < count($positiveVotes); $i++) { // вычисляем значение рейтинга
			$totalVotes[$i] = $positiveVotes[$i] + $negativeVotes[$i];
			$rating[$i] = ( $positiveVotes[$i] / $totalVotes[$i] ) - ( $negativeVotes[$i] / $totalVotes[$i] ); // формула получения значения рейтинга заведения
			$rating[$i] = round($rating[$i], 2); // округление до 2 символов после запятой
			if ( $rating[$i] > 0 ) { // исключаем наличие отрицательного или нулевого рейтинга
				$rating[$i] *= 100;
				if ($rating[$i] == 100)
					$rating[$i] = 99;
			}
			else if ( $rating[$i] <= 0 ) {
				$rating[$i] = 1;	
			}

			$query = 'UPDATE propose_institutions SET index_of_validity = "'.$rating[$i].'" WHERE id = "'.$dbPlaceId[$i].'"'; // обновляем значение индекса валидности в БД
			$stm = $dbh->prepare($query);
			$stm->execute();

			$query = 'UPDATE propose_institutions SET total_votes = "'.$totalVotes[$i].'" WHERE id = "'.$dbPlaceId[$i].'"'; // обновляем значение общего числа голосов в БД
			$stm = $dbh->prepare($query);
			$stm->execute();
		}
	}
	catch(PDOException $e) {  
		echo $e->getMessage();
	}
?>