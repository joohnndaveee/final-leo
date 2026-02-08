<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request and ensure the authenticated user has one of the allowed roles.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $role = $user->role ?? 'buyer';

        if (!empty($roles) && !in_array($role, $roles, true)) {
            abort(403, 'You are not authorized to access this resource.');
        }

        return $next($request);
    }
}
