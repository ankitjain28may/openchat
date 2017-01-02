<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/config/database.php');

/**
* For checking online status
*/
class Online
{

    public static function setOnlineStatus($userId)
    {
        $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "Update login set login_status = 1 where login_id = '$userId'";
        if(!$connect->query($query));
            echo $connect->error;
        $connect->close();
    }

    public static function removeOnlineStatus($userId)
    {
        $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "Update login set login_status = 0 where login_id = '$userId'";
        if(!$connect->query($query));
            echo $connect->error;
        $connect->close();
    }
}
