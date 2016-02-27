<?php
	//получаем данные
	$data = json_decode(file_get_contents("php://input"));
	$placeName = mysql_real_escape_string($data->name);
	$placeCategory = mysql_real_escape_string($data->placeCategory);
	$priceIndex = mysql_real_escape_string($data->priceIndex);
	$openTime = mysql_real_escape_string($data->openTime);
	$closeTime = mysql_real_escape_string($data->closeTime);
	$address = mysql_real_escape_string($data->address);
	$metro = mysql_real_escape_string($data->metro);
	$phone = mysql_real_escape_string($data->phone);
	$site = mysql_real_escape_string($data->site);
	//определяем переменные для подключения к БД
	require '../../db_connect.php';
	$querry = 'INSERT INTO propose_institutions (name, category, pryce_index, open_time, close_time, address, metro, phone, site) values ("'.$placeName.'","'.$placeCategory.'","'.$priceIndex.'","'.$openTime.'","'.$closeTime.'","'.$address.'","'.$metro.'","'.$phone.'","'.$site.'")';
	$stm = $dbh->prepare($querry);
	$stm->execute($values);
?>