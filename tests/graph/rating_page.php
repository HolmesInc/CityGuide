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
	</style>
	<script type="text/javascript" src="../../addons/angular.js"></script>
	<script type="text/javascript" src="../../../addons/jquery.min.js"></script>
	<script type="text/javascript" src="../../../addons/angular-lib/graphs/rg-min.js"></script>
	<script type="text/javascript" src="../../../addons/angular-lib/graphs/angular-rg-lib.min.js"></script>
	<script type="text/javascript" src="d3.min.js"></script>
	<script type="text/javascript" src="ng-knob.js"></script>
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
				<ui-knob value="value" options="options"></ui-knob>
				<script type="text/javascript">
				var ratingApp = angular.module('ratingApp', ['ui.knob']);
				ratingApp.controller('ratingCtrl', function($scope, $http){
					$scope.value = 15;
					$scope.options = {
					  unit: "%",
					  readOnly: true,
					  subText: {
					    enabled: true,
					    text: 'одобривших',
					    color: 'gray',
					    font: 'auto'
					  },
					  trackWidth: 40,
					  barWidth: 25,
					  trackColor: '#337AB7',
					  barColor: '#ACACAC'
					};
					$http.get('../../php_scripts/functionality/votes/get_new_place_data.php').then(function(response) {
						$scope.value = response.data[0].index_of_validity;
						$scope.options.subText.text = response.data[0].name;
						console.log($scope.options.subText.text);
						/*$scope.placeObject.place[0].name = response.data[0].name;
						console.log($scope.placeObject.place[0].name)
						$scope.placeObject.place[1].value = response.data[1].index_of_validity;
						$scope.placeObject.place[1].name = response.data[1].name;
						console.log($scope.placeObject.place[1].name)*/
					});
				});
				</script>
			</div>
			<div class="col-md-3">
				
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