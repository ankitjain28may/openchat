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
        <label id="name_label">Your Name</label><br><br>
        <input type="text" name="name" id="name" placeholder="Name"><br><br>
        <label id="email_label">Email</label><br><br>
        <input type="email" name="email" id="email" placeholder="Email id"><br><br>
        <label id="user_label">Username</label><br><br>
        <input type="text" name="username" id="username" placeholder="Username"><br><br>
        <label id="mob_label">Mobile No.</label><br><br>
        <input type="text" name="mob" id="mob" placeholder="99******00"><br><br>
        <label id="pass_label">Password</label><br><br>
        <input type="password" name="password" id="password" placeholder="Password"><br><br><br>
        <button name="submit" value="Register">Register</button>
    </body>
    <script type="text/javascript" src="registration-module/js/register_validate.js"></script>
</html>
               