<!DOCTYPE html>
<html ng-app="indexApp">
<head>
	<title>ARROW</title>
	<link rel="shortcut icon" href="../img/arrow.ico" type="image/x-icon">
	<script type="text/javascript" src="../addons/angular.js"></script>
	<script type="text/javascript" src="../script.js"></script>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<link rel="stylesheet" type="text/css" href="../addons/bootstrap/css/bootstrap.css">
	<style type="text/css">
		.col-md-3:hover{
			opacity: 0.7;
		}
		.col-md-3:hover .hidde{
			visibility: visible; 
		}
		.col-md-3 img{
			opacity: 1.0;
		}
		.hidde{
			visibility: hidden;
		}
	</style>
</head>
<body>
<!--////////////////////////////////Главное меню///////////////////////////////////////-->
	<nav role="navigation" class="navbar navbar-default">

		<div class="navbar-header">
			<div class="navbar-brand">ARROW</div>
		</div>

		<div class="navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="../index.php">Чертоги</a></li>
				<li><a href="../functionality/votes/rating.php">Голосуем</a></li>
				<li><a href="../functionality/notes/toDo.php">Дела</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="../php_scripts/unlogining_user.php">Выйти</a></li>
			</ul>
		</div>
	</nav>
<!--///////////////////////////////////Страница////////////////////////////////////////-->
	<div class="container">
		<div class="col-md-3">
			<center>
				<a href="../functionality/selection/club.php" style="text-decoration: none";>
					<img src="../img/clubs.jpg" alt="Клубы" class="img-rounded">
					<span class="hidde">Клубы</span>
				</a>
			</center>
		</div>
		<div class="col-md-3">
			<center>
				<a href="../functionality/selection/cinema.php" style="text-decoration: none";>
					<img src="../img/kino.jpg" alt="Кинотеатры" class="img-rounded">
					<span class="hidde">Кинотеатры</span>
				</a>
			</center>
		</div>
		<div class="col-md-3">
			<center>
				<a href="../functionality/selection/sushi.php" style="text-decoration: none";>
					<img src="../img/sushi.jpg" alt="Суши-бары" class="img-rounded">
					<span class="hidde">Суши-бары</span>
				</a>
			</center>
		</div>
		<div class="col-md-3">
			<center>
				<a href="../functionality/selection/pizza.php" style="text-decoration: none";>
					<img src="../img/pizza.jpg" alt="Пиццерии" class="img-rounded">
					<span class="hidde">Пиццерии</span>
				</a>
			</center>
		</div>
	</div>
</body>
</html>