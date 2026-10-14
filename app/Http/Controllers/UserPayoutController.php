<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletType;
use App\Models\CustomField;
use App\Models\AdvanceInfo;
use App\Models\CustomFieldValue;
use App\Models\UserWallet;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Str;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;
use App\Models\Transaction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PinDetail;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserPaymentMethod;
use App\Models\KycDetail;




class UserPayoutController extends Controller
{
    public function index()
    {

        $user = auth()->user();
        $userId = $user->id;

        $wdText = AdvanceInfo::where('label', 'wd_conditions')->value('value') ?? '';

        // Match "Minimum Withdrawal 10"
        preg_match('/Minimum Withdrawal\s+(\d+(\.\d+)?)/i', $wdText, $minMatches);
        $maxLimit = (float)(AdvanceInfo::where('label', 'max_withdrawal_limit')->value('value') ?? 25000);

        // Match "Withdrawal Charges 5%" 
        preg_match('/Withdrawal Charges\s+(\d+(\.\d+)?)%/i', $wdText, $chargeMatches);

        // Set defaults if not matched
        $minWithdrawal = isset($minMatches[1]) ? (float) $minMatches[1] : 50;
        $chargeAmount = isset($chargeMatches[1]) ? (float) $chargeMatches[1] : 1;

        $kycBank = KycDetail::where('user_id', $userId)
            ->where('kyc_status_bank', 'approved')
            ->first();


        $wallets = WalletType::where('wallet_type', 'wallet')->where('status', true)->where('slug', 'main_wallet')->get();
        $userWallet = UserWallet::where('u_code', auth()->id())->first();
        return view('user.withdrawal.payout', compact('wallets', 'userWallet', 'minWithdrawal', 'chargeAmount', 'wdText', 'maxLimit', 'kycBank'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        // 1️⃣ Check pending withdrawal
        $pendingExists = Transaction::where('u_code', $userId)
            ->where('tx_type', 'withdrawal')
            ->where('status', 0)
            ->exists();

        if ($pendingExists) {
            return back()->withErrors([
                'amount' => 'You already have a pending withdrawal request. Please wait for approval.'
            ]);
        }

        // 2️⃣ Check withdrawal days
        $wdDaysJson = AdvanceInfo::where('label', 'wd_days')->value('value') ?? '[]';
        $wdDays = json_decode($wdDaysJson, true);

        $today = Carbon::now()->format('l');

        if (!in_array($today, $wdDays)) {
            return back()->withErrors([
                'amount' => "Withdrawals are not allowed on $today."
            ]);
        }

        // 3️⃣ Check withdrawal time (optional)
        $startTimeStr = AdvanceInfo::where('label', 'wd_start_time')->value('value') ?? '12:00AM';
        $endTimeStr   = AdvanceInfo::where('label', 'wd_end_time')->value('value') ?? '08:00AM';

        $nowIST   = Carbon::now('Asia/Kolkata');
        $startTime = Carbon::createFromFormat('g:iA', $startTimeStr, 'Asia/Kolkata');
        $endTime   = Carbon::createFromFormat('g:iA', $endTimeStr, 'Asia/Kolkata');

        // (Enable below if time restriction required)
        // if ($nowIST->lt($startTime) || $nowIST->gt($endTime)) {
        //     return back()->withErrors([
        //         'amount' => "Withdrawals allowed only between $startTimeStr - $endTimeStr IST."
        //     ]);
        // }

        // 4️⃣ Check KYC (Identity)
        $kycIdentity = KycDetail::where('user_id', $userId)
            ->where('kyc_status_identity', 'approved')
            ->first();

        if (!$kycIdentity) {
            return back()->withErrors([
                'amount' => 'Please complete Identity KYC verification.'
            ]);
        }

        // 5️⃣ Check KYC (Bank)
        $kycBank = KycDetail::where('user_id', $userId)
            ->where('kyc_status_bank', 'approved')
            ->first();

        if (!$kycBank) {
            return back()->withErrors([
                'amount' => 'Please complete Bank KYC verification.'
            ]);
        }

        // Extract bank details from KYC
        $bankDetailsToStore = [
            'account_type'        => 'Bank',
            'bank_name'           => $kycBank->bank_name,
            'account_holder_name' => $kycBank->account_holder_name,
            'account_no'          => $kycBank->account_no,
            'ifsc_code'           => $kycBank->ifsc_code,
            'bank_branch'         => $kycBank->bank_branch,
            'front_image_bank'    => $kycBank->front_image_bank,
        ];

        // 6️⃣ Validate withdrawal amount
        $minWithdrawal = (float)(AdvanceInfo::where('label', 'min_withdrawal_limit')->value('value') ?? 10);
        $maxLimit      = (float)(AdvanceInfo::where('label', 'max_withdrawal_limit')->value('value') ?? 2500);

        $request->validate([
            'amount' => "required|numeric|min:$minWithdrawal|max:$maxLimit",
        ]);

        // 7️⃣ Check wallet balance
        $wallet = UserWallet::where('u_code', $userId)->first();

        if (!$wallet || $wallet->c1 < $request->amount) {
            return back()->withErrors([
                'amount' => 'Insufficient wallet balance.'
            ]);
        }

        // 8️⃣ Withdrawal charges
        $chargeValue = (float)(AdvanceInfo::where('label', 'wd_charges')->value('value') ?? 0);
        $chargeOptionsJson = AdvanceInfo::where('label', 'wd_charges')->value('options') ?? '{"percent":"percent"}';

        $chargeOptions = json_decode($chargeOptionsJson, true);
        $chargeType = array_key_first($chargeOptions);

        $amount = $request->amount;
        $charge = $chargeType === 'percent'
            ? ($amount * $chargeValue) / 100
            : $chargeValue;

        $finalPaid = $amount - $charge;

        // 9️⃣ Deduct wallet balance
        $oldBalance = $wallet->c1;
        $wallet->c1 -= $amount;
        $wallet->save();

        // 🔟 Store withdrawal transaction
        Transaction::create([
            'tx_u_code'      => $userId,
            'u_code'         => $userId,
            'tx_type'        => 'withdrawal',
            'debit_credit'   => 'debit',
            'source'         => 'payout-request',
            'wallet_type'    => 'main_wallet',
            'bank_details'   => json_encode($bankDetailsToStore),
            'amount'         => $amount,
            'tx_charge'      => $charge,
            'payment_type'   => 'wallet',
            'status'         => 0,
            'remark'         => 'Withdrawal request submitted',
            'open_wallet'    => $oldBalance,
            'closing_wallet' => $wallet->c1,
            'request_amount' => $amount,
            'paidAmount'     => $finalPaid,
            'date'           => now(),
            'added_on'       => now(),
            'updated_on'     => now(),
        ]);

        return back()->with('success', 'Withdrawal request submitted successfully.');
    }


public function myWithdrawals()
{
    $userId = Auth::id();

    $kycBank = KycDetail::where('user_id', $userId)
        ->where('kyc_status_bank', 'approved')
        ->first();

    $base = Transaction::where('tx_type', 'withdrawal')
        ->where('u_code', $userId);

    // Amounts
    $pendingAmount  = (clone $base)->where('status', 0)->sum('amount');
    $approvedAmount = (clone $base)->where('status', 1)->sum('amount');
    $rejectedAmount = (clone $base)->where('status', 2)->sum('amount');

    // 🔹 Charges calculator
    $calcCharges = function ($amount) {
        return [
            'tx'    => $amount * 0.07, // 7%
            'admin' => $amount * 0.05, // 5%
            'tds'   => $amount * 0.02, // 2%
        ];
    };

    // 🔹 Define variables BEFORE compact()
    $pendingCharges  = $calcCharges($pendingAmount);
    $approvedCharges = $calcCharges($approvedAmount);
    $rejectedCharges = $calcCharges($rejectedAmount);

    return view('user.withdrawal.report', compact(
        'kycBank',

        'pendingAmount',
        'approvedAmount',
        'rejectedAmount',

        'pendingCharges',
        'approvedCharges',
        'rejectedCharges'
    ));
}
    public function myWithdrawalsList(Request $request)
    {
        $userId = Auth::id();

        $query = Transaction::where('tx_type', 'withdrawal')
            ->where('u_code', $userId)
            ->select('transaction.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status_button', function ($row) {
                if ($row->status == 0) return '<span class="badge bg-warning">Pending</span>';
                if ($row->status == 1) return '<span class="badge bg-success">Approved</span>';
                return '<span class="badge bg-danger">Rejected</span>';
            })
            ->addColumn('reason', function ($row) {
                return $row->reason ?? 'N/A';
            })

            ->addColumn('account', function ($row) {

                if (!$row->bank_details) {
                    return 'N/A';
                }

                $details = json_decode($row->bank_details, true);

                if (!$details) {
                    return 'N/A';
                }

                // Detect BANK type
                if (isset($details['account_type']) && strtolower($details['account_type']) == 'bank') {

                    $bankName   = $details['bank_name'] ?? 'N/A';
                    $holderName = $details['account_holder_name'] ?? 'N/A';
                    $accNo      = $details['account_no'] ?? 'N/A';
                    $ifsc       = $details['ifsc_code'] ?? 'N/A';
                    $branch     = $details['bank_branch'] ?? 'N/A';

                    return "
                            <strong>Type:</strong> Bank<br>
                            <strong>Bank:</strong> {$bankName}<br>
                            <strong>Holder:</strong> {$holderName}<br>
                            <strong>Account No:</strong> {$accNo}<br>
                            <strong>IFSC:</strong> {$ifsc}<br>
                            <strong>Branch:</strong> {$branch}
                        ";
                }

                // CRYPTO DETAILS (Default)
                $address = $details['userAddress'] ?? 'N/A';
                $type    = strtoupper($details['account_type'] ?? 'N/A');

                return "
                        <strong>Type:</strong> {$type}<br>
                        <strong>Address:</strong> {$address}
                    ";
            })

            ->addColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->date)->format('d M Y h:i A');
            })
            ->rawColumns(['status_button', 'account'])
            ->make(true);
    }









    public function PrincipalWithdrawalindex()
    {
        $userId = auth()->id();

        $orders = Order::where('tx_user_id', $userId)
            ->where('package_name', 'Investment')
            ->where('status', 1)
            ->whereNotIn('tx_id', function ($q) use ($userId) {
                $q->selectRaw("TRIM(REPLACE(remark, 'Withdrawal request for Order: ', ''))")
                    ->from('transaction')
                    ->where('tx_type', 'Principal_Withdrawal')
                    ->whereIn('status', [0, 1]) // Don't show if status is 0 (pending) or 1 (approved)
                    ->where('u_code', $userId);
            })
            ->get();

        return view('user.principal_withdrawal.index', compact('orders'));
    }


    public function PrincipalWithdrawalstore(Request $request)
    {
        $request->validate([
            'order_tx_id'  => 'required|exists:orders,id',
        ]);

        $userId = auth()->id();
        $user   = auth()->user();

        $order = Order::where('id', $request->order_tx_id)
            ->where('tx_user_id', $userId)
            ->where('package_name', 'Investment')
            ->firstOrFail();

        $orderTxId = $order->tx_id;

        // ✅ Check if already withdrawn
        $existing = Transaction::where('tx_type', 'Principal_Withdrawal')
            ->where('u_code', $userId)
            ->where('remark', 'Withdrawal for Order: ' . $orderTxId)
            ->where('status', 1) // approved ones only
            ->exists();

        if ($existing) {
            return back()->withErrors(['error' => 'Principal already withdrawn for this order.']);
        }

        $orderAmount = $order->order_amount;
        $orderDate   = Carbon::parse($order->added_on);
        $now         = now();

        // ✅ Charge logic (within 3 months = 10% charge, else 0%)
        $days = $orderDate->diffInDays($now);
        $chargePercent = ($days < 90) ? 0.10 : 0;

        $chargeAmount  = round($orderAmount * $chargePercent, 2);
        $finalPaid     = round($orderAmount - $chargeAmount, 2);

        // ✅ Directly create APPROVED transaction
        Transaction::create([
            'order_id'       => $order->tx_id,
            'tx_u_code'      => $userId,
            'u_code'         => $userId,
            'tx_type'        => 'Principal_Withdrawal',
            'debit_credit'   => 'credit', // credit to user
            'source'         => 'principal-withdrawal',
            'wallet_type'    => 'Main Wallet',
            'ftm_amnt'       => $orderAmount,
            'amount'         => $finalPaid,
            'tx_charge'      => $chargeAmount,
            'payment_type'   => 'wallet',
            'payment_slip'   => '',
            'date'           => now(),
            'status'         => 1, // ✅ Approved
            'remark'         => 'Withdrawal for Order: ' . $orderTxId,
            'request_amount' => $orderAmount,
            'paidAmount'     => $finalPaid,
            'added_on'       => now(),
            'updated_on'     => now(),
        ]);

        // ✅ Mark order as withdrawn
        $order->status = 2;
        $order->save();

        // ✅ Credit directly to user's wallet
        $wallet = UserWallet::firstOrNew(['u_code' => $user->id]);
        $wallet->c1 += $finalPaid;
        $wallet->save();

        return back()->with('success', 'Principal withdrawal successful. Final amount credited to your wallet.');
    }


    public function MyPrincipalWithdrawal()
    {
        $userId = Auth::id();

        // Summaries
        $totalWithdrawal = Transaction::where('tx_type', 'Principal_Withdrawal')
            ->where('u_code', $userId)
            ->sum('amount');

        $paidWithdrawal = Transaction::where('tx_type', 'Principal_Withdrawal')
            ->where('u_code', $userId)
            ->where('status', 1) // Approved
            ->sum('amount');

        $pendingWithdrawal = Transaction::where('tx_type', 'Principal_Withdrawal')
            ->where('u_code', $userId)
            ->where('status', 0) // Pending
            ->sum('amount');

        return view('user.principal_withdrawal.report', compact(
            'totalWithdrawal',
            'paidWithdrawal',
            'pendingWithdrawal'
        ));
    }

    public function PrincipalWithdrawalList(Request $request)
    {
        $userId = Auth::id();

        $query = Transaction::where('tx_type', 'principal_withdrawal')
            ->where('u_code', $userId)
            ->select('transaction.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status_button', function ($row) {
                if ($row->status == 0) return '<span class="badge bg-warning">Pending</span>';
                if ($row->status == 1) return '<span class="badge bg-success">Approved</span>';
                return '<span class="badge bg-danger">Rejected</span>';
            })
            ->addColumn('reason', function ($row) {
                return $row->reason ?? 'N/A';
            })
            ->addColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->date)->format('d M Y h:i A');
            })
            ->rawColumns(['status_button'])
            ->make(true);
    }
}
