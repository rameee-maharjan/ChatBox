<?php

namespace App\Modules\UserGroups\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Chat\Models\Message;
use App\Models\User;
use DB;

class GroupMessage extends Model {

    protected $table = 'group_messages';

    protected $fillable = ['sender', 'group_id', 'message'];

    protected static function booted()
    {   
        static::created(function ($message) {
            $groupUsers = $message->group->users;
            foreach ($groupUsers as $key => $groupUser) {
                event(new \App\Events\GroupMessage($message, authUser(), $message->group, $message->senderUser, $groupUser->id));  
            }
            event(new \App\Events\GroupMessageHistory($message, authUser(), $message->group ));        
        });
    }

    public function senderUser(){
        return $this->belongsTo(User::class, 'sender');
    }

    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }

    public static function createMessage($data){
            return static::create($data);
    }

    public static function getLatesMessages($user_id){
        $sql = " select m.message , m.created_at, g.name as sender  
            from group_messages m  join 
            ( select max(id) as id from group_messages where  exists ( select 1 from group_users where user_id = :user_id ) group by group_id ) latest on
            m.id = latest.id 
            join groups g on g.id = m.group_id ";
        return collect(DB::select($sql, ['user_id' => $user_id ]));
    }

}
