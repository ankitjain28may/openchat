<?php
namespace ChatApp\Tests;

use PHPUnit_Framework_TestCase;
use ChatApp\Session;

class TestSession
    extends
        PHPUnit_Framework_TestCase
{

    protected $array;

    public function setUp()
    {
        $this->array['key'] = 'test';
        $this->array['value'] = 'test';
        Session::put($this->array['key'], $this->array['value']);
    }

    public function testGet()
    {
        $value = Session::get($this->array['key']);
        $this->assertEquals($this->array['value'], $value);
    }

    public function tearDown()
    {
        Session::forget($this->array['key']);
        $value = Session::get($this->array['key']);
        $this->assertEquals(null, $value);
    }
}

