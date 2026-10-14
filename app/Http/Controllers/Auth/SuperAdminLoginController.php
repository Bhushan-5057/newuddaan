<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.superadmin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_username' => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($credentials['email_username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::guard('superadmin')->attempt([
            $field => $credentials['email_username'],
            'password' => $credentials['password'],
            'role' => 'super admin',
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.superadmin')->with('success', 'Super Admin login successful.');
        }

        return back()->withErrors([
            'email_username' => 'Invalid credentials or role.',
        ])->onlyInput('email_username');
    }
}
