<?php

namespace ChatApp;
require_once (dirname(__DIR__).'/vendor/autoload.php');
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
* Store Message in the Database
*/
class Reply
{
    protected $connect;

    public function __construct()
    {
        $this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
    }

    public function replyTo($msg)
    {
        if (!empty($msg)) {
            //checks for the value send
            $userId = $msg->userId;
            $receiverID = $msg->name; //stores id of the person whom message is to be sent
            $identifier;

            if ($receiverID > $userId) {
                // geneate specific unique code to store messages
                $user1 = $userId;
                $user2 = $receiverID;
                $identifier = $userId.":".$receiverID;
            } else {
                $user1 = $receiverID;
                $user2 = $userId;
                $identifier = $receiverID.":".$userId;
            }

            $reply = addslashes(trim($msg->reply)); // stores the message sent by the user.

            $time = date("D d M Y H:i:s", time() + 16200); // current time
            $time_id = date("YmdHis", time() + 16200); //to sort the array on the basis of time

            //the sender id must not be equal to current session id
            if ($reply != "" && $receiverID != $userId) {
                // check whether the receiver is authorized or registered
                $query = "SELECT * from login where login_id = '$receiverID'";

                $result = $this->connect->query($query);
                if ($result->num_rows > 0) {
                    //check whether he is sending message for thr first time or he has sent messages before
                    $query = "SELECT * from total_message where identifier = '$identifier'";
                    $result = $this->connect->query($query);
                    if ($result->num_rows > 0) {
                        // if he has sent messages before Update Total_Message Table
                        $query = "UPDATE total_message SET total_messages = total_messages+1, time = '$time', unread = 1, id = '$time_id' WHERE identifier = '$identifier'";
                        return $this->updateMessages($query, $identifier, $reply, $userId, $time);

                    } else {
                        // if he sends message for the first time insert Details in Total_Message Table
                        $query = "INSERT into total_message values('$identifier', 1, '$user1', '$user2', 1, '$time', '$time_id')";
                        return $this->updateMessages($query, $identifier, $reply, $userId, $time);
                    }
                }
                return "Invalid Authentication"; // if he is unauthorized echo message is failed
            }
        }
        return "Failed";
    }

    /**
     * @param string $identifier
     * @param string $reply
     * @param string $time
     * @param string $userId
     * @param string $query
     */
    public function updateMessages($query, $identifier, $reply, $userId, $time)
    {
        if ($result = $this->connect->query($query)) {
            //insert message in db
            $query = "INSERT into messages values('$identifier', '$reply', '$userId', '$time', null)";
            if ($this->connect->query($query)) {
                // if query is executed return true
                return "Messages is sent";
            }
            return "Message is failed";
        }
    }


}

