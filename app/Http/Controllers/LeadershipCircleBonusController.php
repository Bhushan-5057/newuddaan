<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Helpers\UserHelper;
use Carbon\Carbon;

class LeadershipCircleBonusController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Slabs (highest first)
        $bonusSlabs = [
            1000 => 3000,
            500  => 1500,
            200  => 600,
            150  => 450,
            100  => 300,
            75   => 225,
            50   => 150,
            1    => 0,
        ];

        $pairValue = 50; // 1 pair = 50 volume
        $startDate = Carbon::now()->subMonth()->startOfMonth();
        $endDate   = Carbon::now()->subMonth()->endOfMonth();

        // Default data
        $userData = [
            'id'            => $user->id,
            'name'          => $user->name,
            'status'        => 'Not Eligible',
            'pairsMatched'  => 0,
            'pairsRequired' => 0,
            'bonusEarned'   => 0,
        ];

        // Income disabled
        if ((int)$user->income_status === 0) {
            $userData['status'] = 'Not Eligible (Income Disabled)';
            return view('user.leadership_circle_bonus', compact('userData', 'bonusSlabs', 'startDate', 'endDate'));
        }

        // Active package check
        $lastOrder = Order::where('tx_user_id', $userId)
            ->whereIn('package_name', ['Investment', 'Subscription'])
            ->where('status', 1)
            ->latest('id')
            ->first();

        if (!$lastOrder || $lastOrder->expire_date < Carbon::today()) {
            $userData['status'] = 'Reinvest Required';
            return view('user.leadership_circle_bonus', compact('userData', 'bonusSlabs', 'startDate', 'endDate'));
        }

        // Business calculation (last month)
        $businessLeft  = UserHelper::teamBusinessVolume($userId, 1, $startDate, $endDate);
        $businessRight = UserHelper::teamBusinessVolume($userId, 2, $startDate, $endDate);

        $pairsMatched = (int) floor(min($businessLeft, $businessRight) / $pairValue);

        // Slab check
        $bonusEarned   = 0;
        $pairsRequired = 0;

        foreach ($bonusSlabs as $pairs => $bonus) {
            if ($pairsMatched >= $pairs) {
                $bonusEarned   = $bonus;
                $pairsRequired = 0;
                break;
            } else {
                $pairsRequired = $pairs - $pairsMatched;
                $bonusEarned   = 0;
            }
        }

        // Final user data
        $userData = [
            'id'            => $user->id,
            'name'          => $user->name,
            'status'        => 'Active',
            'pairsMatched'  => $pairsMatched,
            'pairsRequired' => $pairsRequired,
            'bonusEarned'   => $bonusEarned,
        ];

        return view('user.leadership_circle_bonus', compact('userData', 'bonusSlabs', 'startDate', 'endDate'));
    }
}
