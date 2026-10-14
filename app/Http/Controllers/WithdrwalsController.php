<?php

namespace App\Http\Controllers;



use App\Models\UserPaymentMethod;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;



use Illuminate\Support\Facades\Response;





use App\Models\WalletType;

use App\Models\CustomField;

use App\Models\AdvanceInfo;

use App\Models\CustomFieldValue;

use App\Models\UserWallet;

use App\Models\User;

use Illuminate\Support\Str;

use App\Models\MailTemplate;

use Illuminate\Support\Facades\Mail;

use App\Mail\UserRegisteredMail;

use App\Models\Transaction;

// use App\Models\UserPaymentMethod;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;



use Yajra\DataTables\Facades\DataTables;



class WithdrwalsController extends Controller
{

//  public function withdrwalsrequestindex(Request $request)
// {
    
//     // Mark all unseen pending requests as seen
//     Transaction::where('status', 0)
//         ->where('tx_type', 'withdrawal')
//         ->where('is_seen', false)
//         ->update(['is_seen' => true]);

//     // Now your badge count will drop to 0
//     return view('superadmin.withdrwals.pendingwithdrwalsrequest');
// }


public function withdrwalsrequestindex(Request $request)
{
    // Count unseen first
    $pendingCount = Transaction::where('status', 0)
        ->where('tx_type', 'withdrawal')
        ->where('is_seen', 0)
        ->count();

    // Mark unseen as seen
    Transaction::where('status', 0)
        ->where('tx_type', 'withdrawal')
        ->where('is_seen', 0)
        ->update(['is_seen' => true]);

       return view('superadmin.withdrwals.pendingwithdrwalsrequest', ['pendingCount' => $pendingCount]);
     
}

public function withdrwalsrequestlist(Request $request)
{
    $query = Transaction::where('status', 0)
        ->where('tx_type', 'withdrawal')
        ->with('user')
        ->select('transaction.*'); // ✅ Use correct table name if not aliased

    return DataTables::of($query)
        ->addIndexColumn()

        ->addColumn('tx_user', function ($row) {
            return $row->user?->username ?? $row->u_code;
        })

        ->editColumn('amount', function ($row) {
            return company_info('currency', '$') . number_format($row->amount, 2);
        })

        ->addColumn('booster_preview', function ($row) {
            $booster = $row->amount * 0.15;
            return company_info('currency', '$') . number_format($booster, 2);
        })

        ->addColumn('tx_charge', function ($row) {
            return company_info('currency', '$') . number_format($row->tx_charge ?? 0, 2);
        })

       ->addColumn('payable_amount', function ($row) {
    $booster = $row->amount * 0.15;
    $final = ($row->paidAmount ?? 0) - $booster;

    return company_info('currency', '$') . number_format($final, 2);
})


        ->addColumn('account_details', function ($row) {
            return $row->account_details
                ? nl2br(e($row->account_details))
                : 'N/A';
        })

        ->addColumn('status', function ($row) {
            return '<span class="badge bg-warning text-dark">Pending</span>';
        })

        ->addColumn('action', function ($row) {
            return '<a href="' . route('superadmin.withdrwalsrequest.detail', $row->id) . '" class="btn btn-sm btn-info">View</a>';
        })

        ->addColumn('date', function ($row) {
            return $row->date;
        })

        ->rawColumns(['status', 'action', 'account_details'])

        ->make(true);
}



