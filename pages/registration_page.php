<!DOCTYPE html>
<html ng-app="regFormApp">
<head>
	<title>ARROW</title>
	<link rel="shortcut icon" href="img/arrow.ico" type="image/x-icon">
	<style type="text/css">
		.regForm input.ng-invalid.ng-dirty {
			background-color: #FA787E;
		}
		.regForm input.ng-valid.ng-dirty {
			background-color: #78FA89;
		}
	</style>

	<script src="addons/angular.js"></script>
	<script src="//www.google.com/recaptcha/api.js?render=explicit&onload=vcRecaptchaApiLoaded" async defer></script>
	<script src="https://cdn.rawgit.com/VividCortex/angular-recaptcha/master/release/angular-recaptcha.js"></script>
	<script type="text/javascript" src="script.js"></script>
	<script type="text/javascript" src="addons/angular-recaptcha.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="addons/bootstrap/css/bootstrap.css">
</head>
<body>
<!--////////////////////////////////Главное меню///////////////////////////////////////-->
	<nav role="navigation" class="navbar navbar-default">

		<div class="navbar-header">
			<a href="#" class="navbar-brand">ARROW</a>
		</div>

		<div class="navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="index.php">Приветствую!</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="login.php">Войти</a></li>
			</ul>
		</div>
	</nav>	
	&#65279;
<!--/////////////////////////////Область даных регистрации//////////////////////////////-->
	<div class="container">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="well" ng-controller="regCtrl">
				<form class="form-horizontal regForm" name="regForm" role="form" novalidate>

					<div class="form-group">
						<label for="regUserName" class="col-sm-2 control-label">Имя</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="regUserName" name="regUserName" placeholder="Ваше имя" ng-model="newUser.name" required/>
						</div>
					</div>

					<div class="form-group">
						<label for="regUserEmail" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="regUserEmail" name="regUserEmail" placeholder="Email" ng-model="newUser.email" required/>
						</div>
					</div>

					<div class="form-group">
						<label for="regUserPassword" class="col-sm-3 control-label">Придумаем</label>
						<div class="col-sm-9">
							<input type="password" class="form-control" id="regUserPassword" name="regUserPassword" placeholder="Пароль" ng-model="newUser.password" required/>
						</div>
					</div>

					<div class="form-group">
						<label for="confirmPassword" class="col-sm-3 control-label">Повторим</label>
						<div class="col-sm-9">
							<input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Пароль" ng-model="newUser.confirmPassword" required/>
						</div>
					</div>
					
					<div ng-show="regForm.regUserEmail.$dirty && regForm.regUserEmail.$invalid">
						<span ng-show="regForm.regUserEmail.$error.required">
							<small>Пожалуйста, введите E-Mail</small>
						</span>
						<span ng-show="regForm.regUserEmail.$error.email">
							<small>E-Mail введён не верно</small>
						</span>
					</div>

					<div ng-show="newUser.password!=newUser.confirmPassword">
						<small>Пароли не совпадают</small>
					</div>

					<center>
						<div class="form-group" vc-recaptcha key="'6Lc-jBQTAAAAAJ-hg6Iqt92BXtzV1uSPxA08Et00'"></div>
					</center>

					<div class="form-group" style="margin-bottom: 0px;">
						<div class="col-sm-offset-2 col-sm-10">
							<button style="float:right;" class="btn btn-primary" name="signUp" ng-disabled="regForm.$invalid || ConfirmPass(newUser)" ng-click="SignUp(newUser)">Поехали!</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-4"></div>
	</div>
</body>
</html>