<?php
/**
 * Profile Class Doc Comment
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
 * For retreiving User Profile
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
class Profile
{
    /*
    |--------------------------------------------------------------------------
    | Profile Class
    |--------------------------------------------------------------------------
    |
    | Send message to other user.
    |
    */

    /**
     * To get User Profile
     *
     * @param string $userId To store userId
     *
     * @return array / null
     */
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
            return null;
        }
    }
}
