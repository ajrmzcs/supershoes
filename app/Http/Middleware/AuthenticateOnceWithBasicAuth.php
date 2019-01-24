<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateOnceWithBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $username = 'my_user';
        $password = 'my_password';

        if ('Basic ' . base64_encode($username . ':' . $password) === $request->header('Authorization')) {
            return $next($request);
        } else {
            return response()->json([
                'success' => false,
                'error_code' => 401,
                'error_msg' => 'Unauthenticated. Invalid authorization header'
            ], 401);
        }

    }
}
