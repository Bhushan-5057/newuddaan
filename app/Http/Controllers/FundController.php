<?php   

namespace App\Http\Controllers;



use Illuminate\Http\Request;

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

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;



use Yajra\DataTables\Facades\DataTables;



class FundController extends Controller

{

    public function addFundindex(Request $request)

    {

        $wallets = WalletType::where('wallet_type', 'wallet')->where('status', true)->get();



        return view('superadmin.fund.addfund', compact('wallets'));

    }   







public function addFund(Request $request)

{

    $request->validate([

        'username' => 'required|exists:users,username',

        'amount' => 'required|numeric|min:0.01',

        'wallet_type' => 'required',

    ]);



    $username = $request->username;

    $wallet = $request->wallet_type; // e.g., 'c1', 'c2'

    $amount = $request->amount;



    // Get user

    $user = User::where('username', $username)->firstOrFail();



    // Find or create wallet entry

    $userWallet = UserWallet::firstOrNew(['u_code' => $user->id]);



    // Get current wallet balance

    $currentAmount = $userWallet->$wallet ?? 0;



    // Update wallet

    $userWallet->$wallet = $currentAmount + $amount;

    $userWallet->save();



    // Get wallet name for log

    $walletName = $wallet === 'c1' ? 'main_wallet' : ($wallet === 'c2' ? 'fund_wallet' : $wallet);



    // Log transaction

    Transaction::create([

        'tx_u_code'       => 'admin', // indicates admin transferred

        'u_code'          => $user->id,

        'tx_type'         => 'admin_transfer',

        'debit_credit'    => 'credit',

        'source'          => 'admin_panel',

        'wallet_type'     => $walletName,

        'ftm_amnt'        => $amount,

        'amount'          => $amount,

        'tx_charge'       => 0.00,

        'payment_type'    => 'manual',

        'payment_slip'    => '',

        'date'            => Carbon::now(),

        'status'          => 1,

        'open_wallet'     => $currentAmount,

        'closing_wallet'  => $currentAmount + $amount,

        'remark'          => 'Admin added fund to ' . $walletName,

        'request_amount'  => $amount,

        'paidAmount'      => $amount,

        'added_on'        => Carbon::now(),

        'updated_on'      => Carbon::now(),

    ]);



    return back()->with('success', 'Fund added successfully and transaction logged.');

}



 public function transferfundtransactionsindex(Request $request)

    {





        return view('superadmin.fund.transferfundhistory');

    }   

    

public function TransferFundtransactionslist(Request $request)

{

    if ($request->ajax()) {

        $transactions = Transaction::with('user')

            ->where('debit_credit', 'credit') // Filter only credit transactions

            ->orderBy('date', 'desc');



        return DataTables::of($transactions)

            ->addIndexColumn()

            ->addColumn('username', function ($row) {

                return optional($row->user)->username ?? 'N/A';

            })

           ->editColumn('amount', function ($row) {
    return company_info('currency', '$') . number_format($row->amount, 2);
})


            ->editColumn('date', function ($row) {

                return \Carbon\Carbon::parse($row->date)->format('Y-m-d H:i');

            })

            ->rawColumns(['username', 'amount'])

            ->make(true);

    }

}



 public function RetrieveFundindex(Request $request)

    {

        $wallets = WalletType::where('wallet_type', 'wallet')->where('status', true)->get();



        return view('superadmin.fund.retrievefund', compact('wallets'));

    }   

    

