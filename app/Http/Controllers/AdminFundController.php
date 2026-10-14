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
use App\Models\AdminPowerBusiness;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Yajra\DataTables\Facades\DataTables;



class AdminFundController extends Controller

{
    
/**
     * Show the form to add manual power business.
     */
    public function addPowerIndex(Request $request)
    {
        return view('admin.fund.addpower');
    }

    /**
     * Store the admin power business entry.
     */
    public function addPower(Request $request)
    {
        // Validation
        $request->validate([
            'username'      => 'required|exists:users,username',
            'matching_type' => 'required|in:topup,retopup',
            'position'      => 'required|in:1,2', // 1 = Left, 2 = Right
            'amount'        => 'required|numeric|min:1', // BV Amount
            'remark'        => 'nullable|string|max:255',
        ], [
            'username.exists' => 'This username does not exist in our records.',
            'matching_type.in' => 'Please select a valid matching type (Purchase or RePurchase).',
            'position.in'     => 'Please select a valid position (Left or Right).',
        ]);

        // Fetch User by username
        $user = User::where('username', $request->username)->firstOrFail();

        // Create the Power Business Entry
        AdminPowerBusiness::create([
            'user_id'       => $user->id,
            'matching_type' => $request->matching_type,
            'position'      => $request->position,
            'bv_amount'     => $request->amount,
            'remark'        => $request->remark ?? 'Admin Power Business Added',
        ]);

        // The binary cron command will read this data automatically during closing
        return back()->with('success', 'Power business added successfully to the user.');
    }
    
    
    /**
     * Display the Admin Power Business History view page.
     */
    public function powerBusinessHistoryIndex(Request $request)
    {
        return view('admin.fund.powerhistory');
    }

