<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/database.php');
use ChatApp\Username;
use ChatApp\Time;
use ChatApp\Conversation;
/**
*
*/
class Receiver
{
    protected $name;
    protected $username;
    protected $id;
    protected $id2;
    protected $ob;
    protected $conversation;
    protected $messages;

    function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        session_write_close();
        $this->ob = new Username();
        $this->conversation = new Conversation($sessionId);
    }

    function ReceiverLoad($msg)
    {
        $this->id2 = $_SESSION['start'];
        $this->messages = $this->ob->UserName($this->id2);
        $this->username = $this->messages['username'];
        $this->name = $this->messages['name'];
        $this->messages = json_decode($this->conversation->ConversationLoad($msg));
        $this->id = json_decode($msg)->username;
        for ($i=0 ; $i < count($this->messages)-2; $i++) {
            $this->messages[$i]->start = $this->id;
            $this->messages[$i]->username = $this->username;
            $this->messages[$i]->name = $this->name;
            $this->messages[$i]->identifier_message_number = $this->id2;
        }
        return json_encode($this->messages);
    }
}
?>