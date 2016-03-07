<?php
	require '../../db_connect.php';
	$query = 'SELECT * FROM users_votes';
	$stmt = $dbh->prepare($query);
	$stmt->execute();
	//представляем результат в виде массива
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//конвертируем в JSON
	$json = json_encode($result);
	//отправляем для Angular результат
	echo $json;
?>