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
		<title>OpenChat || Login</title>
        <link rel="stylesheet" href="css/main.css">
	</head>
	<body>

        <div class="header">
            <a id="brand" href="">OpenChat</a>
            <ul class="nav-right">
                <li><a href="index.php">About</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </div>
		
        <div class="main">
            <h1>Login</h1>
            <hr><br>
            <label id="login_label">Email or Username</label><br><br>
            <input type="text" name="login" id="login" placeholder="Email or Username" ><br><br>
            <label id="pass_label">Password</label><br><br>
            <input type="password" name="password" id="pass"><br><br><br>
            <button name="submit" onclick="login_check()" value="Login">Login</button>    
        </div>

    </body>
    <script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" src="registration-module/js/login_validate.js"></script>
    
    <script type="text/javascript" src="placeholder.js/placeholder.js"></script>
    <script>
        $("input").keypress(function(e) {
            if(e.keyCode == 13) {
                $("button").click();
            }
        });
    </script>
</html>