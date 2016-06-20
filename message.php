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

		<!-- search -->

		<div class="search_message">
			<input type="text" name="search_item" placeholder="Search">
		</div>

		
		<!-- sidebar -->

		<div class="sidebar" id="message">
		</div>

		<!-- chat name -->

		<div class="chat-name">
			<a id="chat_heading" href="" ></a>
		</div>	

		<!-- conversation -->

		<div class="main" id="conversation">
		</div>

		<!-- Reply -->

		<div class="conversation_reply">
			<textarea type="text" name="conversation_text" placeholder="Write a reply.."></textarea>
		</div>



	</body>
	<script type="text/javascript" src="index.js"></script>
</html>

<?php
}
else{
	header('Location:http://www.localhost/openchat/login.php');
}
?>
