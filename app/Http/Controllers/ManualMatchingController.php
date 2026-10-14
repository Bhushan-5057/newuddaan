<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Helpers\UserHelper;
use Carbon\Carbon;
use DB;

class ManualMatchingController extends Controller
{
    // Show filter form
    public function index()
    {
        return view('admin.matching.index');
    }

    // Filter users based on admin input
    public function filterUsers(Request $request)
    {
        $request->validate([
            'month'      => 'required|date_format:Y-m',
            'pairs'      => 'required|integer|min:1',
            'date_from'  => 'required|date',
            'date_to'    => 'required|date|after_or_equal:date_from',
        ]);

        $month     = $request->month;
        $requiredPairs = $request->pairs;
        $dateFrom  = Carbon::parse($request->date_from);
        $dateTo    = Carbon::parse($request->date_to);

        // Users registered in the selected month
        $users = User::whereYear('created_at', Carbon::parse($month)->year)
            ->whereMonth('created_at', Carbon::parse($month)->month)
            ->where('status', 1)
            ->get();

        $eligibleUsers = [];
        $pairValue = 50; // 1 pair = 50 business

        foreach ($users as $user) {
            $leftBusiness  = UserHelper::teamBusinessVolume($user->id, 1, $dateFrom, $dateTo);
            $rightBusiness = UserHelper::teamBusinessVolume($user->id, 2, $dateFrom, $dateTo);

            $matchingBusiness = min($leftBusiness, $rightBusiness);
            $achievedPairs = floor($matchingBusiness / $pairValue);

            $eligibleUsers[] = [
                'user'       => $user,
                'pair_count' => $achievedPairs,
                'status'     => $achievedPairs >= $requiredPairs ? 'Achieved' : 'Not Achieved',
            ];
        }
        $userIds = array_map(fn($u) => $u['user']->id, $eligibleUsers);
        return view('admin.matching.index', compact('eligibleUsers', 'month', 'requiredPairs', 'dateFrom', 'dateTo', 'userIds'));
    }

    // Distribute income to eligible users
    public function distribute(Request $request)
    {
        $request->validate([
            'pairs'      => 'required|integer|min:1',
            'from_date'  => 'required|date',
            'to_date'    => 'required|date',
            'user_ids'   => 'required|array',
        ]);

        $pairs = $request->pairs;
        $incomePerPair = 6; // static income per pair
        $amount = $pairs * $incomePerPair;

        DB::beginTransaction();
        try {
            foreach ($request->user_ids as $userId) {
                $wallet = UserWallet::firstOrNew(['u_code' => $userId]);
                $wallet->c1  += $amount;
                $wallet->c15 += $amount;
                $wallet->save();

                Transaction::create([
                    'u_code'      => $userId,
                    'tx_type'     => 'income',
                    'debit_credit' => 'credit',
                    'source'      => 'adventure_bonus',
                    'wallet_type' => 'main_wallet',
                    'amount'      => $amount,
                    'date'        => now(),
                    'status'      => 1,
                    'remark'      => "Manual Matching Income ({$pairs} Pairs × {$incomePerPair}) From {$request->from_date} To {$request->to_date}",
                ]);
            }

            DB::commit();
            return back()->with('success', '✅ Income distributed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', '❌ Failed: ' . $e->getMessage());
        }
    }
}
