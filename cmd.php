<?php

require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use ChatApp\Chat;

$port = 8080;
$server = IoServer::factory(new Chat(), $port);

try {
    $server->run();
    echo "Websocket server running on port ${$port} ...";
} catch (\Exception $e) {
    echo sprintf(
        "Error trying to start Websocket server. Err: %s",
        $e->getMessage()
    );
}
