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
  
class GroupMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
  
    public $data = [];
    protected $message; 
    protected $authUser; 
    protected $group; 
    protected $senderUser; 
    protected $channelUser; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $authUser, $group, $senderUser, $channelUser)
    {
        $this->message = $message;
        $this->authUser = $authUser;
        $this->group = $group;
        $this->senderUser = $senderUser;
        $this->channelUser = $channelUser;
    }
  
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('group.message.'.$this->channelUser);
    }
  
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastWith()
    {   
            
            $text =  '<div class="outgoing_msg">
                        <div class="sent_msg">
                            <p>'.$this->message->message.'</p>
                            <span class="time_date">'.chatDateFormat($this->message->created_at).'</span>
                        </div>
                    </div>';

            $summary_text = "<div class='chat_list' id='".keyGeneration([$this->message->group_id, 
                    'group',
                    'summary-id'])."'>
                    <div class='chat_people' 
                    onclick=\"getAjaxViewForMessage(".$this->message->group_id.",
                    'group')\">
                        <div class='chat_img'> <img src='".userImage($this->group)."'> </div>
                        <div class='chat_ib'>
                            <h5>{$this->group->name} <span class='chat_date'>".dateInText($this->message->created_at)."</span></h5>
                            <p>You: ".\Str::limit($this->message->message, 27, '...')."</p>
                        </div>
                    </div>
                </div>";

            $element = $this->message->group_id.'-group-id';
            $summary_element = $this->message->group_id.'-group-summary-id';
            $message_direction = 'outgoing';
            
            $this->data['id-'.$this->message->sender] = [
                'text'=> $text,
                'element' => $element,
                'message_id' => 'message-'.$this->message->id,
                'message_direction' => $message_direction,
                'summary_text' => $summary_text,
                'summary_element' => $summary_element,
                'user' => $this->message->group_id       
            ];
          


            $text = '<div class="incoming_msg">
                <div class="incoming_msg_img"> <img src="'.userImage($this->authUser).'">
                </div>
                <div class="received_msg">
                    <div class="received_withd_msg">
                        <span>'.$this->senderUser->name.'</span>
                        <p>'.$this->message->message.'</p>
                        <span class="time_date">'.chatDateFormat($this->message->created_at).'</span>
                    </div>
                </div>
            </div>';

            $summary_text = "<div class='chat_list' id='".keyGeneration([$this->message->group_id, 
                    'group',
                    'summary-id'])."'>
                    <div class='chat_people' 
                    onclick=\"getAjaxViewForMessage(".$this->message->group_id.",
                    'group')\">
                        <div class='chat_img'> <img src='".userImage($this->group)."'> </div>
                        <div class='chat_ib'>
                            <h5>{$this->group->name} <span class='chat_date'>".dateInText($this->message->created_at)."</span></h5>
                            <p>".$this->senderUser->name." : ".\Str::limit($this->message->message, 27, '...')."</p>
                        </div>
                    </div>
                </div>";


            $element = $this->message->group_id.'-group-id';
            $summary_element = $this->message->group_id.'-group-summary-id';
            $message_direction = 'incoming';

            $this->data['id-receiver'] = [
                'text'=> $text,
                'element' => $element,
                'message_id' => 'message-'.$this->message->id,
                'message_direction' => $message_direction,
                'summary_text' => $summary_text,
                'summary_element' => $summary_element,
                'user' => $this->message->group_id       
    
            ];
    
        
        return $this->data;
    }
}