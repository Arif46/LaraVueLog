<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 422);
        }

        $reqData = request()->only('email', 'password');

        if(Auth::attempt($reqData)) {

           $user = Auth::user();
           $data['token_type'] = 'Bearer';
           $data['access_token'] = $user->createToken('UserName')->accessToken;
           $data['user'] = $user;
            // return response([
            //     'data' => $data
            // ],200);
            return response()->json($data,200);

        } else {
            return response([
                'loginFailed' => 'email or password invalid'
            ],401);
        }

    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|min:4',
            'phone' => 'required|min:11',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 422);
        }

        $reqData = request()->only('name', 'phone','email', 'password');
        $reqData['password'] = Hash::make($request->password);
        $user = User::create($reqData);
        // Auth::login($user);
        $data['token_type'] = 'Bearer';
        $data['access_token'] = $user->createToken('UserName')->accessToken;
        $data['user'] = $user;
        return response()->json($data,200);

    }

    public function logout() 
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'message' => 'User LogOut Success'
        ]);
    }
}
