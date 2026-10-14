<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Get filter values
        $search = $request->get('search', '');
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $userFilter = $request->get('user_id', '');
        
        // Build query
        $query = AdminLoginHistory::with('user');
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply user filter
        if ($userFilter) {
            $query->where('user_id', $userFilter);
        }
        
        // Apply date range filter
        if ($dateFrom) {
            $query->whereDate('login_at', '>=', Carbon::parse($dateFrom));
        }
        if ($dateTo) {
            $query->whereDate('login_at', '<=', Carbon::parse($dateTo));
        }
        
        // Get paginated results
        $loginHistories = $query->orderBy('login_at', 'desc')->paginate(25);
        
        // Get statistics for dashboard
        $stats = [
            'total_logins' => AdminLoginHistory::count(),
            'today_logins' => AdminLoginHistory::whereDate('login_at', today())->count(),
            'this_week' => AdminLoginHistory::whereBetween('login_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => AdminLoginHistory::whereMonth('login_at', Carbon::now()->month)->count(),
            'unique_users' => AdminLoginHistory::distinct('user_id')->count('user_id'),
            'unique_ips' => AdminLoginHistory::distinct('ip_address')->count('ip_address'),
            'active_sessions' => AdminLoginHistory::whereNull('logout_at')
                                    ->where('login_at', '>', Carbon::now()->subMinutes(30))
                                    ->count(),
        ];
        
        // Get top users by login count
        $topUsers = AdminLoginHistory::select('user_id', DB::raw('count(*) as login_count'))
                    ->with('user')
                    ->groupBy('user_id')
                    ->orderBy('login_count', 'desc')
                    ->limit(5)
                    ->get();
        
        // Get users for filter dropdown
        $users = User::whereIn('role', ['admin', 'subadmin'])->orderBy('name')->get();
        
        // Get login activity by hour (for chart)
        $hourlyActivity = AdminLoginHistory::select(
                                DB::raw('HOUR(login_at) as hour'),
                                DB::raw('count(*) as count')
                            )
                            ->whereDate('login_at', today())
                            ->groupBy('hour')
                            ->orderBy('hour')
                            ->get();
        
        return view('admin.login-history', compact(
            'loginHistories', 
            'stats', 
            'topUsers', 
            'users',
            'hourlyActivity',
            'search',
            'dateFrom',
            'dateTo',
            'userFilter'
        ));
    }
    
    public function export(Request $request)
    {
        $query = AdminLoginHistory::with('user');
        
        // Apply same filters as index
        if ($request->date_from) {
            $query->whereDate('login_at', '>=', Carbon::parse($request->date_from));
        }
        if ($request->date_to) {
            $query->whereDate('login_at', '<=', Carbon::parse($request->date_to));
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        $histories = $query->orderBy('login_at', 'desc')->get();
        
        // Export to CSV
        $filename = 'login-history-' . Carbon::now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($histories) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'User Name', 'Email', 'Username', 'IP Address', 
                'Location', 'Login Time', 'Logout Time', 'Session Duration (seconds)', 
                'User Agent'
            ]);
            
            // Add data
            foreach ($histories as $history) {
                fputcsv($file, [
                    $history->id,
                    $history->user->name ?? 'N/A',
                    $history->email,
                    $history->username,
                    $history->ip_address,
                    $history->location ?? 'Unknown',
                    $history->login_at,
                    $history->logout_at ?? 'Active',
                    $history->session_duration ?? 0,
                    $history->user_agent
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function clearOldLogs(Request $request)
    {
        $days = $request->input('days', 90);
        $deleted = AdminLoginHistory::where('login_at', '<', Carbon::now()->subDays($days))->delete();
        
        return redirect()->route('admin.login.history')
                         ->with('success', "Deleted {$deleted} old login records older than {$days} days.");
    }
    
    public function getUserHistory($id)
    {
        $user = User::findOrFail($id);
        $loginHistories = AdminLoginHistory::where('user_id', $id)
                                         ->orderBy('login_at', 'desc')
                                         ->paginate(25);
        
        return view('admin.user-login-history', compact('user', 'loginHistories'));
    }
    
    public function getAjaxData(Request $request)
    {
        $query = AdminLoginHistory::with('user');
        
        // Apply filters
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', "%{$request->search}%")
                  ->orWhere('ip_address', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->date_from) {
            $query->whereDate('login_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('login_at', '<=', $request->date_to);
        }
        
        $records = $query->orderBy('login_at', 'desc')->paginate(25);
        
        return response()->json($records);
    }
}