<!Doctype html>
<html>


<?php
session_start();
include 'database.php';
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$user=explode("/", $_SERVER['REQUEST_URI']);
$user = $user[count($user)-1];
var_dump($user);
if(isset($_SESSION['start']) and !$user)
{
	$login_id=$_SESSION['start'];
	$query="SELECT username from login where login_id='$login_id'";
	if($result=$connect->query($query))
	{
		if($result->num_rows >0)
		{
			$row=$result->fetch_assoc();
			$location="http://localhost/openchat/account.php/".$row['username'];
			header("Location:".$location);
		}
	}

}

else if($user)
{
	$query="SELECT * from login where username='$user'";
	if($result=$connect->query($query))
	{
		if($result->num_rows >0)
		{
			$row=$result->fetch_assoc();
			$id=$row['login_id'];
			$query="SELECT * from profile where login_id='$id'";
			if($result=$connect->query($query))
			{
				if($result->num_rows>0)
				{
					$r=$result->fetch_assoc();
					$row=array_merge($row,$r);
					// var_dump($row);
				}
			}
			else
			{
				die("error");
			}
?>
	<head>
		<title>OpenChat || Profile</title>
        <link rel="stylesheet" href="../css/profile.css">
	</head>



	<body>

		<div class="header">
            <a id="brand" href="">OpenChat</a>
            <ul class="nav-right">
                <li><a href="../index.php">About</a></li>
                <?php if(isset($_SESSION['start']))
				{
				?>
				<li><a href="../message.php">Message</a></li>
				<li><a href="../registration-module/source/class.logout.php">Log out</a></li>
				<?php
				}
				else
				{
				?>
				<li><a href="../login.php">Login</a></li>
				<li><a href="../register.php">Register</a></li>
				<?php
				}
				?>
            </ul>
        </div>




        <div class="main">
			<div class="boxx" >

				<div class="pic">
					<img src="../ankit.png">
				</div>

				<div class="brief">
					<h1 id="name">Name: <?php echo $row['name']; ?></h1><br>
					<?php foreach ($row as $key => $value) {
						if($key=='username' and $value!=null)
							echo '<p>Username: '.$row["username"] .'</p><br>';
						if($key=='email' and $value!=null)
							echo '<p>Email Id: '.$row["email"] .'</p><br>';
						if($key=='status' and $value!=null)
							echo '<p>Status: '.$row["status"] .'</p><br>';
						if($key=='education' and $value!=null)
							echo '<p>Education: '.$row["education"] .'</p><br>';
						if($key=='gender' and $value!=null)
							echo '<p>Gender: 	'.$row["gender"] .'</p><br>';
					}
					?>
				</div>
				<?php if($_SESSION['start']==$row['login_id'])
				{
					?>
				<div class="edit"><a href="#">Edit Profile</a></div>
				<?php
				}
				?>
			</div>

			<?php
			if($_SESSION['start']==$row['login_id'])
			{
				?>

			<div class="boxx" id="profile">
				<form method="post" action="../profile_generate.php">
					<label>Status : </label>
					<textarea name="status" id="status"><?php echo $row['status']; ?></textarea>
					<label>Education : </label>
					<input type="text" name="education" id="education" value="<?php echo $row['education']; ?>"></input>
					<label>Gender : </label><br>
					<input type="radio" name="gender" id="gender" value="Male" <?php echo ($row['gender']=='Male')?'checked':'' ?>> Male<br>
					<input type="radio" name="gender" id="gender" value="Female" <?php echo ($row['gender']=='Female')?'checked':'' ?>> Female<br>
					<input type="submit" name="submit" value="Done" id="submit">
				</form>
			</div>
			<?php
			}
			?>
		</div>


		<div class="footer">
			<h3 class="footer_text">Made with love by <a href="#">Ankit Jain</a></h3>
		</div>

	</body>
    <script type="text/javascript" src="../js/jquery-3.0.0.min.js"></script>
	<script type="text/javascript" src="../js/profile.js"></script>
    <script type="text/javascript" src="../placeholder.js/placeholder.js"></script>


</html>



<?php
		}
		else
		{
			die("Invalid User");
		}
	}
	else
	{
		die("invalid");
	}
}
else
{
	header("Location:http://localhost/openchat/index.php");
}
?>

