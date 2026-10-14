<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\User;

use App\Models\Transaction;

use App\Models\BoosterPackage;

use App\Models\BoosterIncome;

use App\Models\UserWallet;

use Illuminate\Support\Facades\DB;



class BoosterPackageController extends Controller

{

public function placeBoosterPackage(User $user)

{

    $package = BoosterPackage::create([

        'user_id' => $user->id,

        'status' => 0,

    ]);



    // Step 1: Find the root (first package ever placed)

    $root = BoosterPackage::orderBy('id')->first();



    if (!$root) {

        // First package becomes root

        $package->update([

            'placed_under_id' => null,

            'position' => null,

        ]);

        return;

    }



    // Step 2: Global BFS traversal for placement

    $queue = [$root];



    while (!empty($queue)) {

        $current = array_shift($queue);



        // Get children of current node

        $children = BoosterPackage::where('placed_under_id', $current->id)->get();



        if ($children->count() < 2) {

            $position = $children->contains('position', '1') ? '2' : '1';



            $package->update([

                'placed_under_id' => $current->id,

                'position' => $position,

            ]);



           

            return;

        }



        // Push children to queue

        foreach ($children as $child) {

            $queue[] = $child;

        }

    }

}













    public function checkBoosterIncome($parentId)

    {

        if (!$parentId) return;



        $level1 = BoosterPackage::where('placed_under_id', $parentId)->get();

        if ($level1->count() < 2) return;



        $level2 = BoosterPackage::whereIn('placed_under_id', $level1->pluck('id'))->get();

        if ($level2->count() < 2) return;



        $parentUserId = BoosterPackage::find($parentId)->user_id ?? null;

        if (!$parentUserId) return;



        BoosterIncome::create([

            'user_id' => $parentUserId,

            'from_package_id' => $level2->first()->id,

            'amount' => 10.00,

        ]);



        BoosterPackage::where('id', $parentId)->update(['status' => 'complete']);



        // Optionally credit c31 balance

        $wallet = UserWallet::where('u_code', $parentUserId)->first();

        if ($wallet) {

            $wallet->increment('c31', 10); // Booster wallet income

        }

    }

public function boosterTopupView()

{

    $user = auth()->user();

    $wallet = UserWallet::where('u_code', $user->id)->first();



    return view('user.booster_envest.booster_topup', [

        'userWallet' => $wallet,

    ]);

}



public function buyBoosterPackage(Request $request)

{

    $targetUser = auth()->user(); // Default is self



    // Booster wallet (c31) of the buyer (must always be logged-in user)

    $wallet = UserWallet::where('u_code', auth()->id())->first();



    // Validate balance

    if (!$wallet || $wallet->c31 < 10) {

        return back()->with('error', 'Insufficient booster wallet balance.');

    }



    // Deduct $10 from wallet

    $wallet->decrement('c31', 10);



    // Place booster package for the selected user

    $this->placeBoosterPackage($targetUser);



    return back()->with('success', 'Booster package purchased successfully!');

}





    public function withdrawBooster(Request $request)

    {

        $user = auth()->user();

        $wallet = UserWallet::where('u_code', $user->id)->first();

        $amount = $request->amount;



        if (!$wallet || $wallet->c31 < $amount) {

            return back()->with('error', 'Insufficient balance.');

        }



        $fee = $amount * 0.15;

        $net = $amount - $fee;



        $wallet->decrement('c31', $amount);






        return back()->with('success', "Withdrawal of \${$net} requested successfully (after 15% fee).");

    }

public function boosterTree()
{
    $user = auth()->user();

    $packageIds = BoosterPackage::where('user_id', $user->id)->pluck('id');

    $trees = [];
    foreach ($packageIds as $id) {
        $trees[] = $this->buildTree($id);
    }

    return view('user.booster.tree', compact('trees'));
}

private function buildTree($id, $depth = 2)
{
    $node = BoosterPackage::with('user')->find($id);

    if (!$node) {
        return [
            'id' => $id,
            'username' => 'N/A',
            'status' => 0,
            'left' => null,
            'right' => null,
        ];
    }

    $tree = [
        'id' => $node->id,
        'username' => $node->user->username ?? 'N/A',
        'status' => $node->status,
        'left' => null,
        'right' => null,
    ];

    if ($depth > 0) {
        $children = BoosterPackage::with('user')
            ->where('placed_under_id', $id)
            ->get();

        $leftChild = $children->firstWhere('position', 1);
        $rightChild = $children->firstWhere('position', 2);

        if ($leftChild) {
            $tree['left'] = $this->buildTree($leftChild->id, $depth - 1);
        }

        if ($rightChild) {
            $tree['right'] = $this->buildTree($rightChild->id, $depth - 1);
        }
    }

    return $tree;
}








}

