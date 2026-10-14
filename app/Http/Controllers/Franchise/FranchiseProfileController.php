<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FranchiseProfileController extends Controller
{
    public function index()
    {
        $franchise = auth()->guard('franchise')->user();
        return view('franchise.profile.profile', compact('franchise'));
    }

    public function update(Request $request)
    {
        $franchise = auth()->guard('franchise')->user();

        /*
        |--------------------------------------------------------------------------
        | CHANGE PASSWORD
        |--------------------------------------------------------------------------
        */
        if ($request->filled('password_only')) {

            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ], [
                'current_password.required' => 'Current password is required',
                'password.required' => 'New password is required',
                'password.confirmed' => 'Password confirmation does not match',
            ]);

            // ❌ Old password mismatch
            if (!Hash::check($request->current_password, $franchise->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Current password is incorrect',
                ]);
            }

            $franchise->password = Hash::make($request->password);
            $franchise->save();

            return back()->with('success', 'Password changed successfully');
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PROFILE
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'name'   => 'required|string|max:200',
            'email'  => 'required|email|unique:franchise_users,email,' . $franchise->id,
            'mobile' => 'required|digits_between:10,15',
            'city'   => 'required|string|max:100',
            'address'=> 'required|string|min:5',
        ], [
            'name.required' => 'Name is required',
            'email.unique' => 'This email is already in use',
            'mobile.digits_between' => 'Mobile number must be valid',
        ]);

        $franchise->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'mobile'  => $request->mobile,
            'city'    => $request->city,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profile updated successfully');
    }
}
