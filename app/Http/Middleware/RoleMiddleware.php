<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->user()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole($role)) {
            // If user is direksi trying to access restricted page
            if (auth()->user()->hasRole('direksi')) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini. Hanya Superadmin yang dapat mengakses fitur ini.');
            }

            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}