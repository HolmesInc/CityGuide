<?php

$data = json_decode(file_get_contents("php://input"));
$userName = mysql_real_escape_string($data->name);
$userEmail = mysql_real_escape_string($data->email);
$userPassword = mysql_real_escape_string($data->password);
$userEmail = str_replace('.', '', $userEmail);
$userPassword = md5($userPassword);

?>