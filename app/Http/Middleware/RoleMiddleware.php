<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Administrator bypasses all role restrictions
        if (!$user || ($user->role !== 'administrator' && !in_array($user->role, $roles))) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
