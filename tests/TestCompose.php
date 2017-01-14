<?php

namespace ChatApp\Tests;
use PHPUnit_Framework_TestCase;
use ChatApp\Register;
use ChatApp\Compose;
use ChatApp\Session;

session_start();

class TestCompose
extends
    PHPUnit_Framework_TestCase
{

    protected $obRegister;
    protected $obCompose;


    public function setUp()
    {
        $this->obRegister = new Register();
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
    *  Testing for the register with empty username
    */
    public function test_authRegister2()
    {
        $expectedOutput = [
            "Compose" => [
                "0" => (object)[
                    "login_id" => "1",
                    "name" => "Test",
                    "email" => "test@testing.com",
                    "username" => "test",
                    "mobile" => "1234567890",
                    "login_status" => "0"
                ]
            ]
        ];

        $output = $this->obRegister->authRegister(
            [
                "name" => 'Test2',
                "email" => 'test2@testing.com',
                "username" => 'test2',
                "mob" => '1234567890',
                "passRegister" => 'testing'
            ]
        );
        $output = (array)json_decode($output);
        $this->assertEquals([
            'location' => 'http://127.0.0.1/openchat/views/account.php'
            ], $output);

        $sessionId = session_id();
        $compose = new Compose($sessionId);

        // Matched not found
        $output = $compose->selectUser((object)["value" => "ank"]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Compose" => "Not Found"], $output);

        // For suggestion matched
        $output = $compose->selectUser((object)["value" => "t"]);
        $output = (array)json_decode($output);
        $this->assertEquals($expectedOutput, $output);

        // Not Found
        $output = $compose->selectUser((object)["value" => ""]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Compose" => "Not Found"], $output);

        // Query Failed
        $output = $compose->selectUser((object)["value" => "'"]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Compose" => "Query Failed"], $output);

    }

    /**
    *   @depends test_authRegister
    *  Empty the DB
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
