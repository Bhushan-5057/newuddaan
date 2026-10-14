<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // Show forgot password form
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // Send reset link
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check user and role
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        if ($user->role !== 'user') {
            return back()->withErrors(['email' => 'Password reset is only allowed for User accounts.']);
        }

        // Send reset link if role is user
        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
