<?php
	include "../../php_scripts/check_cookie.php";
	$checkCookie = CheckCookies();
	if ($checkCookie === 0){
		header('Refresh: 3; URL=http://arrow.ru/login.php');
		echo "<center><h1>К сожалению, Вы не авторизованы:(</h1></center>";
		echo "<center><h3>Авторизуйтесь, что бы видеть содержание этой страницы</h3></center>";
	}
	else{
		include "../../pages/functionality/club_page.php";
	}
?>