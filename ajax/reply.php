<?php
require_once '../database.php';
session_start();
$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(isset($_SESSION['start']) && isset($_POST['q']))  //checks for session login and the value send through ajax
{
	$id=$_SESSION['start'];
	$text_reply=json_decode($_POST['q']);	//decode json value
	// var_dump($text_reply);
	$identifier=$text_reply->name;	

	$login_check=$identifier;	//stores id of the person whom message is to be sent

	if($identifier>$id)							// geneate specific unique code to store messages
		$identifier=$id.":".$identifier;
	else
		$identifier=$identifier.":".$id;	

	$reply=trim($text_reply->reply[0]);			// stores the message sent by the user.
	
	$reply=addslashes($reply);

	$time=date("D d M Y H:i:s", time()+12600);	// current time
	$time_id=date("YmdHis",time()+12600);		//to sort the array on the basis of time

	if($reply!="" && $login_check!=$id)		//the sender id must not be equal to current session id
	{
		$query="SELECT * from login where login_id='$login_check'";  // check whether the receiver is authorized or registered
		$result=$connect->query($query);
		if($result->num_rows>0)   				// if true
		{
			$query="SELECT * from total_message where identifier='$identifier'";	//check whether he is sending message for thr first time or he has sent messages before
			$result=$connect->query($query);
			if($result->num_rows>0)				// if he has sent messages before
			{
				$query="UPDATE total_message SET total_messages=total_messages+1,time='$time',unread=1,id='$time_id' WHERE identifier='$identifier'";		// update db
				// $query="SELECT * FROM total_message WHERE identifier like '%:$id' or '$id:%'";
				if($result=$connect->query($query)) 
				{
					$query="INSERT into messages values('$identifier','$reply','$id','$time',null)"; 	//insert message in db
					if($result=$connect->query($query))
					{
						echo "Messages is sent";		// if query is executed return true
					}	
					else
					{
						echo "Message is failed";		// else false
					}
				}
			}
			else  						// if he sends message for the first time
			{
				$ln=strlen($id);
				if(substr($identifier,0,$ln)==$id)		// generate specific unique code
				{
					$user2=substr($identifier,$ln+1);
					$user1=$id;
				}
				else
				{
					$user2=$id;
					$user1=substr($identifier,0,$ln);
				}
				$query="INSERT into total_message values('$identifier',1,'$user1','$user2',1,'$time','$time_id')"; //insert messages in db
				if($result=$connect->query($query))
				{
					$query="INSERT into messages values('$identifier','$reply','$id','$time',null)";	// insert in db
					if($result=$connect->query($query))
					{
						echo "Messages is sent";	// if query is executed return true
					}
					else
					{
						echo "Message is failed";	//else false
					}
				}
			}
		}
		else 		// if he is unauthorized echi message is failed
		{
			echo "Message is failed";
		}
	}	
}
?>