<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\STSettings;
use App\Models\STAuth;

class STSettingsController extends Controller
{
    /**
     * @params user_id , token
     * 
     * @return Settings Array if user is authenticated.
     */
    public function get_settings(Request $request,$user_id,$token)
    {
        /**
         * If user is authenticated then return settings.
         */
        if($this->login_checker($token))
        {
            $settings = STSettings::where('user_id',$user_id)->first();
            $data=[];
            if(isset($settings))
            {
                $data=json_decode($settings->settings);
            }
            else
            {
                $settings = new STSettings();
                $settings->user_id=$user_id;
                $screens=[];
                //Screen 1
                $screen['screen_id']=1;
                $screen['option']=1;
                $screens[]=$screen;
                //Screen 2
                 $screen['screen_id']=2;
                 $screen['option']=0;
                 $screens[]=$screen;
                //Screen 3
                $screen['screen_id']=3;
                $screen['option']=2;
                $screens[]=$screen;
                //Screen 4
                 $screen['screen_id']=4;
                 $screen['option']=3;
                 $screens[]=$screen;
                //Screen 5
                 $screen['screen_id']=5;
                 $screen['option']=0;
                 $screens[]=$screen;
                 //Screen 6
                 $screen['screen_id']=6;
                 $screen['option']=4;
                 $screens[]=$screen;
                //Screen 7
                 $screen['screen_id']=7;
                 $screen['option']=5;
                 $screens[]=$screen;
                 //Screen 8
                 $screen['screen_id']=8;
                 $screen['option']=6;
                 $screens[]=$screen;
                //Screen 9
                 $screen['screen_id']=9;
                 $screen['option']=7;
                 $screens[]=$screen;
                //Screen 10
                $screen['screen_id']=10;
                $screen['option']=8;
                $screens[]=$screen;
               //Screen 11
                $screen['screen_id']=11;
                $screen['option']=9;
                $screens[]=$screen;
                //Screen 12
                $screen['screen_id']=12;
                $screen['option']=10;
                $screens[]=$screen;
                $data['screens']=$screens;
                $data['selfie_gesture']=0;
                $data['logout_gesture']=0;
                $data= json_encode($data);
                $settings->settings=$data;
                $settings->save();
            }
            return response()->json([
                "data"=>$data
            ],200);
        }
        /**
         * If token is invalid, return error message.
         */
        $data['msg']="Token error!";
        return response()->json([
            "error"=>$data
        ],401);
    }
    /**
     * @params user_id, token, settings
     * @returns user_settings
     */
    public function set_settings(Request $request)
    {
        if(!$request->has('token') || !$request->has('user_id'))
        {
            $data['msg']="Token or user id missing!";
            return response()->json([
                "error"=>$data
            ],401);
        }
        $token = $request->input('token');
        $user_id = $request->input('user_id');
        /**
         * If user is authenticated then return settings.
         */
        if($this->login_checker($token))
        {
            /**
             * Update OR INSERT Settings to the database;
             */
            $data=[];
            $settings = STSettings::where('user_id',$user_id)->first();
            if(isset($settings))
            {
                $settings->settings=json_encode($request->input('settings'));
                $settings->save();
            }
            $data['msg']="Settings updated";
            return response()->json([
                "data"=>$data
            ],200);
        }
        $data['msg']="Token error!";
        return response()->json([
            "error"=>$data
        ],401);
    }
    /**
     * @params token
     * @returns boolean value (true or false)
     * @purpose Checks if user is authenticated or not
     */
    public function login_checker($token)
    {
        $st_auth = STAuth::where('secret_key',$token)->first();
        /**
         * If token is valid, return the settings array.
         */
        if(isset($st_auth))
        {
            return true;
        }
        return false;
    }
}
