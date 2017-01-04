<?php


namespace ChatApp;
require_once (dirname(__DIR__) . '/vendor/autoload.php');
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
* For retreiving User Information
*/
class User
{
    protected $details;
    protected $query;
    protected $result;
    protected $connect;

    public function __construct()
    {
        $this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
    }

    public function userDetails($userId, $para)
    {
        if($para == True)
            $this->query = "SELECT * from login where login_id = '$userId'";
        else
            $this->query = "SELECT * from login where username = '$userId'";
        $this->result = $this->connect->query($this->query);
        if($this->result->num_rows > 0)                   // if true
        {
            $this->details = $this->result->fetch_assoc();
            return $this->details;
        }
        else
        {
            return NULL;
        }
    }
}
