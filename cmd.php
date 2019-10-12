<?php

require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ChatApp\Chat;

$ws = new WsServer(new Chat());
$server = IoServer::factory(new HttpServer($ws), 8080);
$server->run();
