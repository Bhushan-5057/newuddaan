<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FranchiseSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guard('franchise')->check()) {
            return redirect()->route('franchise.login');
        }

        return $next($request);
    }
}
