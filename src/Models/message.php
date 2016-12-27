<?php

namespace ChatApp\Models;
/**
*
*/
use Illuminate\Database\Eloquent\Model as Model;

class Message extends Model
{
    protected $table='message';

    protected $fillable = ['identifier_message_number', 'message', 'sent_by', 'time'];
}

?>