  public function retrieveFund(Request $request)

{

    $request->validate([

        'username'     => 'required|exists:users,username',

        'amount'       => 'required|numeric|min:0.01',

        'wallet_type'  => 'required',

    ]);



    $username = $request->username;

    $wallet = $request->wallet_type; // e.g., 'c1', 'c2'

    $amount = $request->amount;



    // Get user

    $user = User::where('username', $username)->firstOrFail();



    // Find or create wallet entry

    $userWallet = UserWallet::firstOrNew(['u_code' => $user->id]);



    // Get current wallet balance

    $currentAmount = $userWallet->$wallet ?? 0;



    // Check if user has enough funds

    if ($currentAmount < $amount) {

        return back()->with('error', 'User does not have enough funds in the selected wallet.');

    }



    // Update wallet (deduct)

    $userWallet->$wallet = $currentAmount - $amount;

    $userWallet->save();



    // Get wallet name for log

    $walletName = $wallet === 'c1' ? 'main_wallet' : ($wallet === 'c2' ? 'fund_wallet' : $wallet);



    // Log transaction

    Transaction::create([

        'tx_u_code'       => 'admin', // indicates admin retrieved

        'u_code'          => $user->id,

        'tx_type'         => 'admin_retrieve',

        'debit_credit'    => 'debit',

        'source'          => 'admin_panel',

        'wallet_type'     => $walletName,

        'ftm_amnt'        => $amount,

        'amount'          => $amount,

        'tx_charge'       => 0.00,

        'payment_type'    => 'manual',

        'payment_slip'    => '',

        'date'            => \Carbon\Carbon::now(),

        'status'          => 1,

        'open_wallet'     => $currentAmount,

        'closing_wallet'  => $currentAmount - $amount,

        'remark'          => 'Admin retrieved fund from ' . $walletName,

        'added_on'        => \Carbon\Carbon::now(),

        'updated_on'      => \Carbon\Carbon::now(),

    ]);



    return back()->with('success', 'Fund retrieved successfully and transaction logged.');

}

 public function retrievefundtransactionsindex(Request $request)

