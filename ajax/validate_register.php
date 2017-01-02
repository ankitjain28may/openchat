<?php

require (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Register;

if(isset($_POST['q']))
{
	$registerField = json_decode($_POST['q']);
	$name = $registerField->name;
	$email = $registerField->email;
	$username = $registerField->username;
	$mob = $registerField->mob;
	$password = $registerField->password;
	$obRegister = new Register();
	$result = $obRegister->authRegister($name, $email, $username, $password, $mob);
	if(isset($result))
		echo $result;
	else
		echo json_encode([]);
}

