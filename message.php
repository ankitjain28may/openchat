<?php
session_start();
if(isset($_SESSION['start']) and empty($_GET['user']))
{

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Messages</title>
		<link rel="stylesheet" href="css/style.css">
	 	<link rel="stylesheet" href="css/font-awesome-4.6.3/css/font-awesome.min.css">
	 	<script type="text/javascript" src="js/jquery-3.0.0.min.js"></script>
	<script type="text/javascript" src="js/index.js"></script>
	<!-- // <script type="text/javascript" src="js/mobile.js"></script> -->

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
				<a href="#" onclick="init(1)"><i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i></a>
				<a href="#" onclick="show_search()"><i class="fa fa-search fa-lg" aria-hidden="true"></i></a>
				<a href="#" onclick="compose()"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
			</span>
		</div>


	</body>
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

</html>

<?php
}
else{
	header('Location:http://www.localhost/openchat/login.php');
}
?>
