<?php

namespace App\Console\Commands;

use App\Helpers\RankRewardHelper;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Monthly / on-demand rank achievement closing.
 * Uses RankRewardHelper::processRankReward with precomputed business volumes.
 */
class RankClosing extends Command
{
    protected $signature = 'rank:closing';

    protected $description = 'Run rank achievement closing for all active members';

    public function handle(): int
    {
        $this->info('Rank closing started…');

        $ranks = RankRewardHelper::getRanks();
        if (empty($ranks)) {
            $this->warn('No ranks configured in RankRewardBonus.');
            return self::SUCCESS;
        }

        $business = RankRewardHelper::allBusinessVolumes();
        $users = User::where('status', 1)->where('is_free_id', 0)->pluck('id');
        $done = 0;

        foreach ($users as $userId) {
            $userId = (int) $userId;
            try {
                RankRewardHelper::processRankReward(
                    $userId,
                    $business[$userId] ?? ['left' => 0, 'right' => 0],
                    $ranks
                );
                $done++;
            } catch (\Throwable $e) {
                Log::error('rank:closing failed', [
                    'user_id' => $userId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Rank closing finished. Members processed: {$done}");

        return self::SUCCESS;
    }
}
