<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>OpenChat</title>
		<link rel="stylesheet" href="css/index.css">
	</head>
	<body>

		<!-- header -->

		<div class="header">
			<a id="brand" href="#">OpenChat</a>
			<ul class="nav-right">
				<?php if(isset($_SESSION['start']))
				{
				?>
				<li><a href="account.php">Account</a></li>
				<li><a href="message.php">Message</a></li>
				<li><a href="registration-module/source/class.logout.php">Log out</a></li>
				<?php
				}
				else
				{
				?>
				<li><a href="login.php">Login</a></li>
				<li><a href="register.php">Register</a></li>
				<?php
				}
				?>
			</ul>
		</div>

		<!-- sidebar -->

		<div class="index_sidebar">
			<h2>Description :--</h2><br>
			<p>This is a messaging platform where people can send message to anyone registered to this platform.</p>
			<br>
 			<h2>How to Use :--</h2>
			<ul> 
				<li>Step 1: Register yourself at the platform with your true identity. </li><br>
				<li>Step 2: After registration, Go to the Message Option to send message.</li><br>  
				<li>Step 3: Compose new Message by click on 'New Message'</li><br>
				<li>Step 4: A input field will be shown right next to the new message option, Type the name of the person whom you want to send message.</li><br>
				<li>Step 5: Log out.</li><br>
			</ul>
			<h2>Contribute :--</h2><br>
			<p>
				This is an open source project, contribute at <a href="http://www.github.com/ankitjain28may/openchat">Github.</a><br><br>
				Feel free to open issues and contribute.
			</p>
		</div>

		<!-- Main -->

		<div class="after_login"><h2>Check out your latest messages and keep enjoying and exploring</h2></div>



		<!-- Footer -->

		<div class="footer">
			<h3 class="footer_text">Made with love by <a href="#">Ankit Jain</a></h3>
		</div>

	</body>
</html>

