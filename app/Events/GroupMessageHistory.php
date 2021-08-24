<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class GroupMessageHistory
{
    use SerializesModels;

   
    public $message;
    public $authUser;
    public $group;
    
    public function __construct($message, $authUser, $group)
    {
        $this->message = $message;
        $this->authUser = $authUser;       
        $this->group = $group;       
        
    }
}