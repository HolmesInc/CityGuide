<?php

$data = json_decode(file_get_contents("php://input"));
$userName = mysql_real_escape_string($data->uname);
$userPassword = mysql_real_escape_string($data->pswd);
$userEmail = mysql_real_escape_string($data->email);

$connect = mysql_connect('localhost','holmes','123');
mysql_select_db('instausers', $connect);

$selectQuerry = 'select count(*) as counter from users where email ="' . $userEmail . '"';
$querryRes = mysql_query($selectQuerry);
$res = mysql_fetch_assoc($querryRes);

if ($res['counter'] == 0) {
    $insertQuerry = 'INSERT INTO users (name,pass,email) values ("' . $userName . '","' . $userPassword . '","' . $userEmail . '")';
    $querryRes = mysql_query($insertQuerry);
    if ($querryRes) {
        $outputArr = array('msg' => "User Created Successfully!!!", 'error' => '');
        $json = json_encode($outputArr);
        print_r($json);
    } else {
        $outputArr = array('msg' => "", 'error' => 'Error In inserting record');
        $json = json_encode($outputArr);
        print_r($json);
    }
} else {
    $outputArr = array('msg' => "", 'error' => 'User Already exists with same email');
    $json = json_encode($outputArr);
    print_r($json);
}
?>