<?php
namespace ChatApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use ChatApp\Models\Message;
use ChatApp\Reply;
use ChatApp\Conversation;
use ChatApp\Receiver;
use ChatApp\SideBar;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $conversation;
    protected $sidebar;
    protected $result;
    protected $online;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->result = '';
        $this->online = 0;
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
        $rep->replyTo($msg);

        $msg = json_decode($msg);
        $msg->from = $from->userId;
        $msg->type = 'Chat';


        foreach ($this->clients as $client) {
            if ($client->userId == $msg->name) {
                $sidebar = new SideBar();
                @$this->result->sidebar = json_decode($sidebar->LoadSideBar($client->userId));

                $conv = new Receiver($sessionId);
                $this->conversation = $conv->ReceiverLoad(json_encode(["username" => $client->userId, "load" => 10]));
                $this->result->conversation = json_decode($this->conversation);
                $client->send(json_encode($this->result));
                $this->online = 1;
            }
            elseif($client == $from)
            {
                $sidebar = new SideBar();
                @$this->result->sidebar = json_decode($sidebar->LoadSideBar($client->userId));

                $conv = new Conversation($sessionId);
                $this->conversation = $conv->ConversationLoad(json_encode(["username" => $msg->name, "load" => 10]));
                $this->result->conversation = json_decode($this->conversation);
                $this->result->conversation[0]->login_status = $this->online;
                $client->send(json_encode($this->result));
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