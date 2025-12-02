<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        // 1. Validate incoming request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // 2. Create user in database
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // 3. Generate API token for immediate access
        $token = $user->createToken('api-token')->plainTextToken;

        // 4. Return user data and token
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // Authenticate user and issue token
    public function login(Request $request)
    {
        // 1. Validate credentials format
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Find user by email
        $user = User::where('email', $data['email'])->first();

        // 3. Verify password
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // 4. Generate new token (Revoke existing tokens optionally: $user->tokens()->delete();)
        $token = $user->createToken('api-token')->plainTextToken;

        // 5. Return success response
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Revoke current access token
    public function logout(Request $request)
    {
        // Delete the token that was used to authenticate this request
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
