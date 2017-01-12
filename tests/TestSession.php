<?php
namespace ChatApp\Tests;

use PHPUnit_Framework_TestCase;
use ChatApp\Session;

class TestSession
    extends
        PHPUnit_Framework_TestCase
{


    public function testPutValue()
    {
        $key = 'test';
        $value = 'test';
        Session::put($key, $value);
        return ['key' => $key, 'value' => $value];
    }

    /**
    * @depends testPutValue
    */
    public function testGet($array)
    {
        $value = Session::get($array['key']);
        $this->assertEquals(null, $value);
    }
}

