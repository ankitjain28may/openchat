<?php

namespace ChatApp;
require_once (dirname(__DIR__).'/vendor/autoload.php');
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
* For checking online status
*/
class Online
{

    public static function setOnlineStatus($userId)
    {
        $connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $query = "Update login set login_status = 1 where login_id = '$userId'";
        if (!$connect->query($query)) {
            echo $connect->error;
        }
        $connect->close();
    }

    public static function removeOnlineStatus($userId)
    {
        $connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $query = "Update login set login_status = 0 where login_id = '$userId'";
        if (!$connect->query($query)) {
            echo $connect->error;
        }
        $connect->close();
    }
}
