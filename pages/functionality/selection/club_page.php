<!DOCTYPE html>
<html ng-app="clubsApp">
<head>
	<title>ARROW-Клубы</title>
	<link rel="shortcut icon" href="../../img/arrow.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../../style.css">
	<link rel="stylesheet" type="text/css" href="../../addons/bootstrap/css/bootstrap.css">
	<style type="text/css">
		.navbar-brand a {
			text-decoration: none;
		}
		td{
			border: 1px solid #ddd;
			text-align: center;
		}
		th{
			text-align: center;
			cursor: pointer;
		}
	</style>
	
	<script type="text/javascript" src="../../addons/angular.js"></script>
	<script type="text/javascript" src="../../script.js"></script>
	<script type="text/javascript" src="../../../addons/angular-tablesort.js"></script>
</head>
<body>
<!--////////////////////////////////Главное меню///////////////////////////////////////-->
	<nav role="navigation" class="navbar navbar-default">	
		<div class="navbar-header">
			<div class="navbar-brand"><a href="../../index.php">ARROW</a></div>
		</div>
		
		<div class="navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="../../index.php">Чертоги</a></li>
				<li><a href="../../functionality/votes/rating.php">Голосуем</a></li>
				<li><a href="../../functionality/notes/toDo.php">Дела</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="../../php_scripts/unlogining_user.php">Выйти</a></li>
			</ul>
		</div>
	</nav>
	&#65279;
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
							$sql = ' SELECT text FROM posts WHERE page = "../functionality/selection/club.php" AND id_post = "1" ';
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
		<div ng-controller="clubCtrl">
			<form>
				<div class="row well" style="margin-top: 70px;">
					<ul class="list-inline">
						<li class="col-md-3 col-xs-12 col-sm-6" style="margin-bottom: 4px;">
							<div class="material-switch">
								<input type="checkbox" id="switchOptionPrimary1" name="switchOption001" ng-model="$scope.nearMetro" ng-change="ShowInfo(1)"/>
								<label for="switchOptionPrimary1" class="label-primary"></label>
								Поближе к метро
							</div>
						</li>
						<li class="col-md-3 col-xs-12 col-sm-6" style="margin-bottom: 4px;">
							<div class="material-switch">
								<input type="checkbox" id="switchOptionPrimary2" name="switchOption002" ng-model="$scope.lowPrice" ng-change="ShowInfo(2)"/>
								<label for="switchOptionPrimary2" class="label-primary"></label>
								Подешевле
							</div>
						</li>
						<li class="col-md-3 col-xs-12 col-sm-6" style="margin-bottom: 4px;">
							<div class="material-switch">
								<input type="checkbox" id="switchOptionPrimary3" name="switchOption003" ng-model="$scope.highPrice" ng-change="ShowInfo(3)"/>
								<label for="switchOptionPrimary3" class="label-primary"></label>
								Подороже
							</div>
						</li>
						<li class="col-md-3 col-xs-12 col-sm-6" style="margin-bottom: 4px;">
							<div class="material-switch">
								<input type="checkbox" id="switchOptionPrimary4" name="switchOption004" ng-model="$scope.moreTime" ng-change="ShowInfo(4)"/>
								<label for="switchOptionPrimary4" class="label-primary"></label>
								Подольше работает
							</div>
						</li>
					</ul>
				</div>
			</form>
			<div class="col-md-12 table-responsive">
				<table class="table table-condensed table-striped" ts-wrapper>
					<thead>
						<tr>
							<th ts-criteria="name|lowercase" ts-default>Название</th>
							<th ts-criteria="pryce_index|parseInt">Ценовой индекс</th>
							<th ts-criteria="rating|parseInt">Рейтинг</th>
							<th ts-criteria="open_time|parseInt">Время открытия</th>
							<th ts-criteria="close_time|parseInt">Время закрытия</th>
							<th ts-criteria="adress|lowercase" ts-default>Адрес</th>
							<th ts-criteria="metro|lowercase" ts-default>Ближайшее метро</th>
							<th ts-criteria="phone|lowercase" ts-default>Номер телефона</th>
							<th ts-criteria="site|lowercase" ts-default>Веб-сайт</th>
						</tr>
					</thead>
					<tbody class="table-bordered">
						<tr ng-repeat=" data in dbInfo track by data.name" ts-repeat ts-hide-no-data>
							<td>{{data.name}}</td>
							<td>{{data.pryce_index}}</td>
							<td>{{data.rating}}</td>
							<td>{{data.open_time}}</td>
							<td>{{data.close_time}}</td>
							<td>{{data.adress}}</td>
							<td>{{data.metro}}</td>
							<td>{{data.phone}}</td>
							<td>{{data.site}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
			<!--//Верхнее меню в строку//-->
			<!--
				<ul class="list-inline">
					<li class="col-md-3">
						<div class="checkbox-inline"><input type="checkbox" ng-checked="" value="">Поближе к метро</div>
					</li>
					<li class="col-md-3">
						<div class="checkbox-inline"><input type="checkbox" ng-checked="" value="">Поменьше цена</div>
					</li>
					<li class="col-md-3">
						<div class="checkbox-inline"><input type="checkbox" ng-checked="" value="">Побольше цена</div>
					</li>
					<li class="col-md-3">
						<div class="checkbox-inline"><input type="checkbox" ng-checked="" value="">Подольше работает</div>
					</li>
				</ul>
			-->
			<!--//Верхнее меню в виде блока слева//-->
			<!--
			<div class="col-md-3 well">
				<form>
					<div class="checkbox form-control">
						<label><input type="checkbox" value="">Поближе к метро</label>
					</div>
					<div class="checkbox form-control">
						<label><input type="checkbox" value="">Поменьше цена</label>
					</div>
					<div class="checkbox form-control">
						<label><input type="checkbox" value="">Побольше цена</label>
					</div>
					<div class="checkbox form-control">
						<label><input type="checkbox" value="">Подольше работает</label>
					</div>
					<button style="float:right;" class="btn btn-primary">Подобрать</button>
				</form>
			</div>
			<div class="col-md-7">
			</div>
			<div class="col-md-2"></div>
			-->
	</div>
</body>
</html>