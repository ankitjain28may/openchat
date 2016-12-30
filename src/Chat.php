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

class Chat implements MessageComponentInterface {
    protected $clients;
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
        if($msg == 'OpenChat initiated..!')
        {
            @$initial->initial = json_decode($this->onSidebar($from->userId));

            @$initial->conversation = json_decode(
                $this->onConversation(
                    json_encode([
                        "username" => $initial->initial[0]->login_id,
                        "load" => 10
                    ]), True, $sessionId
                )
            );

            $initial->conversation[0]->login_status = $this->online;
            $from->send(json_encode($initial));
        }
        elseif ($msg == 'Load Sidebar')
        {
            @$initial->initial = json_decode($this->onSidebar($from->userId));
            $from->send(json_encode($initial));
        }
        elseif (@json_decode($msg)->newConversation == 'Initiated')
        {
            @$result->conversation = json_decode($this->onConversation($msg, False, $sessionId));
            $from->send(json_encode($result));
        }
        elseif (@json_decode($msg)->search == 'search')
        {
            $searchResult = $this->onSearch($msg, $sessionId);
            $from->send($searchResult);
        }
        else
        {
            $this->onReply($msg, $sessionId);

            $msg = json_decode($msg);
            // $msg->from = $from->userId;

            foreach ($this->clients as $client)
            {
                if ($client->userId == $msg->name)
                {
                    @$result->sidebar = json_decode($this->onSidebar($client->userId));

                    @$result->conversation = json_decode(
                        $this->onReceiver(
                            json_encode([
                                "username" => $client->userId,
                                "load" => 10
                            ]), True, $sessionId
                        )
                    );

                    $client->send(json_encode($result));
                    $this->online = 1;
                }
                elseif($client == $from)
                {
                    @$result->sidebar = json_decode($this->onSidebar($client->userId));

                    @$result->conversation = json_decode(
                        $this->onConversation(
                            json_encode([
                                "username" => $msg->name,
                                "load" => 10
                            ]), True, $sessionId
                        )
                    );

                    $result->conversation[0]->login_status = $this->online;
                    $client->send(json_encode($result));
                    $this->online = 0;
                }
            }

        }
    }

    public function onSidebar($data)
    {
        $obSidebar = new Sidebar();
        return $obSidebar->LoadSideBar($data);
    }

    public function onConversation($data, $para, $sessionId)
    {
        $obConversation = new Conversation($sessionId);
        return $obConversation->ConversationLoad($data, $para);
    }

    public function onReceiver($data, $para, $sessionId)
    {
        $obReceiver = new Receiver($sessionId);
        return $obReceiver->ReceiverLoad($data, True);
    }

    public function onSearch($data, $sessionId)
    {
        $obSearch = new Search($sessionId);
        return $obSearch->SearchItem(json_decode($data));
    }

    public function onReply($data, $sessionId)
    {
        $obReply = new Reply($sessionId);
        $obReply->replyTo($data);
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