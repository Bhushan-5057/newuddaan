<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Order;
use App\Models\MatchingBonusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;



class UserHelper
{



   public static function getUserTotalBV($userId)
    {
        return Order::where('tx_user_id', $userId)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->sum('order_bv');
    }


    /**
     * Get all team members recursively under a user.
     */
    public static function getTeamMembers($username, &$team = [])
    {
        // Walks the sponsor tree a generation at a time rather than issuing one
        // query per member. Only active (status = 1) members are included, and
        // an inactive member stops that branch - same semantics as before.
        $current = [$username];
        $seen = [$username => true];

        while (!empty($current)) {
            $directs = User::whereIn('sponsor_id', $current)
                ->where('status', 1)
                ->get(['id', 'username']);

            $current = [];
            foreach ($directs as $direct) {
                if (isset($seen[$direct->username])) {
                    continue;
                }
                $seen[$direct->username] = true;

                if (!in_array($direct->id, $team)) {
                    $team[] = $direct->id;
                }
                $current[] = $direct->username;
            }
        }

        return $team;
    }

    /**
     * Get total team size (count of downline).
     */
    public static function getTeamCount($username)
    {
        $team = self::getTeamMembers($username);
        return count($team);
    }

    /**
     * Get total business (orders) of full team.
     */
    public static function getTeamBusiness($username)
    {
        $team = self::getTeamMembers($username);

        if (empty($team)) {
            return 0;
        }

        return Order::whereIn('tx_user_id', $team)
            ->where('status', 1)
            ->sum('order_amount');
    }

    /**
     * Get all direct legs of a user (only 1st level downlines).
     */
    public static function getDirectLegs($username)
    {
        return User::where('sponsor_id', $username)->where('status', 1)->get();
    }



    public static function getEligibleDownlineUserIds($userId, $userRankId)
    {
        // One query per generation instead of one per member, and no per-member
        // logging (that alone wrote thousands of log lines per page load).
        $eligibleIds = [];
        $current = [$userId];
        $seen = [$userId => true];

        while (!empty($current)) {
            $downlines = User::whereIn('u_sponsor', $current)
                ->where('status', 1)
                ->get(['id', 'u_sponsor', 'royalty_rank_id']);

            $current = [];
            foreach ($downlines as $downline) {
                if (isset($seen[$downline->id])) {
                    continue;
                }
                $seen[$downline->id] = true;

                // A downline at or above the caller's rank blocks that branch.
                if ($downline->royalty_rank_id >= $userRankId) {
                    continue;
                }

                $eligibleIds[] = $downline->id;
                $current[] = $downline->id;
            }
        }

        Log::debug("getEligibleDownlineUserIds complete", [
            'user_id' => $userId,
            'user_rank_id' => $userRankId,
            'eligible_ids_count' => count($eligibleIds),
        ]);

        return $eligibleIds;
    }

    /**
     * Calculate business of eligible downlines only
     */
    public static function calculateEligibleBusiness($userid, $userRankIndex, $startDate = null, $endDate = null)
    {
        $downlineIds = self::getEligibleDownlineUserIds($userid, $userRankIndex);

        if (empty($downlineIds)) {
            return 0;
        }

        $totalBusiness = Order::whereIn('tx_user_id', $downlineIds)
            ->whereIn('tx_type', ['topup', 'retopup', 'subcription'])
            ->where('status', 1)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('added_on', [$startDate, $endDate]);
            })
            ->sum('order_amount');

        Log::debug("Calculated eligible business", [
            'user_id' => $userid,
            'total_business' => $totalBusiness,
            'downline_count' => count($downlineIds),
        ]);

        return $totalBusiness;
    }





    public function calculateActiveDirects($user)
    {
        $lastPackage = Order::where('tx_user_id', $user->id)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->latest('added_on')->first();

        if (!$lastPackage) return 0;

        return User::where('sponsor_id', $user->username)
            ->where('status', 1)
            ->whereHas('orders', function ($query) use ($lastPackage) {
                $query->whereIn('tx_type', ['topup', 'retopup'])
                    ->where(function ($q) use ($lastPackage) {
                        $q->whereBetween('added_on', [$lastPackage->added_on, $lastPackage->expire_date])
                            ->orWhereBetween('expire_date', [$lastPackage->added_on, $lastPackage->expire_date]);
                    });
            })->count();
    }

    /**
     * Get count of rank achievers in a specific leg (Position).
     */
    public static function countRankAchievers($userId, $position, $rankName, $fromDate, $toDate)
    {
       
        $downlineIds = self::teamByPosition($userId, $position);

        if (empty($downlineIds)) {
            return 0;
        }

       
        return \App\Models\RankHistory::whereIn('user_id', $downlineIds)
            ->where('rank', $rankName)
            ->whereBetween('created_at', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay()
            ])
            ->count();
    }



    
    public static function teamBusinessVolume(int $userId, int $position, $startDate = null, $endDate = null)
    {
       
        $teamUsers = self::teamByPosition($userId, $position);

        if (empty($teamUsers)) {
            return 0;
        }

       
        $query = Order::whereIn('tx_user_id', $teamUsers)
            ->whereIn('tx_type', ['topup', 'retopup']) 
            ->where('status', 1);

      
        if ($startDate && $endDate) {
            $query->whereBetween('added_on', [
                \Carbon\Carbon::parse($startDate)->startOfDay(),
                \Carbon\Carbon::parse($endDate)->endOfDay()
            ]);
        }

        return (int) $query->sum('order_amount');
    }


    public static function teamBusinessVolumeByType(int $userId,int $position,string $txType)
{
    // teamByPosition() is memoised, so the leg is resolved once per request.
    //
    // Deliberately an IN(...) list rather than a join against the recursive CTE:
    // orders.u_code is a varchar while users.id is an int, so joining the two
    // forces an implicit conversion that discards the index. Measured on this
    // dataset the join form took ~20s against ~2s for the list.
    $teamUsers = self::teamByPosition($userId, $position);

    if (empty($teamUsers)) return 0;

    return (int) Order::whereIn('u_code', $teamUsers)
        ->where('tx_type', $txType)
        ->where('status', 1)
        ->sum('order_bv');
}


