<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Time;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
* Fetching the sidebar results
*/
class SideBar
{

    protected $obTime;
    protected $array;
    protected $connect;

    public function __construct()
    {
        $this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $this->obTime = new Time();
        $this->array = array();
    }

    public function loadSideBar($userId)
    {
        if(isset($userId))
        {
            $query = "SELECT * FROM total_message WHERE user1='$userId' or user2='$userId'  ORDER BY id DESC";
            if($result = $this->connect->query($query))
            {
                if ($result->num_rows > 0)
                {
                    $length = strlen($userId);
                    while($row = $result->fetch_assoc())
                    {
                        $identifier = $row['identifier'];
                        $substring = substr($identifier, 0, $length);
                        if($substring != $userId)
                        {
                            $this->data($substring, $row);
                        }
                        else
                        {
                            $substring = substr($identifier, $length+1);
                            $this->Data($substring, $row);
                        }
                    }
                    return json_encode($this->array);
                }
                return json_encode(null);
            }
            return "Query Failed";
        }
        return "Invalid Authentication";
    }

    public function data($userId, $row)
    {
        $query = "SELECT username, name, login_status, login_id from login where login_id = '$userId'";
        if($result = $this->connect->query($query))
        {
            if($result->num_rows > 0)
            {
                $fetch = $result->fetch_assoc();
                $row['time'] = $this->obTime->timeConversion($row['time']);
                $fetch = array_merge($fetch, ['time' => $row['time']]);
                $this->array = array_merge($this->array, [$fetch]);
            }
        }
    }

}
