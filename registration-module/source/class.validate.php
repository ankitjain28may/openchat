<?php
@session_start();
require_once 'database.php';
class validate
{
	private $connect;
	function __construct()
	{
		$this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
	}
	
	function validate_email_in_db($email)
	{
		$query="SELECT login_id FROM login WHERE email='$email'";
		if ($result=$this->connect->query($query)) 
		{
			if ($result->num_rows>0) {
				return 1;
			}
			else
				return 0;
		}
	}

	function validate_username_in_db($username)
	{
		$query="SELECT login_id FROM login WHERE username='$username'";
		if ($result=$this->connect->query($query)) {
			if ($result->num_rows>0) {
				return 1;
			}
			else
				return 0;
			
		}
	}
}