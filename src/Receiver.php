<?php

namespace ChatApp;
require_once (dirname(__DIR__).'/vendor/autoload.php');
use ChatApp\User;
use ChatApp\Conversation;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();


/**
*
*/
class Receiver
{
    protected $obUser;
    protected $conversation;
    protected $messages;

    public function __construct()
    {
        $this->obUser = new User();
        $this->conversation = new Conversation();
    }

    public function receiverLoad($msg, $para)
    {
        $msg = json_decode($msg);
        $id2 = $msg->userId;
        $this->messages = $this->obUser->userDetails($id2, $para);
        $username = $this->messages['username'];
        $name = $this->messages['name'];
        $id1 = $msg->details;
        $msg->details = bin2hex(convert_uuencode($msg->details));
        $msg = json_encode($msg);
        $this->messages = json_decode($this->conversation->conversationLoad($msg, $para));
        // $id1 = json_decode($msg)->details;
        for ($i = 1 ; $i < count($this->messages); $i++) {
            $this->messages[$i]->start = $id1;
        }
        $id2 = bin2hex(convert_uuencode($id2));

        $this->messages[0]->username = $username;
        $this->messages[0]->name = $name;
        $this->messages[0]->id = $id2;
        return json_encode($this->messages);
    }
}
