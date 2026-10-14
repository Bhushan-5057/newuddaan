<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('superadmin.setting.forgotpassword'); // your blade file path
    }

    public function updatePassword(Request $request)
    {
        // Validate input
        $request->validate([
            'currentPassword' => ['required'],
            'newPassword' => ['required', 'string', 'min:8', 'confirmed'], 
            // 'confirmed' expects a field newPassword_confirmation
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->currentPassword, $user->password)) {
            return back()->withErrors(['currentPassword' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        return back()->with('success', 'Password changed successfully');
    }
}