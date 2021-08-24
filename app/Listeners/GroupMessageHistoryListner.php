<?php

namespace App\Listeners;

use App\Events\GroupMessageHistory as EventGroupMessageHistory;
use App\Modules\UserGroups\Models\GroupMessageHistory;

class GroupMessageHistoryListner
{
    public $message;
    public $authUser;   
    public $group;   
    
    public function handle(EventGroupMessageHistory $event)
    {
        $this->message = $event->message;
        $this->authUser = $event->authUser;
        $this->group = $event->group;

        if( $this->authUser->id == $this->message->sender ){
            
            GroupMessageHistory::updateOrCreate( 
                [  'sender' => $this->message->sender
                ],
                [  
                    'group_id' => $this->message->group_id,
                    'message' => $this->message->message,
                    'msg_direction' => 'out',
                    'time' => $this->message->created_at,
                    'is_read' => 0
                ]);            
        }

        if( $this->authUser->id != $this->message->receiver ){

            $group_users = $this->group->users()->where('user_id', '<>', authUserId())->get();
            foreach ($group_users as $key => $group_user) {
                GroupMessageHistory::updateOrCreate(
                    [  
                        'sender' => $group_user->id
                    ],
                    [
                    
                    'group_id' => $this->message->group_id,
                    'message' => $this->message->message,
                    'msg_direction' => 'in',
                    'time' => $this->message->created_at,
                    'is_read' => 0
                ]);            
                
            }
        }
    }
}