<?php

require (__DIR__ . '/vendor/autoload.php');
use ChatApp\Session;

if(Session::get('start') != null)
{
    Session::forget('start');
    header('Location: index.php');
}
else
{
    echo "Please Login";
}
