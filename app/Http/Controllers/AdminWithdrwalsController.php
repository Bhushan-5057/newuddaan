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
use App\Models\Order;
use App\Models\UserPaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AdminWithdrwalsController extends Controller
{
    public function withdrwalsrequestindex(Request $request)
    {
        // 🔹 store return url
        session(['withdraw_return_url' => url()->current()]);
        $pendingCount = Transaction::where('status', 0)
            ->where('tx_type', 'withdrawal')
            // ->where('is_seen', 0)
            ->count();
        Transaction::where('status', 0)
            ->where('tx_type', 'withdrawal')
            ->where('is_seen', 0)
            ->update(['is_seen' => true]);
        return view('admin.withdrwals.pendingwithdrwalsrequest', compact('pendingCount'));
    }
    /* ================= LIST ================= */
    /* ================= LIST ================= */
    public function withdrwalsrequestlist(Request $request)
    {
        $baseQuery = Transaction::with('user')
            ->where('tx_type', 'withdrawal')
            ->where('status', 0);

        /* ================= TOTAL ================= */
        $totalCount       = (clone $baseQuery)->count();
        $totalAmount      = (clone $baseQuery)->sum('amount');
        $totalPaidAmount  = (clone $baseQuery)->sum('paidAmount');

        /* ================= FILTER ================= */
        if ($request->filled('from_date')) {
            $baseQuery->whereDate('added_on', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $baseQuery->whereDate('added_on', '<=', $request->to_date);
        }

        /* ================= SEARCH ================= */
        if ($search = $request->input('search.value')) {
            $baseQuery->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%");
            });
        }

        /* ================= FILTERED TOTAL ================= */
        $filteredCount      = (clone $baseQuery)->count();
        $filteredAmount     = (clone $baseQuery)->sum('amount');
        $filteredPaidAmount = (clone $baseQuery)->sum('paidAmount');

        /* ================= PAGINATION ================= */
        $start  = $request->start ?? 0;
        $length = $request->length ?? 25;

        $rows = $baseQuery
            ->orderBy('id', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        $currency = company_info('currency', '₹');
        $data = [];

        foreach ($rows as $i => $tx) {

            /* ========= ACCOUNT DETAILS HTML ========= */
            $accountDetails = 'N/A';

            if ($tx->bank_details) {
                $details = json_decode($tx->bank_details, true);

                if ($details) {

                    // BANK
                    if (
                        isset($details['account_type']) &&
                        strtolower($details['account_type']) === 'bank'
                    ) {
                        $accountDetails = "
                        <strong>Type:</strong> Bank<br>
                        <strong>Bank:</strong> " . ($details['bank_name'] ?? 'N/A') . "<br>
                        <strong>Holder:</strong> " . ($details['account_holder_name'] ?? 'N/A') . "<br>
                        <strong>Account No:</strong> " . ($details['account_no'] ?? 'N/A') . "<br>
                        <strong>IFSC:</strong> " . ($details['ifsc_code'] ?? 'N/A') . "<br>
                        <strong>Branch:</strong> " . ($details['bank_branch'] ?? 'N/A');
                    }
                    // CRYPTO
                    else {
                        $address = $details['userAddress'] ?? 'N/A';
                        $type    = strtoupper($details['account_type'] ?? 'N/A');

                        $accountDetails = "
                        <strong>Type:</strong> {$type}<br>
                        <strong>Address:</strong>
                        <span class='copy-text' data-copy='{$address}'>{$address}</span>
                        <button class='btn btn-xs btn-light copy-btn'>Copy</button>
                    ";
                    }
                }
            }

            /* ================= ROW ================= */
            $data[] = [
                'checkbox' => '<input type="checkbox" class="rowCheckbox" value="' . $tx->id . '">',
                'DT_RowIndex' => $start + $i + 1,
                'tx_user' => $tx->user?->username ?? 'N/A',
                'action' => '
                <a href="' . route('admin.withdrwalsrequest.detail', $tx->id) . '"
                   class="btn btn-xs btn-success">View</a>
            ',
                'amount' => number_format($tx->amount, 2),
                'tx_charge' => number_format($tx->tx_charge, 2),
                'payable_amount' => number_format($tx->paidAmount, 2),
                'account_details' => $accountDetails,
                'status' => '<span class="badge bg-warning">Pending</span>',
                'date' => Carbon::parse($tx->added_on)->format('d-m-Y H:i'),
            ];
        }

        /* ================= RESPONSE ================= */
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalCount,
            'recordsFiltered' => $filteredCount,
            'currency' => $currency,
            'totalAmount' => number_format($totalAmount, 2),
            'totalPaidAmount' => number_format($totalPaidAmount, 2),
            'filteredAmount' => number_format($filteredAmount, 2),
            'filteredPaidAmount' => number_format($filteredPaidAmount, 2),
            'totalCount' => $totalCount,
            'filteredCount' => $filteredCount,
            'data' => $data
        ]);
    }

    /* ================= BULK APPROVE ================= */
    public function bulkApprove(Request $request)
    {
        Transaction::whereIn('id', $request->ids)
            ->where('status', 0)
            ->update(['status' => 1, 'updated_on' => now()]);
        return response()->json(['success' => true]);
    }
    /* ================= BULK REJECT ================= */
    public function bulkReject(Request $request)
    {
        $ids = $request->ids;
        $reason = $request->reason;
        if (empty($ids) || !$reason) {
            return response()->json([
                'success' => false,
                'message' => 'IDs and reason are required'
            ]);
        }
        DB::beginTransaction();
        try {
            $transactions = Transaction::whereIn('id', $ids)
                ->where('status', 0) // only pending
                ->get();
            foreach ($transactions as $transaction) {
                // Update transaction status
                $transaction->status = 2; // rejected
                $transaction->reason = $reason;
                $transaction->updated_on = now();
                $transaction->save();
                // Find or create wallet
                $wallet = UserWallet::where('u_code', $transaction->u_code)->first();
                if (!$wallet) {
                    UserWallet::create([
                        'u_code' => $transaction->u_code,
                        'c1'     => $transaction->amount
                    ]);
                } else {
                    $wallet->increment('c1', $transaction->amount);
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Selected withdrawals rejected and wallet updated'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    /* ================= CSV EXPORT ================= */
    public function exportCSV(Request $request)
    {
        $ids = explode(',', $request->ids);

        $data = Transaction::with('user')
            ->whereIn('id', $ids)
            ->get();

        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=withdrawal_requests_" . date('Y-m-d') . ".csv"
        ];

        $callback = function () use ($data) {
            $f = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($f, [
                'User',
                'Amount',
                'Method',
                'Account / Wallet Details',
                'Charge',
                'Payable',
                'Date'
            ]);

            foreach ($data as $r) {

                $methodType   = 'N/A';
                $accountValue = 'N/A';

                if ($r->bank_details) {
                    $details = json_decode($r->bank_details, true);

                    if ($details) {

                        // BANK ACCOUNT
                        if (
                            isset($details['account_type']) &&
                            strtolower($details['account_type']) === 'bank'
                        ) {
                            $methodType = 'BANK';

                            $accountValue =
                                'Bank: ' . ($details['bank_name'] ?? 'N/A') .
                                ' | Holder: ' . ($details['account_holder_name'] ?? 'N/A') .
                                ' | Acc No: ' . ($details['account_no'] ?? 'N/A') .
                                ' | IFSC: ' . ($details['ifsc_code'] ?? 'N/A') .
                                ' | Branch: ' . ($details['bank_branch'] ?? 'N/A');
                        }
                        // CRYPTO
                        else {
                            $methodType   = strtoupper($details['account_type'] ?? 'CRYPTO');
                            $accountValue = $details['userAddress'] ?? 'N/A';
                        }
                    }
                }

                fputcsv($f, [
                    $r->user?->username ?? $r->u_code ?? 'N/A',
                    $r->amount,
                    $methodType,
                    $accountValue,
                    $r->tx_charge,
                    $r->paidAmount,
                    \Carbon\Carbon::parse($r->added_on)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function withdrwalsrequestDetail($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return view('admin.withdrwals.withdrwalsdetail', compact('transaction'));
    }
    // public function approve(Request $request)
    // {
    //     $id = $request->id;
    //     // Start DB transaction
    //     DB::beginTransaction();
    //     try {
    //         $transaction = Transaction::with('user')->findOrFail($id);
    //         if (!$transaction) {
    //             return response()->json(['success' => false, 'message' => 'Transaction not found']);
    //         }
    //         if ($transaction->status != 0) {
    //             return response()->json(['success' => false, 'message' => 'Only pending transactions can be approved']);
    //         }
    //         // $hasDefault = UserPaymentMethod::where('u_code', $transaction->u_code)
    //         // ->where('default_account', 1)
    //         // ->first();
    //         // if ($hasDefault) {
    //         //     $bank_details_json = $hasDefault->accounts;
    //         //     $bank_details = json_decode($bank_details_json, true); // true for associative array            
    //         //     $userAddress = isset($bank_details[0]['bep20']) ? $bank_details[0]['bep20'] : null;
    //         // }else{
    //         //     return redirect()->back()->withErrors(['amount' => 'Please set a default payment method userAddress.']);
    //         // }
    // $user = User::where('id', $transaction->u_code)->first();
    //         $amount = $transaction->paidAmount;
    //         $userAddress = $user->user_wallet_address;
    //          //dd(['amount'=>$amount,'address'=>$userAddress]);
    //         // Call transfer and decode response
    //         $rawResponse = $this->transfer($userAddress, $amount);
    //         $response = json_decode($rawResponse);
    //        if (isset($response->success) && $response->success) {
    //             // Update transaction
    //             $transaction->status = 1;
    //             $transaction->tx_hash = $response->transactionHash;
    //             $transaction->updated_on = now();
    //             $transaction->save();
    //         }
    //         DB::commit();
    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['success' => false, 'message' => $e->getMessage()]);
    //     }
    // }
    // private function transfer($to, $amount)
    // {
    //     $sendAddress = strtolower($to);
    //     $payAmount = $amount;
    //     try {
    //         $url = "http://62.72.23.66:3000/transfer";
    //         $response = Http::withHeaders([
    //             'Content-Type' => 'application/json'
    //         ])->post($url, [
    //             'to' => $sendAddress,
    //             'amount' => $payAmount,
    //         ]);
    //         $httpCode = $response->status();
    //         $body = $response->body();
    //         Log::error("Request: " . json_encode(['to' => $sendAddress, 'amount' => $payAmount]) .
    //                    " | Response Code: $httpCode | Response: $body");
    //         return $body;
    //     } catch (\Exception $e) {
    //         Log::error('Transfer Exception: ' . $e->getMessage());
    //         return $e->getMessage();
    //     }
    // }
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
            // $hasDefault = UserPaymentMethod::where('u_code', $transaction->u_code)
            // ->where('default_account', 1)
            // ->first();
            // if ($hasDefault) {
            //     $bank_details_json = $hasDefault->accounts;
            //     $bank_details = json_decode($bank_details_json, true); // true for associative array            
            //     $userAddress = isset($bank_details[0]['bep20']) ? $bank_details[0]['bep20'] : null;
            // }else{
            //     return redirect()->back()->withErrors(['amount' => 'Please set a default payment method userAddress.']);
            // }
            $amount = $transaction->paidAmount;
            // $userAddress = $transaction->user->user_wallet_address;
            //  if (!$userAddress) {
            //     return response()->json(['success' => false, 'message' => 'User address not found']);
            // }
            $transaction->status = 1;
            $transaction->updated_on = now();
            $transaction->save();
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
        try {
            $url = "http://62.72.23.66:3000/transfer/";
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
        // 🔹 store return url for back button
        session(['withdraw_return_url' => url()->current()]);
        return view('admin.withdrwals.approvedwithdrwalsrequest');
    }
    /* ================= APPROVED WITHDRAWALS CSV ================= */
    public function exportApprovedCSV()
    {
        // Approved withdrawals
        $data = Transaction::where('status', 1)
            ->where('tx_type', 'withdrawal')
            ->with('user')
            ->get();

        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=approved_withdrawals_" . date('Y-m-d') . ".csv"
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'User',
                'Amount',
                'Tx Charge',
                'Payable Amount',
                'Method',
                'Account / Wallet Details',
                'Status',
                'Date'
            ]);

            foreach ($data as $row) {

                $methodType   = 'N/A';
                $accountValue = 'N/A';

                if ($row->bank_details) {
                    $details = json_decode($row->bank_details, true);

                    if ($details) {

                        // BANK
                        if (
                            isset($details['account_type']) &&
                            strtolower($details['account_type']) === 'bank'
                        ) {
                            $methodType = 'BANK';

                            $accountValue =
                                'Bank: ' . ($details['bank_name'] ?? 'N/A') .
                                ' | Holder: ' . ($details['account_holder_name'] ?? 'N/A') .
                                ' | Acc No: ' . ($details['account_no'] ?? 'N/A') .
                                ' | IFSC: ' . ($details['ifsc_code'] ?? 'N/A') .
                                ' | Branch: ' . ($details['bank_branch'] ?? 'N/A');
                        }
                        // CRYPTO
                        else {
                            $methodType   = strtoupper($details['account_type'] ?? 'CRYPTO');
                            $accountValue = $details['userAddress'] ?? 'N/A';
                        }
                    }
                }

                fputcsv($file, [
                    $row->user?->username ?? $row->u_code ?? 'N/A',
                    $row->amount,
                    $row->tx_charge,
                    $row->paidAmount,
                    $methodType,
                    $accountValue,
                    'Approved',
                    \Carbon\Carbon::parse($row->added_on)->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function approvewithdrwalsrequestlist(Request $request)
    {
        $baseQuery = Transaction::where('status', 1)
            ->where('tx_type', 'withdrawal')
            ->with('user');

        /* ===== TOTAL (NO FILTER) ===== */
        $totalCount      = (clone $baseQuery)->count();
        $totalAmount     = (clone $baseQuery)->sum('amount');
        $totalPaidAmount = (clone $baseQuery)->sum('paidAmount');

        /* ===== DATE FILTER ===== */
        if ($request->filled('from_date')) {
            $baseQuery->whereDate('added_on', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $baseQuery->whereDate('added_on', '<=', $request->to_date);
        }

        /* ===== FILTERED ===== */
        $filteredCount      = (clone $baseQuery)->count();
        $filteredAmount     = (clone $baseQuery)->sum('amount');
        $filteredPaidAmount = (clone $baseQuery)->sum('paidAmount');

        return DataTables::of($baseQuery)
            ->addIndexColumn()

            ->addColumn('tx_user', fn($row) => $row->user?->username ?? $row->u_code)

            ->addColumn('tx_charge', fn($row) => number_format($row->tx_charge ?? 0, 2))

            ->addColumn('payable_amount', fn($row) => number_format($row->paidAmount ?? 0, 2))

            /* ===== ACCOUNT DETAILS ===== */
            ->addColumn('account_details', function ($row) {

                if (!$row->bank_details) {
                    return 'N/A';
                }

                $details = json_decode($row->bank_details, true);
                if (!$details) {
                    return 'N/A';
                }

                // BANK
                if (
                    isset($details['account_type']) &&
                    strtolower($details['account_type']) === 'bank'
                ) {
                    return "
                    <strong>Type:</strong> Bank<br>
                    <strong>Bank:</strong> " . ($details['bank_name'] ?? 'N/A') . "<br>
                    <strong>Holder:</strong> " . ($details['account_holder_name'] ?? 'N/A') . "<br>
                    <strong>Account No:</strong> " . ($details['account_no'] ?? 'N/A') . "<br>
                    <strong>IFSC:</strong> " . ($details['ifsc_code'] ?? 'N/A') . "<br>
                    <strong>Branch:</strong> " . ($details['bank_branch'] ?? 'N/A');
                }

                // CRYPTO
                $address = $details['userAddress'] ?? 'N/A';
                $type    = strtoupper($details['account_type'] ?? 'CRYPTO');

                return "
                <strong>Type:</strong> {$type}<br>
                <span class='copy-text' data-copy='{$address}'>{$address}</span>
                <button class='btn btn-xs btn-light copy-btn ms-1'>Copy</button>
            ";
            })

            ->addColumn('status', fn() => '<span class="badge bg-success">Approved</span>')

            ->addColumn(
                'action',
                fn($row) =>
                '<a href="' . route('admin.withdrwalsrequest.detail', $row->id) . '" class="btn btn-sm btn-info">View</a>'
            )

            ->addColumn(
                'date',
                fn($row) =>
                \Carbon\Carbon::parse($row->added_on)->format('d-m-Y H:i')
            )

            ->rawColumns(['account_details', 'status', 'action'])

            ->with([
                'currency'           => company_info('currency', '₹'),
                'totalAmount'        => number_format($totalAmount, 2),
                'totalPaidAmount'    => number_format($totalPaidAmount, 2),
                'totalCount'         => $totalCount,
                'filteredAmount'     => number_format($filteredAmount, 2),
                'filteredPaidAmount' => number_format($filteredPaidAmount, 2),
                'filteredCount'      => $filteredCount,
            ])

            ->make(true);
    }


    public function cancelledwithdrwalsrequestindex()
    {
        // 🔹 store return url for back button
        session(['withdraw_return_url' => url()->current()]);
        return view('admin.withdrwals.cancelledwithdrwalsrequest');
    }

 public function cancelledwithdrwalsrequestlist(Request $request)
{
    $baseQuery = Transaction::where('status', 2)
        ->where('tx_type', 'withdrawal')
        ->with('user');

    /* ===== TOTAL ===== */
    $totalCount      = (clone $baseQuery)->count();
    $totalAmount     = (clone $baseQuery)->sum('amount');
    $totalPaidAmount = (clone $baseQuery)->sum('paidAmount');

    /* ===== DATE FILTER ===== */
    if ($request->filled('from_date')) {
        $baseQuery->whereDate('added_on', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $baseQuery->whereDate('added_on', '<=', $request->to_date);
    }

    /* ===== FILTERED ===== */
    $filteredCount      = (clone $baseQuery)->count();
    $filteredAmount     = (clone $baseQuery)->sum('amount');
    $filteredPaidAmount = (clone $baseQuery)->sum('paidAmount');

    return DataTables::of($baseQuery)
        ->addIndexColumn()

        ->addColumn('tx_user', fn ($row) => $row->user?->username ?? $row->u_code)

        ->addColumn('tx_charge', fn ($row) => number_format($row->tx_charge ?? 0, 2))

        ->addColumn('payable_amount', fn ($row) => number_format($row->paidAmount ?? 0, 2))

        /* ===== ACCOUNT DETAILS ===== */
        ->addColumn('account_details', function ($row) {

            if (!$row->bank_details) return 'N/A';

            $details = json_decode($row->bank_details, true);
            if (!$details) return 'N/A';

            // BANK
            if (
                isset($details['account_type']) &&
                strtolower($details['account_type']) === 'bank'
            ) {
                return "
                    <strong>Type:</strong> Bank<br>
                    <strong>Bank:</strong> " . ($details['bank_name'] ?? 'N/A') . "<br>
                    <strong>Holder:</strong> " . ($details['account_holder_name'] ?? 'N/A') . "<br>
                    <strong>Account No:</strong> " . ($details['account_no'] ?? 'N/A') . "<br>
                    <strong>IFSC:</strong> " . ($details['ifsc_code'] ?? 'N/A') . "<br>
                    <strong>Branch:</strong> " . ($details['bank_branch'] ?? 'N/A');
            }

            // CRYPTO
            $address = $details['userAddress'] ?? 'N/A';
            $type    = strtoupper($details['account_type'] ?? 'CRYPTO');

            return "
                <strong>Type:</strong> {$type}<br>
                <span class='copy-text' data-copy='{$address}'>{$address}</span>
                <button class='btn btn-xs btn-light copy-btn ms-1'>Copy</button>
            ";
        })

        ->addColumn('status', fn () => '<span class="badge bg-danger">Cancelled</span>')

        ->addColumn('reason', fn ($row) => $row->reason ?? 'N/A')

        ->addColumn(
            'action',
            fn ($row) =>
            '<a href="' . route('admin.withdrwalsrequest.detail', $row->id) . '" class="btn btn-sm btn-info">View</a>'
        )

        ->addColumn('date', fn ($row) =>
            \Carbon\Carbon::parse($row->added_on)->format('d-m-Y H:i')
        )

        ->rawColumns(['account_details', 'status', 'action'])

        ->with([
            'currency'           => company_info('currency', '₹'),
            'totalAmount'        => number_format($totalAmount, 2),
            'totalPaidAmount'    => number_format($totalPaidAmount, 2),
            'totalCount'         => $totalCount,
            'filteredAmount'     => number_format($filteredAmount, 2),
            'filteredPaidAmount' => number_format($filteredPaidAmount, 2),
            'filteredCount'      => $filteredCount,
        ])

        ->make(true);
}
   public function exportCancelledWithdrawalsCsv()
{
    $fileName = 'cancelled_withdrawals_' . date('Y-m-d') . '.csv';

    $transactions = Transaction::where('status', 2)
        ->where('tx_type', 'withdrawal')
        ->with('user')
        ->get();

    $headers = [
        "Content-Type"        => "text/csv",
        "Content-Disposition" => "attachment; filename={$fileName}",
    ];

    $callback = function () use ($transactions) {
        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'User',
            'Amount',
            'Tx Charge',
            'Payable Amount',
            'Method',
            'Account / Wallet Details',
            'Reason',
            'Date'
        ]);

        foreach ($transactions as $row) {

            $methodType   = 'N/A';
            $accountValue = 'N/A';

            if ($row->bank_details) {
                $details = json_decode($row->bank_details, true);

                if ($details) {
                    // BANK
                    if (
                        isset($details['account_type']) &&
                        strtolower($details['account_type']) === 'bank'
                    ) {
                        $methodType = 'BANK';
                        $accountValue =
                            'Bank: ' . ($details['bank_name'] ?? 'N/A') .
                            ' | Holder: ' . ($details['account_holder_name'] ?? 'N/A') .
                            ' | Acc No: ' . ($details['account_no'] ?? 'N/A') .
                            ' | IFSC: ' . ($details['ifsc_code'] ?? 'N/A') .
                            ' | Branch: ' . ($details['bank_branch'] ?? 'N/A');
                    }
                    // CRYPTO
                    else {
                        $methodType   = strtoupper($details['account_type'] ?? 'CRYPTO');
                        $accountValue = $details['userAddress'] ?? 'N/A';
                    }
                }
            }

            fputcsv($file, [
                $row->user?->username ?? $row->u_code ?? 'N/A',
                $row->amount,
                $row->tx_charge,
                $row->paidAmount,
                $methodType,
                $accountValue,
                $row->reason ?? 'N/A',
                \Carbon\Carbon::parse($row->added_on)->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
    // principalwithdrwals



    public function principalwithdrwalsrequestindex()
    {
        session(['withdraw_return_url' => url()->current()]);
        return view('admin.principal_withdrawal.pendingwithdrwalsrequest');
    }

    public function principalwithdrwalsrequestlist(Request $request)
    {
        $baseQuery = Transaction::where('status', 0)
            ->where('tx_type', 'Principal_Withdrawal')
            ->with('user');

        /* ================= TOTAL (NO FILTER) ================= */
        $totalAmount      = (clone $baseQuery)->sum('amount');
        $totalPaidAmount  = (clone $baseQuery)->sum('paidAmount');
        $totalCount       = (clone $baseQuery)->count();

        /* ================= DATE FILTER ================= */
        if ($request->filled('from_date')) {
            $baseQuery->whereDate('added_on', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $baseQuery->whereDate('added_on', '<=', $request->to_date);
        }

        /* ================= FILTERED ================= */
        $filteredAmount     = (clone $baseQuery)->sum('amount');
        $filteredPaidAmount = (clone $baseQuery)->sum('paidAmount');
        $filteredCount      = (clone $baseQuery)->count();

        return DataTables::of($baseQuery)
            ->addIndexColumn()

            ->addColumn('tx_user', fn($row) => $row->user?->username ?? $row->u_code)

            ->addColumn('tx_charge', fn($row) => number_format($row->tx_charge ?? 0, 2))

            ->addColumn('payable_amount', fn($row) => number_format($row->paidAmount ?? 0, 2))

            ->addColumn('order_id', fn($row) => $row->order_id ?? '-')

            ->addColumn(
                'status',
                fn() =>
                '<span class="badge bg-warning text-dark">Pending</span>'
            )

            ->addColumn(
                'action',
                fn($row) =>
                '<a href="' . route('admin.principalwithdrwalsrequest.detail', $row->id) . '"
               class="btn btn-sm btn-info">View</a>'
            )

            ->addColumn(
                'date',
                fn($row) =>
                Carbon::parse($row->added_on)->format('d-m-Y H:i')
            )

            ->rawColumns(['status', 'action'])

            ->with([
                'currency'            => company_info('currency', '₹'),
                'totalAmount'         => number_format($totalAmount, 2),
                'totalPaidAmount'     => number_format($totalPaidAmount, 2),
                'totalCount'          => $totalCount,
                'filteredAmount'      => number_format($filteredAmount, 2),
                'filteredPaidAmount'  => number_format($filteredPaidAmount, 2),
                'filteredCount'       => $filteredCount,
            ])

            ->make(true);
    }

    public function approveprincipalwithdrwalsrequestindex(Request $request)
    {
        session(['withdraw_return_url' => url()->current()]);
        return view('admin.principal_withdrawal.approvedwithdrwalsrequest');
    }



    public function approveprincipalwithdrwalsrequestlist(Request $request)
    {
        $baseQuery = Transaction::where('status', 1)
            ->where('tx_type', 'Principal_Withdrawal')
            ->with('user');

        /* ================= TOTAL ================= */
        $totalAmount     = (clone $baseQuery)->sum('amount');
        $totalPaidAmount = (clone $baseQuery)->sum('paidAmount');
        $totalCount      = (clone $baseQuery)->count();

        /* ================= DATE FILTER ================= */
        if ($request->filled('from_date')) {
            $baseQuery->whereDate('added_on', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $baseQuery->whereDate('added_on', '<=', $request->to_date);
        }

        /* ================= FILTERED ================= */
        $filteredAmount     = (clone $baseQuery)->sum('amount');
        $filteredPaidAmount = (clone $baseQuery)->sum('paidAmount');
        $filteredCount      = (clone $baseQuery)->count();

        return DataTables::of($baseQuery)
            ->addIndexColumn()

            ->addColumn('tx_user', fn($row) => $row->user?->username ?? $row->u_code)

            ->addColumn('tx_charge', fn($row) => number_format($row->tx_charge ?? 0, 2))

            ->addColumn('payable_amount', fn($row) => number_format($row->paidAmount ?? 0, 2))

            ->addColumn('order_id', fn($row) => $row->order_id ?? '-')

            ->addColumn(
                'status',
                fn() =>
                '<span class="badge bg-success">Approved</span>'
            )

            ->addColumn(
                'action',
                fn($row) =>
                '<a href="' . route('admin.principalwithdrwalsrequest.detail', $row->id) . '"
               class="btn btn-sm btn-info">View</a>'
            )

            ->addColumn(
                'date',
                fn($row) =>
                Carbon::parse($row->added_on)->format('d-m-Y H:i')
            )

            ->rawColumns(['status', 'action'])

            ->with([
                'currency'            => company_info('currency', '₹'),
                'totalAmount'         => number_format($totalAmount, 2),
                'totalPaidAmount'     => number_format($totalPaidAmount, 2),
                'totalCount'          => $totalCount,
                'filteredAmount'      => number_format($filteredAmount, 2),
                'filteredPaidAmount'  => number_format($filteredPaidAmount, 2),
                'filteredCount'       => $filteredCount,
            ])

            ->make(true);
    }




    public function cancelledprincipalwithdrwalsrequestindex()
    {
        session(['withdraw_return_url' => url()->current()]);
        return view('admin.principal_withdrawal.cancelledwithdrwalsrequest');
    }

    public function cancelledprincipalwithdrwalsrequestlist(Request $request)
    {
        $baseQuery = Transaction::where('status', 2)
            ->where('tx_type', 'Principal_Withdrawal')
            ->with('user');

        /* ================= TOTAL ================= */
        $totalAmount     = (clone $baseQuery)->sum('amount');
        $totalPaidAmount = (clone $baseQuery)->sum('paidAmount');
        $totalCount      = (clone $baseQuery)->count();

        /* ================= DATE FILTER ================= */
        if ($request->filled('from_date')) {
            $baseQuery->whereDate('added_on', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $baseQuery->whereDate('added_on', '<=', $request->to_date);
        }

        /* ================= FILTERED ================= */
        $filteredAmount     = (clone $baseQuery)->sum('amount');
        $filteredPaidAmount = (clone $baseQuery)->sum('paidAmount');
        $filteredCount      = (clone $baseQuery)->count();

        return DataTables::of($baseQuery)
            ->addIndexColumn()

            ->addColumn('tx_user', fn($row) => $row->user?->username ?? $row->u_code)

            ->addColumn('tx_charge', fn($row) => number_format($row->tx_charge ?? 0, 2))

            ->addColumn('payable_amount', fn($row) => number_format($row->paidAmount ?? 0, 2))

            ->addColumn('order_id', fn($row) => $row->order_id ?? '-')

            ->addColumn(
                'status',
                fn() =>
                '<span class="badge bg-danger">Cancelled</span>'
            )

            ->addColumn('reason', fn($row) => $row->reason ?? 'N/A')

            ->addColumn(
                'action',
                fn($row) =>
                '<a href="' . route('admin.principalwithdrwalsrequest.detail', $row->id) . '"
               class="btn btn-sm btn-info">View</a>'
            )

            ->addColumn(
                'date',
                fn($row) =>
                Carbon::parse($row->added_on)->format('d-m-Y H:i')
            )

            ->rawColumns(['status', 'action'])

            ->with([
                'currency'            => company_info('currency', '₹'),
                'totalAmount'         => number_format($totalAmount, 2),
                'totalPaidAmount'     => number_format($totalPaidAmount, 2),
                'totalCount'          => $totalCount,
                'filteredAmount'      => number_format($filteredAmount, 2),
                'filteredPaidAmount'  => number_format($filteredPaidAmount, 2),
                'filteredCount'       => $filteredCount,
            ])

            ->make(true);
    }


    public function principalwithdrwalsrequestDetail($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return view('admin.principal_withdrawal.withdrwalsdetail', compact('transaction'));
    }
    public function principalapprove(Request $request)
    {
        $id = $request->id;
        DB::beginTransaction();
        try {
            // Load transaction with user relation
            $transaction = Transaction::with('user')->findOrFail($id);
            if ($transaction->status != 0) {
                return response()->json(['success' => false, 'message' => 'Only pending transactions can be approved']);
            }
            $userWalletAddress = $transaction->user->name; // ✅ Replace 'name' with actual wallet address field
            $amount = $transaction->paidAmount;
            // Execute external transfer
            $rawResponse = $this->transfer($userWalletAddress, $amount);
            $response = json_decode($rawResponse);
            if (isset($response->success) && $response->success) {
                // ✅ Update transaction
                $transaction->status = 1;
                $transaction->tx_hash = $response->transactionHash ?? null;
                $transaction->updated_on = now();
                $transaction->save();
                // ✅ Update order status to 2 (withdrawal complete)
                Order::where('tx_id', $transaction->order_id)->update(['status' => 2]);
                DB::commit();
                return response()->json(['success' => true]);
            } else {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Transfer failed']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    private function transfer_test($a, $b)
    {
        return json_encode(['success' => true, 'transactionHash' => '0xTEsting']);
    }
    public function principalreject(Request $request)
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
        $orderTxId = $transaction->order_id;
        $order = Order::where('tx_id', $orderTxId)->first();
        if ($order) {
            $order->status = 1;  // Set order status back to 1 (maybe "active" or "completed")
            $order->save();
        }
        $transaction->status = 2; // rejected
        $transaction->reason = $reason;
        $transaction->updated_on = now();
        $transaction->save();
        return response()->json(['success' => true]);
    }
}
