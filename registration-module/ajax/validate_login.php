<?php
session_start();
require_once '../source/class.login.php';
if(isset($_POST['q']))
{
	$login_field=json_decode($_POST['q']);
	$login=$login_field->login;
	$password=$login_field->password;
	$ob = new login();
	$result=$ob->_login($login,$password);
	if(isset($result))
		echo $result;
	else
		echo json_encode([]);
}
?>