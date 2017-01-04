<?php
namespace ChatApp;
require_once (dirname(__DIR__) . '/vendor/autoload.php');
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
* For Validating User Data whether he is registered or not
*/
class Validate
{
	protected $connect;

	public function __construct()
	{
		$this->connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
	}

	public function validateEmailInDb($email)
	{
		$query = "SELECT login_id FROM login WHERE email = '$email'";
		if ($result = $this->connect->query($query))
		{
			if ($result->num_rows > 0) {
				return 1;
			}
			else
				return 0;
		}
	}

	function validateUsernameInDb($username)
	{
		$query = "SELECT login_id FROM login WHERE username = '$username'";
		if ($result = $this->connect->query($query)) {
			if ($result->num_rows > 0) {
				return 1;
			}
			else
				return 0;
		}
	}
}
