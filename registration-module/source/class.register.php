<?php
@session_start();
require_once 'database.php';
require_once 'class.validate.php';

class register
{
	private $array_error;
	private $name;
	private $email;
	private $username;
	private $password;
	private $mob;
	private $key;
	private $ob;
	private $connect;

	function __construct()
	{
		$this->array_error=array();
		$this->key=0;
		$this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->ob=new validate();
	

		$query="CREATE TABLE IF NOT EXISTS register (
			id int primary key auto_increment unique not null,
			email varchar(255) unique not null,
			username varchar(255) unique not null,
			password varchar(255) not null
			) ENGINE=INNODB;";

		if (!$this->connect->query($query)) {
			echo "Table is not created || Query failed";
		}

			$query="CREATE TABLE IF NOT EXISTS login (
			login_id int primary key not null,
			name varchar(255) not null,
			email varchar(255) unique not null,
			username varchar(255) unique not null,
			mobile varchar(255) not null,
			FOREIGN KEY (login_id) REFERENCES register(id)
			) ENGINE=INNODB;";
		
		if (!$this->connect->query($query)) {
			echo "Table is not created || Query failed";
		}

		$query="CREATE TABLE IF NOT EXISTS profile (
		login_id int primary key not null unique,
		status text,
		education text,
		gender varchar(10)
		) ENGINE=INNODB;";
		
		if (!$this->connect->query($query)) {
			echo "Table is not created || Query failed";
		}
		
	}

	function _register($name,$email,$username,$password,$mob)
	{
		$this->name=trim($name);
		$this->email=trim($email);
		$this->username=trim($username);
		$this->password=trim($password);
		$this->mob=trim($mob);
		if (empty($this->name)) {
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["name"=>"Enter the name"]);
		}

		if(empty($this->email)) {
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["email"=>"Enter the email address"]);
		}
		elseif(filter_var($this->email,FILTER_VALIDATE_EMAIL)== false) {
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["email"=>"Enter correct Email address"]);
		}
		else
		{
			if($this->ob->validate_email_in_db($this->email))
			{
				$this->key=1;
				$this->array_error=array_merge($this->array_error,["email"=>"Email is already registered"]);
			}
		}

		if(empty($this->username)) {
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["username"=>"Enter the username"]);
		}
		else
		{
			if($this->ob->validate_username_in_db($this->username))
			{
				$this->key=1;
				$this->array_error=array_merge($this->array_error,["username"=>"Username is already registered"]);
			}
		}

		if(empty($this->password)) {
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["password"=>"Enter the password"]);
		}

		if(empty($this->mob)) {
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["mob"=>"Enter the Mobile Number"]);
		}
		elseif (!ereg("^[0-9]{10}$",$this->mob)) {
			$this->key=1;
			$this->array_error=array_merge($this->array_error,["mob"=>"Enter correct Mobile Number"]);
		}

		if($this->key==1)
		{
			return json_encode($this->array_error);
		}
		else
		{
			$this->key=0;
			$pass=md5($this->password);
			$query="INSERT INTO register VALUES(null,'$this->email','$this->username','$pass')";
			if(!$this->connect->query($query)) {
				$this->key=1;
				echo "You are not registered || Error in registration2";
			}
			else
			{
				$query="SELECT id FROM register WHERE email='$this->email'";
				if($result=$this->connect->query($query)) {
					$row=$result->fetch_assoc();
					$id=$row['id'];
				
					$query="INSERT INTO login VALUES('$id','$this->name','$this->email','$this->username','$this->mob')";
					if(!$this->connect->query($query)) {
						$this->key=1;
						echo "You are not registered || Error in registration1";
					}
					$join="Joined OpenChat";
					$query="INSERT INTO profile VALUES('$id','$join','$join','')";
					if(!$this->connect->query($query)) {
						$this->key=1;
						echo "You are not updated || Error in profile";
					}
				}
			}	
		}
		if ($this->key==0) {
			$_SESSION['start']=$id;
			return json_encode(["location"=>"http://localhost/openchat/account.php"]);
		}
		else
		{
			return json_encode($this->array_error);
		}
	}	
}
?>