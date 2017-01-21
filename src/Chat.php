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

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msg = (object) json_decode($msg);
        if($msg->type == 'OpenChat initiated..!')
        {
            $initial = (object) array();
            $initial->initial = json_decode($this->onSidebar($from->userId));

            if($initial->initial != null)
            {
                $initial->conversation = json_decode(
                    $this->onConversation(
                        json_encode([
                            "details" => $initial->initial[0]->login_id,
                            "load" => 20,
                            "userId" => $from->userId
                        ]), True
                    )
                );
            }
            $from->send(json_encode($initial));
        }
        elseif ($msg->type == 'Load Sidebar')
        {
            $sidebar = (object) array();
            $sidebar->sidebar = json_decode($this->onSidebar($from->userId));
            $from->send(json_encode($sidebar));
        }
        elseif ($msg->type == 'Initiated')
        {
            $msg->userId = $from->userId;
            $result = (object) array();
            $result->conversation = json_decode($this->onConversation(json_encode($msg), False));
            $from->send(json_encode($result));
        }
        elseif ($msg->type == 'Search')
        {
            $msg->userId = $from->userId;
            $searchResult = $this->onSearch($msg);
            $from->send($searchResult);
        }
        elseif ($msg->type == 'Compose')
        {
            $msg->userId = $from->userId;
            $composeResult = $this->onCompose($msg);
            $from->send($composeResult);
        }
        else
        {
            $msg->userId = $from->userId;
            $msg->name = convert_uudecode(hex2bin($msg->name));

            $getReturn = $this->onReply($msg);
            echo $getReturn;

            $receiveResult = (object) array();
            $sentResult = (object) array();
            foreach ($this->clients as $client)
            {
                if ($client->userId == $msg->name)
                {
                    $receiveResult->sidebar = json_decode($this->onSidebar($client->userId));

                    $receiveResult->reply = json_decode(
                        $this->onReceiver(
                            json_encode([
                                "details" => $client->userId,
                                "load" => 20,
                                "userId" => $from->userId
                            ]), True
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
                                "details" => bin2hex(convert_uuencode($msg->name)),
                                "load" => 20,
                                "userId" => $from->userId
                            ]), True
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

    public function onConversation($data, $para)
    {
        $obConversation = new Conversation();
        return $obConversation->conversationLoad($data, $para);
    }

    public function onReceiver($data, $para)
    {
        $obReceiver = new Receiver();
        return $obReceiver->receiverLoad($data, $para);
    }

    public function onSearch($data)
    {
        $obSearch = new Search();
        return $obSearch->searchItem($data);
    }

    public function onCompose($data)
    {
        $obCompose = new Compose();
        return $obCompose->selectUser($data);
    }

    public function onReply($data)
    {
        $obReply = new Reply();
        return $obReply->replyTo($data);
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