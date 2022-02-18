<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function stripe(Request $request)
    {
        $api_key = UserSetting::where('user_id',auth()->user()->id)
                            ->where('key','stripe_api_key')->first();

        $api_secret = UserSetting::where('user_id',auth()->user()->id)
                            ->where('key','stripe_api_secret')->first();

        if(count($request->all()) > 0){
           if(!$api_key){
               $api_key = UserSetting::create([
                   'user_id' => auth()->user()->id,
                   'key' => 'stripe_api_key',
                   'value' => $request->stripe_api_key
               ]);
           }else{
               $api_key->update(['value' => $request->stripe_api_key]);
           }

           if(!$api_secret){
                $api_secret = UserSetting::create([
                    'user_id' => auth()->user()->id,
                    'key' => 'stripe_api_secret',
                    'value' => $request->stripe_api_secret
                ]);
            }else{
                $api_secret->update(['value' => $request->stripe_api_secret]);
            }
        }

        return view('admin.users.setting.stripe',compact('api_key','api_secret'));
    }
}
