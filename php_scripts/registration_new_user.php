<?php

$data = json_decode(file_get_contents("php://input"));
$userName = mysql_real_escape_string($data->name);
$userEmail = mysql_real_escape_string($data->email);
$userPassword = mysql_real_escape_string($data->password);
$userEmail = str_replace('.', '', $userEmail);
$userPassword = md5($userPassword);

$connect = mysql_connect('localhost','holmes','123');
mysql_select_db('arrow_db', $connect);

$selectQuerry = 'SELECT count(*) AS counter FROM users WHERE login ="' . $userEmail . '"';
$querryRes = mysql_query($selectQuerry);
$res = mysql_fetch_assoc($querryRes);

if ($res['counter'] == 0) {
    $insertQuerry = 'INSERT INTO users (name,login,password) values ("' . $userName . '","' . $userEmail . '","' . $userPassword . '")';
    $querryRes = mysql_query($insertQuerry);
    if ($querryRes) {
        $outputArr = array('msg' => "Вы успешно зарегистрированы!", 'error' => '');
        $json = json_encode($outputArr);
        print_r($json);
    } else {
        $outputArr = array('msg' => "", 'error' => 'Ошибка записи пользователя');
        $json = json_encode($outputArr);
        print_r($json);
    }
} else {
    $outputArr = array('msg' => "", 'error' => 'Пользователь с таким E-Mail уже существует.');
    $json = json_encode($outputArr);
    print_r($json);
}
?>