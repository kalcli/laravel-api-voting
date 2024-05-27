<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAdminMiddleware
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
        // Vérifiez que l'utilisateur est authentifié et que le token a la capacité "server:admin"
        if (!$request->user() || !$request->user()->tokenCan('server:admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
