<?php
require_once 'database.php';
session_start();
if(isset($_SESSION['start']) && isset($_REQUEST['q']))
{
	$id=$_SESSION['start'];
	$suggestion=trim($_REQUEST['q']);
	$array=[];
	if($suggestion!="" )
	{
		$query="SELECT * FROM login where login_id!='$id' and name like '$suggestion%' ORDER BY name DESC";
		// $query="SELECT * FROM total_message WHERE identifier like '%:$id' or '$id:%'";
		$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if($result=$connect->query($query)) 
		{
			if($result->num_rows>0)
			{
				while($row=$result->fetch_assoc())
				{
					$array=array_merge($array,[$row]);
				}
				echo json_encode($array);	
			}
			else
			{
				echo json_encode(["Not Found"]);
			}
			
		}
		else
		{
			echo json_encode(["Query Failed"]);
		}
	}
	else
	{
		echo json_encode([]);
	}
}
?>