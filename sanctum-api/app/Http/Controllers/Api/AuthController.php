<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signup(Request $request){

    
    //uta model/post.php ma fillable or gurded bnayerw yeta aayeko
      $validateUser=Validator::make(
        $request->all(),
        [
            'name'=> 'required',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required',
        ]
        );

        if($validateUser->fails()){ // fails is the inbuit medthod of validator
            return response()->json([
                'status'=>false,
                'message'=>'validation Error',
                'errors'=> $validateUser->errors()->all()
            ],401);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,

        ]);
        return response()->json([
            'status'=>True,
            'message'=>'User created',
            'user'=> $user,
        ],200);
    }
    public function login(Request $request){
        $validateUser=Validator::make(
        $request->all(),
        [

            'email'=> 'required|email',
            'password'=> 'required',
        ]
        );
         if($validateUser->fails()){ // fails is the inbuit medthod of validator
            return response()->json([
                'status'=>false,
                'message'=>'Auuthentication fails',
                'errors'=> $validateUser->errors()->all()
            ],422);
        }

        if(Auth::attempt(['email'=> $request->email,'password'=>$request->password])){
            $authUser= Auth::user();
            return response()->json([
              'status'=>True,
              'message'=>'User Logged in successfully',
              'token'=> $authUser->createToken("API token")->plainTextToken,
              'token_type'=> 'bearer'
        ],200);
        }else{
             return response()->json([
                'status'=>false,
                'message'=>'Email and password does not matched',
             ],401);
            }
   

        }


    public function logout(Request $request){
        $user= $request->user();
        $user->tokens()->delete();
        return response()->json([
            'status'=> true,
            'user'=> $user,
            'message'=>'you logged out successfully',
        ],200);
    }
}
