<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');

/**
*
*/
class Reply
{
	protected $id;
	protected $text_reply;
	protected $identifier;
	protected $login_check;
	protected $reply;
	protected $connect;
	protected $time_id;
	protected $time;
	protected $user2;
	protected $user1;
	protected $ln;
	protected $query;
	protected $result;

	function __construct($sessionId)
	{
		var_dump($sessionId);
		session_id($sessionId);
		session_start();
		print_r($_SESSION);
		$this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	}

	function replyTo($msg)
	{
		var_dump(isset($msg));
		var_dump($_SESSION['start']);

		if(isset($_SESSION['start']) && isset($msg))  //checks for session login and the value send through ajax
		{
			$this->id=$_SESSION['start'];
			$this->text_reply=json_decode($msg);	//decode json value
			// var_dump($text_reply);
			$this->identifier=$this->text_reply->name;

			$this->login_check=$this->identifier;	//stores id of the person whom message is to be sent

			if($this->identifier>$this->id)							// geneate specific unique code to store messages
				$this->identifier=$this->id.":".$this->identifier;
			else
				$this->identifier=$this->identifier.":".$this->id;

			$this->reply=trim($this->text_reply->reply[0]);			// stores the message sent by the user.

			$this->reply=addslashes($this->reply);

			$this->time=date("D d M Y H:i:s", time()+12600);	// current time
			$this->time_id=date("YmdHis",time()+12600);		//to sort the array on the basis of time

			if($this->reply!="" && $this->login_check!=$this->id)		//the sender id must not be equal to current session id
			{
				$this->query="SELECT * from login where login_id='$this->login_check'";  // check whether the receiver is authorized or registered
				$this->result=$this->connect->query($this->query);
				if($this->result->num_rows>0)   				// if true
				{
					$this->query="SELECT * from total_message where identifier='$this->identifier'";	//check whether he is sending message for thr first time or he has sent messages before
					$this->result=$this->connect->query($this->query);
					if($this->result->num_rows>0)				// if he has sent messages before
					{
						$this->query="UPDATE total_message SET total_messages=total_messages+1,time='$this->time',unread=1,id='$this->time_id' WHERE identifier='$this->identifier'";		// update db
						// $query="SELECT * FROM total_message WHERE identifier like '%:$id' or '$id:%'";
						if($this->result=$this->connect->query($this->query))
						{
							$this->query="INSERT into messages values('$this->identifier','$this->reply','$this->id','$this->time',null)"; 	//insert message in db
							if($this->result=$this->connect->query($this->query))
							{
								var_dump("Messages is sent");		// if query is executed return true
							}
							else
							{
								var_dump("Message is failed");		// else false
							}
						}
					}
					else  						// if he sends message for the first time
					{
						$this->ln=strlen($this->id);
						if(substr($this->identifier,0,$this->ln)==$this->id)		// generate specific unique code
						{
							$this->user2=substr($this->identifier,$this->ln+1);
							$this->user1=$this->id;
						}
						else
						{
							$this->user2=$this->id;
							$this->ln=strlen($this->identifier)-$this->ln-1;
							$this->user1=substr($this->identifier,0,$this->ln);
						}
						$this->query="INSERT into total_message values('$this->identifier',1,'$this->user1','$this->user2',1,'$this->time','$this->time_id')"; //insert messages in db
						if($this->result=$this->connect->query($this->query))
						{
							$this->query="INSERT into messages values('$this->identifier','$this->reply','$this->id','$this->time',null)";	// insert in db
							if($this->result=$this->connect->query($this->query))
							{
								var_dump("Messages is sent");	// if query is executed return true
							}
							else
							{
								var_dump("Message is failed");	//else false
							}
						}
					}
				}
				else 		// if he is unauthorized echi message is failed
				{
					var_dump("Message is failed");
				}
			}
		}
		else
		{
			var_dump("failed");
		}

	}
}

?>