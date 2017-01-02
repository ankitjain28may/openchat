<?php


namespace ChatApp;
require_once (dirname(__DIR__) . '/config/database.php');

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
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    public function UserDetails($id, $para)
    {
        if($para == True)
            $this->query = "SELECT * from login where login_id = '$id'";
        else
            $this->query = "SELECT * from login where username = '$id'";
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
