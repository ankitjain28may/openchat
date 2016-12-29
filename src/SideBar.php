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
    protected $query;
    protected $connect;
    protected $result;
    protected $result1;
    protected $fetch;
    protected $row;
    protected $length;
    protected $substring;
    protected $identifier;

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
            $this->query = "SELECT * FROM total_message WHERE user1='$userId' or user2='$userId'  ORDER BY id DESC";
            if($this->result = $this->connect->query($this->query))
            {
                if ($this->result->num_rows > 0)
                {
                    $this->length = strlen($userId);
                    while($this->row = $this->result->fetch_assoc())
                    {
                        $this->identifier = $this->row['identifier'];
                        $this->substring = substr($this->identifier, 0, $this->length);
                        if($this->substring != $userId)
                        {
                            $this->array = array_merge($this->array, [$this->Data($this->substring, $this->row)]);
                        }

                        else
                        {
                            $this->substring = substr($this->identifier, $this->length+1);
                            $this->array = array_merge($this->array, [$this->Data($this->substring, $this->row)]);
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
        $this->query = "SELECT username,name,login_status from login where login_id = '$id'";
        if($this->result1 = $this->connect->query($this->query))
        {
            if($this->result1->num_rows > 0)
            {
                $this->fetch = $this->result1->fetch_assoc();
                $row['time'] = $this->obTime->TimeConversion($row['time']);
                $this->fetch = array_merge($this->fetch, ['time' => $row['time']]);
                return $this->fetch;
            }
        }
    }

}
?>