/**
 * Left/right team business for EVERY user at once, for the closing crons.
 *
 * Same figures as teamBusinessVolumeByType() - a leg is the child at that
 * position plus all of its descendants by parent_id, counting orders with
 * status 1 - but built from two queries and one bottom-up pass over the tree
 * instead of a recursive query and a large IN(...) sum per user. Measured on
 * this dataset: 0.04s for the whole tree, identical for all 945 active users.
 *
 * @return array<int, array{1: int, 2: int}>  userId => [1 => left, 2 => right]
 */
public static function allTeamBusinessVolumesByType(string $txType): array
{
    // Own BV per user, kept in hundredths so repeated additions stay exact.
    $own = [];
    $rows = Order::where('tx_type', $txType)
        ->where('status', 1)
        ->groupBy('u_code')
        ->selectRaw('u_code, SUM(order_bv) AS bv')
        ->toBase()
        ->get();

    foreach ($rows as $row) {
        $id = (int) $row->u_code;
        $own[$id] = ($own[$id] ?? 0) + (int) round((float) $row->bv * 100);
    }

    $ids = [];
    $children = [];
    foreach (DB::table('users')->select('id', 'parent_id', 'position')->get() as $node) {
        $ids[] = (int) $node->id;
        if ((int) $node->parent_id > 0) {
            $children[(int) $node->parent_id][] = [(int) $node->id, (int) $node->position];
        }
    }

    // Subtree totals, children before parents. Iterative on purpose: the tree
    // is a chain thousands of levels deep, too deep for PHP recursion.
    $visited = [];
    $subtree = [];
    foreach ($ids as $startId) {
        if (isset($visited[$startId])) {
            continue;
        }

        $stack = [[$startId, false]];
        while ($stack) {
            [$id, $childrenDone] = array_pop($stack);

            if ($childrenDone) {
                $sum = $own[$id] ?? 0;
                foreach ($children[$id] ?? [] as [$childId]) {
                    $sum += $subtree[$childId] ?? 0;
                }
                $subtree[$id] = $sum;
                continue;
            }

            if (isset($visited[$id])) {
                continue;
            }
            $visited[$id] = true;

            $stack[] = [$id, true];
            foreach ($children[$id] ?? [] as [$childId]) {
                if (!isset($visited[$childId])) {
                    $stack[] = [$childId, false];
                }
            }
        }
    }

    $legs = [];
    foreach ($ids as $id) {
        $left = 0;
        $right = 0;
        foreach ($children[$id] ?? [] as [$childId, $position]) {
            if ($position === 1) {
                $left += $subtree[$childId] ?? 0;
            } elseif ($position === 2) {
                $right += $subtree[$childId] ?? 0;
            }
        }
        $legs[$id] = [1 => intdiv($left, 100), 2 => intdiv($right, 100)];
    }

    return $legs;
}

public static function benPairsByType(int $userId,string $type)
{
    return (int) MatchingBonusHistory::where('user_id',$userId)
        ->where('matching_type',$type)
        ->sum('matched_pairs');
}


    /**
     * Get team IDs by position (left = 1, right = 2).
     */
    public static function teamByPosition(int $userId, int $position): array
    {
        // Single recursive query, memoised per request. The level-at-a-time
        // version below issued one query per generation, and this tree is a
        // chain thousands of levels deep - the dashboard alone called this four
        // times, for ~23,000 queries per page load.
        return TreeHelper::legIds($userId, $position);
    }

    /**
     * Get all binary downline (recursively).
     */
    public static function myBinary(int $userId): array
    {
        return TreeHelper::descendantIds($userId);
    }

    /**
     * Get total used pairs (business) for user from binary_matching table.
     */
    public static function benPairs(int $userId, string $column = 'matched_pairs'): int
    {
        return (int) MatchingBonusHistory::where('user_id', $userId)->sum($column);
    }

    /**
     * Get active directs of a user by position (left=1, right=2).
     */
    public static function myActivesByPosition(int $userId, int $position)
    {
        return User::where('u_sponsor', $userId)
            ->where('position', $position)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();
    }
}
