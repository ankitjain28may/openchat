<?php
namespace ChatApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use ChatApp\Models\Message;
use ChatApp\Reply;
use ChatApp\Conversation;
use ChatApp\Receiver;
use ChatApp\SideBar;
use ChatApp\Search;
use ChatApp\Compose;
use ChatApp\Online;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $conn = $this->setID($conn);
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
        Online::setOnlineStatus($conn->userId);
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
        if($msg == 'OpenChat initiated..!')
        {
            $initial = (object) array();
            $initial->initial = json_decode($this->onSidebar($from->userId));

            if($initial->initial != null) {
                $initial->conversation = json_decode(
                    $this->onConversation(
                        json_encode([
                            "username" => $initial->initial[0]->login_id,
                            "load" => 10
                        ]), True, $sessionId
                    )
                );
            }
            $from->send(json_encode($initial));
        }
        elseif ($msg == 'Load Sidebar')
        {
            $sidebar = (object) array();
            $sidebar->sidebar = json_decode($this->onSidebar($from->userId));
            $from->send(json_encode($sidebar));
        }
        elseif (@json_decode($msg)->newConversation == 'Initiated')
        {
            $result = (object) array();
            $result->conversation = json_decode($this->onConversation($msg, False, $sessionId));
            $from->send(json_encode($result));
        }
        elseif (@json_decode($msg)->search == 'search')
        {
            $searchResult = $this->onSearch($msg, $sessionId);
            $from->send($searchResult);
        }
        elseif (@json_decode($msg)->Compose == 'Compose')
        {
            $composeResult = $this->onCompose($msg, $sessionId);
            $from->send($composeResult);
        }
        else
        {
            $this->onReply($msg, $sessionId);

            $msg = json_decode($msg);

            $receiveResult = (object)array();
            $sentResult = (object)array();
            foreach ($this->clients as $client)
            {
                if ($client->userId == $msg->name)
                {
                    $receiveResult->sidebar = json_decode($this->onSidebar($client->userId));

                    $receiveResult->reply = json_decode(
                        $this->onReceiver(
                            json_encode([
                                "username" => $client->userId,
                                "load" => 10
                            ]), True, $sessionId
                        )
                    );

                    $client->send(json_encode($receiveResult));
                }
                elseif($client == $from)
                {
                    $sentResult->sidebar = json_decode($this->onSidebar($client->userId));

                    $sentResult->conversation = json_decode(
                        $this->onConversation(
                            json_encode([
                                "username" => $msg->name,
                                "load" => 10
                            ]), True, $sessionId
                        )
                    );

                    $client->send(json_encode($sentResult));
                }
            }

        }
    }

    public function onSidebar($data)
    {
        $obSidebar = new Sidebar();
        return $obSidebar->loadSideBar($data);
    }

    public function onConversation($data, $para, $sessionId)
    {
        $obConversation = new Conversation($sessionId);
        return $obConversation->conversationLoad($data, $para);
    }

    public function onReceiver($data, $para, $sessionId)
    {
        $obReceiver = new Receiver($sessionId);
        return $obReceiver->receiverLoad($data, $para);
    }

    public function onSearch($data, $sessionId)
    {
        $obSearch = new Search($sessionId);
        return $obSearch->searchItem(json_decode($data));
    }

    public function onCompose($data, $sessionId)
    {
        $obCompose = new Compose($sessionId);
        return $obCompose->selectUser(json_decode($data));
    }

    public function onReply($data, $sessionId)
    {
        $obReply = new Reply($sessionId);
        $obReply->replyTo($data);
    }

    public function onClose(ConnectionInterface $conn) {
        Online::removeOnlineStatus($conn->userId);
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }


}