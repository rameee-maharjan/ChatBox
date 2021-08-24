<?php

namespace App\Modules\UserGroups\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Chat\Models\Message;
use App\Models\User;
use DB;

class Group extends Model {

    protected $fillable = ['name', 'created_by', 'profile_photo_path' ]; 

    public static  $master_group_id = 1;

    public function groupCreator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'group_users', 'group_id', 'user_id')->withTimestamps();
    }

    public static function allGroupUsersList($user){
        return ($user) ? User::where('id', '<>', $user->id)->orderBy('name')->get() : collect([]); 
    }
        
    public function isMasterGroup(){
        return ( $this->id == static::$master_group_id ) ? true : false;
    }

    public function canEditDelete($user){
        if($this->id == static::$master_group_id){
            return false;
        }
        return $this->created_by == $user->id ? true : false;
    }
    public function canLeave($user){
        if($this->id == static::$master_group_id){
            return false;
        }
        return true;
    }
}