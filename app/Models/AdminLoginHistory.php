<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLoginHistory extends Model
{
    protected $table = 'admin_login_histories';
    
    protected $fillable = [
        'user_id',
        'email',
        'username',
        'ip_address',
        'location',
        'user_agent',
        'login_at',
        'logout_at',
        'session_duration'
    ];
    
    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'session_duration' => 'integer'
    ];
    
    /**
     * Get the user that owns the login history
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get recent logins for a user
     */
    public static function getRecentLogins($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
                    ->orderBy('login_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
    
    /**
     * Get today's logins count
     */
    public static function getTodayLoginsCount()
    {
        return self::whereDate('login_at', today())->count();
    }
    
    /**
     * Get this week's logins count
     */
    public static function getThisWeekLoginsCount()
    {
        return self::whereBetween('login_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
    }
}