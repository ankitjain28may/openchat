<?php

namespace ChatApp\Tests;
use PHPUnit_Framework_TestCase;
use ChatApp\Register;
use ChatApp\Search;
use ChatApp\Session;

use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();
session_start();

class TestSearch
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
        $sessionId = session_id();
        return $sessionId;
    }

    /**
    * @depends test_authRegister
    *  Testing for the register with empty username
    */
    public function test_authRegister2($sessionId)
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