    public function withdrwalsrequestDetail($id)
    {

        $transaction = Transaction::with('user')->findOrFail($id);

        return view('superadmin.withdrwals.withdrwalsdetail', compact('transaction'));

    }





/*public function approve(Request $request)
{
    $id = $request->id;

    DB::beginTransaction();

    try {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found']);
        }

        if ($transaction->status != 0) {
            return response()->json(['success' => false, 'message' => 'Only pending transactions can be approved']);
        }

        $userId = $transaction->u_code;
        $wallet = UserWallet::where('u_code', $userId)->lockForUpdate()->first();

        if (!$wallet) {
            return response()->json(['success' => false, 'message' => 'User wallet not found']);
        }

        $withdrawalAmount = $transaction->amount;

        // ✅ Get charge config
        $chargeValue = (float)(AdvanceInfo::where('label', 'wd_charges')->value('value') ?? 0);
        $chargeOptionsJson = AdvanceInfo::where('label', 'wd_charges')->value('options') ?? '{"fix":"fix"}';
        $chargeOptions = json_decode($chargeOptionsJson, true);
        $chargeType = array_key_first($chargeOptions) ?? 'fix';

        $charge = $chargeType === 'percent'
            ? ($withdrawalAmount * $chargeValue) / 100
            : $chargeValue;

        $finalPaid = $withdrawalAmount - $charge;

        // ✅ 15% booster allocation (separate from withdrawal charge)
        $boosterAmount = $withdrawalAmount * 0.15;

        // ✅ Check balance
        if ($wallet->c1 < $withdrawalAmount) {
            return response()->json(['success' => false, 'message' => 'Insufficient wallet balance.']);
        }

        $oldBalance = $wallet->c1;
        $wallet->c1 -= $withdrawalAmount;
        $wallet->c31 += $boosterAmount;
        $wallet->save();

        // ✅ Update withdrawal transaction
        $transaction->status = 1;
        $transaction->updated_on = now();
        $transaction->save();

        // ✅ Log booster credit (for record)
        Transaction::create([
            'u_code'         => $userId,
            'tx_type'        => 'credit',
            'debit_credit'   => 'credit',
            'source'         => 'booster_credit',
            'wallet_type'    => 'booster_wallet',
            'payment_type'   => 'internal',
            'amount'         => $boosterAmount,
            'tx_charge'      => 0,
            'status'         => 1,
            'remark'         => '15% of withdrawal moved to booster wallet (c31)',
            'open_wallet'    => $wallet->c31 - $boosterAmount,
            'closing_wallet' => $wallet->c31,
            'added_on'       => now(),
            'updated_on'     => now(),
        ]);

        DB::commit();

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}*/
    public function approve(Request $request)
{
    $id = $request->id;

    // Start DB transaction
    DB::beginTransaction();

    try {
        $transaction = Transaction::with('user')->findOrFail($id);

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found']);
        }

        if ($transaction->status != 0) {
            return response()->json(['success' => false, 'message' => 'Only pending transactions can be approved']);
        }
        $hasDefault = UserPaymentMethod::where('u_code', $transaction->u_code)
        ->where('default_account', 1)
        ->first();
        if ($hasDefault) {
            $bank_details_json = $hasDefault->accounts;
            $bank_details = json_decode($bank_details_json, true); // true for associative array            
            $userAddress = isset($bank_details[0]['bep20']) ? $bank_details[0]['bep20'] : null;
        }else{
            return redirect()->back()->withErrors(['amount' => 'Please set a default payment method userAddress.']);
        }
        $amount = $transaction->paidAmount;
         //dd(['amount'=>$amount,'address'=>$userAddress]);
        // Call transfer and decode response
        $rawResponse = $this->transfer($userAddress, $amount);
        $response = json_decode($rawResponse);

       if (isset($response->success) && $response->success) {
            // Update transaction
            $transaction->status = 1;
            $transaction->tx_hash = $response->transactionHash;
            $transaction->updated_on = now();
            $transaction->save();
        }
        

        DB::commit();
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}
private function transfer($to, $amount)
{
    $sendAddress = strtolower($to);
    $payAmount = $amount;
    return '{"success":true,"transactionHash":"0xf1234567"}';
    try {
        $url = "http://62.72.23.66:3000/transfer/dmtsyndicate";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($url, [
            'to' => $sendAddress,
            'amount' => $payAmount,
        ]);

        $httpCode = $response->status();
        $body = $response->body();

        Log::error("Request: " . json_encode(['to' => $sendAddress, 'amount' => $payAmount]) .
                   " | Response Code: $httpCode | Response: $body");

        return $body;
    } catch (\Exception $e) {
        Log::error('Transfer Exception: ' . $e->getMessage());
        return $e->getMessage();
    }
}
    public function reject(Request $request)
    {

        $id = $request->id;

        $reason = $request->reason;



        $transaction = Transaction::find($id);



        if (!$transaction) {

            return response()->json(['success' => false, 'message' => 'Transaction not found']);

        }



        if ($transaction->status != 0) {

            return response()->json(['success' => false, 'message' => 'Only pending transactions can be rejected']);

        }



        $transaction->status = 2; // rejected

        $transaction->reason = $reason;

        $transaction->updated_on = now();

        $transaction->save();

        // Find the user's wallet using u_code (foreign key to users table)

        $wallet = UserWallet::where('u_code', $transaction->u_code)->first();



        if (!$wallet) {

            // If wallet doesn't exist, create it with c1 = transaction amount

            UserWallet::create([

                'u_code' => $transaction->u_code,

                'c1' => $transaction->amount

            ]);

        } else {

            // Else, increment c1 by the transaction amount

            $wallet->increment('c1', $transaction->amount);

        }



        return response()->json(['success' => true]);

    }







