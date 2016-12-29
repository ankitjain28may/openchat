<?php


namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');

/**
* For retreiving User Information
*/
class User
{
    protected $details;
    protected $query;
    protected $result;
    protected $connect;

    function __construct()
    {
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    function UserDetails($id)
    {
        $this->query = "SELECT * from login where login_id = '$id'";
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
?>