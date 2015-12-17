<!DOCTYPE html>
<html>
<head>
	<title>ARROW</title>
	<script type="text/javascript" src="script.js"></script>
	<script type="text/javascript" src="addons/angular.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="addons/css/bootstrap.css">
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
			<div class="well">
				<form class="form-horizontal" role="form">
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
					    <div class="col-sm-10">
					      	<input type="email" class="form-control" id="inputEmail3" placeholder="Email">
					    </div>
					</div>
					<div class="form-group">
					    <label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
					    <div class="col-sm-10">
					      	<input type="password" class="form-control" id="inputPassword3" placeholder="Password">
					    </div>
					</div>
					<div class="form-group">
					    <div class="col-sm-offset-2 col-sm-10">
					      	<button type="submit" class="btn btn-primary">Войти</button>
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