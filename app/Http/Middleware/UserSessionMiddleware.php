<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class UserSessionMiddleware
{
        /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            
            if (!$request->cookie('temp_user_id')) {
                
                $tempUserId = 'temp_' . Str::random(10);

                Cookie::queue('temp_user_id', $tempUserId, 60 * 24 * 30); // 30 days
            }
        }

        return $next($request);
    }
}
