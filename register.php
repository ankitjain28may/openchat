<?php

session_start();
if(isset($_POST['submit']))
{
$name=$_POST['name'];
$email=$_POST['email'];
$password=$_POST['password'];
$username=$_POST['username'];
$mob=$_POST['mob'];
include 'registration-module/class.register.php';
$ob= new register();
$result=$ob->_register($name,$email,$username,$password,$mob);
if($result=='ERROR')
{
  $_SESSION['error']="Error in Registration";
}
}
if(isset($_SESSION['start']))
die('Already Logged in');

?>


<!Doctype html>
<html>
	<head>
		<title>OpenChat</title>
	</head>
	<body>
		<?php if(isset($_SESSION['error']))
            echo $_SESSION['error']; ?>
		<form method="POST" action="">
			<input type="text" name="name">
			<input type="email" name="email">
			<input type="text" name="username">
			<input type="text" name="mob">
			<input type="password" name="password">
			<input type="submit" name="submit">
		</form>
	</body>
</html>
<?php
  unset($_SESSION['login']);
 unset($_SESSION['password']);
 unset($_SESSION['error']);
          
  ?>