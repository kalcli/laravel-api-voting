<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifiez que l'utilisateur est authentifié et que le token a la capacité ''
        if (!$request->user() || !$request->user()->tokenCan('')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
