<!DOCTYPE html>
<html ng-app="proposeApp">
<head>
	<title>ARROW Предлагаю</title>
	<link rel="shortcut icon" href="../../img/arrow.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../../style.css">
	<link rel="stylesheet" type="text/css" href="../../addons/bootstrap/css/bootstrap.css">
	<style type="text/css">
		.navbar-brand a {
			text-decoration: none;
		}
		div .col-sm-9 {
			margin-bottom: 10px;
		}
		.proposeForm input.ng-invalid.ng-dirty {
				background-color: #FA787E;
			}
		.proposeForm input.ng-valid.ng-dirty {
			background-color: #78FA89;
		}
	</style>
	<script type="text/javascript" src="../../addons/angular.js"></script>
	<script type="text/javascript" src="../../script.js"></script>
</head>
<body ng-controller="proposeCtrl">
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
							$sql = ' SELECT text FROM posts WHERE page = "../functionality/propose/propose.php" AND id_post = "2" ';
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
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div class="well">
				<form class="form-horizontal proposeForm" name="proposeForm" role="form" novalidate>	
					<div class="form-group">

						<label for="placeName" class="col-sm-3 control-label">Название*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="placeName" name="placeName" minlength="3" maxlength="30" placeholder="Название заведения" ng-model="name" required ng-text-validation /> 
						</div>
						
						<label for="placeCategory" class="col-sm-3 control-label">Категрия*</label>
						<div class="col-sm-9">
							<select class="form-control" name="placeCategory" ng-model="placeCategory" required>
								<option style="display:none;" value="">Выберите категорию</option>
								<option ng-repeat="categorys in category">{{categorys}} </option>
							</select>
						</div>

						<label for="priceIndex" class="col-sm-3 control-label">Индекс цены*</label>
						<div class="col-sm-9">
							<select class="form-control" name="priceIndex" ng-model="priceIndex" required>
								<option style="display:none;" value="">Выберите ценовой индекс</option>
								<option ng-repeat="prices in price">{{prices}}</option>
							</select>
						</div>

						<label for="openTime" class="col-sm-3 control-label">Открывается</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="openTime" name="openTime" maxlength="5" placeholder="**:**" ng-model="openTime" ng-time-validation />
						</div>

						<label for="closeTime" class="col-sm-3 control-label">Закрывается</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="closeTime" name="closeTime" maxlength="5" placeholder="**:**" ng-model="closeTime" ng-time-validation />
						</div>

						<label for="address" class="col-sm-3 control-label">Адрес*</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="address" name="address" minlength="5" placeholder="ул.**, **" ng-model="address" required ng-text-validation />
						</div>

						<label for="metro" class="col-sm-3 control-label">Метро рядом</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="metro" name="metro" minlength="5" placeholder="Название станции" ng-model="metro" />
						</div>

						<label for="phone" class="col-sm-3 control-label">Телефон</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="phone" name="phone" maxlength="15" placeholder="(***)-***-**-**" ng-model="phone" ng-phone-validation />
						</div>

						<label for="site" class="col-sm-3 control-label">Веб-сайт</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="site" name="site" placeholder="www.***.**" ng-model="site" ng-site-validation />
						</div>

						<div class="col-sm-12" style="margin-top: 5px;">
							<button style="float:right;" type="submit" class="btn btn-primary" name="enter" ng-disabled="proposeForm.$invalid" ng-click="ProposePlace()">Предложить!</button>
						</div>

					</div>
				</form>
			</div>
		</div>
		<div class="col-md-3"></div>
	</div>
</body>
</html>
