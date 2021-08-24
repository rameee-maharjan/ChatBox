<?php

namespace App\Modules\UserGroups\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Modules\UserGroups\Models\Group;
use App\Modules\UserGroups\Models\GroupMessageHistory;

class UserGroupsController extends Controller
{
    
    public function index(){         
        $data['groups'] = Group::whereHas('users', function($q){
            $q->where('user_id', authUserId());
        })->withCount('users')->orderBy('created_at', 'desc')->get();
        return view('UserGroups::index', $data);
    }      

    public function store(Request $request){
        $data['name'] = $request->name;
        $data['created_by'] = authUserId();

        if($request->hasFile('profile_photo_path')){
            $upload_file = $request->file('profile_photo_path');
            $extension = strrchr($upload_file->getClientOriginalName(), '.');
            $new_file_name = generateCode();
            $attachment =  $upload_file->storeAs('photo', $new_file_name . $extension);
            $data['profile_photo_path'] = 'photo/'.$new_file_name . $extension;
        }

        $group = Group::create($data);

        if($request->users){
            $group->users()->sync($request->users);
            $group->users()->attach(authUserId());
        }
        return redirect()->route('users.groups.index');
    }

    public function edit(Request $request, $group){
        $data['group'] = Group::find($group);
        $data['user_groups'] = $data['group']->users()->pluck('user_id')->toArray();
        return view('UserGroups::edit', $data);
    }

    public function update(Request $request, $group){
        $group = Group::find($group);
        if(!authUser()->canEditDelete($group)){
            throw new \Exception("Permission denied", 1);                
        }
        $group->name = $request->name;

        if($request->hasFile('profile_photo_path')){
            $upload_file = $request->file('profile_photo_path');
            $extension = strrchr($upload_file->getClientOriginalName(), '.');
            $new_file_name = generateCode();
            $attachment =  $upload_file->storeAs('photo', $new_file_name . $extension);
            $group->profile_photo_path = 'photo/'.$new_file_name . $extension;
        }

        $group->save();
        
        if($request->users){
            $group->users()->sync($request->users);
            $group->users()->attach(authUserId());
        }
        return redirect()->route('users.groups.index');
    }

    public function delete(Request $request, $group){
        try{
            $group = Group::find($group);
            if(!authUser()->canEditDelete($group)){
                throw new \Exception("Permission denied", 1);                
            }
            $group->delete();
            return response()->json( ['message'=> 'success'], 200 );
        }catch(\Exception $e){
            return response()->json( ['message'=> 'Error deleting group'], 500 );
        }
    }

    public function leave(Request $request, $group){
        try{
            $group = Group::find($group);            
            GroupMessageHistory::where('sender', authUserId())->delete();
            $group->users()->detach(authUserId());
            return response()->json( ['message'=> 'success'], 200 );
        }catch(\Exception $e){
            return response()->json( ['message'=> 'Error leaving group'], 500 );
        }
    }
}