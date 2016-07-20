<?php
require_once '../database.php';
session_start();
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(isset($_SESSION['start']) && isset($_REQUEST['q']))
{
	$id=$_SESSION['start'];
	$suggestion=trim($_REQUEST['q']);
	$array=[];
	if($suggestion!="" )
	{
		$query="SELECT * FROM login where login_id!='$id' and name like '$suggestion%' ORDER BY name DESC";
		// $query="SELECT * FROM total_message WHERE identifier like '%:$id' or '$id:%'";
		if($result=$connect->query($query)) 
		{
			if($result->num_rows>0)
			{
				$flag=0;
				while($row=$result->fetch_assoc())
				{
					$newarray=[];

					// var_dump($row);
					$check_id=$row["login_id"];
					$query="SELECT * from total_message where (user1='$check_id' and user2='$id') or (user2='$check_id' and user1='$id')";
					if($res=$connect->query($query))
					{
						if($res->num_rows>0)
						{
							$fetch=$res->fetch_assoc();
							// var_dump($fetch);
							if(substr($fetch['time'],4,11)==date("d M Y", time()+12600))
								$fetch['time']=substr($fetch['time'],16,5);
   							else if(substr($fetch['time'],7,8)==date("M Y", time()+12600) && substr($fetch['time'], 4,2)-date("d")<7)
								$fetch['time']=substr($fetch['time'],0,3);
							else if(substr($fetch['time'],11,4)==date("Y", time()+12600))
								$fetch['time']=substr($fetch['time'],4,6);
							else
								$fetch['time']=substr($fetch['time'],4,11);
							$newarray=array_merge($newarray,['time'=>$fetch['time'],'username'=>$row['username'],'name'=>$row['name']]);
							$array=array_merge($array,[$newarray]);
							$flag=1;
						}
					}
				}
				if($flag!=0)
					echo json_encode($array);
				else
					echo json_encode(null);

			}
			else
			{
				echo json_encode(null);
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