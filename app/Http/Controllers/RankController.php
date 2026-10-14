<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UserHelper;
use App\Models\Order;
use App\Models\RankHistory;
use App\Models\User;
use App\Models\RankReward;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class RankController extends Controller
{
    /**
     * Display the rank achievement page for the authenticated user.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $user = auth()->user();



        return view('user.reward.qualification', compact('user'));
    }
    
    
public function adminindex(Request $request)
{
    // બેઝ ક્વેરી - ડિફોલ્ટમાં બધા જ ડેટા આવશે
    $query = RankHistory::query();

    // તારીખના ફિલ્ટર માટેના વેરિએબલ્સ
    $fromDate = null;
    $toDate = null;
    $dateRange = $request->date_range ?? 'all';

    if ($dateRange !== 'all') {
        switch ($dateRange) {
            case 'today':
                $fromDate = Carbon::today();
                $toDate = Carbon::today()->endOfDay();
                break;
                
            case 'yesterday':
                $fromDate = Carbon::yesterday();
                $toDate = Carbon::yesterday()->endOfDay();
                break;
                
            case 'this_week':
                $fromDate = Carbon::now()->startOfWeek();
                $toDate = Carbon::now()->endOfWeek();
                break;
                
            case 'last_week':
                $fromDate = Carbon::now()->subWeek()->startOfWeek();
                $toDate = Carbon::now()->subWeek()->endOfWeek();
                break;
                
            case 'this_month':
                $fromDate = Carbon::now()->startOfMonth();
                $toDate = Carbon::now()->endOfMonth();
                break;
                
            case 'last_month':
                $fromDate = Carbon::now()->subMonth()->startOfMonth();
                $toDate = Carbon::now()->subMonth()->endOfMonth();
                break;
                
            case 'custom':
                if ($request->filled('from_date')) {
                    $fromDate = Carbon::parse($request->from_date)->startOfDay();
                }
                if ($request->filled('to_date')) {
                    $toDate = Carbon::parse($request->to_date)->endOfDay();
                }
                break;
        }

        // જો તારીખો સેટ થઈ હોય તો જ વેરબીટવીન ફિલ્ટર લગાવવું
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }
    }

    // બાકીના કન્ડિશનલ ફિલ્ટર્સ (Rank, Status, User ID)
    if ($request->filled('rank')) {
        $query->where('rank', $request->rank);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // આંકડાઓની સાચી ગણતરી માટે ક્વેરી ક્લોન કરવી
    $totalBonus = (clone $query)->sum('bonus');
    $totalAchievers = (clone $query)->distinct('user_id')->count('user_id');

    // ફાઇનલ પેજીનેશન રીઝલ્ટ
    $rankRewards = $query->with('user')
        ->latest()
        ->paginate(20);

    // ડ્રોપડાઉન માટે યુનિક રેન્ક લિસ્ટ
    $distinctRanks = RankHistory::distinct()->pluck('rank');

    return view('admin.rewards.rank_report', compact(
        'rankRewards',
        'totalBonus',
        'totalAchievers',
        'fromDate',
        'toDate',
        'dateRange',
        'distinctRanks'
    ));
}

public function royaltyReport(Request $request)
{
    $fromDate=$request->from_date
        ? Carbon::parse($request->from_date)->startOfDay()
        : Carbon::now()->startOfMonth();

    $toDate=$request->to_date
        ? Carbon::parse($request->to_date)->endOfDay()
        : Carbon::now()->endOfMonth();

    $royalties=Transaction::with('user')
        ->where('source','royalty')
        ->whereBetween('added_on',[
            $fromDate,
            $toDate
        ])
        ->latest()
        ->paginate(20);

    $totalRoyalty=Transaction::where('source','royalty')
        ->whereBetween('added_on',[
            $fromDate,
            $toDate
        ])
        ->sum('amount');

    $totalUsers=Transaction::where('source','royalty')
        ->whereBetween('added_on',[
            $fromDate,
            $toDate
        ])
        ->distinct('u_code')
        ->count('u_code');

    $ctoBusiness=Order::where('tx_type','product')
        ->where('status',2)
        ->whereBetween('added_on',[
            $fromDate,
            $toDate
        ])
        ->sum('order_amount');

    return view(
        'admin.rewards.royalty_report',
        compact(
            'royalties',
            'totalRoyalty',
            'totalUsers',
            'ctoBusiness',
            'fromDate',
            'toDate'
        )
    );
}
    public function rewards()
    {
        $userId = Auth::id();
        $user   = User::findOrFail($userId);



        // Bonus slabs (same as cron)
        $rankCriteria = [
            1 => ['rank' => 'Hero',         'target' => 2500,      'reward' => 125],
            2 => ['rank' => 'Bronze',       'target' => 5000,      'reward' => 250],
            3 => ['rank' => 'Silver',       'target' => 12500,     'reward' => 625],
            4 => ['rank' => 'Champion',     'target' => 25000,     'reward' => 1250],
            5 => ['rank' => 'Master',       'target' => 50000,     'reward' => 2500],
            6 => ['rank' => 'Grand Master', 'target' => 125000,    'reward' => 6250],
            7 => ['rank' => 'Commander',    'target' => 250000,    'reward' => 12500],
            8 => ['rank' => 'Royal Prince', 'target' => 500000,    'reward' => 25000],
            9 => ['rank' => 'Crown Prince', 'target' => 1250000,   'reward' => 62500],
            10 => ['rank' => 'Prime Minister', 'target' => 2500000, 'reward' => 125000],
            11 => ['rank' => 'Royal King',  'target' => 5000000,   'reward' => 250000],
        ];

        // ✅ Calculate achieved pairs
        $totalBusinessLeft  = UserHelper::teamBusinessVolume($userId, 1, null, null);
        $totalBusinessRight = UserHelper::teamBusinessVolume($userId, 2, null, null);
        $matchingBusiness   = min($totalBusinessLeft, $totalBusinessRight);



        // ✅ Get already credited rewards
        $userRewards = RankReward::where('user_id', $userId)->pluck('rank')->toArray();

        return view('user.reward.rankrise', compact(
            'user',
            'rankCriteria',
            'userRewards',
            'matchingBusiness',
            'totalBusinessLeft',
            'totalBusinessRight'
        ));
    }


    public function royalty()
    {
        $userId = Auth::id();
        $user   = User::findOrFail($userId);

        // ⭐ STAR RANK CONFIG (FROM EXCEL)
        $royaltyRanks = [
            ['rank' => 'Star-1', 'business' => 1000,    'daily' => 1,    'reward' => 50],
            ['rank' => 'Star-2', 'business' => 5000,    'daily' => 5,    'reward' => 200],
            ['rank' => 'Star-3', 'business' => 15000,   'daily' => 15,   'reward' => 400],
            ['rank' => 'Star-4', 'business' => 40000,   'daily' => 40,   'reward' => 800],
            ['rank' => 'Star-5', 'business' => 120000,  'daily' => 120,  'reward' => 2000],
            ['rank' => 'Star-6', 'business' => 400000,  'daily' => 400,  'reward' => 4000],
            ['rank' => 'Star-7', 'business' => 1000000, 'daily' => 1000, 'reward' => 8000],
            ['rank' => 'Star-8', 'business' => 2500000, 'daily' => 2500, 'reward' => 15000],
        ];

        // LEFT & RIGHT BUSINESS
        $leftBusiness  = UserHelper::teamBusinessVolume($userId, 1);
        $rightBusiness = UserHelper::teamBusinessVolume($userId, 2);

        // STATUS CALCULATION (LEFT + RIGHT REQUIRED)
        foreach ($royaltyRanks as &$rank) {
            $rank['status'] = (
                $leftBusiness  >= $rank['business'] &&
                $rightBusiness >= $rank['business']
            ) ? 'Achieved' : 'Pending';
        }

        return view('user.reward.royalty', compact(
            'user',
            'royaltyRanks',
            'leftBusiness',
            'rightBusiness'
        ));
    }






    public function rankAchievement()
    {
        // Assuming the authenticated user is available.
        // Replace with your actual authentication logic.
        $user = User::find(auth()->id());

        if (!$user) {
            abort(404, 'User not found');
        }

        // Get the rank criteria
        $rankCriteria = [
            1 => ['total_team' => 25, 'ratio' => [10, 15], 'directs' => 1, 'reward' => 200],
            2 => ['total_team' => 50, 'ratio' => [20, 30], 'directs' => 2, 'reward' => 400],
            3 => ['total_team' => 100, 'ratio' => [40, 60], 'directs' => 2, 'reward' => 1000],
            4 => ['total_team' => 250, 'ratio' => [100, 150], 'directs' => 3, 'reward' => 2500],
            5 => ['total_team' => 500, 'ratio' => [200, 300], 'directs' => 3, 'reward' => 5000],
            6 => ['total_team' => 1000, 'ratio' => [400, 600], 'directs' => 4, 'reward' => 10000],
        ];

        // Get the criteria for the next rank
        $nextRankCriteria = $rankCriteria[$user->rank + 1] ?? null;

        // Get the rank history for the current user
        $rankHistory = RankHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $directs = User::where('sponsor_id', $user->id)->where('status', 1)->count();

        return view('user.reward.rank-achievement', [
            'user' => $user,
            'rankCriteria' => $rankCriteria,
            'nextRankCriteria' => $nextRankCriteria,
            'rankHistory' => $rankHistory,
            'directs' => $directs,
        ]);
    }
}
