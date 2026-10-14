<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
  public function userLogout(Request $request)
{
    // Check if this is an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ]);
    }

    // Regular logout for non-AJAX requests
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect()->route('login')->with('success', 'Logged out successfully.');
}

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    public function superAdminLogout(Request $request)
    {
        Auth::guard('superadmin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('superadmin.login')->with('success', 'Logged out successfully.');
    }

    public function franchiseLogout(Request $request)
    {
        if (session('is_impersonating')) {
            Auth::guard('franchise')->logout();
            Auth::guard(session('impersonator_guard'))
                ->loginUsingId(session('impersonator_id'));
            
            session()->forget([
                'impersonator_id',
                'impersonator_guard',
                'is_impersonating'
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Returned to admin account.'
                ]);
            }

            return redirect()
                ->route('admin.login')
                ->with('success', 'Returned to admin account.');
        }

        Auth::guard('franchise')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.'
            ]);
        }

        return redirect()->route('franchise.login');
    }
}