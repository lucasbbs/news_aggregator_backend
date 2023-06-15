<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (\Auth::attempt($credentials)) {
            $user = \Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful.',
                'user' => $user,
                'token' => $token,
            ]);
        }
        return response()->json([
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function logout(Request $request)
    {
        // $request->user()->currentAccessToken()->delete();
        // \Auth::user()->tokens()->delete();
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        $user = \Auth::user();
        $user->password = \Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $user = \Auth::user();
        $user->name = $request->name;
        $user->save();
        return response()->json([
            'message' => 'Name updated successfully.',
        ]);
    }
}