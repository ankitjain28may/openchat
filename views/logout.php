<?php

require (dirname(__DIR__).'/vendor/autoload.php');
use ChatApp\Session;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


if (Session::get('start') != null) {
    Session::forget('start');
    header('Location:'.getenv('APP_URL')."/index.php");
} else {
    echo "Please Login";
}
