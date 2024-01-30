<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('access_token', ['*'], now()->addDay())->plainTextToken;
            return response()->json([
                'message' => 'ورود موفقیت آمیز!',
                'token' => $token
            ]);
        } else {
            return response()->json([
                'message' => 'ایمیل یا رمز عبور اشتباه است!'
            ], 400);
        }
    }
}
