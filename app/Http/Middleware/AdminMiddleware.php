<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $auth = Auth::user();
        if (!$auth) {
            return response()->json([
                'message' => 'unAuthorized'
            ], 401);
        }

        if ($auth['role'] == \Roles::ADMIN) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access Denied'
        ], 400);
    }
}
