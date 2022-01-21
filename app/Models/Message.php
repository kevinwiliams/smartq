<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "message";

    public function sender() 
    {
    	return $this->hasOne('App\Models\User', 'id', 'sender_id');
    }

    public function receiver() 
    {
    	return $this->hasOne('App\Models\User', 'id', 'receiver_id');
    }
}
