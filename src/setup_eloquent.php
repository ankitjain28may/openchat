<?php
use \Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Events\Dispatcher;
use \Illuminate\Container\Container;

date_default_timezone_get("Europe/London");

$container = new Container;
$capsule = new Capsule;

$capsule->addConnection([
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'openchat',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        // 'strict' => false,
        // 'engine' => null,
    ]);

$capsule->setEventDispatcher(new Dispatcher(new $container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

?>