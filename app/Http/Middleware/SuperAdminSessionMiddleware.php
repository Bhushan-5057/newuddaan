<?php

// app/Http/Middleware/AdminSessionMiddleware.php
namespace App\Http\Middleware;

use Closure;

class SuperAdminSessionMiddleware
{
    public function handle($request, Closure $next)
    {
        config(['session.cookie' => 'super_admin_session']);
        return $next($request);
    }
}