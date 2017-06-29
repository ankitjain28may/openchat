<?php
/**
 * Validate Class Doc Comment
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
use mysqli;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
 * For Validating User Data whether he is registered or not
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */

class Validate
{
    /*
    |--------------------------------------------------------------------------
    | Validate Class
    |--------------------------------------------------------------------------
    |
    | For Validating User Data whether he is registered or not.
    |
    */

    protected $connect;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->connect = new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
    }

    /**
     * Validating Email in Database.
     *
     * @param string $email To store email id
     *
     * @return integer 0|1
     */
    public function validateEmailInDb($email)
    {
        $query = "SELECT login_id FROM login WHERE email = '$email'";
        if ($result = $this->connect->query($query)) {
            if ($result->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * Validating Username in Database.
     *
     * @param string $username To store username
     *
     * @return integer 0|1
     */
    function validateUsernameInDb($username)
    {
        $query = "SELECT login_id FROM login WHERE username = '$username'";
        if ($result = $this->connect->query($query)) {
            if ($result->num_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}
