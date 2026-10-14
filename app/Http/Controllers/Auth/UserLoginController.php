<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Changed from wrong namespace
use Illuminate\Support\Facades\Log; // Added missing Log import

use Illuminate\Support\Facades\Hash;


class UserLoginController extends Controller
{

    
    public function showLoginForm(Request $request)
{

    return view('auth.login');
}


// public function login(Request $request)
// {
//     $credentials = $request->validate([
//         'email_username' => 'required|string',
//         'password' => 'required|string',
//     ]);

//     $field = filter_var($credentials['email_username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

//     if (Auth::guard('web')->attempt([
//         $field => $credentials['email_username'],
//         'password' => $credentials['password'],
//         'role' => 'user',
//     ], $request->remember)) {
        
//         $request->session()->regenerate();
//         session()->flash('showPopup', true);

//         // ફેરફાર અહીં છે: back() નો ઉપયોગ કરો જેથી યુઝર એ જ પેજ પર રહે
//         return redirect()->back()->with('success', 'Login successful.');
//     }

//     return back()->withErrors([
//         'email_username' => 'Invalid credentials or role.',
//     ])->onlyInput('email_username');
// }


public function login(Request $request)
{
    $credentials = $request->validate([
        'email_username' => 'required|string',
        'password' => 'required|string',
    ]);

    $loginInput = $credentials['email_username'];

    
    $user = User::where('role', 'user')
        ->where(function ($query) use ($loginInput) {
            $query->where('email', $loginInput)
                  ->orWhere('username', $loginInput)
                  ->orWhere('unique_name', $loginInput);
        })
        ->first();

  
    if ($user && Hash::check($credentials['password'], $user->password)) {
        
        Auth::guard('web')->login($user, $request->boolean('remember'));

        $request->session()->regenerate();
        session()->flash('showPopup', true);

        return redirect()->back()->with('success', 'Login successful.');
    }


    return back()->withErrors([
        'email_username' => 'Invalid credentials or role.',
    ])->onlyInput('email_username');
}


    public function showLoginFormdapp()
    {
        return view('auth.logindapp');
    }

    public function logindapp(Request $request)
    {
        $request->validate([
            'user_address' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'signature' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        $walletAddress = strtolower($request->input('user_address'));

        // Verify signature (optional but recommended for security)
        try {
            $signature = $request->input('signature');
            $message = $request->input('message');

            // You can implement signature verification here using web3.php or similar
            // For example:
            // $verified = $this->verifySignature($message, $signature, $walletAddress);
            // if (!$verified) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Invalid signature'
            //     ], 400);
            // }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature verification'
            ], 400);
        }

        // Find user by wallet address (stored in name field)
        $user = User::where('name', $walletAddress)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wallet address not registered. Please register first.'
            ], 404);
        }

        // Check if user is active
        if ($user->status != 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is inactive. Please contact support.'
            ], 403);
        }

        // Login the user
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        // Log the login activity
        Log::info('Wallet login successful', [
            'user_id' => $user->id,
            'wallet_address' => $walletAddress,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'status' => 'success',
            'redirect' => route('dashboard.user')
        ]);
    }
}
