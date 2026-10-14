<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Order;
use App\Models\UserWallet;
use App\Models\Transaction;
use App\Models\RankHistory;
use App\Models\AdminPowerBusiness;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\RankRewardBonus;

class RankRewardHelper
{
    /**
     * @param array{left: int|float, right: int|float}|null $business
     *        Topup BV per leg including admin power business. rank:closing
     *        passes it precomputed for every member; null works it out here.
     * @param array|null $ranks  Slabs from getRanks(), so a closing run reads them once.
     */
    public static function processRankReward($userId, ?array $business = null, ?array $ranks = null)
    {
        $user = User::find($userId);

        if (!$user || $user->status != 1) return;

        $ranks = $ranks ?? self::getRanks();

        if (empty($ranks)) return;

        // Business is the same for every rank, so it is read once. It used to be
        // re-queried inside the loop for each rank, which is what pushed
        // rank:closing past PHP's 30 second limit.
        $business = $business ?? self::businessVolumes($user->id);
        $totalLeftBV = $business['left'];
        $totalRightBV = $business['right'];

        // Achieved ranks, loaded once. Matched by rank id as well as name, so
        // renaming a rank in Rank Master does not make members achieve it again.
        $achieved = RankHistory::where('user_id', $user->id)->get(['rank_id', 'rank']);
        $achievedIds = $achieved->pluck('rank_id')->filter()->map(fn ($id) => (int) $id)->all();
        $achievedNames = $achieved->pluck('rank')->all();

        foreach ($ranks as $index => $rank) {

            if (in_array((int) $rank['id'], $achievedIds, true) || in_array($rank['name'], $achievedNames, true)) {
                continue;
            }

            // Initialize previous milestones sum
            $previousLeft = 0;
            $previousRight = 0;

            // If it's not the first rank, aggregate the targets of all previous steps
            if ($index > 0) {
                $previousLeft = array_sum(array_column(array_slice($ranks, 0, $index), 'left'));
                $previousRight = array_sum(array_column(array_slice($ranks, 0, $index), 'right'));
            }

            // Your cumulative targets to achieve this specific rank
            $requiredLeft = $previousLeft + $rank['left'];
            $requiredRight = $previousRight + $rank['right'];

            Log::info('Rank Checking with Cumulative Verification', [
                'user_id'         => $user->id,
                'rank'            => $rank['name'],
                'left_required'   => $requiredLeft,
                'right_required'  => $requiredRight,
                'total_left_bv'   => $totalLeftBV,
                'total_right_bv'  => $totalRightBV
            ]);

            // Milestone Target Validation Check using the old structural type logic
            if ($totalLeftBV < $requiredLeft || $totalRightBV < $requiredRight) {
                continue;
            }

            DB::beginTransaction();

            try {
                RankHistory::create([
                    'user_id'        => $user->id,
                    'rank'           => $rank['name'],
                    'slug'           => $rank['slug'],
                    'rank_id'        => $rank['id'],
                    'reward'         => $rank['reward'],
                    'bonus'          => $rank['bonus'],
                    'bonanza'        => $rank['bonanza'],
                    'duration'       => $rank['duration'],
                    'reward_status'  => 1,
                    'left_target'    => $requiredLeft,
                    'right_target'   => $requiredRight,
                    'left_achieved'  => $totalLeftBV,
                    'right_achieved' => $totalRightBV,
                    'status'         => 'completed',
                ]);

                $user->rank = $rank['name'];
                $user->rank_achieved_on = now();
                $user->save();

                /*
                |--------------------------------------------------------------------------
                | BONUS INCOME
                |--------------------------------------------------------------------------
                | Each rank pays only its own bonus amount, and only once.
                | bonus_status = 1 (one-shot 50+ BV business) also gets the NEXT
                | rank's bonus in advance as a separate credit. When that rank is
                | achieved later its bonus is already paid, so it is not paid again.
                */
                self::creditRankBonus($user, $rank, 'Reward Bonus');

                if ($user->bonus_status == 1 && $user->is_free_id == 0) {
                    $nextRank = $ranks[$index + 1] ?? null;

                    if ($nextRank) {
                        self::creditRankBonus($user, $nextRank, 'Advance Reward Bonus');
                    }
                }

                DB::commit();

                Log::info('Rank Achieved Successfully', [
                    'user_id' => $user->id,
                    'rank'    => $rank['name']
                ]);

            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('Rank Processing Failed', [
                    'user_id' => $user->id,
                    'rank'    => $rank['name'],
                    'error'   => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * exact structural map of matrix increments.
     */


    /**
     * One member's topup business per leg: team BV plus admin power business.
     */
    private static function businessVolumes(int $userId): array
    {
        $power = AdminPowerBusiness::where('user_id', $userId)
            ->where('matching_type', 'topup')
            ->selectRaw('position, SUM(bv_amount) AS bv')
            ->groupBy('position')
            ->pluck('bv', 'position');

        return [
            'left'  => (UserHelper::teamBusinessVolumeByType($userId, 1, 'topup') ?? 0) + (float) ($power[1] ?? 0),
            'right' => (UserHelper::teamBusinessVolumeByType($userId, 2, 'topup') ?? 0) + (float) ($power[2] ?? 0),
        ];
    }

    /**
     * Topup business for EVERY member at once, keyed by user id, in the shape
     * processRankReward() takes. Used by rank:closing: three queries in total
     * instead of several per member per rank.
     */
    public static function allBusinessVolumes(): array
    {
        $business = [];

        foreach (UserHelper::allTeamBusinessVolumesByType('topup') as $userId => $legs) {
            $business[$userId] = ['left' => $legs[1], 'right' => $legs[2]];
        }

        $power = AdminPowerBusiness::where('matching_type', 'topup')
            ->selectRaw('user_id, position, SUM(bv_amount) AS bv')
            ->groupBy('user_id', 'position')
            ->toBase()
            ->get();

        foreach ($power as $row) {
            $side = [1 => 'left', 2 => 'right'][(int) $row->position] ?? null;
            if ($side === null) {
                continue;
            }
            $userId = (int) $row->user_id;
            $business[$userId] = $business[$userId] ?? ['left' => 0, 'right' => 0];
            $business[$userId][$side] += (float) $row->bv;
        }

        return $business;
    }

    /**
     * Credit one rank's bonus to the reward wallet, at most once per member.
     * The rank id is stored in tx_record, so a bonus already paid in advance
     * is recognised even if the rank is renamed later.
     */
    private static function creditRankBonus(User $user, array $rank, string $label): void
    {
        $bonus = (float) ($rank['bonus'] ?? 0);

        if ($bonus <= 0) {
            return;
        }

        $marker = 'rank_bonus:' . $rank['id'];

        $alreadyPaid = Transaction::where('u_code', $user->id)
            ->where('source', 'reward')
            ->where('tx_record', $marker)
            ->exists();

        if ($alreadyPaid) {
            Log::info('Rank Bonus Already Paid', [
                'user_id' => $user->id,
                'rank'    => $rank['name'],
                'type'    => $label,
            ]);
            return;
        }

        $wallet = UserWallet::firstOrNew([
            'u_code' => $user->id
        ]);

        $opening = $wallet->c7 ?? 0;
        $closing = $opening + $bonus;

        $transaction = new Transaction([
            'u_code'         => $user->id,
            'tx_type'        => 'income',
            'source'         => 'reward',
            'debit_credit'   => 'credit',
            'wallet_type'    => 'reward_wallet',
            'amount'         => $bonus,
            'date'           => now(),
            'status'         => 1,
            'open_wallet'    => $opening,
            'closing_wallet' => $closing,
            'remark'         => "{$rank['name']} {$label}",
            'added_on'       => now(),
        ]);
        // tx_record is not mass-assignable on Transaction.
        $transaction->tx_record = $marker;
        $transaction->save();

        $wallet->c7 = $closing;
        $wallet->c1 = ($wallet->c1 ?? 0) + $bonus;
        $wallet->save();

        Log::info('Rank Bonus Credited', [
            'user_id' => $user->id,
            'rank'    => $rank['name'],
            'type'    => $label,
            'bonus'   => $bonus,
        ]);
    }

public static function getRanks()
    {
        // ID ના ક્રમ (ascending order) મુજબ ડેટાબેઝમાંથી એરે રિટર્ન કરશે
        return RankRewardBonus::orderBy('id', 'asc')->get()->toArray();
    }
}