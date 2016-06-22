<?php
require_once 'database.php';
session_start();
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if(isset($_SESSION['start']) && isset($_REQUEST['q']))
{
	$id=$_SESSION['start'];
	$text_reply=json_decode($_REQUEST['q']);
	// var_dump($text_reply);
	$identifier=$text_reply->name;
	$reply=trim($text_reply->reply[0]);
	// echo $reply;
	$time=date("D d M Y H:i:s", time()+12600);
	$time_id=date("YmdHis",time()+12600);
	if($reply!="" )
	{
		$query="SELECT * from total_message where identifier='$identifier'";
		$result=$connect->query($query);
		if($result->num_rows>0)
		{
			$query="UPDATE total_message SET total_messages=total_messages+1,time='$time',unread=1,id='$time_id' WHERE identifier='$identifier'";
			// $query="SELECT * FROM total_message WHERE identifier like '%:$id' or '$id:%'";
			if($result=$connect->query($query)) 
			{
				$query="INSERT into messages values('$identifier','$reply','$id','$time',null)";
				if($result=$connect->query($query))
				{
					echo "Messages is sent";
				}
				else
				{
					echo "Message is failed";
				}
			}
		}
		else
		{
			$ln=strlen($id);
			if(substr($identifier,0,$ln)==$id)
			{
				$user2=substr($identifier,$ln+1);
				$user1=$id;
			}
			else
			{
				$user2=$id;
				$user1=substr($identifier,0,$ln);
			}
			$query="INSERT into total_message values('$identifier',1,'$user1','$user2',1,'$time','$time_id')";
			if($result=$connect->query($query))
			{
				$query="INSERT into messages values('$identifier','$reply','$id','$time',null)";
				if($result=$connect->query($query))
				{
					echo "Messages is sent";
				}
				else
				{
					echo "Message is failed";
				}
			}
		}
	}
}
?>