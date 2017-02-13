<?php

namespace ChatApp\Tests;
use PHPUnit_Framework_TestCase;
use ChatApp\Register;
use ChatApp\Login;
use ChatApp\Search;
use ChatApp\Compose;
use ChatApp\Reply;
use ChatApp\Session;
use ChatApp\SideBar;
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
        Session::forget('start');
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
        Session::forget('start');

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
                    "login_id" => bin2hex(convert_uuencode(2)),
                    "name" => "Test2",
                    "email" => "test2@testing.com",
                    "username" => "test2",
                    "mobile" => "1234567890",
                    "login_status" => "0"
                ]
            ]
        ];


        $compose = new Compose();
        $userId = Session::get('start');

        // Matched not found
        $output = $compose->selectUser((object)["value" => "ank", "userId" => $userId]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Compose" => "Not Found"], $output);

        // For suggestion matched
        $output = $compose->selectUser((object)["value" => "t", "userId" => $userId]);
        $output = (array)json_decode($output);
        $this->assertEquals($expectedOutput, $output);

        // Not Found
        $output = $compose->selectUser((object)["value" => "", "userId" => $userId]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Compose" => "Not Found"], $output);

        // Query Failed
        $output = $compose->selectUser((object)["value" => "'", "userId" => $userId]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Compose" => "Query Failed"], $output);
        Session::forget('start');

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

        $userId = Session::get('start');
        $search = new Search();

        // Matched not found
        $output = $search->searchItem((object)["value" => "ank", "userId" => $userId]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Search" => "Not Found"], $output);

        // For suggestion matched but not in total messages
        $output = $search->searchItem((object)["value" => "T", "userId" => $userId]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Search" => "Not Found"], $output);

        // Not Found
        $output = $search->searchItem((object)["value" => "", "userId" => $userId]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Search" => "Not Found"], $output);

        // Query Failed
        $output = $search->searchItem((object)["value" => "'", "userId" => $userId]);
        $output = (array)json_decode($output);
        $this->assertEquals(["Search" => "Not Found"], $output);
        Session::forget('start');

    }



    /**
    * @depends testAuthRegister2
    *  Testing for Reply Class
    */
    public function testReply($userId)
    {
        $expectedOutput = ['location' => 'http://127.0.0.1/openchat/views/account.php'];
        $outputEmail = $this->obLogin->authLogin(
            [
                "login" => 'test',
                "passLogin" => 'testing'
            ]
        );
        $outputEmail = (array) json_decode($outputEmail);
        $this->assertEquals($expectedOutput, $outputEmail);
        $currentId = Session::get('start');
        Session::forget('start');

        $msg =(object) [
            "name" => $userId,
            "reply" => "Hello World",
            "userId" => $currentId
        ];

        $obReply = new Reply();
        $output = $obReply->replyTo($msg);
        $this->assertEquals("Messages is sent", $output);

        $msg =(object) [
            "name" => $currentId,
            "reply" => "Hello World",
            "userId" => $userId
        ];

        $obReply = new Reply();
        $output = $obReply->replyTo($msg);
        $this->assertEquals("Messages is sent", $output);

        $output = $obReply->replyTo([]);
        $this->assertEquals("Failed", $output);

        $output = $obReply->replyTo((object) [
            "name" => -1,
            "reply" => "Hello World",
            "userId" => $currentId
        ]);
        $this->assertEquals("Invalid Authentication", $output);

        $output = $obReply->replyTo((object) [
            "name" => $userId,
            "reply" => "Hello",
            "userId" => $currentId
        ]);
        $this->assertEquals("Messages is sent", $output);
    }



    /**
    * @depends testReply
    *  Testing for Search Class
    */
    public function testSearchWithTotalMessages()
    {
        $expectedOutput = ['location' => 'http://127.0.0.1/openchat/views/account.php'];
        $outputEmail = $this->obLogin->authLogin(
            [
                "login" => 'test',
                "passLogin" => 'testing'
            ]
        );
        $outputEmail = (array)json_decode($outputEmail);
        $this->assertEquals($expectedOutput, $outputEmail);

        $userId = Session::get('start');
        $search = new Search();


        // For suggestion matched but not in total messages
        $output = $search->searchItem((object)["value" => "T", "userId" => $userId]);
        $output = json_decode($output);
        $this->assertEquals("test2", $output->Search[0]->username);
        Session::forget('start');

    }

    /**
    *  Testing for Search Class
    */
    public function testSidebar()
    {
        $expectedOutput = ['location' => 'http://127.0.0.1/openchat/views/account.php'];
        $outputEmail = $this->obLogin->authLogin(
            [
                "login" => 'test',
                "passLogin" => 'testing'
            ]
        );
        $outputEmail = (array)json_decode($outputEmail);
        $this->assertEquals($expectedOutput, $outputEmail);

        $userId = Session::get('start');
        $sidebar = new SideBar();


        // For suggestion matched but not in total messages
        $output = $sidebar->loadSideBar($userId);
        $output = json_decode($output)[0];

        $this->assertEquals("test2", $output->username);
        $this->assertEquals("Test2", $output->name);
        $this->assertEquals(bin2hex(convert_uuencode(2)), $output->login_id);

        $sidebar = new SideBar();
        $output = $sidebar->loadSideBar('');
        $this->assertEquals("Invalid Authentication", $output);

        $output = $sidebar->loadSideBar('\\');
        $this->assertEquals("Query Failed", $output);

        $output = $sidebar->loadSideBar("100");
        $this->assertEquals("null", $output);

        $sidebar = new SideBar();
        $output = $sidebar->loadSideBar(2);
        $output = json_decode($output)[0];

        $this->assertEquals("test", $output->username);
        $this->assertEquals("Test", $output->name);
        $this->assertEquals(bin2hex(convert_uuencode(1)), $output->login_id);
        Session::forget('start');
    }


    /**
    *   @depends testSidebar
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
