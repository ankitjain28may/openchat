<?php
session_start();
if(isset($_SESSION['start']) and empty($_GET['user']))
{
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Messages</title>
		<link rel="stylesheet" href="CSS/style.css">
	 	<!-- <link rel="stylesheet" href="css/font-awesome-4.6.2/css/font-awesome.min.css">		 -->
	</head>
	<body onload="init()">
		<!-- header -->

		<div class="header">
			<a id="brand" href="">OpenChat</a>
			<ul class="nav-right">
			
				<li><a href="">Home</a></li>
				<li><a href="">About</a></li>
			</ul>
		</div>

		<!-- sidebar -->

		<div class="sidebar" id="message">
			<div><a  id="compose" href="">Compose</a></div>
		</div>

		<!-- chat name -->

		<div><a class="chat-name" href="">message</a></div>	

		<!-- conversation -->

		<div class="main" id="conversation">
		</div>

	</body>
	<script type="text/javascript" src="index.js"></script>
</html>


<?php
}
else if(isset($_SESSION['start']) and isset($_GET['user']))
{
	?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Messages</title>
		<link rel="stylesheet" href="../CSS/style.css">
	 	<!-- <link rel="stylesheet" href="css/font-awesome-4.6.2/css/font-awesome.min.css">		 -->
	</head>
	<body onload="init_m()">
		<!-- header -->

		<div class="header">
			<a id="brand" href="">OpenChat</a>
			<ul class="nav-right">
			
				<li><a href="">Home</a></li>
				<li><a href="">About</a></li>
			</ul>
		</div>

		<!-- sidebar -->

		<div class="sidebar" id="message">
			<div><a  id="compose" href="">Compose</a></div>
		</div>

		<!-- chat name -->

		<div><a class="chat-name" href="">message</a></div>	

		<!-- conversation -->

		<div class="main" id="conversation">
		</div>

	</body>
	<script type="text/javascript" src="../index.js"></script>
</html>
<?php
	echo $_GET['user'];
}
else{
	header('Location:http://www.localhost/openchat/login.php');
}
?>
