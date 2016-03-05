<?php
	require '../../db_connect.php';
	$query = 'SELECT * FROM propose_institutions  WHERE approval_indicator = 1';
	$stmt = $dbh->prepare($query);
	$stmt->execute();
	//представляем результат в виде массива
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//конвертируем в JSON
	$json = json_encode($result);
	//отправляем для Angular результат
	echo $json;
?>