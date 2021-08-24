<?php

namespace App\Modules\Chat\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Chat\Models\Message;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;


class Message extends Model {

    protected $fillable = ['sender', 'receiver', 'message'];

    protected static function booted()
    {   
        static::created(function ($message) {
            event(new \App\Events\IndividualMessage($message, authUser(), $message->receiverUser, $message->sender));   
            event(new \App\Events\IndividualMessage($message, authUser(), $message->receiverUser, $message->receiver));   
            event(new \App\Events\MessageHistory($message, authUser()));     
        });
    }

    public function senderUser(){
        return $this->belongsTo(User::class, 'sender');
    }

    public function receiverUser(){
        return $this->belongsTo(User::class, 'receiver');
    }

    public static function createMessage($data){
            return static::create($data);
    }

    public static function getLatesMessages($user_id){
        $sql = " select m.message , m.time as created_at, 
                u.name as receiver_name,
                u.profile_photo_path,
                m.is_read,
                m.msg_direction,
                m.receiver receiver_id, 
                m.sender sender_id, 
                'individual' as message_type
                from message_history m 
                join users u on u.id = m.receiver 
                where   m.sender = :id
            union
                select m.message , 
                m.time as created_at,
                g.name as receiver_name,
                g.profile_photo_path,
                m.is_read,
                m.msg_direction, 
                g.id as receiver_id, 
                m.sender sender_id, 
                'group' as message_type
                from group_message_history m              
                join groups g on g.id = m.group_id 
                where   m.sender = :id2
            order by created_at desc
            ";
        return collect(DB::select($sql, 
            ['id' => $user_id,  'id2' => $user_id, 
            ]
        ));
    }

    public static function individualChatMessage($sender_id, $receiver_id, $pageLimit=10, $request = []){
        $pageLimit=(!empty($request['pagelimit']))?$request['pagelimit']:$pageLimit;
        if(!empty($request['page'])){
            $offset=((int)$request['page']-1)*$pageLimit;
            $page=$request['page'];
        }else{
            $offset=0;
            $page=1;
        } 

        $sqlCount = " select count(*) as total " ;
        $sqlRow = " select m.id, m.message , m.created_at, s.name as sender, s.profile_photo_path , s.id as sender_id, 'individual' as message_type ";
        $sql = " from messages m  
            join users s on s.id = m.sender 
            where ( sender= :sender_id and receiver = :receiver_id)
            or (sender= :receiver_id2 and receiver = :sender_id2)             
            ";
        $orderSql= " order by m.id desc ";

        $limitSql=" limit ".$offset.','.$pageLimit ;
        // return collect(DB::select($sql, ['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'sender_id2' => $sender_id, 'receiver_id2' => $receiver_id]));
        $binding_array = ['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'sender_id2' => $sender_id, 'receiver_id2' => $receiver_id];
        $sqlCount= \DB::select($sqlCount.$sql, $binding_array)[0]->total;       
        $sqlRow.=$sql.$orderSql.$limitSql;    
        $sqlRow = \DB::select($sqlRow, $binding_array);
        $a= new Paginator($sqlRow, $sqlCount, $pageLimit, $page, ['path'=>route('chat.user.message', ['ajax_load'=>'true'])]);    
        return $a;
    }

    public static function groupChatMessage($group_id, $pageLimit=10, $request = []){

        $pageLimit=(!empty($request['pagelimit']))?$request['pagelimit']:$pageLimit;
        if(!empty($request['page'])){
            $offset=((int)$request['page']-1)*$pageLimit;
            $page=$request['page'];
        }else{
            $offset=0;
            $page=1;
        } 

        $sqlCount = " select count(*) as total " ;
        $sqlRow = " select m.id, m.message , m.created_at, u.name as sender,u.profile_photo_path, m.sender as sender_id, 'group' as message_type ";
        $sql = " from group_messages m  
            join groups g on g.id = m.group_id 
            join users u on u.id =  m.sender
            where group_id = :group_id         
            ";
        $orderSql= " order by m.id desc ";

        $limitSql=" limit ".$offset.','.$pageLimit ;
        // return collect(DB::select($sql, ['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'sender_id2' => $sender_id, 'receiver_id2' => $receiver_id]));
        $binding_array = ['group_id' => $group_id];
        $sqlCount= \DB::select($sqlCount.$sql, $binding_array)[0]->total;       
        $sqlRow.=$sql.$orderSql.$limitSql;    
        $sqlRow = \DB::select($sqlRow, $binding_array);
        $a= new Paginator($sqlRow, $sqlCount, $pageLimit, $page, ['path'=>route('chat.user.message', ['ajax_load'=>'true'])]);    
        return $a;
    }

}
