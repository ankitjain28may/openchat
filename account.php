<?php
session_start();
if(isset($_SESSION['start']))
{
	$start=$_SESSION['start'];
	if($start==0)
	{
		echo "Your Registration is successful!";
	}
	elseif ($start==1) {
		echo "You are logged in!";
	}
}
else
{
	die("Please Login");
}
?>

<h1>Welcome</h1>
<a href="registration-module/class.logout.php">Logout</a>
<a href="message.php">Message</a>