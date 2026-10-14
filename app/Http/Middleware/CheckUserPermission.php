<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserPermission
{
public function handle($request, Closure $next, $module, $permission)
{
    $user = auth()->user();

    if (!$user || !$user->hasPermission($module, $permission)) {
        // Check role and return role-specific no-permission view
        if ($user && $user->role === 'admin') {
            return response()->view('admin.components.no-permission');
        } elseif ($user && $user->role === 'user') {
            return response()->view('user.components.no-permission');
        }

        // Fallback if role doesn't match
        return abort(403, 'Access Denied');
    }

    return $next($request);
}


}