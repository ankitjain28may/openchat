<?php

require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();

if(Session::get('start') != null && empty($_GET['user']))
{

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Messages</title>

		<link rel="stylesheet" href="../public/dist/css/style.min.css">
		<!-- <link rel="stylesheet" href="../public/assests/css/style.css"> -->

		<!-- Font Awesome File -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	 	<!-- jQuery File
	 	<script type="text/javascript" src="../public/assests/js/jquery-3.0.0.min.js"></script>

		<script type="text/javascript" src="../public/assests/js/index.js"></script>
		<script src="../public/assests/js/handlebars.min.js"></script>
	    <script src="../public/assests/js/moment.min.js"></script> -->


	    <!-- Minified File -->
		<script type="text/javascript" src="../public/dist/js/script.min.js"></script>

	</head>
	<body>
		<!-- header -->

		<div class="header">
			<a id="brand" href="">OpenChat</a>
			<ul class="nav-right">
				<li><a href="account.php">Account</a></li>
				<li><a href="../index.php">About</a></li>
				<li><a href="logout.php">Log Out</a></li>
			</ul>

			<div class="mob-right">
				<div class="dropdown">
					<div class="line"></div>
					<div class="line"></div>
					<div class="line"></div>
				</div>
			</div>
		</div>

		<div id="dropdown">
			<ul class="dropdown-list">
				<li><a href="account.php">Account</a></li>
				<li><a href="../index.php">About</a></li>
				<li><a href="logout.php">Log Out</a></li>
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
			</div>
			<div class="compose_text" id="compose_text">
				<b id="to">To:</b> &nbsp;<input type="text" name="compose_name" placeholder="Name" id="compose_name" value="" onkeyup="composeChoose()">
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
			<span><input type="submit" name="submit" value="Reply" onclick="reply()"> &nbsp;<i onclick="startDictation()" class="fa fa-microphone" aria-hidden="true"></i></span>
		</div>

		<div class="mob-reply">
			<div class="input-group margin-bottom-sm text_icon">
				<input type="text" name="" id="text_reply" placeholder="OpenChat..">
				<span class="send" ><i class="fa fa-paper-plane" aria-hidden="true" onclick="reply()"></i></span>
			</div>

			<br>
		</div>


		<div class="mob-footer">
			<span>
				<a href="#" onclick="init()"><i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i></a>
				<a href="#" onclick="show_search()"><i class="fa fa-search fa-lg" aria-hidden="true"></i></a>
				<a href="#" onclick="compose()"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
			</span>
		</div>

		<script type="text/javascript">
			$(".dropdown").click(function() {
				$("#dropdown").slideToggle();
				// $("#dropdown").show();
			});
			$("span .fa").click(function() {
				$(".search_item").show();
			});
			// var w='';
			// if(window.innerWidth<500 && window.outerHeight!=w)
			// {
			// 	console.log(1);
			// 	w=window.outerHeight-135;
			// 	$(".main").css('min-height',w);
			// }
		</script>
	</body>
</html>

<?php
}
else{
	header('Location:'. getenv('APP_URL')."/index.php");
}
?>
