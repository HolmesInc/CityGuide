<?php
	include "php_scripts/check_cookie.php";
	$checkCookie = CheckCookies();
	if ($checkCookie === 0){
		include "pages/registration_page.php";
	}
	else{
		header('Refresh: 3; URL=http://arrow.ru');
		echo "<center><h1>Не проказничайте;)</h1></center>";
		echo "<center><h3>На главной Вам место..</h3></center>";
	}		
?>
