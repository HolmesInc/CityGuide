<?php
	include "../../php_scripts/check_cookie.php";
	$checkCookie = CheckCookies();
	if ($checkCookie === 0){
		header('Refresh: 3; URL=http://arrow.ru/login.php');
		echo "<center><h1>� ���������, �� �� ������������:(</h1></center>";
		echo "<center><h3>�������������, ��� �� ������ ���������� ���� ��������</h3></center>";
	}
	else{
		include "../../pages/functionality/selection/pizza_page.php";
	}
?>