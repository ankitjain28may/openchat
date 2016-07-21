<?php
require_once '../database.php';
session_start();
if(isset($_SESSION['start']) && isset($_POST['q']))
{
	$add_load=0;
	$id=$_SESSION['start'];
	$receive=json_decode($_POST['q']);
	$username=$receive->username;
	$load=$receive->load;


	// $query="SELECT * FROM total_message WHERE user1='$id' or user2='$id'";
	$query="SELECT login_id,name FROM login WHERE username='$username'";
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
			$query="SELECT total_messages from total_message where identifier='$check'";
			if($mesg=$connect->query($query))
			{
				if($mesg->num_rows>0)
				{
					$total=$mesg->fetch_assoc();
					$total=$total['total_messages'];
					if($total-$load>0)
						if($total-$load>10)
							$add_load=$load+10;
						else
							$add_load=$total;
					
				}
			}

			$query="SELECT * FROM messages WHERE identifier_message_number='$check' ORDER BY id DESC limit ".$load;
			if($result1=$connect->query($query)) 
			{
				if($result1->num_rows>0)
				{
   					while($row = $result1->fetch_assoc()) 
   					{
   						if(substr($row['time'],4,11)==date("d M Y", time()+12600))
							$row['time']=substr($row['time'],16,5);
   						else if(substr($row['time'],7,8)==date("M Y", time()+12600) && substr($row['time'], 4,2)-date("d")<7)
							$row['time']=substr($row['time'],0,3);
						else if(substr($row['time'],11,4)==date("Y", time()+12600))
							$row['time']=substr($row['time'],4,6);
						else
							$row['time']=substr($row['time'],4,11);
						$row['identifier_message_number']=$login_id;
						$row=array_merge($row,['name'=>$fetch['name']]);
						$row=array_merge($row,['start'=>$id]);
						$row=array_merge($row,['username'=>$username]);
						$array=array_merge($array,[$row]);
					}
					$array=array_merge($array,[['load'=>$add_load]]);
					$array=array_merge($array,[1]);
					echo json_encode($array);
				}
				else
				{
					echo json_encode(['identifier_message_number'=>$login_id,'name'=>$fetch['name'],'new'=>0]);
				}	
			}				
			else
			{
				die("Query Failed");
			}
				
				// var_dump($array);
		}
		
	}
	else{
		echo "Query Failed";
	}
}
else{
	header('Location:../login.php');
}
?>