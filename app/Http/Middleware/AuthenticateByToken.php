<?php

namespace App\Http\Middleware;

use App\Services\PublicUserService;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateByToken
{
    public function __construct(private readonly PublicUserService $userService, private Guard $guard) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Missing Authorization header'], 401);
        }

        if (!Str::isUuid($token)) {
            return response()->json(['message' => 'Invalid token format'], 401);
        }

        $user = $this->userService->getUserByToken($token);
        if (!$user) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        $this->guard->setUser($user);

        return $next($request);
    }
}
