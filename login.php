<?php
session_start();
if(isset($_SESSION['start']))
{
    header("Location: account.php");
}
?>
<!Doctype html>
<html>
	<head>
		<title>OpenChat</title>
		<script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
	</head>
	<body>
		
            <label id="login_label">Email or Username</label><br><br>
            <input type="text" name="login" id="login" placeholder="Email or Username" ><br><br>
            <label id="pass_label">Password</label><br><br>
            <input type="password" name="password" id="password" placeholder="Password"><br><br><br>
            <button name="submit" onclick="login_check()" value="Login">Login</button>
        
    </body>
    <script type="text/javascript" src="registration-module/js/login_validate.js"></script>
</html>