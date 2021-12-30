<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\FuncCall;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,id',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string',
            'dob' => 'required|date',
            'city' => 'required|string',
            'country' => 'required|string',
            'phone' => 'required|string',
            'role' => 'required|in:admin,user'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $data = $request->all();
        $data['status'] = 'inactive';
        $data['otp'] =  mt_rand(100000,999999);
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        $token = $user->createToken('MyApp')->accessToken;

        //SEND OTP MAIL
        $template = view('admin.email.otp',['otp'=> $user->otp])->render();
        sendMail($user->email,'OTP EMAIL',$template);

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    public function confirmOtp(Request $request)
    {
        $message = '';
        $success = false;

        $validate = Validator::make($request->all(),[
            'user_id' => 'integer|required|exists:users,id',
            'otp' => 'integer|required'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $user = User::findorfail($request->user_id);
        if($user->otp == $request->otp){
            $user->status = 'active';
            $user->save();
            $message = 'User Active Successfully!';
            $success = true;
        }else{
            $message = 'Invalid OTP';
            $success = false;
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function login(Request $request){

        $message = '';
        $success = false;

        $validate = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $user = User::where('email',$request->email)->first();
        if($user){

            if($user->status == 'inactive'){

                $success = false;
                $message = 'Email is not verified';

            }elseif($user->status == 'active'){

                $verifyPassword = Hash::check($request->password,$user->password);

                if($verifyPassword){
                    Auth::login($user);
                    $token = $user->createToken('MyApp')->accessToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'Login Successfully!',
                        'token' => $token
                    ]);
                }else{
                    $success = false;
                    $message = 'Invalid Password';
                }

            }

        }else{
            $success = false;
            $message = 'Email not found';
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);

    }

    public function forgotPassword(Request $request)
    {
        $message = '';
        $success = false;

        $validate = Validator::make($request->all(),[
            'email' => 'string|required|email'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()
            ]);
        }

        $user = User::where('email',$request->email)->first();
        if($user){
            $code = Crypt::encrypt($user->id);
            // $decrypted = Crypt::decrypt($code);
            $link = url('/reset-password').'/'.$code;
            $template = view('admin.email.forgot-password',compact('link'));
            sendMail($user->email,'Forgot Password Email',$template);
            $success = true;
            $message = 'Forgot Password Email Send Successfully!';
        }else{
            $success = false;
            $message = 'Email Not Found';
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
