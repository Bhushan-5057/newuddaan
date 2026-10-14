<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Fast binary-tree / sponsor-tree traversal.
 *
 * The original helpers walked the tree one user at a time (User::find() inside
 * a BFS loop): one query per downline member, which measured ~6,500 queries and
 * ~5 seconds for a single call on this dataset - and the user dashboard called
 * it twice.
 *
 * This tree is also very deep rather than bushy (most nodes have a single
 * child, giving a chain thousands of levels long), so walking it a generation
 * at a time does not help much either. Everything here therefore resolves in a
 * single recursive CTE, with a level-at-a-time fallback for servers that do not
 * support one. Results are memoised for the lifetime of the request.
 */
class TreeHelper
{
    /** @var array<string, mixed> */
    private static array $memo = [];

    /** @var bool|null Whether the connection supports WITH RECURSIVE. */
    private static ?bool $supportsCte = null;

    /** @var int|null Recursion steps the connection will actually perform. */
    private static ?int $recursionBudget = null;

    /**
     * All descendant ids below $userId following parent_id (the binary tree),
     * excluding $userId itself.
     *
     * @return array<int, int>
     */
    public static function descendantIds(int $userId): array
    {
        $key = "desc:{$userId}";
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }

        return self::$memo[$key] = self::walk('parent_id', 'id', [$userId], true);
    }

    /**
     * $userId plus everything below it (parent_id tree).
     *
     * @return array<int, int>
     */
    public static function selfAndDescendantIds(int $userId): array
    {
        return array_merge([$userId], self::descendantIds($userId));
    }

    /**
     * Ids of one whole leg: the direct left/right child of $userId plus all of
     * its descendants. $position: 1 = left, 2 = right.
     *
     * Uses parent_id/position rather than the denormalised left_id/right_id
     * columns, so a leg is still counted correctly when those columns have
     * drifted out of sync.
     *
     * @return array<int, int>
     */
    public static function legIds(int $userId, int $position): array
    {
        $key = "leg:{$userId}:{$position}";
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }

        $childIds = DB::table('users')
            ->where('parent_id', $userId)
            ->where('position', $position)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (empty($childIds)) {
            return self::$memo[$key] = [];
        }

        $ids = array_merge($childIds, self::walk('parent_id', 'id', $childIds, true));

        return self::$memo[$key] = array_values(array_unique($ids));
    }

    /**
     * All descendant usernames below $username following sponsor_id (the
     * sponsor/referral tree), excluding $username itself.
     *
     * @return array<int, string>
     */
    public static function sponsorDescendantUsernames(string $username): array
    {
        $key = "sponsor:{$username}";
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }

        return self::$memo[$key] = self::walk('sponsor_id', 'username', [$username], false);
    }

    /**
     * All descendant ids below $username following sponsor_id.
     *
     * @return array<int, int>
     */
    public static function sponsorDescendantIds(string $username): array
    {
        $key = "sponsorIds:{$username}";
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }

        $usernames = self::sponsorDescendantUsernames($username);
        if (empty($usernames)) {
            return self::$memo[$key] = [];
        }

        $ids = [];
        foreach (array_chunk($usernames, 2000) as $chunk) {
            foreach (DB::table('users')->whereIn('username', $chunk)->pluck('id') as $id) {
                $ids[] = (int) $id;
            }
        }

        return self::$memo[$key] = $ids;
    }

    /**
     * Sponsor-tree descendants of $username with their level (1 = directs).
     * Returns User models so callers can read arbitrary columns.
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\User>
     */
    public static function sponsorDescendantsWithLevels(string $username)
    {
        $key = "sponsorLevels:{$username}";
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }

        $levels = self::walkWithLevels('sponsor_id', 'username', $username);
        if (empty($levels)) {
            return self::$memo[$key] = collect();
        }

        // Fetch the models in bulk, then stamp the level onto each.
        $models = collect();
        foreach (array_chunk(array_keys($levels), 2000) as $chunk) {
            foreach (\App\Models\User::whereIn('username', $chunk)->get() as $user) {
                $user->level = $levels[$user->username] ?? null;
                $models->push($user);
            }
        }

        return self::$memo[$key] = $models->sortBy('level')->values();
    }

    /**
     * Collect every descendant reachable from $roots.
     *
     * @param  string  $parentColumn  column on the child pointing at its parent
     * @param  string  $keyColumn     column the parent is identified by
     * @param  array   $roots         starting values of $keyColumn
     * @param  bool    $castInt       cast results to int
     * @return array
     */
    private static function walk(string $parentColumn, string $keyColumn, array $roots, bool $castInt): array
    {
        if (empty($roots)) {
            return [];
        }

        if (self::supportsCte()) {
            try {
                return self::walkViaCte($parentColumn, $keyColumn, $roots, $castInt);
            } catch (\Throwable $e) {
                // Fall through to the portable implementation rather than
                // failing the page; log once so the cause is visible.
                self::$supportsCte = false;
                Log::warning('TreeHelper: recursive CTE failed, falling back to level walk', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return self::walkViaLevels($parentColumn, $keyColumn, $roots, $castInt);
    }

    /**
     * Single-query traversal. One round trip regardless of tree depth.
     */
    private static function walkViaCte(string $parentColumn, string $keyColumn, array $roots, bool $castInt): array
    {
        // Never recurse deeper than the server actually allows. MariaDB stops
        // silently at its cap, so going past it would quietly lose members.
        // Instead the walk is chunked: each round descends as far as the cap
        // permits, then restarts from the deepest nodes it reached.
        $chunkDepth = max(1, self::recursionBudget() - 2);

        $all = [];
        $seen = [];
        foreach ($roots as $r) {
            $seen[(string) $r] = true;
        }

        $frontier = $roots;
        $rounds = 0;

        while (!empty($frontier) && $rounds++ < 10000) {
            $placeholders = implode(',', array_fill(0, count($frontier), '?'));

            // `depth` lets us find the nodes this round stopped at, and caps
            // the recursion at a depth we know the server can reach.
            $sql = "WITH RECURSIVE tree (node, depth) AS ("
                 . " SELECT `{$keyColumn}`, 1 FROM `users`"
                 . "  WHERE `{$parentColumn}` IN ({$placeholders})"
                 . "    AND `{$keyColumn}` IS NOT NULL"
                 . " UNION ALL"
                 . " SELECT u.`{$keyColumn}`, t.depth + 1 FROM `users` u"
                 . "   JOIN tree t ON u.`{$parentColumn}` = t.node"
                 . "  WHERE u.`{$keyColumn}` IS NOT NULL"
                 . "    AND t.depth < {$chunkDepth}"
                 . ") SELECT node, MAX(depth) AS depth FROM tree GROUP BY node";

            $rows = DB::select($sql, array_values($frontier));

            if (empty($rows)) {
                break;
            }

            $next = [];
            foreach ($rows as $row) {
                $key = (string) $row->node;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $all[] = $castInt ? (int) $row->node : $key;

                // Nodes the chunk bottomed out on seed the next round.
                if ((int) $row->depth >= $chunkDepth) {
                    $next[] = $row->node;
                }
            }

            $frontier = $next;
        }

        return $all;
    }

    /**
     * How many recursion steps this connection will actually perform.
     *
     * Read once per request. supportsCte() has already tried to raise it.
     */
    private static function recursionBudget(): int
    {
        if (self::$recursionBudget !== null) {
            return self::$recursionBudget;
        }

        try {
            $version = (string) DB::selectOne('SELECT VERSION() AS v')->v;
            $variable = stripos($version, 'mariadb') !== false
                ? 'max_recursive_iterations'
                : 'cte_max_recursion_depth';

            $row = DB::selectOne("SHOW VARIABLES LIKE '{$variable}'");
            $limit = $row ? (int) $row->Value : 1000;
        } catch (\Throwable $e) {
            $limit = 1000;
        }

        // Stay well inside the cap; huge values are pointless as a chunk size.
        return self::$recursionBudget = max(50, min($limit, 50000));
    }

    /**
     * Portable fallback: one query per generation. Dedupe on the way down
     * protects against cycles in malformed data.
     */
    private static function walkViaLevels(string $parentColumn, string $keyColumn, array $roots, bool $castInt): array
    {
        $all = [];
        $seen = [];
        foreach ($roots as $r) {
            $seen[(string) $r] = true;
        }
        $current = $roots;

        // Hard stop well above any legitimate depth, so corrupt data cannot
        // spin forever.
        $guard = 100000;

        while (!empty($current) && $guard-- > 0) {
            $next = [];
            foreach (array_chunk($current, 2000) as $chunk) {
                $children = DB::table('users')
                    ->whereIn($parentColumn, $chunk)
                    ->whereNotNull($keyColumn)
                    ->pluck($keyColumn)
                    ->all();

                foreach ($children as $child) {
                    $k = (string) $child;
                    if ($k === '' || isset($seen[$k])) {
                        continue;
                    }
                    $seen[$k] = true;
                    $value = $castInt ? (int) $child : $k;
                    $all[] = $value;
                    $next[] = $value;
                }
            }
            $current = $next;
        }

        return $all;
    }

    /**
     * Traversal that also records the depth of each descendant.
     *
     * @return array<string, int> key => level (1 = direct child)
     */
    private static function walkWithLevels(string $parentColumn, string $keyColumn, string $root): array
    {
        $levels = [];
        $seen = [(string) $root => true];
        $current = [$root];
        $level = 0;
        $guard = 100000;

        while (!empty($current) && $guard-- > 0) {
            $level++;
            $next = [];

            foreach (array_chunk($current, 2000) as $chunk) {
                $children = DB::table('users')
                    ->whereIn($parentColumn, $chunk)
                    ->whereNotNull($keyColumn)
                    ->pluck($keyColumn)
                    ->all();

                foreach ($children as $child) {
                    $k = (string) $child;
                    if ($k === '' || isset($seen[$k])) {
                        continue;
                    }
                    $seen[$k] = true;
                    $levels[$k] = $level;
                    $next[] = $child;
                }
            }

            $current = $next;
        }

        return $levels;
    }

    /**
     * How deep this tree can get. The binary tree here is a chain thousands of
     * levels long, so the server's recursion cap has to clear it comfortably.
     */
    private const RECURSION_HEADROOM = 1000000;

    /**
     * Can this connection run a recursive CTE over the FULL depth of the tree?
     *
     * Version support is not enough on its own. MariaDB caps recursion with
     * `max_recursive_iterations` (shared hosting often ships 1000) and then
     * stops SILENTLY - no error, just a short result. A capped server returned
     * 1021 of 6509 downline members here, which quietly under-reported every
     * team count. MySQL caps with `cte_max_recursion_depth` and errors instead.
     *
     * So: raise the cap for this session, read it back, and only use the CTE if
     * it really is high enough. Otherwise fall back to the level-at-a-time walk,
     * which has no depth limit.
     */
    private static function supportsCte(): bool
    {
        if (self::$supportsCte !== null) {
            return self::$supportsCte;
        }

        try {
            $version = (string) DB::selectOne('SELECT VERSION() AS v')->v;
        } catch (\Throwable $e) {
            return self::$supportsCte = false;
        }

        $isMariaDb = stripos($version, 'mariadb') !== false;

        if ($isMariaDb) {
            preg_match('/(\d+)\.(\d+)\.(\d+)/', $version, $m);
            $num = isset($m[1]) ? ((int) $m[1] * 10000 + (int) $m[2] * 100 + (int) $m[3]) : 0;
            if ($num < 100202) {
                return self::$supportsCte = false;
            }
        } else {
            preg_match('/(\d+)\.(\d+)/', $version, $m);
            if ((int) ($m[1] ?? 0) < 8) {
                return self::$supportsCte = false;
            }
        }

        $variable = $isMariaDb ? 'max_recursive_iterations' : 'cte_max_recursion_depth';

        // Try to lift the cap for this connection. Failure is not fatal - the
        // check below decides.
        try {
            DB::statement("SET SESSION {$variable} = " . self::RECURSION_HEADROOM);
        } catch (\Throwable $e) {
            // Read-only / restricted server; fall through to the check.
        }

        try {
            $row = DB::selectOne("SHOW VARIABLES LIKE '{$variable}'");
            $limit = $row ? (int) $row->Value : 0;
        } catch (\Throwable $e) {
            return self::$supportsCte = false;
        }

        // A low cap no longer disables the CTE - walkViaCte() chunks the walk
        // so it never asks the server to recurse past what it allows. Record
        // the effective budget here so the chunk size matches.
        self::$recursionBudget = max(50, min($limit, 50000));

        if ($limit < self::RECURSION_HEADROOM) {
            Log::info('TreeHelper: server caps CTE recursion; walking in chunks', [
                'variable' => $variable,
                'limit' => $limit,
            ]);
        }

        return self::$supportsCte = true;
    }

    /**
     * Count of leg members per status, e.g. [1 => 120, 0 => 43].
     *
     * @return array<int, int>
     */
    public static function legStatusCounts(int $userId, int $position): array
    {
        $key = "legStatus:{$userId}:{$position}";
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }

        // Counted from legIds() rather than its own recursive CTE.
        //
        // A one-shot CTE here would be subject to the server's recursion cap,
        // and MariaDB truncates silently - a capped host reported 1,022 of
        // 6,573 members. legIds() walks in chunks sized to that cap, so it is
        // correct everywhere, and it is memoised for the request anyway.
        $ids = self::legIds($userId, $position);
        $out = [];
        foreach (array_chunk($ids, 20000) as $chunk) {
            $rows = DB::table('users')
                ->selectRaw('status, COUNT(*) AS c')
                ->whereIn('id', $chunk)
                ->groupBy('status')
                ->get();
            foreach ($rows as $row) {
                $out[(int) $row->status] = ($out[(int) $row->status] ?? 0) + (int) $row->c;
            }
        }

        return self::$memo[$key] = $out;
    }

    /**
     * NOTE: aggregating orders through this CTE is a trap on this schema.
     * orders.u_code / orders.tx_user_id are varchars while users.id is an
     * int, so joining them forces an implicit conversion that throws away
     * the index - measured ~20s versus ~2s for an IN(...) list built from
     * legIds(). Aggregate orders with legIds(); use the CTE only for joins
     * against users, where the types match (see legStatusCounts()).
     */

    /**
     * Is $nodeId the same as, or somewhere below, $ancestorId in the binary
     * tree (parent_id)?
     *
     * Walks UP from the node rather than materialising the ancestor's whole
     * downline, so the cost is one query regardless of how large that downline
     * is. Used to stop a user drilling into a branch that is not theirs.
     */
    public static function isSelfOrDescendantOf(int $nodeId, int $ancestorId): bool
    {
        if ($nodeId === $ancestorId) {
            return true;
        }

        $key = "anc:{$nodeId}:{$ancestorId}";
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }

        if (self::supportsCte()) {
            try {
                $sql = "WITH RECURSIVE up (node, parent) AS ("
                     . " SELECT id, parent_id FROM users WHERE id = ?"
                     . " UNION"
                     . " SELECT u.id, u.parent_id FROM users u JOIN up ON u.id = up.parent"
                     . ") SELECT 1 AS hit FROM up WHERE node = ? LIMIT 1";

                $hit = DB::selectOne($sql, [$nodeId, $ancestorId]) !== null;

                return self::$memo[$key] = $hit;
            } catch (\Throwable $e) {
                Log::warning('TreeHelper::isSelfOrDescendantOf CTE failed', ['error' => $e->getMessage()]);
            }
        }

        // Portable fallback: step up one parent at a time.
        $current = $nodeId;
        $seen = [$nodeId => true];
        $guard = 100000;

        while ($current && $guard-- > 0) {
            $parent = DB::table('users')->where('id', $current)->value('parent_id');
            if (!$parent) {
                break;
            }
            $parent = (int) $parent;
            if ($parent === $ancestorId) {
                return self::$memo[$key] = true;
            }
            if (isset($seen[$parent])) {
                break; // cycle in malformed data
            }
            $seen[$parent] = true;
            $current = $parent;
        }

        return self::$memo[$key] = false;
    }

    /**
     * The chain of users from $ancestorId down to $nodeId, inclusive, as
     * ['id' => .., 'username' => .., 'name' => ..] in top-down order.
     *
     * Returns an empty array when $nodeId is not under $ancestorId.
     *
     * @return array<int, array{id:int, username:string, name:?string}>
     */
    public static function pathBetween(int $ancestorId, int $nodeId): array
    {
        $chain = [];
        $current = $nodeId;
        $seen = [];
        $guard = 100000;

        // Collect ids upward first, then resolve them in one query.
        while ($current && $guard-- > 0) {
            if (isset($seen[$current])) {
                return []; // cycle
            }
            $seen[$current] = true;
            $chain[] = $current;

            if ($current === $ancestorId) {
                break;
            }

            $parent = DB::table('users')->where('id', $current)->value('parent_id');
            $current = $parent ? (int) $parent : null;
        }

        if (empty($chain) || end($chain) !== $ancestorId) {
            return [];
        }

        $chain = array_reverse($chain);

        $rows = DB::table('users')
            ->whereIn('id', $chain)
            ->get(['id', 'username', 'name'])
            ->keyBy('id');

        $out = [];
        foreach ($chain as $id) {
            if (!isset($rows[$id])) {
                continue;
            }
            $out[] = [
                'id' => (int) $id,
                'username' => (string) $rows[$id]->username,
                'name' => $rows[$id]->name,
            ];
        }

        return $out;
    }

    /**
     * Drop every memoised result. Useful in long-running commands that mutate
     * the tree between reads.
     */
    public static function flush(): void
    {
        self::$memo = [];
    }

    /**
     * Forget the cached server-capability probe as well. Only needed in tests
     * that simulate a differently configured server.
     */
    public static function flushCapabilities(): void
    {
        self::$supportsCte = null;
        self::$recursionBudget = null;
    }
}
