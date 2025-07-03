<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $token = $request->cookie('token');

    //     $result = JWTToken::VerifyToken($token);

    //     if ($result == 'unauthorized') {
    //         return response()->json(['message' => 'unauthorized'], 401);
    //     } else {
    //         $request->headers->set('email', $result->userEmail);
    //         $request->headers->set('id', $result->userID);
    //         return $next($request);
    //     }
    // }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result = JWTToken::VerifyToken($token);

        if ($result === 'unauthorized') {
            return response()->json(['message' => 'unauthorized'], 401);
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
            return response()->json(['message' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
