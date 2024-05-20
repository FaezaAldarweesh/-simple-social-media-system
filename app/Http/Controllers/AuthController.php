<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\loginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\registerRequest;
use App\Http\Resources\registerResource;

class AuthController extends Controller
{
    use ApiResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
//===========================================================================================================================
    // This method authenticates a user with their email and password. 
    //When a user is successfully authenticated, the Auth facade attempt() method returns the JWT token. 
    //The generated token is retrieved and returned as JSON with the user object
    public function login(loginRequest $request)
    {
        $request->validated();
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json(['status' => 'error','message' => 'Unauthorized',], 401);
        }

       return $this->apiResponse(null,$token," login successfully",200);



    }
//===========================================================================================================================
    public function register(registerRequest $request){
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return $this->apiResponse(new registerResource($user),$token," register successfully",201);

    }
//===========================================================================================================================
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
//===========================================================================================================================
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}
