<?php
session_start();
require_once '../source/class.register.php';
if(isset($_POST['q']))
{
	$register_field=json_decode($_POST['q']);
	$name=$register_field->name;
	$email=$register_field->email;
	$username=$register_field->username;
	$mob=$register_field->mob;
	$password=$register_field->password;
	$ob = new register();
	$result=$ob->_register($name,$email,$username,$password,$mob);
	if(isset($result))
		echo $result;
	else
		echo json_encode([]);
}
?>