<?php
	$db_name  = 'arrow_db';
	$hostname = '127.0.0.1';
	$username = 'holmes';
	$password = '123';

	$dom = new DOMDocument("1.0");
	$dom->formatOutput = true;
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node);
	$dbConnect = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
	if (!$dbConnect) { 
		die(' Нет соединения с БД ');
	}
	$query = "SELECT markers.lat, markers.lng, institutions.name, institutions.adress, institutions.phone 
		FROM institutions 
		INNER JOIN markers 
		ON markers.id_institution = institutions.id";
	$stmt = $dbConnect->prepare($query);
	$stmt->execute();

	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	header("Content-Type: text/xml");

	foreach ($result as $row) {
		// Добавление нового узла в XML
		$node = $dom->createElement("marker");
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("name",$row['name']);
		$newnode->setAttribute("adress", $row['adress']);
		$newnode->setAttribute("phone", $row['phone']);
		$newnode->setAttribute("lat", $row['lat']);
		$newnode->setAttribute("lng", $row['lng']);
	}
	print_r($dom->saveXML());
?>