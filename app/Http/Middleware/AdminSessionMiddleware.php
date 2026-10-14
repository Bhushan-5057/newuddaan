<?php

// app/Http/Middleware/AdminSessionMiddleware.php
namespace App\Http\Middleware;

use Closure;

class AdminSessionMiddleware
{
    public function handle($request, Closure $next)
    {
        config(['session.cookie' => 'admin_session']);
        return $next($request);
    }
}
