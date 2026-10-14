<?php

// app/Http/Middleware/UserSessionMiddleware.php
namespace App\Http\Middleware;

use Closure;

class UserSessionMiddleware
{
    public function handle($request, Closure $next)
    {
        // Store the original session cookie name
        $originalSessionCookie = config('session.cookie');
        
        // Set user session cookie
        config(['session.cookie' => 'user_session']);
        
        // Store the original cookie name in the request for later use
        $request->attributes->set('original_session_cookie', $originalSessionCookie);
        
        $response = $next($request);
        
        // Restore the original session cookie name
        config(['session.cookie' => $originalSessionCookie]);
        
        return $response;
    }
}