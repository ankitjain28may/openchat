<?php
@session_start();
require_once 'database.php';
class login
{

	private $login;
	private $password;
	private $key;

	function __construct()
	{
		$_SESSION['password']='';
		$_SESSION['login']='';
		$this->key=0;
	}

	function _login($login,$password)
	{
		$connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$this->login=trim($login);
		$this->password=trim($password);

		if(empty($this->login)) 
		{
			$this->key=1;
			$_SESSION['login']="Enter the login field";
		}
		elseif (ereg("^[@]{1}$",$this->login))
		{	
			if(filter_var($this->email,FILTER_VALIDATE_EMAIL)== false) 
			{
			$this->key=1;
			$_SESSION['login']="Enter correct Email address";
			}
		}

		
		if(empty($this->password)) {
			$this->key=1;
			$_SESSION['password']="Enter the password";
		}
		else
		{
			$password=md5($this->password);
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
					$query="SELECT id FROM register WHERE id='$login_id' and password='$password'";
					$result=$connect->query($query);
					if ($result->num_rows>0)
					{
						$row=$result->fetch_assoc();
						$_SESSION['start']=$row['id'];
						header('Location: account.php');
					}
					else
					{
						$_SESSION['password']="Invalid Password";
					}
				}
				else
				{
					$_SESSION['login']="Invalid username or email";
				}
			}	
		}
		else
		{
			return "ERROR";
		}
	}
}





?>