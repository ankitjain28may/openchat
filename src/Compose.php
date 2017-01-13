<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
*
*/
class Compose
{
    protected $connect;
    protected $array;

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
        $this->array = array();
    }

    public function selectUser($msg)
    {
        $msg = $msg->value;
        if(Session::get('start') != null && isset($msg))
        {
            $userId = Session::get('start');
            $suggestion = trim($msg);
            if($suggestion != "" )
            {
                $query = "SELECT * FROM login where login_id != '$userId' and name like '$suggestion%' ORDER BY name DESC";
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
    }
}

