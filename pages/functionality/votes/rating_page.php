<!DOCTYPE html>
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
		.decor a {
			text-decoration: none;	
		}
	</style>
	<script type="text/javascript" src="../../addons/angular.js"></script>
	<script type="text/javascript" src="../../../addons/jquery.min.js"></script>
	<script type="text/javascript" src="../../../addons/angular-lib/graphs/rg-min.js"></script>
	<script type="text/javascript" src="../../../addons/angular-lib/graphs/angular-rg-lib.min.js"></script>
	<script type="text/javascript" src="../../../addons/angular-route.js"></script>
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
							include('../../php_scripts/db_connect.php');
							// делаем запрос на получение данных
							$sql = ' SELECT text FROM posts WHERE page = "../functionality/votes/rating.php" AND 	id_post = "3"';
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
		<div class="row col-md-12 decor">
			<div class="col-md-3 col-sm-6 col-xs-12" ng-style="{display: placeCSSStatus[0]}">
				<a href="{{placePathes[0]}}" ng-click="ShowInfo(0)">
					<raphael-gauge id="place1" config="placeObject.place[0]"></raphael-gauge>
				</a>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12" ng-style="{display: placeCSSStatus[1]}">
				<a href="{{placePathes[1]}}" ng-click="ShowInfo(1)">
					<raphael-gauge id="place2" config="placeObject.place[1]"></raphael-gauge>					
				</a>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12" ng-style="{display: placeCSSStatus[2]} ">
				<a href="{{placePathes[2]}}" ng-click="ShowInfo(2)">
					<raphael-gauge id="place3" config="placeObject.place[2]"></raphael-gauge>
				</a>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12" ng-style="{display: placeCSSStatus[3]} ">
				<a href="{{placePathes[3]}}" ng-click="ShowInfo(3)">
					<raphael-gauge id="place4" config="placeObject.place[3]"></raphael-gauge>
				</a>
			</div>
			<ng-view></ng-view>
		</div>
	</div>
</body>
</html>