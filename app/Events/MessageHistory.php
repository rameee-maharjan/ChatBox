<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class MessageHistory
{
    use SerializesModels;

   
    public $message;
    public $authUser;
    
    public function __construct($message, $authUser)
    {
        $this->message = $message;
        $this->authUser = $authUser;       
        
    }
}