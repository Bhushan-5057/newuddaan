<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\AdvanceInfo;

class CheckSiteStatus
{
    public function handle($request, Closure $next)
    {
        $status = AdvanceInfo::where('label', 'maintenance_mode')->value('value');

        // If site is in maintenance mode
        if ($status === 'yes') {
         
                return response()->view('maintenance');
          
        }

        return $next($request);
    }
}
