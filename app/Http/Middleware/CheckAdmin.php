<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        // return response()->json($user);
        if (!$user || $user->user_type !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Only admins can perform this action.'
            ], 403);
        }

        return $next($request);
    }
}
