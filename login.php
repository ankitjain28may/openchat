<?php
session_start();
if(isset($_POST['submit']))
{
	$login=$_POST['login'];
	$pass=$_POST['password'];
	include 'registration-module/class.login.php';
  $ob=new login();
  $result=$ob->_login($login,$pass);
  if($result=='ERROR')
  {
    $_SESSION['error']="Error in Login";
  }
}
else if(isset($_SESSION['start']))
{
	header('Location:account.php');

}

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
			<input type="text" name="login">
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