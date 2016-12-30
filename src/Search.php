<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');
use ChatApp\Time;

/**
*
*/
class Search
{

    protected $connect;
    protected $array;
    protected $obTime;
    protected $obUser;

    function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        session_write_close();
        $this->obTime = new Time();
        $this->array = array();
    }

    function SearchItem($suggestion)
    {
        $suggestion = $suggestion->value;
        $flag = 0;
        if(isset($_SESSION['start']) && isset($suggestion))
        {
            $id = $_SESSION['start'];
            $suggestion = trim($suggestion);
            if($suggestion != "")
            {
                $query = "SELECT * FROM login where login_id != '$id' and name like '$suggestion%' ORDER BY name ASC";
                if($result = $this->connect->query($query))
                {
                    if($result->num_rows > 0)
                    {
                        while($row = $result->fetch_assoc())
                        {
                            $check_id = $row["login_id"];
                            $query = "SELECT * from total_message where (user1 = '$check_id' and user2 = '$id') or (user2 = '$check_id' and user1 = '$id')";
                            if($result1 = $this->connect->query($query))
                            {
                                if($result1->num_rows > 0)
                                {
                                    $fetch = $result1->fetch_assoc();
                                    $fetch['time'] = $this->obTime->TimeConversion($fetch['time']);

                                    $this->array = array_merge($this->array, [['time' => $fetch['time'], 'username' => $row['username'], 'name' => $row['name']]]);
                                    $flag = 1;
                                }
                            }
                        }
                    }
                }
                if($flag != 0)
                {
                    $this->array = array_merge([], ["Search" => $this->array]);
                    return json_encode($this->array);
                }
                else
                    return json_encode(["Search" => "Not Found"]);
            }
            else
            {
                return json_encode(["Search" => "Not Found"]);
            }
        }
        else
        {
            return json_encode(["Search" => "Not Found"]);
        }

    }
}
?>