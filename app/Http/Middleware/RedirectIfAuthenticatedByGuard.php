<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedByGuard
{
    public function handle($request, Closure $next, $guard = 'web')
    {
        if (Auth::guard($guard)->check()) {
            return match ($guard) {
                'admin' => redirect()->route('dashboard.admin'),
                'superadmin' => redirect()->route('dashboard.superadmin'),
                default => redirect()->route('dashboard.user'),
            };
        }

        return $next($request);
    }
}
