<?php

namespace ChatApp\Tests;
use PHPUnit_Framework_TestCase;
use ChatApp\Register;
use ChatApp\Login;
use ChatApp\Search;
use ChatApp\Compose;
use ChatApp\Reply;
use ChatApp\Session;

use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();
session_start();

class TestAll
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

    // Register User 1
    public function testAuthRegister()
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
    * @depends testAuthRegister
    *  Register User2
    */
    public function testAuthRegister2()
    {
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

        $userId = Session::get('start');
        return $userId;
    }

    /**
    * @depends testAuthRegister2
    *  Testing for the register with empty username
    */
    public function testCompose()
    {
        $expectedOutput = ['location' => 'http://127.0.0.1/openchat/views/account.php'];
        $outputEmail = $this->obLogin->authLogin(
            [
                "login" => 'test@testing.com',
                "passLogin" => 'testing'
            ]
        );
        $outputEmail = (array)json_decode($outputEmail);
        $this->assertEquals($expectedOutput, $outputEmail);


        $expectedOutput = [
            "Compose" => [
                "0" => (object)[
                    "login_id" => "2",
                    "name" => "Test2",
                    "email" => "test2@testing.com",
                    "username" => "test2",
                    "mobile" => "1234567890",
                    "login_status" => "0"
                ]
            ]
        ];


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
    * @depends testAuthRegister2
    *  Testing for Search Class
    */
    public function testSearch($userId)
    {
        $expectedOutput = ['location' => 'http://127.0.0.1/openchat/views/account.php'];
        $outputEmail = $this->obLogin->authLogin(
            [
                "login" => 'test@testing.com',
                "passLogin" => 'testing'
            ]
        );
        $outputEmail = (array)json_decode($outputEmail);
        $this->assertEquals($expectedOutput, $outputEmail);

        $sessionId = session_id();
        $search = new Search($sessionId);

        // Matched not found
        $output = $search->searchItem((object)["value" => "ank"]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Search" => "Not Found"], $output);

        // For suggestion matched but not in total messages
        $output = $search->searchItem((object)["value" => "T"]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Search" => "Not Found"], $output);

        // Not Found
        $output = $search->searchItem((object)["value" => ""]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Search" => "Not Found"], $output);

        // Query Failed
        $output = $search->searchItem((object)["value" => "'"]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Search" => "Not Found"], $output);

    }

    /**
    * @depends testAuthRegister2
    *  Testing for Reply Class
    */
    public function testReply($userId)
    {
        $msg = [
            "name" => $userId,
            "reply" => [
                0 => "Hello World"
            ]
        ];

        $msg = json_encode($msg);

        $expectedOutput = ['location' => 'http://127.0.0.1/openchat/views/account.php'];
        $outputEmail = $this->obLogin->authLogin(
            [
                "login" => 'test@testing.com',
                "passLogin" => 'testing'
            ]
        );
        $outputEmail = (array)json_decode($outputEmail);
        $this->assertEquals($expectedOutput, $outputEmail);


        $sessionId = session_id();
        $obReply = new Reply($sessionId);

        $output = $obReply->replyTo($msg);
        $this->assertEquals("Messages is sent", $output);

        $output = $obReply->replyTo(json_encode([]));
        $this->assertEquals("Failed", $output);

        $output = $obReply->replyTo(json_encode([
            "name" => -1,
            "reply" => [
                0 => "Hello World"
            ]
        ]));
        $this->assertEquals("Invalid Authentication", $output);

        $output = $obReply->replyTo(json_encode([
            "name" => $userId,
            "reply" => [
                0 => "Hello"
            ]
        ]));
        $this->assertEquals("Messages is sent", $output);

    }


    /**
    *   @depends testReply
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
        $query = "TRUNCATE `messages`";
        $this->assertTrue($connect->query($query));
        $query = "TRUNCATE `total_message`";
        $this->assertTrue($connect->query($query));
        $query = "TRUNCATE `register`";
        $this->assertTrue($connect->query($query));
    }

}
