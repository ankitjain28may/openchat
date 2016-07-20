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
		<link rel="stylesheet" href="css/style.css">
	 	<!-- <link rel="stylesheet" href="css/font-awesome-4.6.2/css/font-awesome.min.css">		 -->
	 	<script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
	<script type="text/javascript" src="js/index.js"></script>
	</head>
	<body onload="init(0)">
		<!-- header -->

		<div class="header">
			<a id="brand" href="">OpenChat</a>
			<ul class="nav-right">
			
				<li><a href="account.php">Account</a></li>
				<li><a href="index.php">About</a></li>
				<li><a href="registration-module/source/class.logout.php">Log Out</a></li>
			</ul>
		</div>

		<!-- search -->

		<div class="search_message">
			<input type="text" name="search_item" id="search_item" value="" onkeyup="search_choose();" placeholder="Search">
			<!-- <select name='search_item' id='search_item' onkeyup='search_choose()'></select> -->
		</div>

		
		<!-- sidebar -->

		<div class="sidebar" id="message">
		</div>

		<!-- chat name -->

		<div class="chat_name" id="chat_name">
			<div id="chat_heading">
				<!-- <a  href="" ></a> -->
			</div>
			<div class="compose_text" id="compose_text">
				<b id="to">To:</b> &nbsp;<input type="text" name="compose_name" placeholder="Name" id="compose_name" value="" onkeyup="compose_message()">
				<div id="compose_selection">
					<ul id="suggestion">
					</ul>
				</div>
			</div>

			<div class="compose" onclick="compose()"><a href="#">+ New Message</a></div>
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
	
</html>

<?php
}
else{
	header('Location:http://www.localhost/openchat/login.php');
}
?>
