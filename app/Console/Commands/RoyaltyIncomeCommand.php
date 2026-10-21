<?php

namespace App\Console\Commands;

use App\Models\RoyaltyIncome;
use App\Models\Transaction;
use App\Models\UserWallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Royalty / monthly hold income release — pays daily_amount for active
 * royalty_incomes rows that are still within their date window.
 */
class RoyaltyIncomeCommand extends Command
{
    protected $signature = 'royalty:income';

    protected $description = 'Release royalty / hold income for active royalty schedules';

    public function handle(): int
    {
        $this->info('Royalty income release started…');

        $today = now()->toDateString();
        $rows = RoyaltyIncome::query()
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
            })
            ->get();

        $paid = 0;

        foreach ($rows as $row) {
            $amount = (float) ($row->daily_amount ?? 0);
            if ($amount <= 0) {
                continue;
            }

            // One credit per schedule per calendar day.
            $already = Transaction::where('u_code', $row->user_id)
                ->where('source', 'royalty')
                ->whereDate('date', $today)
                ->where('tx_record', 'royalty:' . $row->id)
                ->exists();

            if ($already) {
                continue;
            }

            try {
                DB::transaction(function () use ($row, $amount, $today, &$paid) {
                    $wallet = UserWallet::firstOrNew(['u_code' => $row->user_id]);
                    $opening = (float) ($wallet->c1 ?? 0);
                    $closing = $opening + $amount;

                    $tx = new Transaction([
                        'u_code' => $row->user_id,
                        'tx_type' => 'income',
                        'debit_credit' => 'credit',
                        'source' => 'royalty',
                        'wallet_type' => 'main_wallet',
                        'amount' => $amount,
                        'date' => $today,
                        'status' => 1,
                        'open_wallet' => $opening,
                        'closing_wallet' => $closing,
                        'remark' => 'Royalty income (' . ($row->rank ?? 'schedule') . ')',
                        'added_on' => now(),
                    ]);
                    $tx->tx_record = 'royalty:' . $row->id;
                    $tx->save();

                    $wallet->c1 = $closing;
                    $wallet->save();

                    $row->days_paid = (int) ($row->days_paid ?? 0) + 1;
                    if ($row->end_date && $row->end_date->toDateString() <= $today) {
                        $row->status = 'completed';
                    }
                    $row->save();

                    $paid++;
                });
            } catch (\Throwable $e) {
                Log::error('royalty:income failed', [
                    'royalty_id' => $row->id,
                    'user_id' => $row->user_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Royalty income release finished. Payments: {$paid}");

        return self::SUCCESS;
    }
}