    /**
     * Fetch and Stream Admin Power Business Data for Server-Side DataTable.
     */
    public function powerBusinessHistoryList(Request $request)
    {
        if ($request->ajax()) {
            $history = AdminPowerBusiness::with('user')
                ->orderBy('created_at', 'desc');

            return DataTables::of($history)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return optional($row->user)->username ?? 'N/A';
                })
                ->editColumn('matching_type', function ($row) {
                    return $row->matching_type === 'topup' ? 'Purchase (Topup)' : 'RePurchase (Retopup)';
                })
                ->editColumn('position', function ($row) {
                    return $row->position == 1 ? 'Left Side' : 'Right Side';
                })
                ->editColumn('bv_amount', function ($row) {
                    return number_format($row->bv_amount, 2);
                })
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i');
                })
                ->rawColumns(['username', 'bv_amount'])
                ->make(true);
        }
    }

    /**
     * Stream CSV Export for Admin Power Business Logs.
     */
    public function powerBusinessExportCSV()
    {
        $records = AdminPowerBusiness::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=admin_power_business_history.csv"
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');

            // Header row layout mapping your display columns
            fputcsv($file, [
                '#',
                'User',
                'Business Type',
                'Binary Side Position',
                'BV Amount',
                'Date Granted',
                'Remark'
            ]);

            $i = 1;
            foreach ($records as $row) {
                fputcsv($file, [
                    $i++,
                    $row->user?->username ?? 'N/A',
                    $row->matching_type === 'topup' ? 'Purchase' : 'RePurchase',
                    $row->position == 1 ? 'Left' : 'Right',
                    number_format($row->bv_amount, 2),
                    \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i'),
                    $row->remark
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function addFundindex(Request $request)

    {

        // Get active admin wallets from advance_info

        $activeWalletsJson = AdvanceInfo::where('label', 'admin_transfer_wallets')->value('value');

        $activeWallets = json_decode($activeWalletsJson, true) ?? [];

        // Get wallet types that are marked active in advance_info

        $wallets = WalletType::where('wallet_type', 'wallet')

            ->where('status', true)

            ->get()

            ->filter(function ($wallet) use ($activeWallets) {

                return isset($activeWallets[$wallet->slug]) && $activeWallets[$wallet->slug] == 1;

            });

        return view('admin.fund.addfund', compact('wallets'));

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

        return view('admin.fund.transferfundhistory');

    }

    /* ================= TRANSFER FUND CSV EXPORT ================= */

    public function transferFundExportCSV()

    {

        $transactions = Transaction::with('user')

            ->where('debit_credit', 'credit')

            ->where('tx_type', 'admin_transfer')

            ->orderBy('date', 'desc')

            ->get();

        $headers = [

            "Content-Type" => "text/csv",

            "Content-Disposition" => "attachment; filename=transfer_fund_transactions.csv"

        ];

        $callback = function () use ($transactions) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [

                '#',

                'User',

                'Type',

                'Wallet',

                'Amount',

                'Debit/Credit',

                'Date',

                'Remark'

            ]);

            $i = 1;

            foreach ($transactions as $row) {

                fputcsv($file, [

                    $i++,

                    $row->user?->username ?? 'N/A',

                    $row->tx_type,

                    $row->wallet_type,

                    number_format($row->amount, 2),

                    $row->debit_credit,

                    \Carbon\Carbon::parse($row->date)->format('Y-m-d H:i'),

                    $row->remark

                ]);

            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);

    }

    public function TransferFundtransactionslist(Request $request)

    {

        if ($request->ajax()) {

            $transactions = Transaction::with('user')

                ->where('debit_credit', 'credit') // Filter only credit transactions

                ->where('tx_type', 'admin_transfer')

                ->orderBy('date', 'desc');

            return DataTables::of($transactions)

                ->addIndexColumn()

                ->addColumn('username', function ($row) {

                    return optional($row->user)->username ?? 'N/A';

                })

                ->editColumn('amount', function ($row) {

                    return number_format($row->amount, 2);

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

        return view('admin.fund.retrievefund', compact('wallets'));

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

        return view('admin.fund.retrievefundhistory');

    }

    public function retrievefundtransactionslist(Request $request)

    {

        if ($request->ajax()) {

            $transactions = Transaction::with('user')

                ->where('tx_type', 'admin_retrieve')

                ->where('debit_credit', 'debit')

                ->orderBy('date', 'desc');

            return DataTables::of($transactions)

                ->addIndexColumn()

                ->addColumn('username', function ($row) {

                    return optional($row->user)->username ?? 'N/A';

                })

                ->editColumn('amount', function ($row) {

                    return number_format($row->amount, 2);

                })

                ->editColumn('date', function ($row) {

                    return \Carbon\Carbon::parse($row->date)->format('Y-m-d H:i');

                })

                ->rawColumns(['username', 'amount'])

                ->make(true);

        }

    }

    public function exportRetrieveFundCsv()

    {

        $filename = "retrieve_fund_transactions_" . date('Ymd_His') . ".csv";

        $transactions = Transaction::with('user')

            ->where('debit_credit', 'debit')

            ->orderBy('date', 'desc')

            ->get();

        $headers = [

            "Content-Type" => "text/csv",

            "Content-Disposition" => "attachment; filename=$filename",

        ];

        $callback = function () use ($transactions) {

            $file = fopen('php://output', 'w');

            // CSV Header

            fputcsv($file, [

                '#',

                'Username',

                'Type',

                'Wallet',

                'Amount',

                'Debit/Credit',

                'Date',

                'Remark'

            ]);

            foreach ($transactions as $index => $row) {

                fputcsv($file, [

                    $index + 1,

                    optional($row->user)->username ?? 'N/A',

                    $row->tx_type,

                    $row->wallet_type,

                    number_format($row->amount, 2),

                    $row->debit_credit,

                    $row->date,

                    $row->remark,

                ]);

            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);

    }

    public function fundrequestindex(Request $request)

    {

        $pendingfund = Transaction::where('status', 0)

            ->where('tx_type', 'user_deposit')

         

            ->count();

        Transaction::where('status', 0)

            ->where('tx_type', 'user_deposit')

            ->where('is_seen', 0)

            ->update(['is_seen' => true]);

        return view('admin.fund.pendingfundrequest', compact('pendingfund'));

    }

    public function exportCSV(Request $request)

    {

        $ids = explode(',', $request->ids);

        $data = Transaction::whereIn('id', $ids)->get();

        $headers = [

            "Content-Type" => "text/csv",

            "Content-Disposition" => "attachment; filename=pending_fund_requests.csv"

        ];

        $callback = function () use ($data) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [

                'User',

                'Amount',

                'Wallet Address',

                'Transaction Hash',

                'Method',

                'Date'

            ]);

            foreach ($data as $row) {

                fputcsv($file, [

                    $row->user ? $row->user->username : $row->u_code,

                    $row->amount,

                    $row->cripto_address,

                    $row->tx_hash,

                    $row->payment_type,

                    $row->date

                ]);

            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);

    }

    public function fundrequestlist(Request $request)

    {

        $query = Transaction::where('status', 0)->where('tx_type', 'user_deposit')

            ->with('user')

            ->select('transaction.*'); // Make sure table name is correct

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('tx_user', function ($row) {

                return $row->user ? $row->user->username : $row->u_code;

            })

            ->addColumn('utr_number', function ($row) {

                if (!$row->tx_hash) {

                    return 'N/A';

                }

                $short = Str::limit($row->tx_hash, 15, '...');

                return '

        <div class="text-center">

            <span class="copy-text" data-copy="' . $row->tx_hash . '">' . $short . '</span>

            <button class="btn btn-xs btn-outline-secondary ms-1 copy-btn">Copy</button>

        </div>

    ';

            })

            ->addColumn('cripto_address', function ($row) {

                if (!$row->cripto_address) {

                    return 'N/A';

                }

                $short = Str::limit($row->cripto_address, 15, '...');

                return '

        <div class="text-center">

            <span class="copy-text" data-copy="' . $row->cripto_address . '">' . $short . '</span>

            <button class="btn btn-xs btn-outline-secondary ms-1 copy-btn">Copy</button>

        </div>

    ';

            })

            ->addColumn('payment_slip', function ($row) {

                if ($row->payment_slip) {

                    return '<a href="' . asset('storage/' . $row->payment_slip) . '" target="_blank" class="btn btn-sm btn-primary">View Slip</a>';

                }

                return 'N/A';

            })

            ->addColumn('status', function ($row) {

                // Since showing only status=0, you can just return 'Pending' with badge

                return '<span class="badge bg-warning text-dark">Pending</span>';

            })

            ->addColumn('action', function ($row) {

                return '<a href="' . route('admin.transferfundtransactionsrequest.detail', $row->id) . '" class="btn btn-sm btn-info">View Details</a>';

            })

            ->addColumn('date', function ($row) {

                return $row->date;

            })

            ->rawColumns(['payment_slip', 'status', 'action', 'cripto_address', 'utr_number'])

            ->make(true);

    }

    public function fundrequestDetail($id)

    {

        $transaction = Transaction::with('user')->findOrFail($id);

        return view('admin.fund.detail', compact('transaction'));

    }

    public function bulkApprove(Request $request)

    {

        DB::transaction(function () use ($request) {

            $transactions = Transaction::whereIn('id', $request->ids)

                ->where('status', 0)

                ->get();

            foreach ($transactions as $tx) {

                $tx->update(['status' => 1]);

                UserWallet::updateOrCreate(

                    ['u_code' => $tx->u_code],

                    ['c2' => DB::raw("c2 + {$tx->amount}")]

                );

            }

        });

        return response()->json(['success' => true]);

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

                    'c4' => $transaction->amount

                ]);

            } else {

                // Else, increment c4 by the transaction amount

                $wallet->increment('c4', $transaction->amount);

            }

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);

        }

    }

    public function bulkReject(Request $request)

    {

        Transaction::whereIn('id', $request->ids)

            ->where('status', 0)

            ->update([

                'status' => 2,

                'reason' => $request->reason

            ]);

        return response()->json(['success' => true]);

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

        return view('admin.fund.approvedfundrequest');

    }

    public function approvefundrequestlist(Request $request)

    {

        $query = Transaction::where('status', 1)->where('tx_type', 'user_deposit')

            ->with('user')

            ->select('transaction.*');

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('tx_user', function ($row) {

                return $row->user ? $row->user->username : $row->u_code;

            })

            ->addColumn('utr_number', function ($row) {

                return $row->tx_hash ?? '';

            })

            ->addColumn('payment_slip', function ($row) {

                if ($row->payment_slip) {

                    return '<a href="' . asset('storage/' . $row->payment_slip) . '" target="_blank" class="btn btn-sm btn-primary">View Slip</a>';

                }

                return 'N/A';

            })

            ->addColumn('status_button', function ($row) {

                return '<span class="badge bg-success ">Approved</span>';

            })

            ->addColumn('date', function ($row) {

                return $row->date;

            })

            ->rawColumns(['payment_slip', 'status_button']) // Allow HTML

            ->make(true);

    }

    public function approvedFundRequestExport()

    {

        $filename = "approved_fund_requests_" . date('Ymd_His') . ".csv";

        $transactions = Transaction::where('status', 1)

            ->where('tx_type', 'user_deposit')

            ->with('user')

            ->orderBy('date', 'desc')

            ->get();

        $headers = [

            "Content-Type" => "text/csv",

            "Content-Disposition" => "attachment; filename=$filename",

        ];

        $callback = function () use ($transactions) {

            $file = fopen('php://output', 'w');

            // CSV Header

            fputcsv($file, [

                'S No',

                'Username',

                'Debit/Credit',

                'Amount',

                'Payment Method',

                'Type',

                'UTR Number',

                'Status',

                'Date'

            ]);

            foreach ($transactions as $index => $row) {

                fputcsv($file, [

                    $index + 1,

                    $row->user->username ?? $row->u_code,

                    $row->debit_credit,

                    $row->amount,

                    $row->payment_type,

                    $row->tx_type,

                    $row->tx_hash,

                    'Approved',

                    $row->date,

                ]);

            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);

    }

    public function cancelledfundrequestindex(Request $request)

    {

        return view('admin.fund.cancelledfundrequest');

    }

    public function cancelledfundrequestlist(Request $request)

    {

        $query = Transaction::where('status', 2)->where('tx_type', 'user_deposit')

            ->with('user')

            ->select('transaction.*');

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('tx_user', function ($row) {

                return $row->user ? $row->user->username : $row->u_code;

            })

            ->addColumn('utr_number', function ($row) {

                return $row->tx_hash ?? '';

            })

            ->addColumn('payment_slip', function ($row) {

                if ($row->payment_slip) {

                    return '<a href="' . asset('storage/' . $row->payment_slip) . '" target="_blank" class="btn btn-sm btn-primary">View Slip</a>';

                }

                return 'N/A';

            })

            ->addColumn('status_button', function ($row) {

                return '<span class="badge bg-danger">Cancelled</span>';

            })

            ->addColumn('date', function ($row) {

                return $row->date;

            })

            ->rawColumns(['payment_slip', 'status_button']) // Allow HTML

            ->make(true);

    }

    public function cancelledFundRequestExport()

    {

        $filename = "cancelled_fund_requests_" . date('Ymd_His') . ".csv";

        $transactions = Transaction::where('status', 2)

            ->where('tx_type', 'user_deposit')

            ->with('user')

            ->orderBy('date', 'desc')

            ->get();

        $headers = [

            "Content-Type" => "text/csv",

            "Content-Disposition" => "attachment; filename=$filename",

        ];

        $callback = function () use ($transactions) {

            $file = fopen('php://output', 'w');

            // CSV Header

            fputcsv($file, [

                'S No',

                'Username',

                'Amount',

                'Payment Method',

                'Type',

                'UTR Number',

                'Status',

                'Reason',

                'Date'

            ]);

            foreach ($transactions as $index => $row) {

                fputcsv($file, [

                    $index + 1,

                    $row->user->username ?? $row->u_code,

                    $row->amount,

                    $row->payment_type,

                    $row->tx_type,

                    $row->tx_hash,

                    'Cancelled',

                    $row->reason ?? '',

                    $row->date,

                ]);

            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);

    }

    public function fundConvertHistory()

    {

        return view('admin.fund.fundconverthistory');

    }

    public function fundConvertHistoryData(Request $request)

    {

        $query = Transaction::with('user') // now works correctly

            ->where('tx_type', 'Internal Transfer')

            ->orderBy('date', 'desc');

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('tx_user', function ($row) {

                return $row->user ? $row->user->username : $row->u_code;

            })

            ->editColumn('debit_credit', function ($row) {

                return ucfirst($row->debit_credit);

            })

            ->editColumn('amount', function ($row) {

                return number_format($row->amount, 2);

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

