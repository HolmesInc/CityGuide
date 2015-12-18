<?php
	// задаём переменные для подключения к базе данных
        $db_name  = 'arrow_db';
        $hostname = '127.0.0.1';
        $username = 'holmes';
        $password = '123';

        // подключаемся к базе данных
        $dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);

        // делаем запрос на получение данных
        $sql = 'SELECT login, password FROM users';

        $stmt = $dbh->prepare( $sql );
        $login='holmes';
        $u_password = "e10adc3949ba59abbe56e057f20f883e";
        // запускаем запрос
        $stmt->execute();
        $checker = 0;
        while ($compare = $stmt->fetch(PDO::FETCH_LAZY)){
            if($login === $compare->login){
                if($u_password === $compare->password){
                    $checker = 1;
                    break;
                }
                else 
                    echo "<script> alert('Пароль введён неверно'); </script>";
            }   
        }
        if($checker === 0)
            echo "<script> alert('Логин не существует или введён неверно'); </script>"; 
/*
        // fetchим наши данные
        $result = $stmt->fetchAll( PDO::FETCH_ASSOC );

        // конвертируем данные а json
        $json = json_encode( $result, JSON_UNESCAPED_UNICODE );

        // отплавляем json нашему скрипту
        echo $json;
*/
?>