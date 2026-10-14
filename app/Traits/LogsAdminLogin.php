<?php

namespace App\Traits;

use App\Models\AdminLoginHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait LogsAdminLogin
{
    /**
     * Log successful login only
     */
    protected function logSuccessfulLogin($user, $ip, $location, $userAgent)
    {
        try {
            return AdminLoginHistory::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'ip_address' => $ip,
                'location' => $location,
                'user_agent' => $userAgent,
                'login_at' => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log successful login: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update logout time for the last successful login
     */
    protected function updateLogoutTime($userId)
    {
        try {
            $lastLogin = AdminLoginHistory::where('user_id', $userId)
                                        ->whereNull('logout_at')
                                        ->orderBy('login_at', 'desc')
                                        ->first();
            
            if ($lastLogin) {
                $logoutTime = Carbon::now();
                $sessionDuration = $logoutTime->diffInSeconds($lastLogin->login_at);
                
                $lastLogin->update([
                    'logout_at' => $logoutTime,
                    'session_duration' => $sessionDuration
                ]);
                
                Log::info('Logout time updated', [
                    'user_id' => $userId,
                    'session_duration' => $sessionDuration . ' seconds'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update logout time: ' . $e->getMessage());
        }
    }
    
    /**
     * Get user's login statistics
     */
    protected function getUserLoginStats($userId)
    {
        return [
            'total_logins' => AdminLoginHistory::where('user_id', $userId)->count(),
            'last_login' => AdminLoginHistory::where('user_id', $userId)
                                            ->latest('login_at')
                                            ->first(),
            'logins_this_month' => AdminLoginHistory::where('user_id', $userId)
                                    ->whereMonth('login_at', now()->month)
                                    ->count(),
            'unique_ips' => AdminLoginHistory::where('user_id', $userId)
                            ->distinct('ip_address')
                            ->count('ip_address')
        ];
    }
}