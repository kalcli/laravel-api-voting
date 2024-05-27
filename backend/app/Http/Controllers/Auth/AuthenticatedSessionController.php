<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->role === 1 ? 1 : 0;
            $tokenName = $role === 1 ? $user->email.'_AdminToken' : $user->email.'_Token';
            $token = $user->createToken($tokenName, $role === 1 ? ['server:admin'] : [])->plainTextToken;

            return response()->json([
                'status' => 200,
                'token' => $token,
                'type' => 'Bearer',
                'role' => $role,
                'message' => 'Logged In Successfully',

            ]);
        }

        return response()->json([
            'status' => 422,
            'message' => 'Invalid credentials',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        if($user)
        {
            $user->tokens()->delete();
        }

        return response()->json([
            'status' => '200',
            'message' => 'Disconnected Successfully'
        ]);
    }
}
