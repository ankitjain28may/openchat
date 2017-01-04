<?php

namespace ChatApp;
require_once (dirname(__DIR__) . '/vendor/autoload.php');
use ChatApp\User;
use ChatApp\Session;
use ChatApp\Conversation;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
*
*/
class Receiver
{
    protected $ob;
    protected $conversation;
    protected $messages;

    public function __construct($sessionId)
    {
        session_id($sessionId);
        @session_start();
        session_write_close();
        $this->ob = new User();
        $this->conversation = new Conversation($sessionId);
    }

    public function receiverLoad($msg)
    {
        $id2 = Session::get('start');
        $this->messages = $this->ob->userDetails($id2, True);
        $username = $this->messages['username'];
        $name = $this->messages['name'];
        $this->messages = json_decode($this->conversation->conversationLoad($msg, True));
        $id = json_decode($msg)->username;
        for ($i=1 ; $i < count($this->messages); $i++) {
            $this->messages[$i]->start = $id;
        }
        $this->messages[0]->username = $username;
        $this->messages[0]->name = $name;
        $this->messages[0]->id = $id2;
        return json_encode($this->messages);
    }
}
