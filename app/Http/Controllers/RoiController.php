<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Models\WalletType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoiController extends Controller
{
    public function mineRoi()
    {
        $user = Auth::user();

        // Check if mined in last 24h
        if ($user->last_mine_at && Carbon::parse($user->last_mine_at)->diffInHours(now()) < 24) {
            return back()->with('error', 'You can mine again after 24 hours.');
        }

        // Get active orders
        $orders = Order::where('tx_user_id', $user->id)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->where('status', 1)
            ->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'No active orders found.');
        }

        foreach ($orders as $order) {
            $roiPercent = $order->roi ?? 1;
            $roiAmount = ($order->order_amount * $roiPercent) / 100;

            // Capping 2.5x
            $maxCap = $order->order_amount * 2.5;
            $creditedSoFar = Transaction::where('tx_u_code', $order->id)
                ->where('u_code', $user->id)
                ->where('tx_type', 'income')
                ->sum('amount');

            if ($creditedSoFar >= $maxCap) {
                $order->status = 2; // CAP reached
                $order->save();
                continue;
            }

            if ($creditedSoFar + $roiAmount > $maxCap) {
                $roiAmount = $maxCap - $creditedSoFar;
            }

            // Credit ROI to user wallet
            DB::transaction(function () use ($user, $order, $roiAmount, $roiPercent) {
                $wallet = UserWallet::firstOrNew(['u_code' => $user->id]);
                $wallet->c1 += $roiAmount;
                $wallet->c16 += $roiAmount;
                $wallet->save();

                $walletType = WalletType::where('wallet_column', 'c1')->first();
                Transaction::create([
                    'tx_u_code' => $order->id,
                    'u_code' => $user->id,
                    'tx_type' => 'income',
                    'debit_credit' => 'credit',
                    'source' => 'roi_income',
                    'wallet_type' => $walletType->name ?? 'Main Wallet',
                    'amount' => $roiAmount,
                    'date' => now(),
                    'status' => 1,
                    'remark' => "ROI {$roiPercent}% of {$order->order_amount}",
                    'added_on' => now(),
                    'updated_on' => now(),
                ]);
            });

            // Check if CAP is now reached
            $creditedAfter = Transaction::where('tx_u_code', $order->id)
                ->where('u_code', $user->id)
                ->where('tx_type', 'income')
                ->sum('amount');

            if ($creditedAfter >= $maxCap) {
                $order->status = 2; // CAP reached
                $order->save();
            }

            // Credit Level Income to uplines
            $this->creditLevelIncome($user, $roiAmount);
        }

        // Update last mine time
        $user->last_mine_at = now();
        $user->save();

        return back()->with('success', 'ROI and Level Income credited successfully.');
    }

    private function creditLevelIncome($user, $roiAmount)
    {
        $levelSettings = [
            1 => ['percent' => 20, 'direct' => 0],
            2 => ['percent' => 15, 'direct' => 3],
            3 => ['percent' => 10, 'direct' => 5],
            4 => ['percent' => 9,  'direct' => 7],
            5 => ['percent' => 8,  'direct' => 8],
            6 => ['percent' => 7,  'direct' => 9],
            7 => ['percent' => 6,  'direct' => 10],
            8 => ['percent' => 5,  'direct' => 11],
            9 => ['percent' => 4,  'direct' => 12],
            10 => ['percent' => 3, 'direct' => 13],
            11 => ['percent' => 3, 'direct' => 14],
            12 => ['percent' => 3, 'direct' => 14],
            13 => ['percent' => 3, 'direct' => 14],
            14 => ['percent' => 3, 'direct' => 14],
            15 => ['percent' => 1, 'direct' => 15],
        ];

        $current = $user;

        for ($level = 1; $level <= 15; $level++) {
            $sponsor = User::where('username', $current->sponsor_id)
                ->where('status', 1)
                ->first();

            if (!$sponsor) break;

            // Check active directs requirement
            $directUsers = User::where('sponsor_id', $sponsor->username)
                ->where('status', 1)
                ->pluck('id');

            $activeDirectCount = Order::whereIn('tx_user_id', $directUsers)
                ->where('status', 1)
                ->distinct('tx_user_id')
                ->count('tx_user_id');

            if ($activeDirectCount < $levelSettings[$level]['direct']) {
                $current = $sponsor;
                continue;
            }

            // Sponsor's orders for capping
            $orders = Order::where('tx_user_id', $sponsor->id)
                ->whereIn('tx_type', ['topup', 'retopup'])
                ->where('status', 1)
                ->get();

            foreach ($orders as $order) {
                $maxCap = $order->order_amount * 2.5;

                $creditedSoFar = Transaction::where('tx_u_code', $order->id)
                    ->where('u_code', $sponsor->id)
                    ->where('tx_type', 'income')
                    ->sum('amount');

                if ($creditedSoFar >= $maxCap) {
                    $order->status = 2;
                    $order->save();
                    continue;
                }

                $income = ($roiAmount * $levelSettings[$level]['percent']) / 100;

                if ($creditedSoFar + $income > $maxCap) {
                    $income = $maxCap - $creditedSoFar;
                }

                if ($income <= 0) {
                    continue;
                }

                // Credit Level Income
                $wallet = UserWallet::firstOrNew(['u_code' => $sponsor->id]);
                $wallet->c1 += $income;
                $wallet->c4 += $income;
                $wallet->save();

                $walletType = WalletType::where('wallet_column', 'c1')->first();
                Transaction::create([
                    'tx_u_code' => $order->id,
                    'u_code' => $sponsor->id,
                    'tx_type' => 'income',
                    'debit_credit' => 'credit',
                    'source' => 'level_income',
                    'wallet_type' => $walletType->name ?? 'Main Wallet',
                    'amount' => $income,
                    'date' => now(),
                    'status' => 1,
                    'remark' => "Level {$level} income from {$user->username} ({$levelSettings[$level]['percent']}%)",
                    'added_on' => now(),
                    'updated_on' => now(),
                ]);

                // If CAP now reached
                $creditedAfter = Transaction::where('tx_u_code', $order->id)
                    ->where('u_code', $sponsor->id)
                    ->where('tx_type', 'income')
                    ->sum('amount');

                if ($creditedAfter >= $maxCap) {
                    $order->status = 2;
                    $order->save();
                }
            }

            $current = $sponsor;
        }
    }
}
