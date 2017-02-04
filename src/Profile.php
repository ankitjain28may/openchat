<?php

namespace ChatApp;
require_once (dirname(__DIR__).'/vendor/autoload.php');
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
* For retreiving User Profile
*/
class Profile
{

    public static function getProfile($userId)
    {
        $connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $query = "SELECT * from profile where login_id = '$userId'";
        $result = $connect->query($query);
        if ($result->num_rows > 0) {
            // if true
            $details = $result->fetch_assoc();
            return $details;
        } else {
            return NULL;
        }
    }
}