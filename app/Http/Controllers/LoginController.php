<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Modules\UserGroups\Models\Group;

class LoginController extends Controller{

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function authenticate(Request $request){
        if(authUser()){
            return redirect()->route('users.index');
        }
        if($request->isMethod('get')){
            return view('auth.login');
        }else{
        
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {    
                $user = User::find(authUserId());
                $user->online_status = 'online';
                $user->save();          
                event(new \App\Events\OnlineUser( authUserId(), true ));
                return redirect()->route('users.index');
            }
            // if failed login
            return redirect('login')
                ->withErrorMsg('Credentials not matched');
        }
    }

    public function register(Request $request)
    {   
        if(authUser()){            
            return redirect()->route('users.index'); 
        }

        if($request->isMethod('get')){
            return view('auth.register');
        }

        $this->validator( request()->all())->validate();
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        \Auth::guard()->login($user);
        $user->groups()->attach(Group::$master_group_id);

        $user->online_status = 'online';
        $user->save();          
        event(new \App\Events\OnlineUser( authUserId(), true ));

        return redirect()->route('users.index');
    }

    public function profile(Request $request)
    {   
        if($request->isMethod('get')){
            $data['user'] = authUser();
            return view('profile.show', $data);
        }

        $input = $request->all();
        $rule = [ 'name' => ['required', 'string', 'max:255']];
        if($request->password){
            $rule['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }
        Validator::make($input, $rule)->validate();

        $user = User::find(authUserId());
        if($request->hasFile('profile_photo_path')){
            $upload_file = $request->file('profile_photo_path');
            $extension = strrchr($upload_file->getClientOriginalName(), '.');
            $new_file_name = generateCode();
            $attachment =  $upload_file->storeAs('photo', $new_file_name . $extension);
            $user->profile_photo_path = 'photo/'.$new_file_name . $extension;
        }

        $user->name = $request->name;
        if($request->password)
            $user->password = Hash::make($request->password);
        $user->save();  
        return redirect()->route('users.index');
    }

}