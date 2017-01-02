<?php

namespace ChatApp;
session_start();

class Session{

    public function __construct()
    {
        # code
    }

    public function put($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
        return (isset($_SESSION[$key]) ? $_SESSION[$key] : null);
    }

    public function forget($key){
        unset($_SESSION[$key]);
    }
}
