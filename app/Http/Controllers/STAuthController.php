<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\STUser;
use App\Models\STAuth;
use Illuminate\Support\Str;
use DB;

class STAuthController extends Controller
{
    public function loginViaFaceID(Request $request, $user_id)
    {
        $user =  STUser::where('id',$user_id)->first();
        if(isset($user))
        {
            $data['user_id']=$user_id;
            $data['token']=Str::random(60);
            $st_auth=STAuth::where('user_id',$user_id)->first();
            if(isset($st_auth))
            {
                $st_auth->secret_key=$data['token'];
            }
            else{
                $st_auth = new STAuth();
                $st_auth->user_id=$user_id;
                $st_auth->secret_key=$data['token'];
            }
            $st_auth->save();
            return response()->json(["data"=>$data],200);
        }
        $data['msg']="User  not found!";
        return response()->json(["error"=>$data],404);
    }
    public function login(Request $request)
    {
        if(!$request->has('email') && !$request->has('password'))
        {
            $data['msg']="Email or password is missing!";
            return response()->json(["error"=>$data],400);
        }
        $data['email']=$request->input('email');
        $data['password']=$request->input('password');
        $user = STUser::where([
            'email'=>$data['email']
        ])->first();
        
        if(isset($user))
        {
            if(password_verify($data['password'],$user->password))
            {
                $datas['user_id']=$user->id;
                $datas['token']=Str::random(60);
                $st_auth=STAuth::where('user_id',$user->id)->first();
                if(isset($st_auth))
                {
                    $st_auth->secret_key=$datas['token'];
                }
                else{
                    $st_auth = new STAuth();
                    $st_auth->user_id=$datas['user_id'];
                    $st_auth->secret_key=$datas['token'];
                }
                $st_auth->save();
                return response()->json(['data'=>$datas],200);
            }
        }
        $dataError['msg']="Login error, please check your email or password.";
        return response()->json(['error'=>$dataError],404);
    }
    public function logout($user_id,$token)
    {
        $st_auth=STAuth::where('user_id',$user_id)->first();
        if(isset($st_auth))
        {
            $st_auth->secret_key="";
            $st_auth->save();
        }
    }
    public function update_password(Request $request)
    {
        $user_id = $request->input('user_id');
        $token= $request->input('token');
        if($this->login_checker($token))
        {
            $password=$request->input('password');
            $password=password_hash( $password, PASSWORD_DEFAULT, array() );
            DB::table('mdlub_user')->where('id','=',$user_id)
            ->update([
                'password'=>$password
            ]);
            $data['msg']="Password changed!";
            return response()->json([
                "data"=>$data
            ],201);
        }
        $error['msg']="Invalid token!";
        return response()->json([
            "error"=>$error
        ],401);
    }
    public function get_profile($user_id,$token)
    {
        if($this->login_checker($token))
        {
            $profile = STUser::where('id','=',$user_id)
                        ->select('id as user_id','username','firstname','lastname','email',
                                'phone1','address','city','picture')
                        ->first();
            if(isset($profile))
            {
                $profile->picture= "https://cawoy.co.uk/moodle/pluginfile.php/28/user/icon/boost/f1?rev=".$profile->picture;
                return response()->json([
                    "data"=>$profile
                ],200);
            }
            $error['msg']="User not found!";
            return response()->json([
                "error"=>$error
            ],404);
        }
        $error['msg']="Invalid token!";
        return response()->json([
            "error"=>$error
        ],401);
    }
    public function update_profile(Request $request)
    {
        $user_id=$request->input('user_id');
        $token=$request->input('token');
        if($this->login_checker($token))
        {
            $data['firstname']=$request->input('firstname');
            $data['lastname']=$request->input('lastname');
            $data['phone1']=$request->input('phone1');
            $data['address']=$request->input('address');
            $data['city']=$request->input('city');
            STUser::where('id','=',$user_id)
                        ->update([
                            'firstname'=>$data['firstname'],
                            'lastname'=>$data['lastname'],
                            'phone1'=>$data['phone1'],
                            'address'=>$data['address'],
                            'city'=>$data['city']
                        ]);
            $data=[];
            $data['msg']="Profile updated!";
            return response()->json([
                    "data"=>$data
                ],201);
        }
        $error['msg']="Invalid token!";
        return response()->json([
            "error"=>$error
        ],401);
    }
    public function update_profile_picture(Request $request)
    {

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
