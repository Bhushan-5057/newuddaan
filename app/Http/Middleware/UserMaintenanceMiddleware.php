<?php

namespace App\Http\Middleware;

use Closure;
use DB;
    use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

    use App\Models\RolePermission;
    use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;




class UserMaintenanceMiddleware
{
public function handle(Request $request, Closure $next): Response
    {
        // Only check if the user is logged in and has role "user"
        $user = Auth::user();

        if ($user && $user->role === 'user') {
            $permission = RolePermission::where('feature', 'Maintenance Mode')
                ->where('role', 'user')
                ->first();

                

            if ($permission) {
                $options = json_decode($permission->options, true);
            
                //If can_view is false => Show maintenance page 
                 
                if (!($options['can_view'] ?? true)) {
                    // dd('maintenance active ');
                    
                    return response()->view('maintenance');


                }
            }
            
        }
                
        return $next($request);
    }
}






   
