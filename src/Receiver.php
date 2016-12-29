<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');
use ChatApp\User;
use ChatApp\Time;
use ChatApp\Conversation;
/**
*
*/
class Receiver
{
    protected $ob;
    protected $conversation;
    protected $messages;

    function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        session_write_close();
        $this->ob = new User();
        $this->conversation = new Conversation($sessionId);
    }

    function ReceiverLoad($msg)
    {
        $id2 = $_SESSION['start'];
        $this->messages = $this->ob->UserDetails($id2);
        $username = $this->messages['username'];
        $name = $this->messages['name'];
        $this->messages = json_decode($this->conversation->ConversationLoad($msg));
        $id = json_decode($msg)->username;
        for ($i=0 ; $i < count($this->messages)-2; $i++) {
            $this->messages[$i]->start = $id;
            $this->messages[$i]->username = $username;
            $this->messages[$i]->name = $name;
            $this->messages[$i]->identifier_message_number = $id2;
        }
        return json_encode($this->messages);
    }
}
?>