    {

        return view('superadmin.fund.retrievefundhistory');

    }   

    

public function retrievefundtransactionslist(Request $request)

{

    if ($request->ajax()) {

        $transactions = Transaction::with('user')

            ->where('debit_credit', 'debit') // Filter only credit transactions

            ->orderBy('date', 'desc');



        return DataTables::of($transactions)

            ->addIndexColumn()

            ->addColumn('username', function ($row) {

                return optional($row->user)->username ?? 'N/A';

            })

            ->editColumn('amount', function ($row) {

                return company_info('currency', '$') . number_format($row->amount, 2);

            })

            ->editColumn('date', function ($row) {

                return \Carbon\Carbon::parse($row->date)->format('Y-m-d H:i');

            })

            ->rawColumns(['username', 'amount'])

            ->make(true);

    }

}





public function fundrequestindex(Request $request)

{




    $pendingfund = Transaction::where('status', 0)
        ->where('tx_type', 'user_deposit')
        ->where('is_seen', 0)
        ->count();

     Transaction::where('status', 0)
        ->where('tx_type', 'user_deposit')
        ->where('is_seen', 0)
        ->update(['is_seen' => true]);

    
    return view('superadmin.fund.pendingfundrequest', compact('pendingfund'));




  
}



public function fundrequestlist(Request $request)

{

    $query = Transaction::where('status', 0)->where('tx_type', 'user_deposit')

        ->with('user')

        ->select('transaction.*'); // Make sure table name is correct



    return DataTables::of($query)

        ->addIndexColumn()

        ->addColumn('tx_user', function($row) {

            return $row->user ? $row->user->username : $row->u_code;

        })

        ->addColumn('utr_number', function($row){

            return $row->tx_hash ?? '';

        })
 ->editColumn('amount', function ($row) {

                return company_info('currency', '$') . number_format($row->amount, 2);

            })
        ->addColumn('payment_slip', function($row){

            if ($row->payment_slip) {

                return '<a href="'.asset('storage/'.$row->payment_slip).'" target="_blank" class="btn btn-sm btn-primary">View Slip</a>';

            }

            return 'N/A';

        })

        ->addColumn('status', function($row){

            // Since showing only status=0, you can just return 'Pending' with badge

            return '<span class="badge bg-warning text-dark">Pending</span>';

        })

     ->addColumn('action', function($row){

    return '<a href="'.route('superadmin.transferfundtransactionsrequest.detail', $row->id).'" class="btn btn-sm btn-info">View Details</a>';

})



        ->addColumn('date', function($row){

            return $row->date;

        })

        ->rawColumns(['payment_slip', 'status', 'action']) // Allow HTML rendering for these columns

        ->make(true);

}



public function fundrequestDetail($id)

{

    $transaction = Transaction::with('user')->findOrFail($id);

    return view('superadmin.fund.detail', compact('transaction'));

}





public function approve(Request $request)

{

    $id = $request->id;



    // Start DB transaction

    DB::beginTransaction();



    try {

        $transaction = Transaction::find($id);



        if (!$transaction) {

            return response()->json(['success' => false, 'message' => 'Transaction not found']);

        }



        if ($transaction->status != 0) {

            return response()->json(['success' => false, 'message' => 'Only pending transactions can be approved']);

        }



        // Update transaction

        $transaction->status = 1;

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



        DB::commit();

        return response()->json(['success' => true]);



    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json(['success' => false, 'message' => $e->getMessage()]);

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



    return response()->json(['success' => true]);

}







public function approvefundrequestindex(Request $request)

{

    return view('superadmin.fund.approvedfundrequest');

}



public function approvefundrequestlist(Request $request)

{

    $query = Transaction::where('status', 1)->where('tx_type', 'user_deposit')

        ->with('user')

        ->select('transaction.*');





    return DataTables::of($query)

    ->addIndexColumn()

    ->addColumn('tx_user', function($row) {

        return $row->user ? $row->user->username : $row->u_code;

    })

    ->addColumn('utr_number', function($row){

        return $row->tx_hash ?? '';

    })

    ->addColumn('payment_slip', function($row){

        if ($row->payment_slip) {

            return '<a href="'.asset('storage/'.$row->payment_slip).'" target="_blank" class="btn btn-sm btn-primary">View Slip</a>';

        }

        return 'N/A';

    })
->editColumn('amount', function ($row) {

                return company_info('currency', '$') . number_format($row->amount, 2);

            })
    ->addColumn('status_button', function($row){

        return '<span class="badge bg-success ">Approved</span>';

    })

    ->addColumn('date', function($row){

        return $row->date;

    })

    ->rawColumns(['payment_slip', 'status_button']) // Allow HTML

    ->make(true);



}



public function cancelledfundrequestindex(Request $request)

{

    return view('superadmin.fund.cancelledfundrequest');

}



public function cancelledfundrequestlist(Request $request)

{

    $query = Transaction::where('status', 2)->where('tx_type', 'user_deposit')

        ->with('user')

        ->select('transaction.*');





    return DataTables::of($query)

    ->addIndexColumn()

    ->addColumn('tx_user', function($row) {

        return $row->user ? $row->user->username : $row->u_code;

    })

    ->addColumn('utr_number', function($row){

        return $row->tx_hash ?? '';

    })

    ->addColumn('payment_slip', function($row){

        if ($row->payment_slip) {

            return '<a href="'.asset('storage/'.$row->payment_slip).'" target="_blank" class="btn btn-sm btn-primary">View Slip</a>';

        }

        return 'N/A';

    })
->editColumn('amount', function ($row) {

                return company_info('currency', '$') . number_format($row->amount, 2);

            })
    ->addColumn('status_button', function($row){

        return '<span class="badge bg-danger">Cancelled</span>';

    })

    ->addColumn('date', function($row){

        return $row->date;

    })

    ->rawColumns(['payment_slip', 'status_button']) // Allow HTML

    ->make(true);



}



public function fundConvertHistory()

{

    return view('superadmin.fund.fundconverthistory');

}







public function fundConvertHistoryData(Request $request)

{

    $query = Transaction::with('user') // now works correctly

        ->where('tx_type', 'Internal Transfer')

        ->orderBy('date', 'desc');



    return DataTables::of($query)

        ->addIndexColumn()

        ->addColumn('tx_user', function($row) {

            return $row->user ? $row->user->username : $row->u_code;

        })

        ->editColumn('debit_credit', function ($row) {

            return ucfirst($row->debit_credit);

        })

       ->editColumn('amount', function ($row) {

                return company_info('currency', '$') . number_format($row->amount, 2);

            })

        ->editColumn('date', function ($row) {

            return \Carbon\Carbon::parse($row->date)->format('d-m-Y h:i A');

        })

        ->editColumn('remark', function ($row) {

            return $row->remark ?? '-';

        })

        ->make(true);

}

















}