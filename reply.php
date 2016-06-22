<?php
require_once 'database.php';
session_start();
if(isset($_SESSION['start']) && isset($_REQUEST['q']))
{
	$id=$_SESSION['start'];
	$text_reply=json_decode($_REQUEST['q']);
	// var_dump($text_reply);
	$identifier=$text_reply->name;
	$reply=trim($text_reply->reply[0]);
	// echo $reply;
	$time=date("D d M Y H:i:s", time()+12600);
	if($reply!="" )
	{
		$query="UPDATE total_message SET total_messages=total_messages+1,time='$time',unread=1 WHERE identifier='$identifier'";
		// $query="SELECT * FROM total_message WHERE identifier like '%:$id' or '$id:%'";
		$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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
?>