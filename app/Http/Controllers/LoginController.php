<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LoginService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    protected $loginService;

     public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $key = 'login-attempt:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Trop de tentatives. Réessayez dans ' . RateLimiter::availableIn($key) . ' secondes.'
            ], 429);
        }

        $user = $this->loginService->login(
            $request->registration_number,
            $request->password
        );

        if (!$user) {
            RateLimiter::hit($key, 60); 
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        RateLimiter::clear($key);

        return response()->json([
        'success' => true,
        'user' => [
            'id' => $user->id,
            'firstname' => $user->firstname,
            'registration_number' => $user->registration_number,
            'authorization' => $user->authorization
        ]
        ]);
    }
}