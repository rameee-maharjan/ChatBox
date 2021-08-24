<?php

use App\Models\User;
// use Carbon;

function authUserId(){
    return auth()->user() ? auth()->user()->id : '';
    return $user->id;
}

function authUser(){
    return auth()->user()?:'';
}

function dateInText($date , $textOnly= true){
    if($textOnly){
        return Carbon\Carbon::parse($date)->diffForHumans();
    }
    return $date. ' ( '.Carbon\Carbon::parse($date)->diffForHumans().' )';
}

function generateCode($length=10){
    $code=bin2hex(openssl_random_pseudo_bytes($length));
    return strtoupper($code);
} 

function chatDateFormat($date){
    $dateFormat = Carbon\Carbon::parse($date);
    if($dateFormat->isToday()){
        return $dateFormat->format('g:i a') ;
    }elseif($dateFormat->isYesterDay()){
        return $dateFormat->format('g:i a').' , Yesterday' ;
    }
    return Carbon\Carbon::parse($date)->format("M j,y,g:i a");
}

function keyGeneration($array_key_list){
    $key = '';
    if($array_key_list){
        $key = join('-', $array_key_list);
    }
    return $key;
}

function paginationParams($request,$object){
    return $object->appends($request)->links();
}

function paginationObject($request,$object){
    return $object->appends($request);
}

function userImage($user){
    if($user->profile_photo_path){
        return asset('storage/'.$user->profile_photo_path);
    }else
        return asset('user-profile.png');
}

function pathUserImage($path){
    if($path){
        return asset('storage/'.$path);
    }else
        return asset('user-profile.png');
}
