<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');
use ChatApp\Time;

/**
* Fetching the sidebar results
*/
class SideBar
{

    protected $obTime;
    protected $array;
    protected $connect;

    function __construct()
    {
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->obTime = new Time();
        $this->array = array();
    }

    function LoadSideBar($userId)
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
                            $this->Data($substring, $row);
                        }

                        else
                        {
                            $substring = substr($identifier, $length+1);
                            $this->Data($substring, $row);
                        }
                    }
                    $this->array = array_merge([], [$this->array]);
                    return json_encode($this->array);
                }
                else
                {
                    return json_encode(null);
                }
            }
            else
            {
                echo "Query Failed";
            }
        }
        else
        {
            header('Location:../login.php');
        }
        $this->connect->close();
    }

    function Data($id, $row)
    {
        $query = "SELECT username,name,login_status from login where login_id = '$id'";
        if($result = $this->connect->query($query))
        {
            if($result->num_rows > 0)
            {
                $fetch = $result->fetch_assoc();
                $row['time'] = $this->obTime->TimeConversion($row['time']);
                $fetch = array_merge($fetch, ['time' => $row['time']]);
                $this->array = array_merge($this->array, [$fetch]);
            }
        }
    }

}
?>