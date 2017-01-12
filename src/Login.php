<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


class Login
{

	protected $flag;
	protected $error;
	protected $connect;

	public function __construct()
	{
		$this->flag = 0;
		$this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
		$this->error = array();
	}

	public function authLogin($data)
	{
		$data = $this->emptyValue($data);

		$login = $data["login"];
		$password = $data["passLogin"];

		if (preg_match("/^.+[@]{1}.+$/", $login))
		{
			if(filter_var($login, FILTER_VALIDATE_EMAIL) == false)
			{
			$this->onError("login", " *Enter correct Email address");
			}
		}

		if($this->flag == 0)
		{
			$password = md5($password);
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
						$this->onError("passLogin", " *Invalid password");
						return json_encode($this->error);
					}
					return json_encode(["Error" => "You are not registered, ".$this->connect->error ]);
				}
				$this->onError("login", " *Invalid username or email");
				return json_encode($this->error);
			}
			return json_encode(["Error" => "You are not registered, ".$this->connect->error ]);
		}
		else
		{
			return json_encode($this->error);
		}
	}

	public function onError($key, $value)
	{
		$this->flag = 1;
		$this->error = array_merge($this->error, [["key" => $key, "value" => $value]]);
	}

	public function emptyValue($data)
	{
		$errorCode = array(
			"login" => " *Enter the login field",
			"passLogin" => " *Enter the password"
		);

		foreach ($data as $key => $value) {
			$data[$key] = trim($data[$key]);
			$value = trim($value);
			if(empty($value))
			{
				$this->onError($key, $errorCode[$key]);
			}
		}
		return $data;
	}

}

