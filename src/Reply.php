<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');

/**
* Store Message in the Database
*/
class Reply
{
    protected $id;
    protected $identifier;
    protected $receiverID;
    protected $reply;
    protected $connect;
    protected $time_id;
    protected $time;
    protected $user2;
    protected $user1;
    protected $length;
    protected $query;
    protected $result;

    function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        session_write_close();
    }

    function replyTo($msg)
    {

        if(isset($_SESSION['start']) && isset($msg))  //checks for session login and the value send
        {
            $this->id = $_SESSION['start'];
            $msg = json_decode($msg);   //decode json value
            $this->identifier = $msg->name;

            $this->receiverID = $this->identifier;  //stores id of the person whom message is to be sent

            if($this->identifier > $this->id)    // geneate specific unique code to store messages
                $this->identifier = $this->id.":".$this->identifier;
            else
                $this->identifier = $this->identifier.":".$this->id;

            $this->reply = addslashes(trim($msg->reply[0])); // stores the message sent by the user.

            $this->time = date("D d M Y H:i:s", time()+12600);  // current time
            $this->time_id = date("YmdHis",time()+12600); //to sort the array on the basis of time

            //the sender id must not be equal to current session id
            if($this->reply != "" && $this->receiverID != $this->id)
            {
                // check whether the receiver is authorized or registered
                $this->query = "SELECT * from login where login_id = '$this->receiverID'";

                $this->result = $this->connect->query($this->query);
                if($this->result->num_rows > 0)     // if true
                {
                    //check whether he is sending message for thr first time or he has sent messages before
                    $this->query = "SELECT * from total_message where identifier = '$this->identifier'";
                    $this->result = $this->connect->query($this->query);
                    if($this->result->num_rows>0)               // if he has sent messages before
                    {
                        // Update Total_Message Table
                        $this->query = "UPDATE total_message SET total_messages = total_messages+1, time = '$this->time', unread = 1, id = '$this->time_id' WHERE identifier = '$this->identifier'";
                        $this->UpdateMessages();

                    }
                    else    // if he sends message for the first time
                    {
                        $this->length = strlen($this->id);
                        if(substr($this->identifier, 0, $this->length) == $this->id) // generate specific unique code
                        {
                            $this->user2 = substr($this->identifier,$this->length+1);
                            $this->user1 = $this->id;
                        }
                        else
                        {
                            $this->user2 = $this->id;
                            $this->length = strlen($this->identifier)-$this->length-1;
                            $this->user1 = substr($this->identifier,0,$this->length);
                        }
                        // insert Details in Total_Message Table
                        $this->query = "INSERT into total_message values('$this->identifier', 1, '$this->user1', '$this->user2', 1, '$this->time', '$this->time_id')";
                        $this->UpdateMessages();


                    }
                }
                else // if he is unauthorized echo message is failed
                {
                    echo "Message is failed";
                }
            }
        }
        else
        {
            echo "failed";
        }
        $this->connect->close();
    }

    function UpdateMessages()
    {
        if($this->result = $this->connect->query($this->query))
        {
            $this->query = "INSERT into messages values('$this->identifier', '$this->reply', '$this->id', '$this->time', null)";    //insert message in db
            if($this->result = $this->connect->query($this->query))
            {
                echo "Messages is sent \n";    // if query is executed return true
            }
            else
            {
                echo "Message is failed";
            }
        }
    }


}

?>