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
  
class IndividualMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
  
    // public $data = ['asas'];
    protected $message; 
    protected $authUser; 
    protected $receiverUser; 
    protected $channelUser; 
    public $data;
  
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $authUser, $receiverUser, $channelUser)
    {
        $this->message = $message;
        $this->authUser = $authUser;
        $this->receiverUser = $receiverUser;
        $this->channelUser = $channelUser;
    }
  
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('individual.message.'.$this->channelUser);
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

            $summary_text = "<div class='chat_list' id='".keyGeneration([$this->message->receiver, 
                    'individual',
                    'summary-id'])."'>
                    <div class='chat_people' 
                    onclick=\"getAjaxViewForMessage(".$this->message->receiver.",
                    'individual')\">
                        <div class='chat_img'> <img src='".userImage($this->receiverUser)."'> </div>
                        <div class='chat_ib'>
                            <h5>{$this->receiverUser->name} <span class='chat_date'>".dateInText($this->message->created_at)."</span></h5>
                            <p>You: ".\Str::limit($this->message->message, 26, '...')."</p>
                        </div>
                    </div>
                </div>";

            $element = $this->message->receiver.'-individual-id';
            $summary_element = $this->message->receiver.'-individual-summary-id';
            $message_direction = 'outgoing';
            
            $this->data['id-'.$this->message->sender] = [
                'text'=> $text,
                'element' => $element,
                'message_id' => 'message-'.$this->message->id,
                'message_direction' => $message_direction,
                'summary_text' => $summary_text,
                'summary_element' => $summary_element,
                'user' => $this->message->receiver    
            ];
        


            $text = '<div class="incoming_msg">
                <div class="incoming_msg_img"> <img src="'.userImage($this->authUser).'">
                </div>
                <div class="received_msg">
                    <div class="received_withd_msg">
                        <p>'.$this->message->message.'</p>
                        <span class="time_date">'.chatDateFormat($this->message->created_at).'</span>
                    </div>
                </div>
            </div>';

            $summary_text = "<div class='chat_list' id='".keyGeneration([$this->message->sender, 
                    'individual',
                    'summary-id'])."'>
                    <div class='chat_people' 
                    onclick=\"getAjaxViewForMessage(".$this->message->sender.",
                    'individual')\">
                        <div class='chat_img'> <img src='".userImage($this->authUser)."'> </div>
                        <div class='chat_ib'>
                            <h5>{$this->authUser->name} <span class='chat_date'>".dateInText($this->message->created_at)."</span></h5>
                            <p ".\Str::limit($this->message->message, 26, '...')."</p>
                        </div>
                    </div>
                </div>";


            $element = $this->message->sender.'-individual-id';
            $summary_element = $this->message->sender.'-individual-summary-id';
            $message_direction = 'incoming';

            $this->data['id-'.$this->message->receiver] = [
                'text'=> $text,
                'element' => $element,
                'message_id' => 'message-'.$this->message->id,
                'message_direction' => $message_direction,
                'summary_text' => $summary_text,
                'summary_element' => $summary_element,
                'user' => $this->message->sender    
            ];
        
        return $this->data;
    }
    
}