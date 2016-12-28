<?php


namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');

/**
*
*/
class Username
{
    protected $username;
    protected $query;
    protected $result;

    function __construct()
    {
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    function UserName($id)
    {
        $this->query="SELECT username,name from login where login_id='$id'";
        $this->result=$this->connect->query($this->query);
        if($this->result->num_rows>0)                   // if true
        {
            $this->username = $this->result->fetch_assoc();
            return $this->username;
        }
    }
}
?>