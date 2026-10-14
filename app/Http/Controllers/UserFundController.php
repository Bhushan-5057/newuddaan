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
use App\Models\PaymentMethod;
use App\Models\Transaction;

use Carbon\Carbon;

use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;



class UserFundController extends Controller
{

    public function addFundindex(Request $request)
    {

        $fundWalletAddress = company_info('company_smart_chain_address', '0xasdsdsdsaddsdsads');

        return view('user.fund.addfund', compact('fundWalletAddress'));

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

            'tx_u_code' => 'user', // indicates admin transferred

            'u_code' => $user->id,

            'tx_type' => 'user_transfer',

            'debit_credit' => 'credit',

            'source' => 'user_panel',

            'wallet_type' => $walletName,

            'ftm_amnt' => $amount,

            'amount' => $amount,

            'tx_charge' => 0.00,

            'payment_type' => 'manual',

            'payment_slip' => '',

            'date' => Carbon::now(),

            'status' => 1,

            'open_wallet' => $currentAmount,

            'closing_wallet' => $currentAmount + $amount,

            'remark' => 'User added fund to ' . $walletName,

            'request_amount' => $amount,

            'paidAmount' => $amount,

            'added_on' => Carbon::now(),

            'updated_on' => Carbon::now(),

        ]);



        return back()->with('success', 'Fund added successfully and transaction logged.');

    }


    public function addfundrequestindex()
    {
        $methods = PaymentMethod::where('is_parent', 1)->where('status', 1)->get();
        return view('user.fund.fundrequest', compact('methods'));
    }

    public function getChildPaymentMethods(Request $request)
    {
        $parentSlug = $request->get('slug');
        $children = PaymentMethod::where('parent_method', $parentSlug)->where('status', 1)->get();

        return response()->json($children);
    }







    public function storefundrequest(Request $request)
    {

        $minDepositLimit = AdvanceInfo::where('label', 'min_deposit_limit')->value('value') ?? 0.01;



        $request->validate([

            'method' => 'required|string',

            'payment_type' => 'required|string',

            'amount' => "required|numeric|min:$minDepositLimit",

            'utr_number' => 'required|string',

            'payment_slip' => 'required|image|max:2048',

        ]);



        // Upload slip to public storage

        $slipPath = $request->file('payment_slip')->store('payment_slips', 'public');



        $tx_u_code = strtoupper(Str::random(10));



        DB::table('transaction')->insert([

            'tx_u_code' => $tx_u_code,

            'u_code' => auth()->user()->id ?? 'U001',

            'tx_type' => 'user_deposit',

            'debit_credit' => 'credit',

            'source' => 'user_panel',

            'wallet_type' => 'main_wallet',

            'amount' => $request->amount,

            'ftm_amnt' => $request->amount,

            'tx_charge' => 0,

            'cripto_type' => $request->method,

            'payment_type' => $request->payment_type,

            'cripto_address' => null,

            'payment_slip' => $slipPath,

            'tx_hash' => $request->utr_number,

            'status' => 0,

            'deposit_status' => 0,

            'date' => now(),

            'added_on' => now(),

            'updated_on' => now(),

        ]);



        return back()->with('success', 'Transaction submitted successfully.');

    }









    public function fundrequesthistory()
    {

        return view('user.fund.fundrequesthistory');

    }



    public function fundrequesthistoryData(Request $request)
    {

        if ($request->ajax()) {

            // Make sure u_code matches your logged-in user identification

            $userId = auth()->id();



            $fundRequests = DB::table('transaction')

                ->where('u_code', $userId)

                ->where('tx_type', 'user_deposit')

                ->orderBy('id', 'desc');



            return DataTables::of($fundRequests)

                ->addIndexColumn() // this adds DT_RowIndex automatically

                ->editColumn('payment_slip', function ($row) {

                    if ($row->payment_slip) {

                        // Assuming you have a symbolic link for storage: php artisan storage:link
    
                        return '<a href="' . asset('storage/' . $row->payment_slip) . '" target="_blank">View</a>';

                    }

                    return '-';

                })

                ->editColumn('status', function ($row) {

                    return $row->status == 0 ? 'Pending' : ($row->status == 1 ? 'Approved' : 'Rejected');

                })

                ->editColumn('reason', fn($row) => $row->reason ?? '-')

                ->editColumn('added_on', function ($row) {

                    return \Carbon\Carbon::parse($row->added_on)->format('d M Y h:i A');

                })

                ->rawColumns(['payment_slip']) // to allow html in payment_slip column

                ->make(true);

        }



        abort(403);

    }



    public function FundTransferindex(Request $request)
    {

        // Get active wallets JSON from AdvanceInfo

        $activeWalletsJson = AdvanceInfo::where('label', 'transfer_wallets')->value('value');

        $activeWallets = json_decode($activeWalletsJson, true); // Convert to associative array



        // Get all wallet types

        $wallets = WalletType::where('wallet_type', 'wallet')->where('status', true)->get();



        // Filter wallets that are active in AdvanceInfo setting

        $wallets = $wallets->filter(function ($wallet) use ($activeWallets) {

            return isset($activeWallets[$wallet->slug]) && $activeWallets[$wallet->slug] == 1;

        });



        $userWallet = UserWallet::where('u_code', auth()->id())->first();



        return view('user.fund.fundtransfer', compact('wallets', 'userWallet'));

    }



    public function FundTransfer(Request $request)
    {

        $request->validate([

            'username' => 'required|exists:users,username',

            'amount' => 'required|numeric|min:0.01',

            'wallet_type' => 'required', // e.g., 'c1'

        ]);



        $walletColumn = $request->wallet_type;

        $amount = $request->amount;

        $recipientUsername = $request->username;

        $sender = auth()->user();



        // Get the minimum transfer limit

        $minLimit = AdvanceInfo::where('label', 'min_transfer_limit')->value('value') ?? 1;



        // Check minimum transfer limit

        if ($amount < $minLimit) {

            return back()->withErrors(['amount' => "Minimum transfer amount is $minLimit."]);

        }



        // Recipient user

        $recipient = User::where('username', $recipientUsername)->firstOrFail();



        if ($sender->id === $recipient->id) {

            return back()->withErrors('You cannot transfer funds to yourself.');

        }



        // Get all active wallets

        $wallets = WalletType::where('wallet_type', 'wallet')->where('status', true)->get();



        // Find the wallet by wallet_column

        $wallet = $wallets->firstWhere('wallet_column', $walletColumn);



        if (!$wallet) {

            return back()->withErrors('Invalid wallet selected.');

        }



        // Compose the wallet_type string: "Name (slug)"

        $walletTypeString = $wallet->name . ' (' . $wallet->slug . ')';



        // Sender wallet balance check

        $senderWallet = UserWallet::firstOrCreate(['u_code' => $sender->id]);

        $senderBalance = $senderWallet->$walletColumn ?? 0;



        if ($senderBalance < $amount) {

            return back()->withErrors('Insufficient balance in your wallet.');

        }



        // Deduct from sender
        $chargeRate = AdvanceInfo::where('label', 'fund_transfer_charge')->value('value') ?? 0;
        $chargeType = AdvanceInfo::where('label', 'fund_transfer_charge_type')->value('value') ?? 'fix';

        $chargeAmount = $chargeType === 'percentage' ? ($amount * $chargeRate / 100) : $chargeRate;
        $netAmount = $amount - $chargeAmount;

        if ($netAmount <= 0) {
            return back()->withErrors('Amount too low after deduction of transfer charges.');
        }

        // Update sender wallet
        $senderWallet->$walletColumn = $senderBalance - $amount;
        $senderWallet->save();

        // Update recipient wallet
        $recipientWallet = UserWallet::firstOrCreate(['u_code' => $recipient->id]);
        $recipientOldBalance = $recipientWallet->$walletColumn ?? 0;
        $recipientWallet->$walletColumn = $recipientOldBalance + $netAmount;
        $recipientWallet->save();

        $now = now();








        // Log sender transaction

        Transaction::create([

            'tx_u_code' => $sender->id,

            'u_code' => $sender->id,

            'tx_type' => 'user_transfer',

            'debit_credit' => 'debit',

            'source' => 'wallet_transfer',

            'wallet_type' => $walletTypeString,

            'ftm_amnt' => $amount,

            'amount' => $amount,

            'tx_charge' => $chargeAmount,

            'payment_type' => 'wallet',

            'payment_slip' => '',

            'date' => $now,

            'status' => 1,

            'open_wallet' => $senderBalance,

            'closing_wallet' => $senderBalance - $amount,

            'remark' => 'Transferred to user: ' . $recipient->username,

            'request_amount' => $amount,

            'paidAmount' => $amount,

            'added_on' => $now,

            'updated_on' => $now,

        ]);



        // Log recipient transaction

        Transaction::create([

            'tx_u_code' => $sender->id,

            'u_code' => $recipient->id,

            'tx_type' => 'user_transfer',

            'debit_credit' => 'credit',

            'source' => 'wallet_transfer',

            'wallet_type' => $walletTypeString,

            'ftm_amnt' => $netAmount,

            'amount' => $netAmount,

            'tx_charge' => 0.00,

            'payment_type' => 'wallet',

            'payment_slip' => '',

            'date' => $now,

            'status' => 1,

            'open_wallet' => $recipientOldBalance,

            'closing_wallet' => $recipientOldBalance + $amount,

            'remark' => 'Received from user: ' . $sender->username,

            'request_amount' => $amount,

            'paidAmount' => $netAmount,

            'added_on' => $now,

            'updated_on' => $now,

        ]);



        return back()->with('success', 'Fund transferred successfully.');

    }





    public function fundTransferHistory()
    {

        return view('user.fund.transferfundhistory');

    }





    public function fundTransferHistoryData(Request $request)
    {

        $userId = auth()->id();



        $query = Transaction::where(function ($q) use ($userId) {

            $q->where('tx_u_code', $userId)

                ->Where('u_code', $userId);

        })->where('tx_type', 'user_transfer')

            ->orderBy('date', 'desc');



        return DataTables::of($query)

            ->editColumn('date', function ($row) {

                return $row->date ? \Carbon\Carbon::parse($row->date)->format('Y-m-d H:i:s') : '';

            })

            ->editColumn('debit_credit', function ($row) {

                return ucfirst($row->debit_credit);

            })

            ->addColumn('counterparty', function ($row) use ($userId) {

                if ($row->debit_credit === 'debit') {

                    $recipient = User::find($row->u_code);

                    return $recipient ? $recipient->username : 'N/A';

                } else {

                    $sender = User::find($row->tx_u_code);

                    return $sender ? $sender->username : 'N/A';

                }

            })

            ->rawColumns(['counterparty'])

            ->make(true);

    }





    public function internalWalletIndex()
    {

        // Get convert_from_wallets setting

        $fromWalletsJson = AdvanceInfo::where('label', 'convert_from_wallets')->value('value');

        $toWalletsJson = AdvanceInfo::where('label', 'convert_to_wallets')->value('value');



        $fromWallets = json_decode($fromWalletsJson, true) ?? [];

        $toWallets = json_decode($toWalletsJson, true) ?? [];



        // Get all wallet types

        $walletTypes = WalletType::where('wallet_type', 'wallet')->where('status', true)->get();



        // Filter from_wallets

        $fromWalletTypes = $walletTypes->filter(function ($wallet) use ($fromWallets) {

            return isset($fromWallets[$wallet->slug]) && $fromWallets[$wallet->slug] == 1;

        });



        // Filter to_wallets

        $toWalletTypes = $walletTypes->filter(function ($wallet) use ($toWallets) {

            return isset($toWallets[$wallet->slug]) && $toWallets[$wallet->slug] == 1;

        });



        $adminWallet = UserWallet::where('u_code', auth()->id())->first();



        return view('user.fund.fundconvert', compact('fromWalletTypes', 'toWalletTypes', 'adminWallet'));

    }



    public function internalWalletTransfer(Request $request)
    {

        $walletTypes = WalletType::where('status', true)

            ->where('wallet_type', 'wallet')

            ->get();



        $walletMap = [];

        foreach ($walletTypes as $type) {

            $walletMap[$type->slug] = [

                'column' => $type->wallet_column,

                'name' => $type->name,

                'slug' => $type->slug,

            ];

        }



        $validSlugs = array_keys($walletMap);

        $request->validate([

            'from_wallet' => ['required', 'in:' . implode(',', $validSlugs)],

            'to_wallet' => ['required', 'in:' . implode(',', $validSlugs), 'different:from_wallet'],

            'amount' => 'required|numeric|min:0.01',

        ]);



        $userId = auth()->id();

        $amount = $request->amount;



        $from = $walletMap[$request->from_wallet]['column'];

        $to = $walletMap[$request->to_wallet]['column'];

        $fromName = $walletMap[$request->from_wallet]['name'];

        $fromSlug = $walletMap[$request->from_wallet]['slug'];

        $toName = $walletMap[$request->to_wallet]['name'];

        $toSlug = $walletMap[$request->to_wallet]['slug'];



        $wallet = UserWallet::where('u_code', $userId)->first();



        if (!$wallet) {

            return back()->withErrors(['error' => 'Wallet not found.']);

        }



        if ($wallet->$from < $amount) {

            return back()->withErrors(['amount' => 'Insufficient balance in selected wallet.']);

        }



        DB::beginTransaction();



        try {

            $openingFrom = $wallet->$from;

            $openingTo = $wallet->$to;



            $chargeValue = AdvanceInfo::where('label', 'fund_convert_charge')->value('value') ?? 0;
            $chargetype = AdvanceInfo::where('label', 'fund_convert_charge_type')->value('value') ?? 'fix';

            if ($chargetype === 'percentage') {
                // Correct calculation for percentage deduction
                $chargeAmount = ($amount * $chargeValue) / 100;
                $amountotal = $amount - $chargeAmount;
            } else {
                // Fixed amount deduction
                $chargeAmount = $chargeValue;
                $amountotal = $amount - $chargeAmount;
            }

            $wallet->decrement($from, $amount);        // Take full amount from source
            $wallet->increment($to, $amountotal);      // Add net amount after charge to destination
            $wallet->refresh();




            $closingFrom = $wallet->$from;

            $closingTo = $wallet->$to;



            DB::table('transaction')->insert([

                // Debit (from_wallet)

                [

                    'tx_u_code' => $userId,

                    'u_code' => $userId,

                    'tx_type' => 'Internal Transfer',

                    'debit_credit' => 'debit',

                    'source' => 'internal_transfer',

                    'wallet_type' => "$fromName ($fromSlug)",

                    'ftm_amnt' => $amount,

                    'amount' => $amount,

                    'tx_charge' => $chargeAmount,

                    'payment_type' => 'internal',

                    'payment_slip' => '',

                    'date' => now(),

                    'status' => 1,

                    'remark' => "Transferred to $toName",

                    'open_wallet' => $openingFrom,

                    'closing_wallet' => $closingFrom,

                    'request_amount' => $amount,

                    'paidAmount' => $amountotal,

                    'added_on' => now(),

                    'updated_on' => now(),

                ],

                // Credit (to_wallet)

                [

                    'tx_u_code' => $userId,

                    'u_code' => $userId,

                    'tx_type' => 'Internal Transfer',

                    'debit_credit' => 'credit',

                    'source' => 'internal_transfer',

                    'wallet_type' => "$toName ($toSlug)",

                    'ftm_amnt' => $amountotal,

                    'amount' => $amountotal,

                    'tx_charge' => 0,

                    'payment_type' => 'internal',

                    'payment_slip' => '',

                    'date' => now(),

                    'status' => 1,

                    'remark' => "Received from $fromName",

                    'open_wallet' => $openingTo,

                    'closing_wallet' => $closingTo,

                    'request_amount' => $amount,

                    'paidAmount' => $amountotal,

                    'added_on' => now(),

                    'updated_on' => now(),

                ],

            ]);



            DB::commit();

            return back()->with('success', 'Transfer successful!');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Transfer Error: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Transfer failed. Please try again.']);

        }

    }







    public function fundConvertHistory()
    {

        return view('user.fund.fundconverthistory');

    }



    public function fundConvertHistoryData(Request $request)
    {

        $userId = auth()->id();



        $query = DB::table('transaction')

            ->where('u_code', $userId)

            ->where('tx_type', 'Internal Transfer') // Filter only fund conversion

            ->orderBy('date', 'desc');



        return DataTables::of($query)

            ->addIndexColumn()

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
    public function newDappFund(Request $request)
    {
        Log::info("BSCscan User: transaction request data", [
            'amount' => $request->amount,
            'tx_hash' => $request->tx_hash
        ]);

        $hash   = $request->tx_hash;
        $amount = $request->amount;
        $useraddress = $request->wallet;

        if (!$hash || !$amount) {
            return response()->json(['message' => 'Missing hash or amount.'], 422);
        }
        $userId = auth()->id();
        $confirm = $this->addFundConfirm($hash, $amount);

        if ($confirm) {
            $user_id = auth()->id() ?? 'U001';

            // Prevent duplicate transaction
            if (Transaction::where('tx_hash', $hash)->exists()) {
                return response()->json(['message' => 'Transaction already exists.'], 409);
            }

            // Create transaction
            Transaction::create([
                'tx_u_code'       => '',
                'u_code'          => $user_id,
                'tx_type'         => 'user_auto_deposit',
                'debit_credit'    => 'credit',
                'source'          => 'user_panel',
                'wallet_type'     => 'fund wallet',
                'amount'          => $amount,
                'ftm_amnt'        => $amount,
                'tx_charge'       => 0,
                'cripto_type'     => 'live',
                'payment_type'    => 'USDT',
                'payment_slip'    => '',
                'tx_hash'         => $hash,
                'status'          => 1,
                'remark'          => 'Add Fund credited to USDT Amount: ' . $amount,
                'deposit_status'  => 0,
                'date'            => now(),
                'added_on'        => now(),
                'updated_on'      => now(),
            ]);

            // Update wallet
            $wallet = 'c2'; // fund wallet
            $userWallet = UserWallet::firstOrNew(['u_code' => $user_id]);
            $currentAmount = $userWallet->$wallet ?? 0;
            $userWallet->$wallet = $currentAmount + $amount;
            $userWallet->save();

            // Update user deposit status
            $user = User::find($user_id);
            if ($user) {
                 $user->user_wallet_address = $useraddress;
                $user->deposit_status = 1;
                $user->save();
            }

            return response()->json(['message' => 'Success'], 200);
        }

        return response()->json(['message' => 'Transaction not confirmed.'], 404);
    }
    
    
    
    public function addFundConfirm($tr_hash, $amount)
{

    // ✔ Prevent duplicate entries
    if (Transaction::where('tx_hash', $tr_hash)->exists()) {
        Log::error('Duplicate transaction hash: ' . $tr_hash);
        return false;
    }

    // Moralis API
        $apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJub25jZSI6ImQwNGQ3Y2FlLWU0NGMtNDE3My05YjFmLTJkZGY4MTNkNTMwNiIsIm9yZ0lkIjoiNDgyOTQyIiwidXNlcklkIjoiNDk2ODU3IiwidHlwZUlkIjoiZjY1N2MxNjItNWE2OS00YTQ1LTliZDYtYmI4ZTRiYTFjMDk2IiwidHlwZSI6IlBST0pFQ1QiLCJpYXQiOjE3NjM5ODE1MTksImV4cCI6NDkxOTc0MTUxOX0.R5IWBWonnUG1-J_2WyoBPHgc-QKKlvNJOm42wSQtNu8'; // 🔐 Move to .env ideally
        $url = "https://deep-index.moralis.io/api/v2/transaction/{$tr_hash}?chain=bsc";
    try {
        $response = Http::withHeaders([
            'X-API-Key' => $apiKey,
            'Accept' => 'application/json',
        ])->get($url);

        $data = $response->json();
        Log::info("🔍 Moralis Response: " . json_encode($data));

        /** 1) Must be confirmed */
        if (empty($data['block_number'])) {
            Log::warning("⏳ Pending transaction: {$tr_hash}");
            return false;
        }

        /** 2) USDT contract must match */
        $usdtContract = strtolower('0x55d398326f99059fF775485246999027B3197955'); // BEP20 USDT
        if (!isset($data['to_address']) || strtolower($data['to_address']) !== $usdtContract) {
            Log::error("❌ Not a USDT transaction");
            return false;
        }

        /** 3) REAL RECEIVER wallet is stored in topic2 */
        $log = $data['logs'][0] ?? null;
        if (!$log || !isset($log['topic2'])) {
            Log::error("❌ No receiving wallet in topic2");
            return false;
        }

        $receiverWallet = '0x' . substr($log['topic2'], 26); // Decode HEX
        $receiverWallet = strtolower($receiverWallet);

        // Your official wallet
        $expectedWallet = strtolower(company_info('company_smart_chain_address'));

        if ($receiverWallet !== $expectedWallet) {
            Log::error("🚨 POSSIBLE FRAUD! Expected: $expectedWallet, Received: $receiverWallet");
            return false;
        }

        /** 4) Validate USDT amount */
        if (isset($log['data'])) {
            $valueWei = hexdec($log['data']);    // Convert HEX amount to decimal
            $valueInToken = $valueWei / pow(10, 18);  // (BEP20 USDT uses 10^18)

            if (floatval($valueInToken) < floatval($amount)) {
                Log::error("❌ Amount mismatch — Expected: $amount, Received: $valueInToken");
                return false;
            }
        } else {
            Log::error("❌ No token amount found in logs");
            return false;
        }

        Log::info("✔ USDT Transaction Verified & Validated (Hash: {$tr_hash})");
        return true;

    } catch (\Exception $e) {
        Log::error('❌ API Error: ' . $e->getMessage());
        return false;
    }
}
    
    
    public function addFundConfirmold($hash, $expectedAmount)
    {
        $companyAddress = strtolower(company_info('company_smart_chain_address'));
        $apiKey = 'SZFAG9HGCGKUDA67UUDANETJPFK57V8Q49';
        $user_id = auth()->id() ?? 'U001';

        Log::info("Etherscan API Init", [
            'hash' => $hash,
            'user_id' => $user_id,
            'apiKey' => $apiKey,
            'expected_amount' => $expectedAmount,
            'company_address' => $companyAddress
        ]);

        $existingTx = Transaction::where('tx_hash', $hash)->first();
        if ($existingTx) {
            Log::warning('Transaction already exists or has failed.', [
                'tx_hash' => $hash,
                'user_id' => $user_id,
                'transaction_id' => $existingTx->id ?? null,
                'status' => $existingTx->status ?? 'unknown'
            ]);
            return false;
        }

        try {
            Log::info("Sending API Request to Etherscan...");
            sleep(2); // throttle

            $receiptUrl = "https://api.etherscan.io/v2/api?chainid=1&module=transaction&action=getstatus&txhash=$hash&apikey=$apiKey";

            $response = Http::get($receiptUrl);
            $body = $response->body();
            Log::info("Etherscan API Response:", ['user_id' => $user_id, 'url' => $receiptUrl, 'response' => $body]);

            $json = json_decode($body, true);       
            if (
                isset($json['status']) &&
                isset($json['result']['isError']) && $json['result']['isError'] === "0"
            ) {
                Log::info("✅ $user_id Transaction confirmed: $hash");
                return true;
            } else {
                Log::warning("❌ $user_id Transaction error: $hash", [
                    'error' => $json['result']['errDescription'] ?? 'Unknown'
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Etherscan API Error", [
                'hash' => $hash,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    public function addfundHistory()
    {

        return view('user.fund.addfundhistory');

    }





    public function addfundHistoryData(Request $request)
    {

        $userId = auth()->id();



        $query = Transaction::where(function ($q) use ($userId) {

            $q->Where('u_code', $userId);

        })->where('source', 'user_panel')

            ->orderBy('date', 'desc');



        return DataTables::of($query)

            ->editColumn('date', function ($row) {

                return $row->date ? \Carbon\Carbon::parse($row->date)->format('Y-m-d H:i:s') : '';

            })

            ->editColumn('debit_credit', function ($row) {

                return ucfirst($row->debit_credit);

            })
            
             ->addColumn('tx_hash', function ($row) {
            if (!empty($row->tx_hash)) {
                // Create a View button linking to BscScan
                $url = 'https://bscscan.com/tx/' . $row->tx_hash;
                return '<a href="' . e($url) . '" target="_blank" 
                           class="btn btn-sm btn-dark"
                           title="View Transaction">
                            View
                        </a>';
            }
            return 'N/A';
        })

            ->addColumn('counterparty', function ($row) use ($userId) {

                if ($row->debit_credit === 'debit') {

                    $recipient = User::find($row->u_code);

                    return $recipient ? $recipient->username : 'N/A';

                } else {

                    $sender = User::find($row->u_code);

                    return $sender ? $sender->username : 'N/A';

                }

            })

              ->rawColumns(['tx_hash', 'counterparty'])


            ->make(true);

    }
}