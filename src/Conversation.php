<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');
use ChatApp\Username;
use ChatApp\Time;
/**
*
*/
class Conversation
{
    protected $flag;
    protected $connect;
    protected $query;
    protected $result;
    protected $add_load;
    protected $result1;
    protected $row;
    protected $id;
    protected $load;
    protected $receive;
    protected $username;
    protected $array;
    protected $check;
    protected $fetch;
    protected $login_id;
    protected $mesg;
    protected $obTime;

    function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        session_write_close();
        $this->obTime = new Time();
    }

    function ConversationLoad($msg)
    {

        $this->flag=1;
        if(isset($_SESSION['start']) && isset($msg))
        {
            $this->add_load=0;
            $this->id=$_SESSION['start'];
            $this->receive=json_decode($msg);
            $this->username=$this->receive->username;
            $ob = new Username;
            $this->username = $ob->UserName($this->username)['username'];
            $this->load=$this->receive->load;


            // $query="SELECT * FROM total_message WHERE user1='$id' or user2='$id'";
            $this->query="SELECT login_id,name,login_status FROM login WHERE username='$this->username'";
            $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if($this->result=$this->connect->query($this->query))
            {
                $this->check="";
                $this->array = array();
                if ($this->result->num_rows > 0)
                {
                    // echo $result->fetch_assoc();
                    $this->fetch=$this->result->fetch_assoc();
                    $this->login_id=(int)$this->fetch['login_id'];
                    if($this->login_id>$this->id)
                        $this->check=$this->id.':'.$this->login_id;
                    else
                        $this->check=$this->login_id.':'.$this->id;
                    // var_dump($check);
                    $this->query="SELECT total_messages from total_message where identifier='$this->check'";
                    if($this->mesg=$this->connect->query($this->query))
                    {
                        if($this->mesg->num_rows>0)
                        {
                            $this->total=$this->mesg->fetch_assoc();
                            $this->total=$this->total['total_messages'];
                            if($this->total-$this->load>0)
                                if($this->total-$this->load>10)
                                    $this->add_load=$this->load+10;
                                else
                                    $this->add_load=$this->total;

                        }
                    }

                    $this->query="SELECT * FROM messages WHERE identifier_message_number='$this->check' ORDER BY id DESC limit ".$this->load;
                    if($this->result1=$this->connect->query($this->query))
                    {
                        if($this->result1->num_rows>0)
                        {
                            while($this->row = $this->result1->fetch_assoc())
                            {
                                $this->row['time'] = $this->obTime->TimeConversion($this->row['time']);

                                $this->row['identifier_message_number']=$this->login_id;
                                $this->row=array_merge($this->row,['name'=>$this->fetch['name']]);
                                $this->row=array_merge($this->row,['login_status'=>$this->fetch['login_status']]);
                                $this->row=array_merge($this->row,['start'=>$this->id]);
                                $this->row=array_merge($this->row,['username'=>$this->username]);
                                $this->array=array_merge($this->array,[$this->row]);
                            }
                            $this->array=array_merge($this->array,[['load'=>$this->add_load]]);
                            $this->array=array_merge($this->array,[1]);
                            return json_encode($this->array);
                        }
                        else
                        {
                            return json_encode(['identifier_message_number'=>$this->login_id,'name'=>$this->fetch['name'],'login_status'=>$this->fetch['login_status'],'new'=>0]);
                        }
                    }
                    else
                    {
                        die("Query Failed");
                    }

                        // var_dump($array);
                }

            }
            else{
                echo "Query Failed";
            }
        }
        else{
            header('Location:../login.php');
        }
        $this->connect->close();
    }
}
?>