<?php
require_once (__DIR__ . '/config/database.php');
require_once (__DIR__ . '/vendor/autoload.php');
use ChatApp\Session;
use ChatApp\Profile;

$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$userId = Session::get('start');
$data = '';
if(isset($_POST['submit']))
{
	$data = Profile::getProfile($userId);
	if($data != NULL):
		$status = get($_POST['status'], $data['status']);
		$edu = get($_POST['education'], $data['education']);
		$gender = get($_POST['gender'], $data['gender']);
		$query = "UPDATE profile set status='$status', education='$edu', gender='$gender' where login_id = '$userId'";
		if($result = $connect->query($query))
		{
			header('Location:'.URL.'/account.php');
		}
		else
		{
			header("Location:".URL."/error.php");
		}
	endif;
}
else
{
	header("Location:".URL."/error.php");
}

function get($value, $default)
{
	$value = trim($value);
	return (isset($value) ? $value : $default);
}
