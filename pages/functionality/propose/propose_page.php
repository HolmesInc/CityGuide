<!DOCTYPE html>
<html>
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
	</style>
</head>
<body>
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
				<form class="form-horizontal" role="form" novalidate>	
					<div class="form-group">

						<label for="shopName" class="col-sm-3 control-label">Название</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="shopName" name="shopName" placeholder="Название заведения" required/>
						</div>
						
						<label for="priceIndex" class="col-sm-3 control-label">Индекс цены</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="priceIndex" name="priceIndex" placeholder="1-5" required/>
						</div>
						<label for="openTime" class="col-sm-3 control-label">Открывается</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="openTime" name="openTime" placeholder="**:**" required/>
						</div>

						<label for="closeTime" class="col-sm-3 control-label">Закрывается</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="closeTime" name="closeTime" placeholder="**:**" required/>
						</div>

						<label for="address" class="col-sm-3 control-label">Адрес</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="address" name="address" placeholder="ул.**, **" required/>
						</div>

						<label for="metro" class="col-sm-3 control-label">Метро рядом</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="metro" name="metro" placeholder="Название станции" required/>
						</div>

						<label for="phone" class="col-sm-3 control-label">Телефон</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="phone" name="phone" placeholder="(***)-***-**-**" required/>
						</div>

						<label for="site" class="col-sm-3 control-label">Веб-сайт</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="site" name="site" placeholder="www.***.**" required/>
						</div>


					</div>
				</form>
			</div>
		</div>
		<div class="col-md-3"></div>
	</div>
</body>
</html>
