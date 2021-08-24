<?php
  
namespace App\Events;
  
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
  
class OnlineUser implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
   
    protected $authUser; 
    protected $status; 
    
    public function __construct($authUser, $status)
    {
        $this->authUser = $authUser;
        $this->status = $status;
    }
  
    public function broadcastOn()
    {
        return new PresenceChannel('online.user');
    }
  
    public function broadcastWith()
    {   
        $id = ($this->authUser)? $this->authUser : 'logout';
        $status = $this->status? 'online' : 'offline';
        return [
            'id' => $id ,
            'status' => $status
        ];
    }
}