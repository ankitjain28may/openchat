<?php
session_start();
if(isset($_SESSION['start']))
{
	unset($_SESSION['start']);
	header('Location: ../../index.php');
}
else
{
	echo "Please Login";
}
?>
