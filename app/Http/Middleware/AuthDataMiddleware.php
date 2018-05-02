<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthDataMiddleware
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
        $api_key = Auth::user()->api_key;

        $request->request->add(['user_token' => $api_key]);
        $response =  $next($request);
        $response->header('Authorization', $api_key);

        return $response;
    }
}
