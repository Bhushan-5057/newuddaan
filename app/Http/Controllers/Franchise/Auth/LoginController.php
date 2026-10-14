<?php

namespace App\Http\Controllers\Franchise\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\FranchiseUser;

class LoginController extends Controller
{


    public function showLoginForm(Request $request)
    {
   
 return view('franchise.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required',
            'password' => 'required',
        ], [
            'login.required' => 'Username, Email or Mobile is required',
        ]);

        $login = $request->login;

        $franchise = FranchiseUser::where('username', $login)
            ->orWhere('email', $login)
            ->orWhere('mobile', $login)
            ->first();

        if (!$franchise || !Hash::check($request->password, $franchise->password)) {
            return back()
                ->withInput()
                ->with('error', 'Invalid login credentials');
        }

        if ($franchise->active_status != 1) {
            return back()
                ->with('error', 'Your account is inactive. Contact support.');
        }

        Auth::guard('franchise')->login($franchise, $request->filled('remember'));

        return redirect()->route('franchise.dashboard');
    }
}
