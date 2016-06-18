<?php
session_start();
if(isset($_SESSION['start']))
{
	unset($_SESSION['start']);
	header('Location: ../login.php');

}
else
{
	echo "Please Login";
}
?>
