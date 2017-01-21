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

    public function __construct()
    {
        $this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );

        $this->array = array();
    }

    public function selectUser($msg)
    {
        $userId = $msg->userId;
        $suggestion = $msg->value;
        $suggestion = trim($suggestion);
        if(!empty($userId) && !empty($suggestion))
        {
            $query = "SELECT * FROM login where login_id != '$userId' and name like '$suggestion%' ORDER BY name DESC";
            if($result = $this->connect->query($query))
            {
                if($result->num_rows > 0)
                {
                    while($row = $result->fetch_assoc())
                    {
                        $row["login_id"] = bin2hex(convert_uuencode($row["login_id"]));
                        $this->array = array_merge($this->array, [$row]);
                    }
                    $this->array = array_merge([], ["Compose" => $this->array]);
                    return json_encode($this->array);
                }
                return json_encode(["Compose" => "Not Found"]);
            }
            return json_encode(["Compose" => "Query Failed"]);
        }
        return json_encode(["Compose" => "Not Found"]);
    }
}

