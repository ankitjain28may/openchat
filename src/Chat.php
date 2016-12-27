<?php
namespace ChatApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use ChatApp\Models\Message;
use ChatApp\Reply;
// @session_start();

class Chat implements MessageComponentInterface {
    protected $clients;
    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        $sessionId = $from->WebSocket->request->getCookies()['PHPSESSID'];
        var_dump($sessionId);
        var_dump($from->resourceId);
        foreach ($this->clients as $client) {
            echo $client->resourceId;
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        // foreach($this->cli as $client) {
        //     echo $client->resourceId;
        //     if($client->resourceId == 66)
        //     {
        //         $client->send($msg);
        //     }
        // }
        print_r($_SESSION);
        $rep = new Reply($sessionId);
        $rep->replyTo($msg);
        // print_r($rep);
        // Message::create([
        //     'text' => $msg['text']
        // ]);
    }
    public function onClose(ConnectionInterface $conn) {

        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}