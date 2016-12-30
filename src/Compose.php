<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');

/**
*
*/
class Compose
{
    protected $connect;
    protected $array;

    function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        session_write_close();
        $this->array = array();
    }

    public function SelectUser($msg)
    {
        $msg = $msg->value;
        if(isset($_SESSION['start']) && isset($msg))
        {
            $id = $_SESSION['start'];
            $suggestion = trim($msg);
            if($suggestion != "" )
            {
                $query = "SELECT * FROM login where login_id != '$id' and name like '$suggestion%' ORDER BY name DESC";
                if($result = $this->connect->query($query))
                {
                    if($result->num_rows > 0)
                    {
                        while($row = $result->fetch_assoc())
                        {
                            $this->array = array_merge($this->array, [$row]);
                        }
                        $this->array = array_merge([], ["Compose" => $this->array]);
                        return json_encode($this->array);
                    }
                    else
                    {
                        return json_encode(["Compose" => "Not Found"]);
                    }
                }
                else
                {
                    return json_encode(["Compose" => "Query Failed"]);
                }
            }
            else
            {
                return json_encode(["Compose" => "Not Found"]);
            }
        }
        $this->connect->close();
    }
}

?>