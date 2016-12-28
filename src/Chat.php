<?php
namespace ChatApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use ChatApp\Models\Message;
use ChatApp\Reply;
use ChatApp\Conversation;
use ChatApp\SideBar;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $username;
    protected $conversation;
    protected $sidebar;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }
    public function onOpen(ConnectionInterface $conn) {
        $conn = $this->setID($conn);
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function setID($conn)
    {
        session_id($conn->WebSocket->request->getCookies()['PHPSESSID']);
        @session_start();
        $conn->userId = $_SESSION['start'];
        session_write_close();
        return $conn;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $sessionId = $from->WebSocket->request->getCookies()['PHPSESSID'];

        $rep = new Reply($sessionId);
        $this->username = $rep->replyTo($msg);

        $msg = json_decode($msg);
        $msg->from = $from->userId;
        $msg->type = 'Chat';

        $conv = new Conversation($sessionId);
        $this->conversation = $conv->ConversationLoad(json_encode(["username" => $this->username, "load" => 10]));

        foreach ($this->clients as $client) {
            if ($client->userId == $msg->name) {
                $client->send($this->conversation);
                $sidebar = new SideBar();
                $client->send($sidebar->LoadSideBar($client->userId));
            }
            elseif($client == $from)
            {
                $sidebar = new SideBar();
                $client->send($sidebar->LoadSideBar($client->userId));
                $client->send($this->conversation);
            }
        }
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