<?php
/**
 * User Class Doc Comment
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
 * For retreiving User Information
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
class User
{
    /*
    |--------------------------------------------------------------------------
    | User Class
    |--------------------------------------------------------------------------
    |
    | For retreiving User Information.
    |
    */

    protected $details;
    protected $query;
    protected $result;
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
     * Getting User details on the basis of uername or login Id
     *
     * @param string  $details To store loginid/username
     * @param boollen $para    To store True/False
     *
     * @return array or null
     */
    public function userDetails($details, $para)
    {
        if ($para == true) {
            $this->query = "SELECT * from login where login_id = '$details'";
        } else {
            $this->query = "SELECT * from login where username = '$details'";
        }
        $this->result = $this->connect->query($this->query);
        if ($this->result->num_rows > 0) {
            $this->details = $this->result->fetch_assoc();
            return $this->details;
        } else {
            return null;
        }
    }
}
