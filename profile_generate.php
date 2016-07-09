<?php
session_start();
include 'database.php';
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$login_id=$_SESSION['start'];
if(isset($_POST['submit']))
{
	$query="SELECT * from profile where login_id='$login_id'";
	if($result=$connect->query($query))
	{
		if($result->num_rows>0)
		{
			$row=$result->fetch_assoc();
		}
	}
	if(trim($_POST['status']))
		$status=$_POST['status'];
	else
		$status=$row['status'];
	if(trim($_POST['education']))
		$edu=$_POST['education'];
	else
		$edu=$row['education'];
	if(isset($_POST['gender']))
		$gender=$_POST['gender'];
	else
		$gender=$row['gender'];

	$query="UPDATE profile set status='$status', education='$edu', gender='$gender' where login_id='$login_id'";
	if($result=$connect->query($query))
	{
		header('Location: account.php');
	}
	else
	{
		die("error");
	}
}

?>