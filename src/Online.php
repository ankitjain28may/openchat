<?php
/**
 * Online Class Doc Comment
 *
 * PHP version 5
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
namespace ChatApp;
require_once dirname(__DIR__).'/vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
 * For checking online status
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
class Online
{

    /*
    |--------------------------------------------------------------------------
    | Online Class
    |--------------------------------------------------------------------------
    |
    | For checking online status.
    |
    */

    /**
     * Set Online Status
     *
     * @param string $userId To store userId
     *
     * @return void
     */
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

    /**
     * Remove Online Status
     *
     * @param string $userId To store userId
     *
     * @return void
     */
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
