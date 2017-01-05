<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


class Login
{

	protected $key;
	protected $error;
	protected $connect;

	public function __construct()
	{
		$this->key = 0;
		$this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
		$this->error = array();
	}

	public function authLogin($login, $password)
	{

		$login = trim($login);
		$password = trim($password);

		if(empty($login))
		{
			$this->key = 1;
			$this->error = array_merge($this->error, ["login" => " *Enter the login field"]);
		}
		elseif (preg_match("/^[@]{1}$/", $login))
		{
			if(filter_var($login, FILTER_VALIDATE_EMAIL) == false)
			{
			$this->key = 1;
			$this->error = array_merge($this->error, ["login" => " *Enter correct Email address"]);
			}
		}
		if(empty($password)) {
			$this->key = 1;
			$this->error = array_merge($this->error, ["password" => " *Enter the password"]);
		}
		else
		{
			$password = md5($password);
		}

		if($this->key == 0)
		{
			$query = "SELECT * FROM login WHERE email = '$login' or username = '$login'";
			if ($result = $this->connect->query($query))
			{
				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$loginID = $row['login_id'];
					$query = "SELECT id FROM register WHERE id = '$loginID' and password = '$password'";
					if($result = $this->connect->query($query))
					{
						if ($result->num_rows > 0)
						{
							Session::put('start', $loginID);
							return json_encode([
								"location" => getenv('APP_URL')."/views/account.php"
							]);
						}
						else
						{
							$this->error = array_merge($this->error, ["password" => " *Invalid password"]);
							return json_encode($this->error);
						}
					}
				}
				else
				{
					$this->error = array_merge($this->error, ["login" => " *Invalid username or email"]);
					return json_encode($this->error);
				}
			}

		}
		else
		{
			return json_encode($this->error);
		}
		$this->connect->close();
	}
}
