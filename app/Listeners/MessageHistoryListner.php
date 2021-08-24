<?php

namespace App\Listeners;

use App\Events\MessageHistory as EventMessageHistory;
use App\Modules\Chat\Models\MessageHistory;

class MessageHistoryListner
{
    public $message;
    public $authUser;   
    
    public function handle(EventMessageHistory $event)
    {
        $this->message = $event->message;
        $this->authUser = $event->authUser;

        if( $this->authUser->id == $this->message->sender ){
            
            MessageHistory::updateOrCreate( 
                [  'sender' => $this->message->sender
                ],
                [  
                    'receiver' => $this->message->receiver,
                    'message' => $this->message->message,
                    'msg_direction' => 'out',
                    'time' => $this->message->created_at,
                    'is_read' => 0
                ]);            
        }

        if( $this->authUser->id != $this->message->receiver ){
            MessageHistory::updateOrCreate(
                [  
                    'sender' => $this->message->receiver
                ],
                [
                
                'receiver' => $this->message->sender,
                'message' => $this->message->message,
                'msg_direction' => 'in',
                'time' => $this->message->created_at,
                'is_read' => 0
            ]);            
        }
    }
}