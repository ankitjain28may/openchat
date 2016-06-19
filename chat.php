<?php
require_once 'database.php';
session_start();
if(isset($_SESSION['start']) && isset($_REQUEST['q']))
{
	$id=$_SESSION['start'];
	$username=$_REQUEST['q'];
	// $query="SELECT * FROM total_message WHERE user1='$id' or user2='$id'";
	$query="SELECT login_id FROM login WHERE username='$username'";
	$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if($result=$connect->query($query)) 
	{
		$check="";
		$array = array();
		if ($result->num_rows > 0) 
		{
			// echo $result->fetch_assoc();
			$fetch=$result->fetch_assoc();
			$login_id=(int)$fetch['login_id'];
			if($login_id>$id)
				$check=$id.':'.$login_id;
			else
				$check=$login_id.':'.$id;
			// var_dump($check);
			$query="SELECT * FROM messages WHERE identifier_message_number='$check' ORDER BY time DESC";
			if($result1=$connect->query($query)) 
			{
				if($result1->num_rows>0)
				{
   					while($row = $result1->fetch_assoc()) 
   					{
						$row=array_merge($row,['start'=>$id]);
						$array=array_merge($array,[$row]);
					}
				}
			}				
			else
			{
				die("Query Failed");
			}
				echo json_encode($array);
				// var_dump($array);
		}
	}
	else{
		echo "Query Failed";
	}
}
else{
	header('Location:login.php');
}
?>