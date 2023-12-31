<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;

class AuthController extends Controller
{
    /**
     * User Registration
     */
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User Register Successfully.',
            'data' => $user
        ], 200);
    }

    /**
     * User Login
     */
    public function login(UserLoginRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('MyAuthApp')->accessToken;
            $user = auth()->user();
            $data = [
                'user' => $user,
                'token' => $token
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successfully.',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    /**
     * User Logout
     */
    public function logout(Request $request)
    {
        if (Auth::user()) {
            $request->user()->token()->revoke();
            return response()->json([
                'status' => 'success',
                'message' => 'You have been successfully logged out!!!'
            ], 200);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unable to Logout, something went wrong please try again!!!'
            ], 500);
        }
    }
}
