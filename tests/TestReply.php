<?php

namespace ChatApp\Tests;
use PHPUnit_Framework_TestCase;
use ChatApp\Register;
use ChatApp\Search;
use ChatApp\Reply;
use ChatApp\Session;

use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();
session_start();

class TestReply
extends
    PHPUnit_Framework_TestCase
{

    protected $obRegister;
    protected $obCompose;
    protected $obReply;

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
        $userId = Session::get('start');
        return $userId;
    }

    /**
    * @depends test_authRegister
    *  Testing for the register with empty username
    */
    public function test_authRegister2($userId)
    {
        $msg = [
            "name" => $userId,
            "reply" => [
                0 => "Hello World"
            ]
        ];

        $msg = json_encode($msg);

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
        $search = new Reply($sessionId);

        $output = $search->replyTo($msg);
        $this->assertEquals("Messages is sent", $output);

        $output = $search->replyTo(json_encode([]));
        $this->assertEquals("Failed", $output);

        $output = $search->replyTo(json_encode([
            "name" => -1,
            "reply" => [
                0 => "Hello World"
            ]
        ]));
        $this->assertEquals("Invalid Authentication", $output);

        $output = $search->replyTo(json_encode([
            "name" => $userId,
            "reply" => [
                0 => "Hello"
            ]
        ]));
        $this->assertEquals("Messages is sent", $output);

        // For suggestion matched but not in total messages
        // $output = $search->replyTo((object)["value" => "T"]);
        // $output = (array)json_decode($output);
        // $this->assertEquals(["Search" => "Not Found"], $output);

        // // Not Found
        // $output = $search->replyTo((object)["value" => ""]);
        // $output = (array)json_decode($output);
        // $this->assertEquals(["Search" => "Not Found"], $output);

        // // Query Failed
        // $output = $search->replyTo((object)["value" => "'"]);
        // $output = (array)json_decode($output);
        // $this->assertEquals(["Search" => "Not Found"], $output);

    }

    /**
    *   @depends test_authRegister2
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
