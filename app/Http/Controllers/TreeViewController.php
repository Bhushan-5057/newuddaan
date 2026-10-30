<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\TreeHelper;

class TreeViewController extends Controller
{
    /**
     * Placements skipped while building the current tree because another user
     * already occupied that parent+side. Populated by visibleTreeData().
     *
     * @var array<int, array{parent_id:int, position:int, kept:int, skipped:int, skipped_username:string}>
     */
    private array $duplicatePlacements = [];
    /**
     * Display the binary tree view for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
public function showTree()
{
    $rootUser = Auth::user();
    if (!$rootUser) {
        return redirect()->route('login')->with('error', 'You must be logged in to view the tree.');
    }

    $userId = $rootUser->id;
    $userusername = $rootUser->username;

    // Each leg can hold thousands of ids, and every whereIn() against it sends
    // that whole list to MySQL. The root's active directs are a small set, so
    // fetch them once and intersect in PHP rather than adding another huge IN.
    $directActiveIds = User::where('sponsor_id', $userusername)
        ->where('status', 1)
        ->pluck('id')
        ->map(fn ($id) => (int) $id)
        ->all();

    // Left team downline IDs
    $leftTeamIds = $this->getDownlineUserIds($rootUser->left_id);
    $totalLeftTeam = count($leftTeamIds);
    $activeLeftTeam = $this->countActive($leftTeamIds);
    $leftBusiness = $this->packageBv($leftTeamIds);
    $leftDirectActive = count(array_intersect($directActiveIds, $leftTeamIds));

    // Right team downline IDs
    $rightTeamIds = $this->getDownlineUserIds($rootUser->right_id);
    $totalRightTeam = count($rightTeamIds);
    $activeRightTeam = $this->countActive($rightTeamIds);
    $rightBusiness = $this->packageBv($rightTeamIds);
    $rightDirectActive = count(array_intersect($directActiveIds, $rightTeamIds));


    $leftTeam = [
        'member'       => $totalLeftTeam,
        'point'        => $leftBusiness,
        'green_unit'   => $activeLeftTeam,
        'green_direct' => $leftDirectActive,
    ];

    $rightTeam = [
        'member'       => $totalRightTeam,
        'point'        => $rightBusiness,
        'green_unit'   => $activeRightTeam,
        'green_direct' => $rightDirectActive,
    ];

    // The tree markup used to be rendered for the whole downline and then
    // hidden down to three levels by JavaScript. On a deep tree that meant
    // thousands of nested <ul>s plus three queries per node, which is what made
    // this page time out. Render only the levels the UI can actually show.
    $treeMaxLevel = (int) config('tree.render_levels', 3);

    [$treeNodes, $treeBv, $treeChildren] = $this->visibleTreeData($rootUser, $treeMaxLevel);

    // Members a bad placement pushed out of the drawing (see visibleTreeData).
    $treeHidden = array_map(fn ($d) => $d['skipped_username'], $this->duplicatePlacements);

    // Breadcrumb starts at the viewer; drilling down appends to it.
    $treePath = [[
        'id' => $rootUser->id,
        'username' => $rootUser->username,
        'name' => $rootUser->name,
    ]];

    return view('user.tree.view', compact(
        'rootUser',
        'leftTeam',
        'rightTeam',
        'treeMaxLevel',
        'treeNodes',
        'treeBv',
        'treeChildren',
        'treePath',
        'treeHidden'
    ));
}

/**
 * Re-root the tree on a clicked member: that member becomes the top node
 * (same render_levels UI as the default sponsor view). Left/right Null only
 * when that side is empty.
 */
public function fetchSubTree(Request $request)
{
    $viewer = Auth::user();
    if (!$viewer) {
        return response()->json(['message' => 'Not authenticated.'], 401);
    }

    $targetId = (int) $request->query('id');
    if ($targetId <= 0) {
        return response()->json(['message' => 'A user id is required.'], 422);
    }

    // A member may only open branches inside their own downline.
    if (!TreeHelper::isSelfOrDescendantOf($targetId, (int) $viewer->id)) {
        return response()->json(['message' => 'That member is not in your team.'], 403);
    }

    $target = User::find($targetId);
    if (!$target) {
        return response()->json(['message' => 'Member not found.'], 404);
    }

    // Clicked member becomes top; open three levels below (existing drilldown).
    $maxLevel = max(1, (int) config('tree.drilldown_levels', 3));
    [$treeNodes, $treeBv, $treeChildren] = $this->visibleTreeData($target, $maxLevel, null);

    // Render clicked member as the new top root (existing user_node partial).
    $html = view('user.tree.partials.user_node', [
        'user' => $target,
        'level' => 0,
        'treeMaxLevel' => $maxLevel,
        'treeNodes' => $treeNodes,
        'treeBv' => $treeBv,
        'treeChildren' => $treeChildren,
        'onlyPosition' => null,
    ])->render();

    return response()->json([
        'html' => $html,
        'root' => [
            'id' => $target->id,
            'username' => $target->username,
            'name' => $target->name,
        ],
        'hidden' => array_map(fn ($d) => $d['skipped_username'], $this->duplicatePlacements),
    ]);
}

/**
 * Collect the users rendered by the tree partial (root down to $maxLevel) plus
 * their package BV, in a fixed handful of queries.
 *
 * @return array{0: array<int, \App\Models\User>, 1: array<string, float>}
 *               [id => user, tx_user_id => total BV]
 */
private function visibleTreeData(User $root, int $maxLevel, ?int $onlyPosition = null): array
{
    $nodes = [$root->id => $root];
    $childMap = [];          // [parentId][1|2] => childId
    $frontier = [$root->id];
    $positions = $onlyPosition ? [$onlyPosition] : [1, 2];

    for ($level = 0; $level < $maxLevel; $level++) {
        if (empty($frontier)) {
            break;
        }

        // Children come from parent_id + position, the same placement the
        // binary-matching income uses. The old walk followed left_id/right_id,
        // denormalised columns that can drift out of step with it.
        // When $onlyPosition is set (left- or right-leg drill-down), only that
        // side of each node is loaded.
        $children = User::whereIn('parent_id', $frontier)
            ->whereIn('position', $positions)
            ->orderBy('id')
            ->get();

        $frontier = [];
        foreach ($children as $child) {
            $parentId = (int) $child->parent_id;
            $pos = (int) $child->position;

            // A slot can only hold one node. Where bad data put two users on
            // the same side, the earliest placement wins and the rest are
            // recorded so the view can flag them.
            if (isset($childMap[$parentId][$pos])) {
                $this->duplicatePlacements[] = [
                    'parent_id' => $parentId,
                    'position' => $pos,
                    'kept' => $childMap[$parentId][$pos],
                    'skipped' => (int) $child->id,
                    'skipped_username' => $child->username,
                ];
                continue;
            }

            if (isset($nodes[(int) $child->id])) {
                continue; // cycle guard
            }

            $childMap[$parentId][$pos] = (int) $child->id;
            $nodes[(int) $child->id] = $child;
            $frontier[] = (int) $child->id;
        }
    }

    // One grouped query for every visible node instead of a SUM per node.
    // orders.tx_user_id is a varchar, so the keys come back as strings.
    $bv = Order::selectRaw('tx_user_id, SUM(order_bv) AS total')
        ->whereIn('tx_user_id', array_keys($nodes))
        ->whereIn('tx_type', ['topup', 'retopup'])
        ->where('status', 1)
        ->groupBy('tx_user_id')
        ->pluck('total', 'tx_user_id')
        ->map(fn ($v) => (float) $v)
        ->all();

    return [$nodes, $bv, $childMap];
}


/**
 * Get all downline user IDs including the root of that side.
 */
private function getDownlineUserIds($rootUserId)
{
    if (!$rootUserId) return [];

    return \App\Helpers\TreeHelper::selfAndDescendantIds((int) $rootUserId);
}

/**
 * Number of active users among $ids.
 */
private function countActive(array $ids): int
{
    if (empty($ids)) {
        return 0;
    }

    $count = 0;
    foreach (array_chunk($ids, 20000) as $chunk) {
        $count += User::whereIn('id', $chunk)->where('status', 1)->count();
    }

    return $count;
}

/**
 * Total topup/retopup BV booked by $ids.
 */
private function packageBv(array $ids): float
{
    if (empty($ids)) {
        return 0.0;
    }

    $sum = 0.0;
    foreach (array_chunk($ids, 20000) as $chunk) {
        $sum += (float) Order::whereIn('tx_user_id', $chunk)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->where('status', 1)
            ->sum('order_bv');
    }

    return $sum;
}





}