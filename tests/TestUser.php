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
        $expectedOutput = ['location' => 'http://127.0.0.1/openchat/views/account.php'];
        $outputEmail = $this->obLogin->authLogin(
            [
                "login" => 'test@testing.com',
                "passLogin" => 'testing'
            ]
        );
        $outputEmail = (array)json_decode($outputEmail);
        $outputUsername = $this->obLogin->authLogin(
            [
                "login" => 'test',
                "passLogin" => 'testing'
            ]
        );
        $outputUsername = (array)json_decode($outputUsername);
        $this->assertEquals($expectedOutput, $outputEmail);
        $this->assertEquals($expectedOutput, $outputUsername);
    }

    /**
    * @depends test_authRegister
    */

    public function test_authLoginEmptyValues()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => '',
                "passLogin" => ''
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "login",
                "value" => " *Enter the login field"
            ],
            [
                "key" => "passLogin",
                "value" => " *Enter the password"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    */

    public function test_authLoginWrongEmail()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => 'email@-domain.com',
                "passLogin" => 'egfb'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "login",
                "value" => " *Enter correct Email address"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    */
    public function test_authLoginInvalidUsernameEmail()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => 'invalid',
                "passLogin" => 'invalid'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "login",
                "value" => " *Invalid username or email"
            ]
        ];

        $this->assertEquals($expectedOutput, $output);
    }

    /**
    * @depends test_authRegister
    */
    public function test_authLoginInvalidPassword()
    {
        $output = $this->obLogin->authLogin(
            [
                "login" => 'test',
                "passLogin" => 'invalid'
            ]
        );
        $output = (array)json_decode($output, True);
        $expectedOutput = [
            [
                "key" => "passLogin",
                "value" => " *Invalid password"
            ]
        ];
        $this->assertEquals($expectedOutput, $output);
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

