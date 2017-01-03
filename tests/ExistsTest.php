<?php
namespace ChatApp\Tests;

use PHPUnit_Framework_TestCase;
use ChatApp\Chat;
use ChatApp\Compose;
use ChatApp\Conversation;
use ChatApp\Online;
use ChatApp\Profile;
use ChatApp\Receiver;
use ChatApp\Reply;
use ChatApp\Search;
use ChatApp\Session;
use ChatApp\SideBar;
use ChatApp\Time;
use ChatApp\User;
use ChatApp\Login;
use ChatApp\Register;
use ChatApp\Validate;

class ExistsTest
    extends
        PHPUnit_Framework_TestCase
{


    public function ClassNameProvider()
    {
        return [
            [
                Chat::class,
            ],
            [
                Compose::class,
            ],
            [
                Conversation::class,
            ],
            [
                Login::class,
            ],
            [
                Online::class,
            ],
            [
                Profile::class,
            ],
            [
                Receiver::class,
            ],
            [
                Register::class,
            ],
            [
                Reply::class,
            ],
            [
                Search::class,
            ],
            [
                Session::class,
            ],
            [
                SideBar::class,
            ],
            [
                Time::class,
            ],
            [
                User::class,
            ],
            [
                Validate::class,
            ],
        ];
    }

    /**
    * @dataProvider ClassNameProvider
    */
    public function testClassExists($className)
    {
        $this->assertTrue(class_exists($className));
    }
}
