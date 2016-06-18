<?php
session_start();
if(isset($_SESSION['start']))
{
	echo $_SESSION['start'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Messages</title>
		<script type="text/javascript" src="index.js"></script>
	</head>
	<body onload="init()">
		<div class="message">
		</div>
	</body>
</html>


<?php
}
else{
	header('Location:login.php');
}
?>