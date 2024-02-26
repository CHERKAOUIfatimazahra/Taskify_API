<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = validator($request->all(),[
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email:unique,users,email'],
            'password' => 'required|min:6'
        ]);
        
        if($validate->fails()){
            return response()->json([
                "status" => false
                ,
                "errors" => $validate->errors()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken()->plainTextToken;

        return response()->json([
            "status"=> true
            ,
            "user" => $user
            ,
            "token" => $token
        ]);
    }

    

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

   

}
