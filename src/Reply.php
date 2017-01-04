<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/config/database.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
* Store Message in the Database
*/
class Reply
{
    protected $connect;

    public function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        $this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        session_write_close();
    }

    public function replyTo($msg)
    {

        if(Session::get('start') != null && isset($msg))  //checks for session login and the value send
        {
            $userId = Session::get('start');
            $msg = json_decode($msg);   //decode json value
            $identifier = $msg->name;

            $receiverID = $identifier;  //stores id of the person whom message is to be sent

            if($identifier > $userId)    // geneate specific unique code to store messages
                $identifier = $userId . ":" . $identifier;
            else
                $identifier = $identifier . ":" . $userId;

            $reply = addslashes(trim($msg->reply[0])); // stores the message sent by the user.

            $time = date("D d M Y H:i:s", time() + 16200);  // current time
            $time_id = date("YmdHis", time() + 16200); //to sort the array on the basis of time

            //the sender id must not be equal to current session id
            if($reply != "" && $receiverID != $userId)
            {
                // check whether the receiver is authorized or registered
                $query = "SELECT * from login where login_id = '$receiverID'";

                $result = $this->connect->query($query);
                if($result->num_rows > 0)     // if true
                {
                    //check whether he is sending message for thr first time or he has sent messages before
                    $query = "SELECT * from total_message where identifier = '$identifier'";
                    $result = $this->connect->query($query);
                    if($result->num_rows>0)               // if he has sent messages before
                    {
                        // Update Total_Message Table
                        $query = "UPDATE total_message SET total_messages = total_messages+1, time = '$time', unread = 1, id = '$time_id' WHERE identifier = '$identifier'";
                        $this->updateMessages($query, $identifier, $reply, $userId, $time);

                    }
                    else    // if he sends message for the first time
                    {
                        $length = strlen($userId);
                        if(substr($identifier, 0, $length) == $userId) // generate specific unique code
                        {
                            $user2 = substr($identifier, $length+1);
                            $user1 = $userId;
                        }
                        else
                        {
                            $user2 = $userId;
                            $length = strlen($identifier) - $length-1;
                            $user1 = substr($identifier, 0, $length);
                        }
                        // insert Details in Total_Message Table
                        $query = "INSERT into total_message values('$identifier', 1, '$user1', '$user2', 1, '$time', '$time_id')";
                        $this->updateMessages($query, $identifier, $reply, $userId, $time);
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

    public function updateMessages($query, $identifier, $reply, $userId, $time)
    {
        if($result = $this->connect->query($query))
        {
            //insert message in db
            $query = "INSERT into messages values('$identifier', '$reply', '$userId', '$time', null)";
            if($result = $this->connect->query($query))
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
