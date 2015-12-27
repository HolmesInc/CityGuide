<?php
	include "php_scripts/check_cookie.php";
	$checkCookie = CheckCookies();
	if ($checkCookie === 0){
		include "pages/unlogged_index_page.php";
	}
	else{
		include "pages/logged_index_page.php";
	}		
?>
