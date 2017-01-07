<?php

require (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Login;

if(isset($_POST['q']))
{
    $loginField = json_decode($_POST['q']);
    $login = $loginField->login;
    $password = $loginField->password;
    $obLogin = new Login();
    $data = array(
        'login' => $login,
        'passLogin' => $password
    );
    $result = $obLogin->authLogin($data);
    if(isset($result))
        echo $result;
    else
        echo json_encode([]);
}