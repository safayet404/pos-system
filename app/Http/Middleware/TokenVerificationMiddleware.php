<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{


    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result = JWTToken::VerifyToken($token);

        if ($result === 'unauthorized') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'unauthorized'], 401);
            }
            if ($request->header('X-Inertia')) {
                return Inertia::location('/login-page');
            }
            return redirect('/login-page');
        }

        // Handle employee login
        if (isset($result->employeeID)) {
            $request->headers->set('employee-id', $result->employeeID);
            $request->headers->set('email', $result->employeeEmail);
            $request->headers->set('id', $result->userID); // this is the owner's ID
        }
        // Handle user login (owner)
        elseif (isset($result->userID)) {
            $request->headers->set('id', $result->userID); // original user ID
            $request->headers->set('email', $result->userEmail);
            $request->headers->set('user-id', $result->userID); // for consistent usage across app
        } else {
            return redirect('/login-page');
        }

        return $next($request);
    }
}
