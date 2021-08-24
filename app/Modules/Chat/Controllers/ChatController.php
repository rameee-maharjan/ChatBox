<?php

namespace App\Modules\Chat\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use App\Modules\Chat\Models\Message;
use App\Modules\UserGroups\Models\GroupMessage;
use App\Modules\UserGroups\Models\Group;
use Illuminate\Http\Request;
use App\Models\User;
use App\Modules\UserGroups\Models\GroupMessageHistory;
use App\Modules\Chat\Models\MessageHistory;

class ChatController extends Controller
{
    
    public function index(){         
        $data['messages'] = Message::getLatesMessages(authUserId());
        // dd($data['messages']);
        return view('Chat::chat', $data);
    }      

    public function ajaxChat(Request $request){
        $data['user_id'] = $user_id = request()->user_id;
        $message_type = $data['message_type'] = request()->message_type;
        $data['messages'] = collect([]);

        if($message_type == 'individual'){
            $data['messages'] = Message::individualChatMessage(authUserId(), $user_id, 10, $request->all());
            $data['messages']->setCollection(
                collect(
                    collect( $data['messages']->items())->sortBy('id')
                )->values()
            );       
            $data['chat_user_object'] =   User::find($user_id);
        }elseif($message_type == 'group'){
            $data['messages'] = Message::groupChatMessage($user_id, 10, $request->all());
            $data['messages']->setCollection(
                collect(
                    collect( $data['messages']->items())->sortBy('id')
                )->values()
            );     
            $data['chat_user_object'] =   Group::find($user_id);
        }else{
            $data['messages'] = array();
        }

        if(!$data['messages']){
            $data['paginationLinks'] = null;
        }else
            $data['paginationLinks'] = (count($data['messages']->toArray()['data']))?paginationObject($request->except('page'),$data['messages']):null;
        // dd($data['messages']);
        if($request->ajax_load == 'true'){
            return view('Chat::ajaxLoadChat', $data);
        }

        return view('Chat::ajaxChat', $data);
    }

    public function store(){

        try{
            $data['message_type'] = $message_type = request()->message_type;
            $data['message'] = $message = request()->message;
            if($message &&  $message_type){
                if($message_type == 'individual'){
                    $data['sender'] = $sender =  authUserId();
                    $data['receiver'] = request()->user_id;
                    $response =Message::createMessage($data);
                    $text = '<div class="outgoing_msg" id="message-'.$response->id.'">
                        <div class="sent_msg">
                            <p>'.$response->message.'</p>
                            <span class="time_date">'.chatDateFormat($response->created_at).'</span>
                        </div>
                    </div>';
                }elseif($message_type == 'group'){
                    $data['sender'] =  authUserId();
                    $data['group_id'] =  request()->user_id;
                    $response = GroupMessage::createMessage($data);                   

                    $text = '<div class="outgoing_msg" id="message-'.$response->id.'">
                        <div class="sent_msg">
                            <p>'.$response->message.'</p>
                            <span class="time_date">'.chatDateFormat($response->created_at).'</span>
                        </div>
                    </div>';

                    // $text = '<div class="incoming_msg" id="message-'.$response->id.'">
                    //     <div class="incoming_msg_img"> <img src="https://pdddtetusdsdtorialsasdasdasdsadsrofile.png" alt="sunil">
                    //     </div>
                    //     <div class="received_msg">
                    //         <div class="received_withd_msg">
                    //             <span>'.$response->senderUser->name.'</span>
                    //             <p>'.$response->message.'</p>
                    //             <span class="time_date">'.chatDateFormat($response->created_at).'</span>
                    //         </div>
                    //     </div>
                    // </div>';
                }
            }
            return response()->json(['text' => $text], 200);
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);    
        }
    }

    public function readMessage(Request $request){
        $user_id = $request->user_id;
        $message_type = $request->message_type;
        if($message_type == 'individual'){
            MessageHistory::where('sender', authUserId())
                ->where('receiver', $user_id)
                ->update(['is_read' => '1']);
        }else{
            GroupMessageHistory::where('sender', authUserId())
                ->where('grouP_id', $user_id)
                ->update(['is_read' => '1']);
        }
    }
}