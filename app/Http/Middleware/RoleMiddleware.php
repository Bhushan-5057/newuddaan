<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        /**
         * Get guard from route middleware (auth:guard)
         */
        $guard = 'web'; // default

        foreach ($request->route()->middleware() as $middleware) {
            if (str_starts_with($middleware, 'auth:')) {
                $guard = explode(':', $middleware)[1];
                break;
            }
        }

        $user = Auth::guard($guard)->user();

        if (!$user) {
            abort(403, 'Unauthenticated.');
        }

        if (!in_array($user->role, $roles, true)) {
            abort(403, 'Unauthorized role.');
        }

        return $next($request);
    }
}
