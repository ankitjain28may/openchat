<?php

namespace ChatApp;

require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\Validate;
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


class Register
{
	protected $error;
	protected $flag;
	protected $obValidate;
	protected $connect;

	public function __construct()
	{
		$this->error = array();
		$this->flag = 0;
		$this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
		$this->obValidate = new Validate();

	}

	public function authRegister($data)
	{
		$data = $this->emptyValue($data);
		$name = $data["name"];
		$email = $data["email"];
		$username = $data["username"];
		$mob = $data["mob"];
		$password = $data["passRegister"];
		$userId = '';

		if(filter_var($email, FILTER_VALIDATE_EMAIL) == false)
		{
			$this->onError("email", " *Enter correct Email address");
		}
		else if($this->obValidate->validateEmailInDb($email) === 1)
		{
			$this->onError("email", " *Email is already registered");
		}

		if($this->obValidate->validateUsernameInDb($username) === 1)
		{
			$this->onError("username", " *Username is already registered");
		}

		if (!preg_match("/^[0-9]{10}$/", $data["mob"])) {
			$this->onError("mob", " *Enter correct Mobile Number");
		}

		if($this->flag == 1)
		{
			return json_encode($this->error);
		}

		$password = md5($password);

		$query = "INSERT INTO register VALUES(null, '$email', '$username', '$password')";
		if(!$this->connect->query($query))
		{
			return json_encode(["Error" => "You are not registered, ".$this->connect->error ]);
		}
		$query = "SELECT id FROM register WHERE email = '$email'";
		if($result = $this->connect->query($query))
		{
			$row = $result->fetch_assoc();
			$userId = $row['id'];
			$query = "INSERT INTO login VALUES('$userId', '$name', '$email', '$username', '$mob', 0)";

			if(!$this->connect->query($query))
			{
				return json_encode(["Error" => "You are not registered, ".$this->connect->error ]);
			}

			$query = "INSERT INTO profile VALUES('$userId', 'Joined OpenChat', 'Joined OpenChat', '')";
			if(!$this->connect->query($query))
			{
				return json_encode(["Error" => "You are not registered, ".$this->connect->error ]);
			}

			Session::put('start', $userId);
			return json_encode([
				"location" => getenv('APP_URL')."/views/account.php"
			]);
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
			"name" => " *Enter the name",
			"email" => " *Enter the email address",
			"username" => " *Enter the username",
			"passRegister" => " *Enter the password",
			"mob" => " *Enter the Mobile Number"
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
