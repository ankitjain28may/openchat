<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Time;
use ChatApp\User;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
*
*/
class Conversation
{
    protected $connect;
    protected $array;
    protected $obTime;
    protected $obUser;

    public function __construct()
    {
        $this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $this->obTime = new Time();
        $this->obUser = new User();
        $this->array = array();
    }

    public function conversationLoad($msg, $para)
    {
        $msg = json_decode($msg);

        if(!empty($msg))
        {
            $userId = $msg->userId;
            $add_load = 0;
            $username = $msg->username;
            $load = $msg->load;

            $fetch = $this->obUser->userDetails($username, $para);

            if($fetch != NULL)
            {
                $login_id = (int)$fetch['login_id'];

                // Unique Identifier
                if($login_id > $userId)
                    $identifier = $userId.':'.$login_id;
                else
                    $identifier = $login_id.':'.$userId;

                $query = "SELECT total_messages from total_message where identifier = '$identifier'";
                if($result = $this->connect->query($query))
                {
                    if($result->num_rows > 0)
                    {
                        $total = $result->fetch_assoc();
                        $total = $total['total_messages'];
                        if($total - $load > 0)
                            if($total - $load > 10)
                                $add_load = $load + 10;
                            else
                                $add_load = $total;
                    }
                }

                $query = "SELECT message, time, sent_by FROM messages WHERE identifier_message_number = '$identifier' ORDER BY id DESC limit ".$load;
                if($result = $this->connect->query($query))
                {
                    if($result->num_rows > 0)
                    {
                        while($row = $result->fetch_assoc())
                        {
                            $row['time'] = $this->obTime->timeConversion($row['time']);
                            $row = array_merge($row,['start' => $userId]);
                            $this->array = array_merge($this->array, [$row]);
                        }

                        $this->array = array_merge([['name' => $fetch['name'], 'username' => $fetch['username'], 'id' => $fetch['login_id'], 'load' => $add_load, 'login_status' => $fetch['login_status'], 'type' => 1]], $this->array);
                        return json_encode($this->array);
                    }
                    else
                    {
                        return json_encode([['name' => $fetch['name'], 'username' => $fetch['username'], 'id' => $fetch['login_id'], 'login_status' => $fetch['login_status'], 'type' => 0]]);
                    }
                }
                return "Query Failed";
            }
            return "Query Failed";
        }
        return "Empty";
    }
}
