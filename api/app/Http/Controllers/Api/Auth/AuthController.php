<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @SWG\Post(
     *     path="/api/auth/register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Register a new user with name, email, and password.",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="name", type="string", example="John Doe"),
     *             @SWG\Property(property="email", type="string", example="john@example.com"),
     *             @SWG\Property(property="password", type="string", example="password"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="boolean", example=true),
     *             @SWG\Property(property="user", ref="#/definitions/User"),
     *             @SWG\Property(property="token", type="string", example="JWT_TOKEN"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Validation error or user already exists"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validate = validator($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "status"=> true
            ,
            "user" => $user
            ,
            "token" => $token
        ]);
    }
    /**
     * Login a user.
     *
     * @SWG\Post(
     *     path="/api/auth/login",
     *     tags={"Authentication"},
     *     summary="Login a user",
     *     description="Login a user with email and password.",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="email", type="string", example="john@example.com"),
     *             @SWG\Property(property="password", type="string", example="password"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="boolean", example=true),
     *             @SWG\Property(property="user", ref="#/definitions/User"),
     *             @SWG\Property(property="token", type="string", example="JWT_TOKEN"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Validation error or user not found"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validate = validator($request->all(),[
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);
        
        if($validate->fails()){
            return response()->json([
                "status" => false
                ,
                "errors" => $validate->errors()
            ]);
        }
       
        $status_auth = Auth::attempt($request->only('email', 'password'));

        if($status_auth){
            $user_Auth = User::find(Auth::id());
            $token = $user_Auth->createToken('auth_token')->plainTextToken;
            return response()->json([
                "status"=> true
                ,
                "user" => $user_Auth
                ,
                "token" => $token
            ]);
        }else{
            return response()->json([
                "status"=> false
                ,
                "message" => "user not existe"
            ]);
        }

    }
/**
     * Logout a user.
     *
     * @SWG\Post(
     *     path="/api/auth/logout",
     *     tags={"Authentication"},
     *     summary="Logout a user",
     *     description="Logout the currently authenticated user.",
     *     @SWG\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="boolean", example=true),
     *             @SWG\Property(property="message", type="string", example="User successfully logged out"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'statut' => true,
            'message' => "user successflly logged out"
        ]);
    }
}