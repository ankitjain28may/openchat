<?php
namespace ChatApp\Tests;

use PHPUnit_Framework_TestCase;
use ChatApp\Profile;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();

class TestProfile
    extends
        PHPUnit_Framework_TestCase
{
    protected $userId;

    public function setUp()
    {
        $connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $this->userId = 1000;
        $query = "INSERT INTO profile VALUES('$this->userId', 'Joined OpenChat', 'Joined OpenChat', '')";
        $this->assertTrue($connect->query($query));
    }


    public function test_getProfile()
    {
        $output = Profile::getProfile($this->userId);
        $this->assertEquals([
            'login_id' => '1000',
            'status' => 'Joined OpenChat',
            'education' => 'Joined OpenChat',
            'gender' => ''
        ], $output);
    }

    public function tearDown()
    {
        $connect = mysqli_connect(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        $query = "DELETE from profile where login_id = '$this->userId'";
        $this->assertTrue($connect->query($query));
    }
}

