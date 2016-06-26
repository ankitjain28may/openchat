<?php
@session_start();
require_once 'database.php';
class login
{

	private $login;
	private $password;
	private $key;
	private $array_error;

	function __construct()
	{
		$_SESSION['password']='';
		$_SESSION['login']='';
		$this->key=0;
		$this->array_error=array();
	}

	function _login($login,$password)
	{
		$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$this->login=trim($login);
		$this->password=trim($password);

		if(empty($this->login)) 
		{
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["login"=>"Enter the login field"]);
		}
		elseif (ereg("^[@]{1}$",$this->login))
		{	
			if(filter_var($this->email,FILTER_VALIDATE_EMAIL)== false) 
			{
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["login"=>"Enter correct Email address"]);
			}
		}	
		if(empty($this->password)) {
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["password"=>"Enter the password"]);
		}
		else
		{
			$pass=md5($this->password);
		}

		if($this->key==0)
		{
			$query="SELECT * FROM login WHERE email='$this->login' or username='$this->login'";
			if ($result=$connect->query($query)) 
			{
				if ($result->num_rows>0) 
				{
					$row=$result->fetch_assoc();
					$login_id=$row['login_id'];
					$query="SELECT id FROM register WHERE id='$login_id' and password='$pass'";
					if($result=$connect->query($query))
					{
						if ($result->num_rows>0)
						{
							$_SESSION['start']=$login_id;
							return json_encode(["location"=>"http://localhost/openchat/account.php"]);
						}
						else
						{
							$this->array_error=array_merge($this->array_error,["password"=>"Invalid password"]);
							return json_encode($this->array_error);
						}
					}
				}
				else
				{
					$this->array_error=array_merge($this->array_error,["login"=>"Invalid username or email"]);
					return json_encode($this->array_error);
				}
			}	
			
		}
		else
		{
			return json_encode($this->array_error);
		}
	}
}


?>