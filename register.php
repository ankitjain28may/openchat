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
        <title>OpenChat || Register</title>
        <link rel="stylesheet" href="css/main.css">
        <script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
    </head>

    <body>

        <div class="header">
            <a id="brand" href="">OpenChat</a>
            <ul class="nav-right">
                <li><a href="index.php">About</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>

        <div class="main">
            <h1>Register</h1>
            <hr><br>
                <label id="name_label">Your Name</label><br><br>
                <input type="text" name="name" id="name"><br><br>
                <label id="email_label">Email</label><br><br>
                <input type="email" name="email" id="email"><br><br>
                <label id="user_label">Username</label><br><br>
                <input type="text" name="username" id="username"><br><br>
                <label id="mob_label">Mobile No.</label><br><br>
                <input type="tel" name="mob" id="mob"><br><br>
                <label id="pass_label">Password</label><br><br>
                <input type="password" name="password" id="password"><br><br><br>
                <button name="submit" value="Register" onclick="register_check()" >Register</button>

        </div>
        <script type="text/javascript" src="registration-module/js/register_validate.js"></script>
        <script type="text/javascript" src="node_modules/place-holder.js/place-holder.min.js"></script>

        <script>
            $("input").keypress(function(e) {
                if(e.keyCode == 13) {
                    $("button").click();
                }
            });
        </script>
    </body>
</html>
