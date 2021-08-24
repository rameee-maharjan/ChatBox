<?php

namespace App\Modules\UserGroups\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Chat\Models\Message;
use App\Models\User;
use DB;

class GroupMessageHistory extends Model {

    protected $table = 'group_message_history'; 
    
    protected $fillable = ['sender', 'group_id', 'message', 'msg_direction', 'time', 'is_read'];

    public static function unreadMessageCount($user_id){
        return static::where('sender', $user_id)->where('msg_direction', 'in')->where('is_read', '0')->count();
    }

    public static function unreadMessage($user_id){
        return static::where('sender', $user_id)->where('msg_direction', 'in')->where('is_read', '0')->get();
    }
}
