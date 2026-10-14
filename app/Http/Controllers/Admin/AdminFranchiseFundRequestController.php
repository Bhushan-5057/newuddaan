<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundRequest;
use App\Models\TransactionFranchise;
use App\Models\FranchiseUser;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AdminFranchiseFundRequestController extends Controller
{


public function index()
{
    $requests = TransactionFranchise::with('franchise')
        ->where('tx_type', 'fund_request')
        ->latest()
        ->get();

    return view('admin.franchise.fund_requests', compact('requests'));
}

public function approve($id)
{
    try {
        DB::transaction(function () use ($id) {

            $request = TransactionFranchise::with('franchise')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($request->status != 0) {
                throw new \Exception('Already processed');
            }

            $franchise = $request->franchise;

            $openBalance    = $franchise->balance ?? 0;
            $closingBalance = $openBalance + $request->amount;

            // 🔹 Update wallet
            $franchise->update([
                'balance' => $closingBalance
            ]);

            // 🔹 Update transaction itself
            $request->update([
                'open_wallet'    => $openBalance,
                'closing_wallet' => $closingBalance,
                'status'         => 1,
                'approved_date'    => now(),
                'remark'         => 'Fund approved by admin',
            ]);
        });

        return back()->with('success', 'Fund approved successfully');

    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

public function reject(Request $request, $id)
{
    $request->validate([
        'reject_reason' => 'required|string|max:255'
    ]);

    $fund = TransactionFranchise::findOrFail($id);

    if ($fund->status != 0) {
        return back()->withErrors(['error' => 'Already processed']);
    }

    $fund->update([
        'status'        => 2,
        'reject_reason' => $request->reject_reason,
        'reject_date'   => now(),
    ]);

    return back()->with('success', 'Fund request rejected');
}

}