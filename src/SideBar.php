<?php


namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');

/**
*
*/
class SideBar
{

    protected $id;
    protected $query;
    protected $result;
    protected $connect;
    protected $fetch;
    protected $row;
    protected $ln;
    protected $st;
    protected $result1;
    protected $array;
    protected $value;

    function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        session_write_close();
    }

    function LoadSideBar()
    {
        if(isset($_SESSION['start']))
        {
            $this->id=$_SESSION['start'];
            $this->query="SELECT * FROM total_message WHERE user1='$this->id' or user2='$this->id'  ORDER BY id DESC";
            // $query="SELECT * FROM total_message WHERE identifier like '%:$id' or '$id:%'";
            if($this->result=$this->connect->query($this->query))
            {
                if ($this->result->num_rows > 0)
                {
                    $this->array = array();
                    $this->ln=strlen($this->id);
                    while($this->row = $this->result->fetch_assoc())
                    {
                        $this->value=$this->row['identifier'];
                        $this->st=substr($this->value, 0,$this->ln);
                        if($this->st!=$this->id)
                        {
                            $this->query="SELECT username,name,login_status from login where login_id='$this->st'";
                            if($this->result1=$this->connect->query($this->query))
                            {
                                if($this->result1->num_rows>0)
                                {
                                    $this->fetch=$this->result1->fetch_assoc();
                                    if(substr($this->row['time'],4,11)==date("d M Y", time()+12600))
                                        $this->row['time']=substr($this->row['time'],16,5);
                                    else if(substr($this->row['time'],7,8)==date("M Y", time()+12600) && substr($this->row['time'], 4,2)-date("d")<7)
                                        $this->row['time']=substr($this->row['time'],0,3);
                                    else if(substr($this->row['time'],11,4)==date("Y", time()+12600))
                                        $this->row['time']=substr($this->row['time'],4,6);
                                    else
                                        $this->row['time']=substr($this->row['time'],4,11);
                                    $this->fetch=array_merge($this->fetch,['time'=>$this->row['time']]);
                                    $this->array=array_merge($this->array,[$this->fetch]);
                                }
                            }
                        }

                        else
                        {
                            $this->st=substr($this->value,$this->ln+1);
                            $this->query="SELECT username,name,login_status from login where login_id='$this->st'";
                            if($this->result1=$this->connect->query($this->query))
                            {
                                if($this->result1->num_rows>0)
                                {
                                    $this->fetch=$this->result1->fetch_assoc();
                                    if(substr($this->row['time'],4,11)==date("d M Y", time()+12600))
                                        $this->row['time']=substr($this->row['time'],16,5);
                                    else if(substr($this->row['time'],7,8)==date("M Y", time()+12600) && substr($this->row['time'], 4,2)-date("d")<7)
                                        $this->row['time']=substr($this->row['time'],0,3);
                                    else if(substr($this->row['time'],11,4)==date("Y", time()+12600))
                                        $this->row['time']=substr($this->row['time'],4,6);
                                    else
                                        $this->row['time']=substr($this->row['time'],4,11);
                                    $this->fetch=array_merge($this->fetch,['time'=>$this->row['time']]);
                                    $this->array=array_merge($this->array,[$this->fetch]);
                                }
                            }
                        }
                    }
                    $this->array=array_merge([],[$this->array]);
                    $this->array=array_merge($this->array,['type'=>'SideBar']);
                    return json_encode($this->array);
                }
                else
                {
                    return json_encode(null);
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