<?php
namespace ChatApp\Tests;

use PHPUnit_Framework_TestCase;
use ChatApp\Login;
use ChatApp\Register;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();

class TestUser
    extends
        PHPUnit_Framework_TestCase
{
    protected $obRegister;
    protected $obLogin;

    public function setUp()
    {
        $this->obRegister = new Register();
        $this->obLogin = new Login();
    }


    public function test_authRegister()
    {

        $output = $this->obRegister->authRegister(
            [
                "name" => 'Test',
                "email" => 'test@testing.com',
                "username" => 'test',
                "mob" => '1234567890',
                "passRegister" => 'testing'
            ]
        );
        $output = (array)json_decode($output);
        $this->assertEquals([
            'location' => 'http://127.0.0.1/openchat/views/account.php'
            ], $output);

    }

    /**
    * @depends test_authRegister
    */

    public function test_authLogin()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => 'test@testing.com',
                "passLogin" => 'testing'
            ]
        );
        $output = (array)json_decode($output);
        $this->assertEquals([
            'location' => 'http://127.0.0.1/openchat/views/account.php'
            ], $output);
    }

    /**
    *   @depends test_authLogin
    */
    public function test_EmptyDB()
    {
        $connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $query = "TRUNCATE `login`";
        $this->assertTrue($connect->query($query));
        $query = "TRUNCATE `profile`";
        $this->assertTrue($connect->query($query));
        $query = "TRUNCATE `register`";
        $this->assertTrue($connect->query($query));
    }
}