    public function approvewithdrwalsrequestindex(Request $request)
    {

        return view('superadmin.withdrwals.approvedwithdrwalsrequest');

    }


public function approvewithdrwalsrequestlist(Request $request)
{
    $query = Transaction::where('status', 1)
        ->where('tx_type', 'withdrawal')
        ->with('user')
        ->select('transaction.*');

    return DataTables::of($query)
        ->addIndexColumn()

        ->addColumn('tx_user', fn($row) => $row->user?->username ?? $row->u_code)

        ->addColumn('tx_charge', fn($row) => company_info('currency', '$') . number_format($row->tx_charge ?? 0, 2))

        // ✅ Add Booster Deduction
        ->addColumn('booster_deduction', fn($row) => company_info('currency', '$') . number_format($row->amount * 0.15, 2))

        // ✅ Add Payable After Booster
        ->addColumn('payable_amount', fn($row) => company_info('currency', '$') . number_format(($row->paidAmount ?? 0) - ($row->amount * 0.15), 2))

        ->addColumn('account_details', function ($row) {
            return $row->account_details
                ? nl2br(e($row->account_details))
                : 'N/A';
        })

        ->addColumn('status', fn($row) => '<span class="badge bg-success">Approved</span>')

        ->addColumn('action', function ($row) {
            return '<a href="' . route('superadmin.withdrwalsrequest.detail', $row->id) . '" class="btn btn-sm btn-info">View</a>';
        })

        ->editColumn('amount', function ($row) {
            return company_info('currency', '$') . number_format($row->amount, 2);
        })

        ->addColumn('date', fn($row) => $row->date)

        ->rawColumns(['status', 'action', 'account_details'])

        ->make(true);
}




    public function cancelledwithdrwalsrequestindex(Request $request)
    {

        return view('superadmin.withdrwals.cancelledwithdrwalsrequest');

    }



   public function cancelledwithdrwalsrequestlist(Request $request)
{
    $query = Transaction::where('status', 2) // 2 = Cancelled
        ->where('tx_type', 'withdrawal')
        ->with('user')
        ->select('transaction.*');

    return DataTables::of($query)
        ->addIndexColumn()

        ->addColumn('tx_user', fn($row) => $row->user?->username ?? $row->u_code)

        ->addColumn('tx_charge', fn($row) => company_info('currency', '$') . number_format($row->tx_charge ?? 0, 2))

        // ✅ Booster Deduction (15% of original amount)
        ->addColumn('booster_deduction', fn($row) => company_info('currency', '$') . number_format($row->amount * 0.15, 2))

        // ✅ Final payable after booster deduction
        ->addColumn('payable_amount', fn($row) => company_info('currency', '$') . number_format(($row->paidAmount ?? 0) - ($row->amount * 0.15), 2))

        ->addColumn('account_details', function ($row) {
            return $row->account_details
                ? nl2br(e($row->account_details))
                : 'N/A';
        })

        ->addColumn('status', fn($row) => '<span class="badge bg-danger">Cancelled</span>')

        ->editColumn('amount', function ($row) {
            return company_info('currency', '$') . number_format($row->amount, 2);
        })

        ->addColumn('reason', function ($row) {
            return $row->reason ?? 'N/A'; // cancellation reason
        })

        ->addColumn('action', function ($row) {
            return '<a href="' . route('superadmin.withdrwalsrequest.detail', $row->id) . '" class="btn btn-sm btn-info">View</a>';
        })

        ->addColumn('date', fn($row) => $row->date)

        ->rawColumns(['status', 'action', 'account_details'])

        ->make(true);
}













}