<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/config/database.php');

/**
* For retreiving User Profile
*/
class Profile
{

    public static function getProfile($userId)
    {
        $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $query = "SELECT * from profile where login_id = '$userId'";
        $result = $connect->query($query);
        if($result->num_rows > 0)                   // if true
        {
            $details = $result->fetch_assoc();
            return $details;
        }
        else
        {
            return NULL;
        }
    }
}