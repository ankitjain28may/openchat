<?php
require_once 'database.php';
session_start();
if(isset($_SESSION['start']) && isset($_REQUEST['q']))
{
	$id=$_SESSION['start'];
	$query="SELECT * FROM total_message WHERE user1='$id' or user2='$id'  ORDER BY time DESC";
	// $query="SELECT * FROM total_message WHERE identifier like '%:$id' or '$id:%'";
	$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if($result=$connect->query($query)) 
	{
		if ($result->num_rows > 0) 
		{
			$array = array();
			$ln=strlen($id);
    		while($row = $result->fetch_assoc()) 
    		{
				$value=$row['identifier'];
				$st=substr($value, 0,$ln);
				if($st!=$id)
				{
					$query="SELECT username,name from login where login_id='$st'";
					if($result1=$connect->query($query)) 
					{
						if($result1->num_rows>0)
						{
							$fetch=$result1->fetch_assoc();
							$fetch=array_merge($fetch,['time'=>$row['time']]);
							$array=array_merge($array,[$fetch]);
						}
					}
				}
				
				else
				{
					$st=substr($value,$ln+1);
					$query="SELECT username,name from login where login_id='$st'";
					if($result1=$connect->query($query)) 
					{
						if($result1->num_rows>0)
						{
							$fetch=$result1->fetch_assoc();
							$fetch=array_merge($fetch,['time'=>$row['time']]);
							$array=array_merge($array,[$fetch]);
						}
					}
				}
			}
				echo json_encode($array);
				// var_dump($array);
		}
		else
		{
			echo "No messages";
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