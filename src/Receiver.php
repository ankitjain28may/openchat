<?php
/**
 * Receiver Class Doc Comment
 *
 * PHP version 5
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
namespace ChatApp;

require_once dirname(__DIR__).'/vendor/autoload.php';
use ChatApp\User;
use ChatApp\Conversation;
use mysqli;
use Dotenv\Dotenv;
$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();

/**
 * Send message to other user
 *
 * @category PHP
 * @package  OpenChat
 * @author   Ankit Jain <ankitjain28may77@gmail.com>
 * @license  The MIT License (MIT)
 * @link     https://github.com/ankitjain28may/openchat
 */
class Receiver
{
    /*
    |--------------------------------------------------------------------------
    | Receiver Class
    |--------------------------------------------------------------------------
    |
    | Send message to other user.
    |
    */

    protected $obUser;
    protected $conversation;
    protected $messages;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->obUser = new User();
        $this->conversation = new Conversation();
    }

    /**
     * Swaping value of conversation class to modify them for receiver
     *
     * @param string  $msg  To store message
     * @param boolean $para To store True/False
     *
     * @return string
     */
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
        $this->messages = json_decode(
            $this->conversation->conversationLoad($msg, $para)
        );
        // $id1 = json_decode($msg)->details;
        for ($i = 1; $i < count($this->messages); $i++) {
            $this->messages[$i]->start = $id1;
        }
        $id2 = bin2hex(convert_uuencode($id2));

        $this->messages[0]->username = $username;
        $this->messages[0]->name = $name;
        $this->messages[0]->id = $id2;
        return json_encode($this->messages);
    }
}
