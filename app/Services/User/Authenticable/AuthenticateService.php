<?php

namespace App\Services\User\Authenticable;

use App\Http\Requests\User\Authenticable\AuthenticationRequest;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateService
{
    public function authenticate(AuthenticationRequest $request)
    {
        $request->authenticate();

        $user = Auth::user();

        if(!$user || !($user instanceof User)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}
