<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\KycDetail;
use App\Helpers\UserHelper;
use Carbon\Carbon;

class DreamCarBonusController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $userId = $user->id;

        // Bonus Slabs: Pairs => Bonus $
        $bonusSlabs = [
            1000 => 4000,
            500  => 2000,
            300  => 800,
            100  => 400,
            50   => 200,
        ];

        $pairValue = 50; // 1 pair = 50 business
        $startDate = Carbon::now()->subMonth()->startOfMonth(); // Last month start
        $endDate   = Carbon::now()->subMonth()->endOfMonth();   // Last month end

        // Get last active order
        $lastOrder = Order::where('tx_user_id', $userId)
            ->whereIn('package_name', ['Investment', 'Subscription'])
            ->where('status', 1)
            ->orderByDesc('id')
            ->first();

        // KYC Approved check
        $kycApproved = KycDetail::where('user_id', $userId)
            ->where('kyc_status_identity', 'approved')
            ->where('kyc_status_bank', 'approved')
            ->exists();

        $status        = 'Not Eligible';
        $pairsMatched  = 0;
        $pairsRequired = 0;
        $bonusEarned   = 0;

        if ($user->rankrise_status == 1 && $lastOrder && $lastOrder->expire_date >= Carbon::today()) {
            // Calculate business last month
            $businessLeft  = UserHelper::teamBusinessVolume($userId, 1, $startDate, $endDate);
            $businessRight = UserHelper::teamBusinessVolume($userId, 2, $startDate, $endDate);

            $pairsMatched = floor(min($businessLeft, $businessRight) / $pairValue);

            // ✅ Find matching slab
            foreach ($bonusSlabs as $pair => $bonus) {
                if ($pairsMatched >= $pair) {
                    $bonusEarned   = $bonus;
                    $pairsRequired = 0;
                    break;
                } else {
                    $pairsRequired = $pair - $pairsMatched;
                    $bonusEarned   = 0;
                }
            }

            $status = 'Active';
        } elseif (!$lastOrder || $lastOrder->expire_date < Carbon::today()) {
            $status = 'Reinvest Required';
        }

        $userData = [
            'id'            => $user->id,
            'name'          => $user->name,
            'status'        => $status,
            'kyc_approved'  => $kycApproved,
            'pairsMatched'  => $pairsMatched,
            'pairsRequired' => $pairsRequired,
            'bonusEarned'   => $bonusEarned,
        ];

        return view('user.dream_car_bonus', compact('userData', 'bonusSlabs', 'startDate', 'endDate'));
    }
}
