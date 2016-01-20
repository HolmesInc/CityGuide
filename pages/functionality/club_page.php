<!DOCTYPE html>
<html ng-app="clubsApp">
<head>
	<title>ARROW-Клубы</title>
	<link rel="shortcut icon" href="../../img/arrow.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../../style.css">
	<link rel="stylesheet" type="text/css" href="../../addons/bootstrap/css/bootstrap.css">
	<script type="text/javascript" src="../../addons/angular.js"></script>
	<script type="text/javascript" src="../../script.js"></script>
</head>
<body>
<!--////////////////////////////////Главное меню///////////////////////////////////////-->
	<nav role="navigation" class="navbar navbar-default">	
		<div class="navbar-header">
			<div class="navbar-brand">ARROW</div>
		</div>
		
		<div class="navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="../../index.php">Чертоги</a></li>
				<li><a href="../../functionality/votes/rating.php">Голосуем</a></li>
				<li><a href="../../functionality/notes/toDo.php">Пометки</a></li>
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
		<div class="row well" style="margin-top: 70px;">
			<form ng-controller="clubCtrl">
				<label class="checkbox-inline col-md-3"><input type="checkbox" ng-checked="" value="">Поближе к метро</label>
				<label class="checkbox-inline col-md-3"><input type="checkbox" ng-checked="" value="">Поменьше цена</label>
				<label class="checkbox-inline col-md-3"><input type="checkbox" ng-checked="" value="">Побольше цена</label>
				<label class="checkbox-inline col-md-2"><input type="checkbox" ng-checked="" value="">Подольше работает</label>
			</form>
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
	</div>
</body>
</html>