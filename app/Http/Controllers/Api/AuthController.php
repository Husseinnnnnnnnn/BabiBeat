<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Userr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => ['required','string','min:3','max:50', 'unique:userss,username'],
            'name'     => ['nullable','string','max:100'],
            'email'    => ['required','email','max:100', 'unique:userss,email'],
            'password' => ['required','string','min:6','max:100'],
            'avatar_url' => ['nullable','url'],
            'subscription_id' => ['nullable','exists:subscriptions,id'],
        ]);

        $user = Userr::create([
            'username'        => $data['username'],
            'name'            => $data['name'] ?? null,
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'avatar_url'      => $data['avatar_url'] ?? null,
            'subscription_id' => $data['subscription_id'] ?? null,
        ]);

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'user'    => [
                'id'       => $user->id,
                'username' => $user->username,
                'name'     => $user->name,
                'email'    => $user->email,
                'avatar'   => $user->avatar_url,
            ],
            'token'   => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        $user = Userr::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'Logged in',
            'token'   => $token,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        // revoke current token
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
