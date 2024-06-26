<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginRegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // create a new user
        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // generate a plan text token
        $token = $this->createToken($user);


        // create the response array
        $response = [
            'message' => "register successfully",
            'data' => [
                'user' => $user->makeHidden(['created_at', 'updated_at']),
                'token' => $token,
            ]
        ];

        return response()->json($response);
    }

    public function login(LoginRequest $request)
    {
        // find the user
        $user = User::where('email', $request->email)->first();

        // check if user exists
        if (!$user) {
            return response()->json([
                'message' => "User does not exist or is not registered",
            ], 404);
        }

        // check if password is correct
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Password is incorrect',
            ], 401);
        }

        // generate a plan text token
        $token = $this->createToken($user);


        // create the response array
        $response = [
            'message' => "Login successfully",
            'data' => [
                'token' => $token,
                'user' => $user->makeHidden(['email_verified_at', 'created_at', 'updated_at']),
            ]
        ];

        return response()->json($response);
    }


    private function createToken(User $user): string
    {
        return $user->createToken($user->email)->plainTextToken;
    }
}
