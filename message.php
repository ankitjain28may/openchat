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
	<body onload="init(0)">
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

		<div class="chat_name" id="chat_name">
			<a id="chat_heading" href="" ></a>
			<div class="compose_text" id="compose_text">
				To:&nbsp;<input type="text" name="compose_name" placeholder="Name" id="compose_name" value="" onkeyup="compose_message()">
			</div>
			<div class="compose" onclick="compose()"><a href="#">+ New Message</a></div>
		</div>	

		<div id="compose_selection">
			<ul id="suggestion">
			</ul>
		</div>

		<!-- conversation -->
		<div class="main" id="conversation">
		</div>

		<!-- Reply -->

		<div class="conversation_reply">
			<textarea type="text" name="" id="text_reply" placeholder="Write a reply.."></textarea>
			<br>
			<input type="submit" name="submit" value="Reply" onclick="reply()">
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
