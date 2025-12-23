<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/role-selection');
        }

        if (auth()->user()->role !== $role) {
            // Redirect non-admin users away from admin routes
            if ($role === 'admin') {
                return redirect('/client/dashboard')->with('error', 'Access denied. Admin privileges required.');
            }
            abort(403, 'Access denied. ' . ucfirst($role) . ' privileges required.');
        }

        return $next($request);
    }
}
