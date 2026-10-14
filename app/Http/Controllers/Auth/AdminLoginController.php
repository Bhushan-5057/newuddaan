<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.adminlogin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_username' => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($credentials['email_username'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (Auth::guard('admin')->attempt([
            $field     => $credentials['email_username'],
            'password' => $credentials['password'],
        ], $request->remember)) {

            $user = Auth::guard('admin')->user();

            // Allow admin & subadmin
            if (!in_array($user->role, ['admin', 'subadmin'], true)) {
                Auth::guard('admin')->logout();

                return back()->withErrors([
                    'email_username' => 'Unauthorized access.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()
                ->route('dashboard.admin')
                ->with('success', 'Admin login successful.');
        }

        return back()->withErrors([
            'email_username' => 'Invalid credentials.',
        ]);
    }


       public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();
        
        if ($user) {
            // Update logout time in history for successful login
            // $this->updateLogoutTime($user->id);
            
            Log::info('Admin logout', ['user_id' => $user->id, 'email' => $user->email]);
        }
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

}
