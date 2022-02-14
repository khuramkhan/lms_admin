<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Mockery\Matcher\Contains;

class HomeController extends Controller
{
    public function index(Request $request){

        if(auth()->user() && auth()->user()->role == 'admin')
        {
            return view('admin.dashboard');
        }
        return view('admin.login');
    }

    public function loginProcess(Request $request){

            $user = User::where('email',$request->email)->where('role','admin')->first();
            if(!$user){
                return back()->with('error','Invalid User');
            }

            if(!Hash::check($request->password,$user->password)){
                return back()->with('error','Invalid Password');
            }
            Auth::login($user);
            return view('admin.dashboard');
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect()->to('/');
    }

    public function aboutUs(Request $request){
        $data = $request->all();
        $aboutUs = SiteInfo::where('key','aboutUs')->first();
        if(count($data) > 0){
            if($aboutUs){
                $aboutUs->update($data);
            }else{
                $data['key'] = 'aboutUs';
                $aboutUs = SiteInfo::create($data);
            }
            return back()->with('success','AboutUs Content Updated Successfully!');
        }

        return view('admin.aboutUs.index',compact('aboutUs'));
    }

    public function faqs(Request $request){
        $data = $request->all();
        $faqs = SiteInfo::where('key','faqs')->first();
        if(count($data) > 0){
            if($faqs){
                $faqs->update($data);
            }else{
                $data['key'] = 'faqs';
                $faqs = SiteInfo::create($data);
            }
            return back()->with('success','AboutUs Content Updated Successfully!');
        }

        return view('admin.faqs.index',compact('faqs'));
    }

    public function contactUs(){
        $contactUs = ContactUs::all();
        return view('admin.contactUs.index',compact('contactUs'));
    }

    public function deleteContactUs(Request $request)
    {
        $contactUs = ContactUs::find($request->id);
        if($contactUs){
            $contactUs->delete();
            return ['success' => true];
        }
        return ['success' => false];
    }
}
