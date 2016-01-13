<?php
	//error_reporting( E_ERROR );
	if(isset($_POST['enter'])){
		$login = $_POST["userEmail"];
		$login = str_replace('.', '', $login);
		$password = md5($_POST["userPassword"]);
		$dbName  = 'arrow_db';
	    $hostname = '127.0.0.1';
	    $dbUsername = 'holmes';
	    $dbPassword = '123';

	    // подключаемся к базе данных
	    $dbh = new PDO("mysql:host=$hostname;dbname=$dbName", $dbUsername, $dbPassword);

	    // делаем запрос на получение данных
	    $sql = 'SELECT name, login, password FROM users';
	    
	    $stmt = $dbh->prepare( $sql );
		// запускаем запрос
		$stmt->execute();
		
		$checkLogin = 0;
		while ($compare = $stmt->fetch(PDO::FETCH_LAZY)){
			if($login === $compare->login){
				if($password === $compare->password){
					//$login = str_replace('.', '', $login);
					setcookie($login,$compare->name,time()+604800);
					$checkLogin = 1;
					header('Location:http://arrow.ru');
  					exit;
					break;
				}
				else{ 
					echo "<script> alert('Пароль введён неверно'); </script>";
					$checkLogin = 1;
				}
			}
		}
		if($checkLogin === 0)
            echo "<script> alert('Логин не существует или введён неверно'); </script>";
	}
?>
<?php
	include "php_scripts/check_cookie.php";
	$checkCookie = CheckCookies();
	if ($checkCookie === 0){
		include "pages/login_page.php";
	}
	else{
		header('Refresh: 3; URL=http://arrow.ru');
		echo "<center><h1>Вы уже авторизованы;)</h1></center>";
		echo "<center><h3>..и поэтому будете изгнаны на главную страницу</h3></center>";
	}		
?>

