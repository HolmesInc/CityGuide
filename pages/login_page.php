<!DOCTYPE html>
<html ng-app="loginFormApp">
<head>
	<title>ARROW</title>
	<link rel="shortcut icon" href="../img/arrow.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../style.css">
	<link rel="stylesheet" type="text/css" href="../addons/bootstrap/css/bootstrap.css">
	<style type="text/css">
		.loginForm input.ng-invalid.ng-dirty {
			background-color: #FA787E;
		}
		.loginForm input.ng-valid.ng-dirty {
			background-color: #78FA89;
		}
				.navbar-brand a {
			text-decoration: none;
		}
	</style>
	<script type="text/javascript" src="addons/angular_for_login.js"></script>
	<script type="text/javascript" src="script.js"></script>
</head>
<body>
<!--////////////////////////////////Главное меню///////////////////////////////////////-->
	<nav role="navigation" class="navbar navbar-default">	
		<div class="navbar-header">
			<div class="navbar-brand"><a href="../../index.php"> < ARROW</a></div>
		</div>
		
		<div class="navbar-collapse">
			<ul class="nav navbar-nav">
				<li><a href="../index.php">Приветствую!</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="active"><a href="../login.php">Войти</a></li>
			</ul>
		</div>
	</nav>
	&#65279;
<!--/////////////////////////////Поле ввода логин/пароля//////////////////////////////-->
	<div class="container">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="well" ng-controller="loginCtrl" style="margin-bottom: 10px;">
				<form class="form-horizontal loginForm" name="loginForm" role="form" method="post" action="login.php" novalidate>
					
					<div class="form-group">
					    <label for="userEmail" class="col-sm-2 control-label">Email</label>
					    <div class="col-sm-10">
					      	<input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="Email" ng-model="user.email" required/>
					    </div>
					</div>
					
					<div class="form-group">
					    <label for="userPassword" class="col-sm-2 control-label">Пароль</label>
					    <div class="col-sm-10">
					      	<input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Password" ng-model="user.password" required />
					    </div>
					</div>
					
					<div ng-show="loginForm.userEmail.$dirty && loginForm.userEmail.$invalid">
						<span ng-show="loginForm.userEmail.$error.required">
							<small>Пожалуйста, введите E-Mail</small>
						</span>
						<span ng-show="loginForm.userEmail.$error.email">
							<small>E-Mail введён не верно</small>
						</span>
					</div>
					
					<div ng-show="loginForm.userPassword.$dirty && loginForm.userPassword.$invalid">
						<span ng-show="loginForm.userPassword.$error.required">
							<small>Пожалуйста, введите пароль</small>
						</span>
					</div>
					
					<div class="form-group" style="margin-bottom: 0px;">
						<div class="col-sm-offset-2 col-sm-10">
							<button style="float:right;" type="submit" class="btn btn-primary" name="enter" ng-disabled="loginForm.$invalid || isUnchanged(user)" >Войти</button>
						</div>
					</div>
				</form>
			</div>
			<center>Пора что то менять.. <a href="registration.php">Регистрируюсь!</a></center>
		</div>
		<div class="col-md-4"></div>
	</div>
<!--//////////////////////////////////////////////////////////////////////////////////-->
</body>
</html>