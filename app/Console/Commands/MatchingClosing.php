<?php

namespace App\Console\Commands;

use App\Helpers\UserHelper;
use App\Models\AdminPowerBusiness;
use App\Models\MatchingBonusHistory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Binary matching closing — parameters match the dashboard prediction block
 * in DashboardController (pair value, pair income, capping, carry).
 */
class MatchingClosing extends Command
{
    protected $signature = 'matching:closing';

    protected $description = 'Run binary matching closing for topup and retopup business';

    public function handle(): int
    {
        $types = [
            'topup' => [
                'pair_value' => 10,
                'pair_income' => 100,
                'capping' => 200000,
                'source' => 'binary_topup',
            ],
            'retopup' => [
                'pair_value' => 10,
                'pair_income' => 150,
                'capping' => 0,
                'source' => 'binary_retopup',
            ],
        ];

        $credited = 0;

        foreach ($types as $type => $cfg) {
            $this->info("Matching closing: {$type}");

            // Precompute every member's leg BV once (same helper rank:closing uses).
            $legs = UserHelper::allTeamBusinessVolumesByType($type);

            $powerRows = AdminPowerBusiness::where('matching_type', $type)
                ->selectRaw('user_id, position, SUM(bv_amount) AS bv')
                ->groupBy('user_id', 'position')
                ->toBase()
                ->get();

            $power = [];
            foreach ($powerRows as $row) {
                $uid = (int) $row->user_id;
                $power[$uid][(int) $row->position] = (float) $row->bv;
            }

            $users = User::where('status', 1)->where('is_free_id', 0)->pluck('id');

            foreach ($users as $userId) {
                $userId = (int) $userId;
                $totalLeft = (float) (($legs[$userId][1] ?? 0) + ($power[$userId][1] ?? 0));
                $totalRight = (float) (($legs[$userId][2] ?? 0) + ($power[$userId][2] ?? 0));

                $usedPairs = (float) UserHelper::benPairsByType($userId, $type);
                $usedBusiness = $usedPairs * $cfg['pair_value'];

                $matchingBusiness = min($totalLeft, $totalRight);
                $eligibleBusiness = max(0, $matchingBusiness - $usedBusiness);
                $pairs = (int) floor($eligibleBusiness / $cfg['pair_value']);
                if ($pairs <= 0) {
                    continue;
                }

                $income = $pairs * $cfg['pair_income'];
                if ($cfg['capping'] > 0 && $income > $cfg['capping']) {
                    $income = $cfg['capping'];
                    $pairs = (int) floor($income / $cfg['pair_income']);
                    if ($pairs <= 0) {
                        continue;
                    }
                    $income = $pairs * $cfg['pair_income'];
                }

                $matchedBusiness = $pairs * $cfg['pair_value'];
                $availableLeft = max(0, $totalLeft - $usedBusiness);
                $availableRight = max(0, $totalRight - $usedBusiness);
                $leftFlush = max(0, $availableLeft - $matchedBusiness);
                $rightFlush = max(0, $availableRight - $matchedBusiness);

                try {
                    DB::transaction(function () use (
                        $userId, $type, $cfg, $pairs, $income, $matchedBusiness, $leftFlush, $rightFlush
                    ) {
                        MatchingBonusHistory::create([
                            'user_id' => $userId,
                            'bonus_amount' => $income,
                            'matched_pairs' => $pairs,
                            'matching_type' => $type,
                            'matched_business' => $matchedBusiness,
                            'left_flush' => $leftFlush,
                            'right_flush' => $rightFlush,
                            'date' => now()->toDateString(),
                        ]);

                        $wallet = UserWallet::firstOrNew(['u_code' => $userId]);
                        $opening = (float) ($wallet->c5 ?? 0);
                        $closing = $opening + $income;

                        $wallet->c5 = $closing;
                        $wallet->c1 = (float) ($wallet->c1 ?? 0) + $income;
                        // Carry forward (dashboard comment: c23 left / c24 right).
                        $wallet->c23 = $leftFlush;
                        $wallet->c24 = $rightFlush;
                        $wallet->save();

                        Transaction::create([
                            'u_code' => $userId,
                            'tx_type' => 'income',
                            'debit_credit' => 'credit',
                            'source' => $cfg['source'],
                            'wallet_type' => 'main_wallet',
                            'amount' => $income,
                            'date' => now(),
                            'status' => 1,
                            'open_wallet' => $opening,
                            'closing_wallet' => $closing,
                            'remark' => 'Binary matching (' . $type . ') ' . $pairs . ' pairs',
                            'added_on' => now(),
                        ]);
                    });
                    $credited++;
                } catch (\Throwable $e) {
                    Log::error('matching:closing failed', [
                        'user_id' => $userId,
                        'type' => $type,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Matching closing finished. Credits issued: {$credited}");

        return self::SUCCESS;
    }
}
