<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Traits\LogsAdminLogin;

class AdminLoginController extends Controller
{
    use LogsAdminLogin;
    
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Determine if input is email or username
        $field = filter_var($credentials['email_username'], FILTER_VALIDATE_EMAIL) 
            ? 'email' 
            : 'username';

        // Check if user exists and credentials are correct
        if (Auth::guard('admin')->attempt([
            $field => $credentials['email_username'],
            'password' => $credentials['password'],
        ], $request->remember)) {

            $user = Auth::guard('admin')->user();

            // Role check - only admin and subadmin
            if (!in_array($user->role, ['admin', 'subadmin'], true)) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email_username' => 'Unauthorized access. Admin privileges required.',
                ])->onlyInput('email_username');
            }

            // Check if user is active
            // if (isset($user->status) && $user->status !== 'active') {
            //     Auth::guard('admin')->logout();
            //     return back()->withErrors([
            //         'email_username' => 'Your account is inactive. Please contact administrator.',
            //     ])->onlyInput('email_username');
            // }

            // Generate OTP
            $otp = rand(100000, 999999);

            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(5);
            $user->save();

            // Store OTP in cache as backup
            Cache::put('otp_' . $user->id, $otp, 300);

            // Send OTP email
            try {
                Mail::send('emails.admin-otp', [
                    'otp' => $otp, 
                    'user' => $user,
                    'app_name' => config('app.name')
                ], function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Admin Login OTP - ' . config('app.name'))
                            ->priority(1);
                });
                
                Log::info('OTP sent successfully', ['user_id' => $user->id, 'email' => $user->email]);
            } catch (\Exception $e) {
                Log::error('OTP email failed: ' . $e->getMessage());
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email_username' => 'Failed to send OTP. Please try again later.',
                ])->onlyInput('email_username');
            }

            // Logout and store user ID in session
            Auth::guard('admin')->logout();

            session([
                'otp_user_id' => $user->id,
                'otp_created_at' => Carbon::now(),
                'otp_email' => $user->email
            ]);

            return redirect()->route('admin.otp.form')->with('success', 'OTP has been sent to ' . $this->maskEmail($user->email));
        }

        return back()->withErrors([
            'email_username' => 'Invalid credentials.',
        ])->onlyInput('email_username');
    }

    public function showOtpForm()
    {
        // Check if OTP session exists
        if (!session('otp_user_id')) {
            return redirect()->route('admin.login')->with('error', 'Session expired. Please login again.');
        }
        
        return view('auth.admin-otp', [
            'email' => session('otp_email')
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            session()->forget(['otp_user_id', 'otp_created_at', 'otp_email']);
            return redirect()->route('admin.login')->withErrors([
                'otp' => 'Session expired. Please login again.'
            ]);
        }

        // Check maximum attempts
        $attemptKey = 'otp_attempts_' . $user->id;
        $attempts = Cache::get($attemptKey, 0);
        
        if ($attempts >= 5) {
            session()->forget(['otp_user_id', 'otp_created_at', 'otp_email']);
            return redirect()->route('admin.login')->withErrors([
                'otp' => 'Too many failed attempts. Please login again.'
            ]);
        }

        // Check if OTP matches (from database or cache)
        $isValidOtp = ($user->otp === $request->otp) || (Cache::get('otp_' . $user->id) === $request->otp);
        
        if (!$isValidOtp) {
            Cache::put($attemptKey, $attempts + 1, 900); // 15 minutes
            $remainingAttempts = 4 - $attempts;
            return back()->withErrors(['otp' => "Invalid OTP. {$remainingAttempts} attempts remaining."])->withInput();
        }

        // Check if OTP is expired
        if (Carbon::now()->gt($user->otp_expires_at)) {
            // Clear OTP from database and cache
            $user->update([
                'otp' => null,
                'otp_expires_at' => null
            ]);
            Cache::forget('otp_' . $user->id);
            
            return back()->withErrors(['otp' => 'OTP has expired. Please login again.']);
        }

        // Get IP Address and Location
        $ip = $this->getClientIp();
        $location = $this->getLocationFromIp($ip);
        $userAgent = $request->userAgent();

        // LOG SUCCESSFUL LOGIN TO HISTORY TABLE
        $loginHistory = $this->logSuccessfulLogin($user, $ip, $location, $userAgent);

        // Login success
        Auth::guard('admin')->login($user);

        // Update user with login information
        $user->update([
            'last_login_ip' => $ip,
            'last_login_location' => $location,
            'last_login_at' => Carbon::now(),
            'otp' => null,
            'otp_expires_at' => null
        ]);

        // Store login history ID in session for logout tracking
        if ($loginHistory) {
            session(['current_login_history_id' => $loginHistory->id]);
        }

        // Clear all session and cache data
        session()->forget(['otp_user_id', 'otp_created_at', 'otp_email']);
        Cache::forget('otp_' . $user->id);
        Cache::forget($attemptKey);

        // Log the login
        Log::info('Admin login successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $ip,
            'location' => $location,
            'user_agent' => $userAgent,
            'history_id' => $loginHistory ? $loginHistory->id : null
        ]);

        // Regenerate session for security
        $request->session()->regenerate();

        // Redirect to dashboard with success message
        return redirect()->route('dashboard.admin')
            ->with('success', "Welcome back, {$user->name}! Last login from {$location} ({$ip})");
    }

    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');
        
        if (!$userId) {
            return redirect()->route('admin.login')->with('error', 'Session expired. Please login again.');
        }
        
        // Check resend rate limit
        $resendKey = 'otp_resend_' . $userId;
        $lastResend = Cache::get($resendKey);
        
        if ($lastResend && Carbon::now()->diffInSeconds($lastResend) < 60) {
            return back()->with('error', 'Please wait 60 seconds before requesting another OTP.');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            session()->forget('otp_user_id');
            return redirect()->route('admin.login')->with('error', 'User not found. Please login again.');
        }
        
        // Generate new OTP
        $otp = rand(100000, 999999);
        
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);
        
        // Store in cache
        Cache::put('otp_' . $user->id, $otp, 300);
        Cache::put($resendKey, Carbon::now(), 60);
        
        // Send new OTP email
        try {
            Mail::send('emails.admin-otp', [
                'otp' => $otp, 
                'user' => $user,
                'app_name' => config('app.name')
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('New Admin Login OTP - ' . config('app.name'));
            });
            
            Log::info('OTP resent', ['user_id' => $user->id, 'email' => $user->email]);
            
            return back()->with('success', 'New OTP has been sent to your email.');
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP: ' . $e->getMessage());
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();
        
        if ($user) {
            // Update logout time in history for successful login
            $this->updateLogoutTime($user->id);
            
            Log::info('Admin logout', ['user_id' => $user->id, 'email' => $user->email]);
        }
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    /**
     * Get client IP address
     */
    private function getClientIp()
    {
        $ip = request()->ip();
        
        // Handle proxy/cloudflare IPs
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        
        return $ip;
    }

    /**
     * Get location from IP address
     */
    private function getLocationFromIp($ip)
    {
        // Handle local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost', '192.168.0.1', '192.168.1.1'])) {
            return 'Local Network';
        }

        // Check cache first
        $cacheKey = 'ip_location_' . str_replace('.', '_', $ip);
        $cachedLocation = Cache::get($cacheKey);
        
        if ($cachedLocation) {
            return $cachedLocation;
        }

        $location = 'Unknown Location';

        try {
            // Try ip-api.com first (free, no API key required)
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city");
            
            if ($response->successful()) {
                $data = $response->json();
                if ($data && isset($data['status']) && $data['status'] === 'success') {
                    $locationParts = array_filter([
                        $data['city'] ?? null,
                        $data['regionName'] ?? null,
                        $data['country'] ?? null
                    ]);
                    
                    $location = !empty($locationParts) ? implode(', ', $locationParts) : 'Unknown';
                    
                    // Cache for 24 hours
                    Cache::put($cacheKey, $location, 86400);
                    return $location;
                }
            }
        } catch (\Exception $e) {
            Log::warning('IP Geolocation failed: ' . $e->getMessage());
        }

        return $location;
    }

    /**
     * Mask email for display
     */
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $len = strlen($name);
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(3, $len - 3)) . substr($name, -1);
        return $maskedName . '@' . $parts[1];
    }
}