<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletType;
use App\Models\CustomField;
use App\Models\AdvanceInfo;
use App\Models\CustomFieldValue;
use App\Models\UserWallet;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use App\Models\Transaction;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class GenealogyController extends Controller
{
    /**
     * Rows per page for the team listings. These pages used to render an
     * entire leg - thousands of rows - in one response.
     */
    private const TEAM_PAGE_SIZE = 50;

    /**
     * Display a listing of direct users with filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
      public function index(Request $request)
    {
        $userId = auth()->id();
    
        $sponsorUsername = User::where("id", $userId)->value('username');
    
        // Base query for direct users
        $query = User::where('sponsor_id', $sponsorUsername);
    
        // Apply filters
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }
    
        if ($request->filled('user_id')) {
            $query->where('username', 'like', "%{$request->user_id}%");
        }
    
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ]);
        }
    
        // Fetch direct users
        $users = $query->orderBy('created_at', 'desc')->get();
    
        // Get all custom fields once
        $customFields = \App\Models\CustomField::all()->keyBy('id');

        // Self package totals for every listed user in one grouped query
        // instead of one SELECT SUM per row.
        $selfTotals = $this->selfPackageTotals($users->pluck('id')->all());

        // Downline package totals, also one query for the whole page.
        $downlineTotals = $this->downlineBusinessTotals($users->pluck('username')->all());

        // Process each user
        $filteredUsers = $users->filter(function ($user) use ($request, $customFields, $selfTotals, $downlineTotals) {
            // Active status
            $user->active_status = ($user->status === 1) ? 'active' : 'inactive';

            $user->total_amount = $selfTotals[(string) $user->id] ?? 0;
            $user->current_business = $downlineTotals[$user->username] ?? 0;

            // Get custom fields
            $userCustomFields = $user->custom_fields ?? [];
    
            // Add mobile number (custom field ID 27)
            $user->mobile_number = $userCustomFields[30] ?? null;
            
            // Add compensation flag (check if is_free_id == 1)
            $user->show_compensation_badge = ($user->is_free_id == 1);
    
            // Filter by status if applied
            if ($request->filled('status')) {
                return $user->active_status === strtolower($request->status);
            }
    
            return true;
        });
    
        return view('user.genealogy.direct-users', [
            'users' => $filteredUsers->values()
        ]);
    }

    /**
     * Helper function to calculate current business from an array of usernames.
     *
     * @param  string  $username
     * @return float
     */
    private function calculateCurrentBusiness($username)
    {
        $downlineUsernames = $this->getDownlineUsernames($username);

        $userIds = User::whereIn('username', $downlineUsernames)->pluck('id');

        return Order::whereIn('tx_user_id', $userIds)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->sum('order_amount');
    }



    public function teamList(Request $request)
    {
        $authUsername = auth()->user()->username;

        // Get downline users with level info
        $allUsers = $this->getDownlineUsersWithLevels($authUsername);

        // Filter
        if ($request->filled('name')) {
            $allUsers = $allUsers->filter(fn($u) => str_contains(strtolower($u->name), strtolower($request->name)));
        }

        if ($request->filled('user_id')) {
            $allUsers = $allUsers->filter(fn($u) => str_contains(strtolower($u->username), strtolower($request->user_id)));
        }

        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();
            $allUsers = $allUsers->filter(fn($u) => $u->created_at >= $from && $u->created_at <= $to);
        }

        if ($request->filled('status')) {
            $allUsers = $allUsers->filter(function ($u) use ($request) {
                $wallet = UserWallet::where('u_code', $u->id)->first();
                $activeStatus = $u->status === 1 ? 'active' : 'inactive';
                return $activeStatus === strtolower($request->status);
            });
        }
        if ($request->filled('level')) {
            $allUsers = $allUsers->filter(fn($u) => $u->level == $request->level);
        }
        // Enrich data
        foreach ($allUsers as $user) {
            $wallet = UserWallet::where('u_code', $user->id)->first();
            $user->active_status = $user->status === 1 ? 'Active' : 'Inactive';

            // Self amount
            $user->total_amount = Order::where('tx_user_id', $user->id)
                ->whereIn('tx_type', ['topup', 'retopup'])
                ->sum('order_amount');


            // ✅ Get all downline usernames for the current user
            $downlineUsernames = $this->getDownlineUsernames($user->username);

            // ✅ Calculate current business (downline total order)
            $user->current_business = Order::whereIn('tx_user_id', function ($q) use ($downlineUsernames) {
                $q->select('id')->from('users')->whereIn('username', $downlineUsernames);
            })
                ->whereIn('tx_type', ['topup', 'retopup'])
                ->sum('order_amount');
                
                 
        // ✅ Add compensation flag (check if is_free_id == 1)
        $user->show_compensation_badge = ($user->is_free_id == 1);
        }

        return view('user.genealogy.generation', ['users' => $allUsers->values()]);
    }


    private function getDownlineUsersWithLevels($username)
    {
        return \App\Helpers\TreeHelper::sponsorDescendantsWithLevels((string) $username);
    }




    /**
     * Display a listing of left-team users with filtering and downline business calculations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function leftteamindex(Request $request)
    {
        $userId = auth()->id();

        // Get the IDs of all users in the user's left team downline.
        $leftTeamIds = $this->getLeftTeamIds($userId);

        // Base query for all users in the left team
        $query = User::whereIn('id', $leftTeamIds);

        // Apply filters based on the request parameters
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }

        if ($request->filled('user_id')) {
            $query->where('username', 'like', "%{$request->user_id}%");
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ]);
        }

        // Status is filtered in SQL now. It used to be applied in PHP after
        // every row had been loaded and enriched, which meant paying for rows
        // that were then thrown away.
        $this->applyStatusFilter($query, $request);

        // A leg can hold thousands of members; this listing used to render all
        // of them and run three queries per row.
        $users = $query->orderBy('created_at', 'desc')
            ->paginate(self::TEAM_PAGE_SIZE)
            ->withQueryString();

        $this->decorateTeamRows($users->getCollection(), $leftTeamIds);

        // Return the view with the processed and filtered user collection
        return view('user.genealogy.left-team', [
            'users' => $users
        ]);
    }

    /**
     * Display a listing of right-team users with filtering and downline business calculations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function rightteamindex(Request $request)
    {
        $userId = auth()->id();

        // Get the IDs of all users in the user's right team downline.
        $rightTeamIds = $this->getRightTeamIds($userId);

        // Base query for all users in the right team
        $query = User::whereIn('id', $rightTeamIds);

        // Apply filters based on the request parameters
        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }

        if ($request->filled('user_id')) {
            $query->where('username', 'like', "%{$request->user_id}%");
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ]);
        }

        // See leftteamindex() - status filtered in SQL, rows paginated, and the
        // per-row aggregates resolved in bulk.
        $this->applyStatusFilter($query, $request);

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(self::TEAM_PAGE_SIZE)
            ->withQueryString();

        $this->decorateTeamRows($users->getCollection(), $rightTeamIds);

        // Return the view with the processed and filtered user collection
        return view('user.genealogy.right-team', [
            'users' => $users
        ]);
    }

    /**
     * Get all user IDs in the left team (including direct left_id user + their generations).
     */
    protected function getLeftTeamIds(int $userId): array
    {
        // Resolved through parent_id/position, matching the binary-matching
        // income path - see TreeHelper::legIds().
        return \App\Helpers\TreeHelper::legIds($userId, 1);
    }

    /**
     * Get all user IDs in the right team (including direct right_id user + their generations).
     */
    protected function getRightTeamIds(int $userId): array
    {
        return \App\Helpers\TreeHelper::legIds($userId, 2);
    }


    private function getDownlineIds($userId): Collection
    {
        return collect(\App\Helpers\TreeHelper::descendantIds((int) $userId));
    }

    /**
     * Apply the ?status=active|inactive filter in SQL.
     */
    private function applyStatusFilter($query, Request $request): void
    {
        if (!$request->filled('status')) {
            return;
        }

        $status = strtolower($request->input('status'));
        if ($status === 'active') {
            $query->where('status', 1);
        } elseif ($status === 'inactive') {
            $query->where('status', '!=', 1);
        }
    }

    /**
     * Attach the display fields the team listings expect to one page of rows.
     *
     * @param  \Illuminate\Support\Collection  $rows      the current page
     * @param  array<int, int>                 $legIds    every id in the leg
     */
    private function decorateTeamRows($rows, array $legIds): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $selfTotals = $this->selfPackageTotals($rows->pluck('id')->all());
        $downlineTotals = $this->legDownlineBusiness($legIds);

        foreach ($rows as $user) {
            $user->active_status = ($user->status === 1) ? 'active' : 'inactive';
            $user->total_amount = $selfTotals[(string) $user->id] ?? 0;
            $user->current_business = $downlineTotals[(int) $user->id] ?? 0;

            $userCustomFields = $user->custom_fields ?? [];
            $user->mobile_number = $userCustomFields[30] ?? null;
            $user->show_compensation_badge = ($user->is_free_id == 1);
        }
    }

    /**
     * Business booked by each member's own downline, for a whole leg at once.
     *
     * Computing this per row meant a subtree query plus a SUM for every user
     * listed - thousands of queries on a large leg. Instead: pull the leg's
     * parent links and per-user totals in two queries, then roll the subtree
     * sums up in a single bottom-up pass.
     *
     * The rollup is iterative on purpose: this tree runs thousands of levels
     * deep, which would overflow the stack if done recursively.
     *
     * @param  array<int, int>  $memberIds  every id in the leg (closed under descendants)
     * @return array<int, float>            member id => downline business (excludes own orders)
     */
    private function legDownlineBusiness(array $memberIds): array
    {
        if (empty($memberIds)) {
            return [];
        }

        $inLeg = array_flip($memberIds);

        // parent links, restricted to the leg
        $children = [];
        foreach (array_chunk($memberIds, 20000) as $chunk) {
            foreach (DB::table('users')->whereIn('id', $chunk)->get(['id', 'parent_id']) as $row) {
                $pid = $row->parent_id === null ? null : (int) $row->parent_id;
                if ($pid !== null && isset($inLeg[$pid])) {
                    $children[$pid][] = (int) $row->id;
                }
            }
        }

        // each member's own package business
        $own = [];
        foreach (array_chunk($memberIds, 20000) as $chunk) {
            $rows = Order::selectRaw('tx_user_id, SUM(order_amount) AS total')
                ->whereIn('tx_user_id', $chunk)
                ->whereIn('tx_type', ['topup', 'retopup'])
                ->groupBy('tx_user_id')
                ->get();

            foreach ($rows as $row) {
                $own[(int) $row->tx_user_id] = (float) $row->total;
            }
        }

        // Order nodes by depth (roots first) with an iterative BFS, then walk
        // the list backwards so every child is summed before its parent.
        // A node with no parent inside the leg is a root of this subforest.
        $roots = [];
        $hasParent = [];
        foreach ($children as $parent => $kids) {
            foreach ($kids as $kid) {
                $hasParent[$kid] = true;
            }
        }
        foreach ($memberIds as $id) {
            if (!isset($hasParent[$id])) {
                $roots[] = $id;
            }
        }

        $order = [];
        $queue = $roots;
        $seen = array_flip($roots);
        while (!empty($queue)) {
            $id = array_shift($queue);
            $order[] = $id;
            foreach ($children[$id] ?? [] as $kid) {
                if (!isset($seen[$kid])) {
                    $seen[$kid] = true;
                    $queue[] = $kid;
                }
            }
        }

        // subtree[] includes the node's own orders; downline excludes them.
        $subtree = [];
        foreach (array_reverse($order) as $id) {
            $total = $own[$id] ?? 0.0;
            foreach ($children[$id] ?? [] as $kid) {
                $total += $subtree[$kid] ?? 0.0;
            }
            $subtree[$id] = $total;
        }

        $downline = [];
        foreach ($memberIds as $id) {
            $downline[$id] = ($subtree[$id] ?? 0.0) - ($own[$id] ?? 0.0);
        }

        return $downline;
    }

    /**
     * Sum of topup/retopup order_amount per user, for many users at once.
     *
     * orders.tx_user_id is a varchar, so the keys come back as strings.
     *
     * @param  array<int, int|string>  $userIds
     * @return array<string, float>
     */
    private function selfPackageTotals(array $userIds): array
    {
        if (empty($userIds)) {
            return [];
        }

        return Order::selectRaw('tx_user_id, SUM(order_amount) AS total')
            ->whereIn('tx_user_id', $userIds)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->groupBy('tx_user_id')
            ->pluck('total', 'tx_user_id')
            ->map(fn ($v) => (float) $v)
            ->all();
    }

    /**
     * Total downline topup/retopup business for each of $usernames.
     *
     * Resolves every listed user's sponsor subtree in one recursive pass and
     * sums the orders in one grouped query, rather than a tree walk plus a
     * SUM per row.
     *
     * @param  array<int, string>  $usernames
     * @return array<string, float>
     */
    private function downlineBusinessTotals(array $usernames): array
    {
        $usernames = array_values(array_filter($usernames, fn ($u) => $u !== null && $u !== ''));
        if (empty($usernames)) {
            return [];
        }

        // username => list of descendant usernames
        $subtrees = [];
        $allDescendants = [];
        foreach ($usernames as $username) {
            $descendants = \App\Helpers\TreeHelper::sponsorDescendantUsernames((string) $username);
            $subtrees[$username] = $descendants;
            foreach ($descendants as $d) {
                $allDescendants[$d] = true;
            }
        }

        if (empty($allDescendants)) {
            return array_fill_keys($usernames, 0.0);
        }

        // One grouped sum covering every descendant on the page, keyed by the
        // descendant's username.
        $totalsByUsername = [];
        foreach (array_chunk(array_keys($allDescendants), 2000) as $chunk) {
            $rows = Order::selectRaw('users.username AS username, SUM(orders.order_amount) AS total')
                ->join('users', 'users.id', '=', 'orders.tx_user_id')
                ->whereIn('users.username', $chunk)
                ->whereIn('orders.tx_type', ['topup', 'retopup'])
                ->groupBy('users.username')
                ->get();

            foreach ($rows as $row) {
                $totalsByUsername[$row->username] = (float) $row->total;
            }
        }

        $result = [];
        foreach ($usernames as $username) {
            $sum = 0.0;
            foreach ($subtrees[$username] as $d) {
                $sum += $totalsByUsername[$d] ?? 0.0;
            }
            $result[$username] = $sum;
        }

        return $result;
    }



    private function getDownlineUsernames($username)
    {
        return collect(\App\Helpers\TreeHelper::sponsorDescendantUsernames((string) $username));
    }

    
}
