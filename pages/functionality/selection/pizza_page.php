<!DOCTYPE html>
<html ng-app="selectionApp">
<head>
	<title>ARROW Пицца</title>
	<link rel="shortcut icon" href="../../img/arrow.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../../style.css">
	<link rel="stylesheet" type="text/css" href="../../addons/bootstrap/css/bootstrap.css">
	<style type="text/css">
		.navbar-brand a {
			text-decoration: none;
		}
		td {
			border: 1px solid #ddd;
			text-align: center;
		}
		th {
			text-align: center;
			cursor: pointer;
		}
		.checker li {
			margin-bottom: 4px;
			padding-left: 5%;
		}
	</style>
	<script type="text/javascript" src="../../addons/angular.js"></script>
	<script type="text/javascript" src="../../script.js"></script>
	<script type="text/javascript" src="../../../addons/angular-tablesort.js"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	<!--
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	-->
</head>
<body ng-controller="selectionCtrl" ng-init="ShowMap(4)"> <!--ng-init="ShowMap()"-->
<!--////////////////////////////////Главное меню///////////////////////////////////////-->
	<?php 
		include "../../functionality/menu.php";
	?>
<!--///////////////////////////////////Страница////////////////////////////////////////-->
	<div class="container">
		<div class="col-sm-12">
			<center>
				<h4>
					<?php 
						try{
							include('../../php_scripts/db_connect.php');
							// делаем запрос на получение данных
							$sql = ' SELECT text FROM posts WHERE page = "../functionality/selection/pizza.php" ';
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
		<div class="col-md-12 panel panel-default" style="padding-left: 0; padding-right: 0;">
			<div class="col-md-12 col-xs-12 col-sm-12 well">
				<ul class="list-inline checker">
					<li class="col-md-3 col-xs-12 col-sm-6">
						<div class="material-switch">
							<input type="checkbox" id="switchOptionPrimary1" name="switchOption001" ng-model="$scope.nearMetro" ng-change="ShowInfo(1,4)"/>
							<label for="switchOptionPrimary1" class="label-primary" title="Убрать заведеня, удалённые от метро"></label>
							Поближе к метро
						</div>
					</li>
					<li class="col-md-3 col-xs-12 col-sm-6">
						<div class="material-switch">
							<input type="checkbox" id="switchOptionPrimary2" name="switchOption002" ng-model="$scope.lowPrice" ng-change="ShowInfo(2,4)"/>
							<label for="switchOptionPrimary2" class="label-primary" title="Убрать заведеня с ценовым индексом выше 3"></label>
							Подешевле
						</div>
					</li>
					<li class="col-md-3 col-xs-12 col-sm-6">
						<div class="material-switch">
							<input type="checkbox" id="switchOptionPrimary3" name="switchOption003" ng-model="$scope.highPrice" ng-change="ShowInfo(3,4)"/>
							<label for="switchOptionPrimary3" class="label-primary" title="Убрать заведеня с ценовым индексом ниже 4"></label>
							Подороже
						</div>
					</li>
					<li class="col-md-3 col-xs-12 col-sm-6">
						<div class="material-switch">
							<input type="checkbox" id="switchOptionPrimary4" name="switchOption004" ng-model="$scope.withSite" ng-change="ShowInfo(4,4)"/>
							<label for="switchOptionPrimary4" class="label-primary" title="Убрать заведеня без сайта"></label>
							Есть сайт
						</div>
					</li>
				</ul>
			</div>
			<div class="col-md-12 table-responsive">
				<table class="table table-condensed table-striped" ts-wrapper>
					<thead>
						<tr>
							<th ts-criteria="name|lowercase">Название</th>
							<th ts-criteria="pryce_index|parseInt">Ценовой индекс</th>
							<!--th ts-criteria="rating|parseInt">Рейтинг</th-->
							<th ts-criteria="open_time|parseInt">Время открытия</th>
							<th ts-criteria="close_time|parseInt">Время закрытия</th>
							<th ts-criteria="adress|lowercase">Адрес</th>
							<th ts-criteria="metro|lowercase">Ближайшее метро</th>
							<th ts-criteria="phone|lowercase">Номер телефона</th>
							<th ts-criteria="site|lowercase">Веб-сайт</th>
						</tr>
					</thead>
					<tbody class="table-bordered">
						<tr ng-repeat="data in dbPizzaInfo" ts-repeat ts-hide-no-data ng-style="{display: tableRowCSSStatus[$index]}">
							<td>{{data.name}}</td>
							<td>{{data.pryce_index}}</td>
							<!--td><div ng-show="data.rating != '-' ">{{data.rating}}</div></td-->
							<td>{{data.open_time}}</td>
							<td>{{data.close_time}}</td>
							<td><a href = "#map" ng-click="NewZoom($index)">{{data.adress}}</a></td>
							<td><div ng-show="data.metro != '-' ">{{data.metro}}</div></td>
							<td><div ng-show="data.phone != '-' ">{{data.phone}}</div></td>
							<td><a ng-show="data.site != '-' " ng-href="http://{{data.site}}">{{data.site}}</a></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12" style="padding-top: 15px; padding-bottom: 15px;">
				<div id="map" style="width: 100%; height: 600px"></div>
			</div>
		</div>
	</div>
</body>
</html>