<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::where('role','user')->get();
        return view('admin.users.index',compact('users'));
    }

    public function userAction(Request $request){
        $user = User::find($request->userId);
        if(!$user){
            return back()->with('error','Something went wrong!');
        }
        if($request->action == 'removeId'){
            if($user->device_id == null){
                return back()->with('error',"User Does't have Device");
            }
            $user->device_id = null;
            $user->save();
            return back()->with('succes',"User Device Removed Successfully");
        }
        return back();
    }
}
