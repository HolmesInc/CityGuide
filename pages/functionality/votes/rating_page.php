﻿<!DOCTYPE html>
<html ng-app="ratingApp">
<head>
	<title>ARROW Голосую</title>
	<link rel="shortcut icon" href="../../img/arrow.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../../style.css">
	<link rel="stylesheet" type="text/css" href="../../addons/bootstrap/css/bootstrap.css">
	<style type="text/css">
		.navbar-brand a {
			text-decoration: none;
		}		
	</style>
	<script type="text/javascript" src="../../addons/angular.js"></script>
	<script type="text/javascript" src="../../../addons/jquery.min.js"></script>
	<script type="text/javascript" src="../../../addons/angular-lib/graphs/rg-min.js"></script>
	<script type="text/javascript" src="../../../addons/angular-lib/graphs/angular-rg-lib.min.js"></script>
	<script type="text/javascript" src="../../script.js"></script>
</head>
<body ng-controller="ratingCtrl">
<!--////////////////////////////////Главное меню///////////////////////////////////////-->
	<?php
		include "../../functionality/menu.php";
	?>
<!--///////////////////////////////////Страница////////////////////////////////////////-->
	<div class="container">
		<div class="row col-sm-12">
			<center>
				<h4>
					<?php 
						try{
							$db_name  = 'arrow_db';
							$hostname = '127.0.0.1';
							$db_username = 'holmes';
							$db_password = '123';
							// подключаемся к базе данных
							$dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $db_username, $db_password);
							// делаем запрос на получение данных
							$sql = ' SELECT text FROM posts WHERE page = "../functionality/votes/rating.php" ';
							$stmt = $dbh->prepare( $sql );
							// запускаем запрос
							$stmt->execute();
							$compare = $stmt->fetch(PDO::FETCH_LAZY);
							print_r($compare[0]);
						}
						catch(PDOException $e) {  
						echo $e->getMessage();
						}
					?>
				</h4>
			</center>
		</div>
		<div class=" row col-md-12">
			<div class="col-md-3">
				<raphael-gauge id="place1" config="placeObject.place[0]"></raphael-gauge>
			</div>
			<div class="col-md-3">
				<raphael-gauge id="place2" config="placeObject.place[1]"></raphael-gauge>
			</div>
			<!--
			<div class="col-md-3">
				<raphael-gauge id="place3" config="place3"></raphael-gauge>
			</div>
			<div class="col-md-3">
				<raphael-gauge id="place4" config="place4"></raphael-gauge>
			</div>
			-->
		</div>
	</div>
</body>
</html>