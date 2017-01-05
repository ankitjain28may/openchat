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
	protected $key;
	protected $obValidate;
	protected $connect;

	public function __construct()
	{
		$this->error = array();
		$this->key = 0;
		$this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
		$this->obValidate = new Validate();

	}

	public function authRegister($name, $email, $username, $password, $mob)
	{
		$name = trim($name);
		$email = trim($email);
		$username = trim($username);
		$password = trim($password);
		$mob = trim($mob);
		$userId = '';

		if (empty($name)) {
			$this->onError(["name" => " *Enter the name"]);
		}

		if(empty($email)) {
			$this->onError(["email" => " *Enter the email address"]);
		}
		elseif(filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
			$this->onError(["email" => " *Enter correct Email address"]);
		}
		else
		{
			if($this->obValidate->validateEmailInDb($email) === 1)
			{

				$this->onError(["email" => " *Email is already registered"]);
			}
		}

		if(empty($username)) {
			$this->onError(["username" => " *Enter the username"]);
		}
		else
		{
			if($this->obValidate->validateUsernameInDb($username) === 1)
			{

				$this->onError(["username" => " *Username is already registered"]);
			}
		}

		if(empty($password)) {
			$this->onError(["password" => " *Enter the password"]);
		}

		if(empty($mob)) {
			$this->onError(["mob" => " *Enter the Mobile Number"]);
		}
		elseif (!preg_match("/^[0-9]{10}$/", $mob)) {
			$this->onError(["mob" => " *Enter correct Mobile Number"]);
		}

		if($this->key == 1)
		{
			return json_encode($this->error);
		}
		else
		{
			$this->key = 0;
			$pass = md5($password);
			$query = "INSERT INTO register VALUES(null, '$email', '$username', '$pass')";
			if(!$this->connect->query($query)) {
				$this->key = 1;
				echo "You are not registered || Error in registration1";
			}
			else
			{
				$query = "SELECT id FROM register WHERE email = '$email'";
				if($result = $this->connect->query($query)) {
					$row = $result->fetch_assoc();
					$userId = $row['id'];

					$query = "INSERT INTO login VALUES('$userId', '$name', '$email', '$username', '$mob', 0)";
					if(!$this->connect->query($query)) {
						$this->key = 1;
						echo "You are not registered || Error in registration2";
					}
					else
					{
						$query = "INSERT INTO profile VALUES('$userId', 'Joined OpenChat', 'Joined OpenChat', '')";
						if(!$this->connect->query($query)) {
							$this->key = 1;
							echo "You are not registered || Error in registration3";
						}
					}
				}
			}
		}
		if ($this->key == 0) {
			Session::put('start', $userId);
			return json_encode([
				"location" => getenv('APP_URL')."/views/account.php"
			]);
		}
		else
		{
			return json_encode($this->error);
		}
	}

	public function onError($value)
	{
		$this->key = 1;
		$this->error = array_merge($this->error, $value);
	}
}
