<!DOCTYPE html>
<html ng-app="loginFormApp">
<head>
	<title>ARROW</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="addons/bootstrap/css/bootstrap.css">
	<style type="text/css">
        .loginForm input.ng-invalid.ng-dirty 
        {
            background-color: #FA787E;
        }     
        .loginForm input.ng-valid.ng-dirty 
        {
            background-color: #78FA89;
        }
    </style>

   	<script type="text/javascript" src="addons/angular_for_login.js"></script>
	<script type="text/javascript">
		var loginFormApp = angular.module('loginFormApp', []);

		loginFormApp.controller('loginCtrl',function($scope)
		{
			$scope.master= {};
			$scope.update = function(user) 
			{
				$scope.master= angular.copy(user);
			};
					
			$scope.reset = function() 
			{
				$scope.user = angular.copy($scope.master);
			};

			$scope.isUnchanged = function(user) 
			{
				return angular.equals(user, $scope.master);
			};

			$scope.reset();
		});
	</script>
</head>
<body>
	<?php
		include "menus/unlogged_registration_menu.php"; 
	?>
	&#65279;
<!--/////////////////////////////Поле ввода логин/пароля//////////////////////////////-->
	<div class="container">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="well" ng-controller="loginCtrl">
				<form class="form-horizontal loginForm" name="loginForm" novalidate>
					
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
						<span class="" ng-show="loginForm.userPassword.$error.required">
							<small>Пожалуйста, введите пароль</small>
						</span>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button style="float:right;" type="submit" class="btn btn-primary">Войти</button>
					    </div>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-4"></div>
	</div>
<!--//////////////////////////////////////////////////////////////////////////////////-->
</body>
</html>