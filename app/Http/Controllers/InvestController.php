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
use App\Models\PinDetail;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InvestController extends Controller
{

    public function topupIndex(Request $request)

    {

        $user = Auth::user();



        // Get user wallet record

        $userWallet = UserWallet::firstOrNew(['u_code' => $user->id]);



        // Fetch allowed wallet slugs from advance_info (JSON)

        $activeWalletsJson = AdvanceInfo::where('label', 'invest_wallets')->value('value');

        $activeWallets = json_decode($activeWalletsJson, true); // example: ["main_wallet" => 1, "bonus_wallet" => 1]



        // Get all enabled wallet types

        $allWallets = WalletType::where('wallet_type', 'wallet')->where('status', true)->get();



        // Filter only wallets enabled in the advance_info

        $wallets = $allWallets->filter(function ($wallet) use ($activeWallets) {

            return isset($activeWallets[$wallet->slug]) && $activeWallets[$wallet->slug] == 1;
        });



        // Set default wallet balance (optional fallback to 'c1')

        $defaultWallet = $wallets->first();

        $walletBalance = $defaultWallet ? ($userWallet->{$defaultWallet->wallet_column} ?? 0) : 0;



        // Get topup_type: "pin", "amount", or "pin amount"

        $topupType = AdvanceInfo::where('label', 'topup_type')->value('value');
        $username_req = AdvanceInfo::where('label', 'username')->value('value');



        // Get dynamic field IDs for custom range

        $customFieldMap = CustomField::where('module_name', 'pin_details')

            ->whereIn('name', ['pin rate', 'pin rate2', 'Package Name'])

            ->pluck('id', 'name');



        $minRateId = $customFieldMap['pin rate'] ?? null;

        $maxRateId = $customFieldMap['pin rate2'] ?? null;
        $packageNameId = $customFieldMap['Package Name'] ?? null;



        // Prepare packages with min/max rate

        $pinDetails = PinDetail::where('status', 1)->select('id', 'pin_type', 'custom_fields')->get();

        $packages = [];



        foreach ($pinDetails as $pin) {

            $fields = json_decode($pin->custom_fields, true);

            $minRate = $minRateId ? ($fields[$minRateId] ?? null) : null;

            $maxRate = $maxRateId ? ($fields[$maxRateId] ?? null) : null;
            $packageName = $packageNameId ? ($fields[$packageNameId] ?? null) : null;



            if ($minRate) {

                $packages[] = [

                    'id'  => $pin->id,

                    'min' => $minRate,

                    'max' => $maxRate,
                    'name' => $packageName,

                ];
            }
        }
        $user_name = $user->username ?? '';

        $fundWalletAddress = company_info('company_smart_chain_address', '0xasdsdsdsaddsdsads');

        return view('user.envest.topup', compact(

            'walletBalance',
            'wallets',
            'userWallet',
            'packages',
            'topupType',
            'fundWalletAddress',
            'user_name',
            'username_req'

        ));
    }



    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'packageAmount' => 'nullable|numeric|min:1',
            'package' => 'nullable|numeric|min:1|exists:pin_details,id',
        ]);

        $receiver = User::where('username', $request->username)->first();
        if (!$receiver) {
            return redirect()->back()->withErrors(['username' => 'Receiving user not found.']);
        }

        $pinDetail = $request->package
            ? PinDetail::find($request->package)
            : PinDetail::where('status', 1)->orderBy('id')->first();

        if (!$pinDetail) {
            return redirect()->back()->withErrors(['package' => 'No valid package found.']);
        }

        $customFields = json_decode($pinDetail->custom_fields, true);

        $customFieldMap = CustomField::where('module_name', 'pin_details')
            ->whereIn('name', ['roi', 'pin rate'])
            ->pluck('id', 'name');

        $roiFieldId = $customFieldMap['roi'] ?? null;
        $pinRateId = $customFieldMap['pin rate'] ?? null;

        $roiValue = $roiFieldId && isset($customFields[$roiFieldId]) ? $customFields[$roiFieldId] : 0;
        $defaultAmount = $pinRateId && isset($customFields[$pinRateId]) ? $customFields[$pinRateId] : null;

        $amount = $request->packageAmount ?? $defaultAmount;

        if (!$amount) {
            return redirect()->back()->withErrors(['packageAmount' => 'Amount is required or pin rate not set.']);
        }

        $wallet = $request->wallet ?? 'c2';
        $userWallet = UserWallet::firstOrNew(['u_code' => auth()->id()]);
        $currentAmount = $userWallet->$wallet ?? 0;

        if ($currentAmount < $amount) {
            return redirect()->back()->withErrors(['balance' => 'Insufficient balance to complete the purchase.']);
        }
        $userWallet->$wallet = $currentAmount - $amount;
        $userWallet->save();



        $existingOrder = Order::where('tx_user_id', $receiver->id)
            ->where('package_name', $pinDetail->pin_type)
            ->exists();

        $txType = $existingOrder ? 'retopup' : 'topup';

        $haveuser = Order::where('tx_user_id', $receiver->id)->exists() ? 'haveuser' : 'notuser';

        $userWalletActive = UserWallet::firstOrNew(['u_code' => $receiver->id]);

        $directUpline = User::where('username', $receiver->sponsor_id)->where('status', 1)->first();


        // 3. First time activation handling
        if ($haveuser === 'notuser') {

            $maxId = Order::max('active_id');
            $newActiveId = $maxId ? $maxId + 1 : 1;
            // Update active_id in user_wallets
            $userWalletActive->active_id = $newActiveId;
            $userWalletActive->save();

            // Set status = 1 in users table
            $receiver->status = 1;
            $receiver->activate_date = now();
            $receiver->save();

            if ($directUpline) {
                $directWallet = UserWallet::firstOrNew(['u_code' => $directUpline->id]);
                $directWallet->c9 = ($directWallet->c9 ?? 0) + 1;
                $directWallet->c10 = max(($directWallet->c10 ?? 0) - 1, 0);
                $directWallet->save();

                $currentSponsorUsername = $receiver->sponsor_id;
                while ($currentSponsorUsername) {
                    $uplineUser = User::where('username', $currentSponsorUsername)->first();
                    if (!$uplineUser) break;

                    $uplineWallet = UserWallet::firstOrNew(['u_code' => $uplineUser->id]);
                    $uplineWallet->c12 = ($uplineWallet->c12 ?? 0) + 1;
                    $uplineWallet->save();

                    $currentSponsorUsername = $uplineUser->sponsor_id;
                }
            }
        }


        $receiver->save();

        $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER';
        $randomNumber = mt_rand(10000000, 99999999);
        $username = $prefix . $randomNumber;

        Order::create([
            'u_code' => auth()->id(),
            'active_id' => $newActiveId ?? '0',
            'tx_id' => $username,
            'tx_user_id' => $receiver->id,
            'tx_type' => $txType,
            'order_mrp' => $amount,
            'order_amount' => $amount,
            'order_bv' => $amount,
            'package_name' => $pinDetail->pin_type,
            'roi' => $roiValue,
            'times' => 0,
            'roi_bv' => 0,
            'expire_date' => Carbon::now()->addMonth(),
            'status' => 1,
            'payment_status' => 1,
            'payout_id' => 0,
            'added_on' => now(),
            'updated_on' => now(),
        ]);


        $this->processSelfIncome($receiver, $amount, $txType);

        $this->processDirectSponsorIncome($receiver, $amount, $txType);


        return back()->with('success', 'Topup successful.');
    }


    /**
     * પરચેઝ અથવા રી-પરચેઝ પર સેલ્ફ ઇન્કમ (5% BV) પ્રોસેસ કરવા માટેનું ફંક્શન
     */
    private function processSelfIncome($user, $amount, $txType)
    {
        $calculatedIncome = ($amount * 5) / 100;
        $source = 'roi'; // You can customize this source as needed

        // Daily Capping ચેક કરો
        $amount = $this->validateAndApplyCapping($user->id, $calculatedIncome, $source);

        $selfIncomeAmount = ($amount == -1) ? 0 : $amount;
        $status = ($amount == -1) ? 3 : 0; // 3 for fully capped, 0 for Hold



        if ($selfIncomeAmount > 0) {
            $wallet = UserWallet::firstOrNew(['u_code' => $user->id]);
            $openingBalance = $wallet->c1 ?? 0;
            $closingBalance = $openingBalance + $selfIncomeAmount;

            // $wallet->c1 = ($wallet->c1 ?? 0) + $selfIncomeAmount;
            // $wallet->c3 = ($wallet->c3 ?? 0) + $selfIncomeAmount;
            // $wallet->save();

            $walletType = WalletType::where('wallet_column', 'c1')->first();
            $incomeNameData = WalletType::where('slug', $source)->first();
            $incomename = $incomeNameData->name ?? 'Direct Income';


            Transaction::create([
                'u_code'         => $user->id,
                'tx_u_code'      => auth()->id(),
                'tx_type'        => 'income',
                'debit_credit'   => 'credit',
                'source'         => $source,
                'wallet_type'    => $walletType->name ?? 'Main Wallet',
                'amount'         => $selfIncomeAmount,
                'open_wallet'    => $openingBalance,
                'closing_wallet' => $closingBalance,
                'date'           => now(),
                'status'         => $status,
                'remark'         => "5% $incomename from " . ucfirst($txType) . " of order Amount " . $amount . " on " . now(),
                'added_on'       => now(),
            ]);
        }
    }

    /**
     * પેકેજની રકમના ૩૦% ડાયરેક્ટ સ્પોન્સર ઇન્કમ પ્રોસેસ કરવા માટેનું ફંક્શન
     */
    private function processDirectSponsorIncome($receiver, $packageAmount, $txType)
    {


        $sponsor = User::where('username', $receiver->sponsor_id)->where('status', 1)->first();
        $source = 'direct';

        if ($sponsor) {

            // ૩. ઇન્કમ ગણતરી (૩૦%)
            $calculatedIncome = ($packageAmount * 30) / 100;

            // ૪. કેપિંગ વેલિડેશન
            $amount = $this->validateAndApplyCapping($sponsor->id, $calculatedIncome, $source);

            $directIncomeAmount = ($amount == -1) ? 0 : $amount;

            if ($directIncomeAmount > 0) {
                $sponsorWallet = UserWallet::firstOrNew(['u_code' => $sponsor->id]);
                $openingBalance = $sponsorWallet->c1 ?? 0;
                $closingBalance = $openingBalance + $directIncomeAmount;

                $walletType = WalletType::where('wallet_column', 'c1')->first();
                $incomeNameData = WalletType::where('slug', $source)->first();
                $incomename = $incomeNameData->name ?? 'Direct Income';
                // Credit Main Wallet + Reference/Direct income column from WalletType
                $incomeColumn = $incomeNameData->wallet_column ?? 'c16';

                $sponsorWallet->c1 = ($sponsorWallet->c1 ?? 0) + $directIncomeAmount;
                $sponsorWallet->{$incomeColumn} = ($sponsorWallet->{$incomeColumn} ?? 0) + $directIncomeAmount;
                $sponsorWallet->save();

                // ૫. ટ્રાન્ઝેક્શન એન્ટ્રી
                Transaction::create([
                    'u_code'         => $sponsor->id,
                    'tx_u_code'      => $receiver->id,
                    'tx_type'        => 'income',
                    'debit_credit'   => 'credit',
                    'source'         => 'direct',
                    'wallet_type'    => $walletType->name ?? 'Main Wallet',
                    'amount'         => $directIncomeAmount,
                    'open_wallet'    => $openingBalance,
                    'closing_wallet' => $closingBalance,
                    'date'           => now(),
                    'status'         => 1,
                    'remark'         => "30% $incomename from " . ucfirst($txType) . " of user " . $receiver->username . " (Amount: $packageAmount) on " . now(),
                    'added_on'       => now(),
                ]);

                Log::info("✅ Direct Income Processed: Sponsor {$sponsor->username} received income from {$receiver->username}.");
            }
        } else {
            Log::error("❌ Direct Income Failed: Sponsor not found or inactive for receiver {$receiver->username}");
        }
    }


    private function validateAndApplyCapping($userId, $incomingAmount, $source)
    {
        $user = User::find($userId);
        $dailyLimit = AdvanceInfo::where('label', 'capping')->value('value') ?? 5000;
        $now = now();


        // ૨. ડેઇલી કેપિંગ ગણતરી (પહેલેથી મળેલી + નવી ઇન્કમ)
        $alreadyReceivedToday = Transaction::where('u_code', $userId)
            ->where('tx_type', 'income')
            ->whereIn('source', ['roi', 'direct', 'binary', 'reward', 'rank',])
            ->whereDate('added_on', $now->toDateString())
            ->sum('amount');

        $totalPotentialIncome = $alreadyReceivedToday + $incomingAmount;

        if ($alreadyReceivedToday >= $dailyLimit) {
            $this->recordFlushAmount($userId, $incomingAmount, $source);
            $this->updateCappingStatus($user);
            return -1;
        }

        if ($totalPotentialIncome > $dailyLimit) {
            $payableAmount = $dailyLimit - $alreadyReceivedToday;
            $flushAmount = $incomingAmount - $payableAmount;

            $this->recordFlushAmount($userId, $flushAmount, $source);
            $this->updateCappingStatus($user);
            return $payableAmount;
        }

        return $incomingAmount;
    }

    /**
     * કેપિંગ હિટ સ્ટેટસ અને ચોક્કસ તારીખ અપડેટ કરવા માટે
     */
    private function updateCappingStatus($user)
    {
        if ($user->capping_hit_status == 0) {
            $user->capping_hit_status = 1;
            $user->capping_hit_date = now();
            $user->save();
        }
    }
    /**
     * Flush થયેલી રકમની ટ્રાન્ઝેક્શનમાં એન્ટ્રી પાડવા માટે
     */
    private function recordFlushAmount($userId, $amount, $source)
    {
        if ($amount > 0) {
            Transaction::create([
                'u_code' => $userId,
                'tx_type' => 'flush_out',
                'debit_credit' => 'debit',
                'source' => $source,
                'amount' => $amount,
                'date' => now(),
                'status' => 1,
                'remark' => "Amount Flush Out due to Daily Capping Limit (5000) of user ID " . $userId . " for source " . $source . " Amount: " . $amount . "date: " . now(),
                'added_on' => now(),
            ]);
        }
    }





    public function invest_api(Request $request)
    {
        try {
            // 1. Validate inputs
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'packageAmount' => 'nullable|numeric|min:10',
                'package' => 'nullable|numeric|min:1|exists:pin_details,id',
                'tx_hash' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }
            // Second validation (amount range)
            if (!$request->packageAmount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Package amount is required.'
                ], 422);
            }
            if ($request->packageAmount < 30 || $request->packageAmount > 100000) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Package amount must be between 30 and 100000.'
                ], 422);
            }
            // 2. Get receiver
            $receiver = User::where('username', $request->username)->first();
            if (!$receiver) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Receiving user not found.'
                ], 404);
            }
            // 3. Get package details
            $pinDetail = $request->package
                ? PinDetail::find($request->package)
                : PinDetail::where('status', 1)->orderBy('id')->first();
            if (!$pinDetail) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No valid package found.'
                ], 404);
            }
            $verify_hash = $this->addFundConfirm($request->tx_hash, 'usdt', $request->amount);
            if (!$verify_hash) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Receiving user not found valid hash.'
                ], 404);
            }
            $customFields = json_decode($pinDetail->custom_fields, true);
            $customFieldMap = CustomField::where('module_name', 'pin_details')
                ->whereIn('name', ['roi', 'pin rate'])
                ->pluck('id', 'name');
            $roiFieldId = $customFieldMap['roi'] ?? null;
            $pinRateId = $customFieldMap['pin rate'] ?? null;
            $roiValue = $roiFieldId && isset($customFields[$roiFieldId]) ? $customFields[$roiFieldId] : 0;
            $defaultAmount = $pinRateId && isset($customFields[$pinRateId]) ? $customFields[$pinRateId] : null;
            $amount = $request->packageAmount ?? $defaultAmount;
            $hash = $request->tx_hash ?? '';
            if (!$amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Amount is required or pin rate not set.'
                ], 422);
            }
            // 4. Wallet balance check
            // $wallet = $request->wallet ?? 'c1';
            // $userWallet = UserWallet::firstOrNew(['u_code' => auth()->id()]);
            // $currentAmount = $userWallet->$wallet ?? 0;
            // if ($currentAmount < $amount) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Insufficient balance to complete the purchase.'
            //     ], 400);
            // }
            // $userWallet->$wallet = $currentAmount - $amount;
            // $userWallet->save();
            // 5. Generate active_id
            $maxId = Order::max('active_id');
            $newActiveId = $maxId ? $maxId + 1 : 1;
            // 6. Detect topup type
            $existingOrder = Order::where('tx_user_id', $receiver->id)
                ->where('package_name', $pinDetail->pin_type)
                ->exists();
            $txType = $existingOrder ? 'retopup' : 'topup';
            $haveuser = Order::where('tx_user_id', $receiver->id)->exists() ? 'haveuser' : 'notuser';
            $userWalletActive = UserWallet::firstOrNew(['u_code' => $receiver->id]);
            // 7. Direct sponsor
            $directUpline = User::where('username', $receiver->sponsor_id)->where('status', 1)->first();
            // 8. First time activation
            if ($haveuser === 'notuser') {
                $userWalletActive->active_id = $newActiveId;
                $userWalletActive->save();
                $receiver->status = 1;
                $receiver->save();
                if ($directUpline) {
                    $directWallet = UserWallet::firstOrNew(['u_code' => $directUpline->id]);
                    $directWallet->c9 = ($directWallet->c9 ?? 0) + 1;
                    $directWallet->c10 = max(($directWallet->c10 ?? 0) - 1, 0);
                    $directWallet->save();
                    $currentSponsorUsername = $receiver->sponsor_id;
                    while ($currentSponsorUsername) {
                        $uplineUser = User::where('username', $currentSponsorUsername)->first();
                        if (!$uplineUser) break;
                        $uplineWallet = UserWallet::firstOrNew(['u_code' => $uplineUser->id]);
                        $uplineWallet->c12 = ($uplineWallet->c12 ?? 0) + 1;
                        $uplineWallet->save();
                        $currentSponsorUsername = $uplineUser->sponsor_id;
                    }
                }
            }
            // 9. Create Order
            $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER';
            $randomNumber = mt_rand(10000000, 99999999);
            $username = $prefix . $randomNumber;
            Order::create([
                'u_code' => auth()->id(),
                'active_id' => $newActiveId,
                'tx_id' => $username,
                'tx_user_id' => $receiver->id,
                'tx_type' => $txType,
                'order_mrp' => $amount,
                'order_amount' => $amount,
                'order_bv' => $amount,
                'package_name' => $pinDetail->pin_type,
                'roi' => $txType == 'retopup' ? 0.30 : $roiValue,
                'times' => 0,
                'roi_bv' => 0,
                'tx_record' => $hash,
                'expire_date' => Carbon::now()->addYear(),
                'status' => 1,
                'payment_status' => 1,
                'payout_id' => 0,
                'added_on' => now(),
                'updated_on' => now(),
            ]);
            // 10. Sponsor Income
            if ($directUpline) {
                $directPercent = 5;
                $sponsorIncome = $amount * 0.50;
                $sponsorWallet = UserWallet::firstOrNew(['u_code' => $directUpline->id]);
                $sponsorWallet->c1 = ($sponsorWallet->c1 ?? 0) + $sponsorIncome;
                $sponsorWallet->c3 = ($sponsorWallet->c3 ?? 0) + $sponsorIncome;
                $sponsorWallet->save();
                Transaction::create([
                    'u_code' => $directUpline->id,
                    'tx_u_code' => $receiver->id,
                    'tx_type' => 'income',
                    'debit_credit' => 'credit',
                    'source' => 'direct',
                    'wallet_type' => 'main_wallet',
                    'ftm_amnt' => $directPercent,
                    'amount' => $sponsorIncome,
                    'tx_charge' => 0.00,
                    'payment_type' => 'direct',
                    'payment_slip' => '',
                    'date' => now(),
                    'status' => 1,
                    'open_wallet' => $sponsorWallet->c1,
                    'closing_wallet' => $sponsorWallet->c1 + $sponsorIncome,
                    'remark' => "{$amount} 10% sponsor income from user {$receiver->username}",
                    'request_amount' => $sponsorIncome,
                    'paidAmount' => $sponsorIncome,
                    'added_on' => now(),
                    'updated_on' => now(),
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => "Topup successful for user {$receiver->username} with Package Amount \${$amount}.",
                'data' => [
                    'username' => $receiver->username,
                    'amount' => $amount,
                    'tx_type' => $txType,
                    'active_id' => $newActiveId
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function orderHistory(Request $request)
    {
        $userId = auth()->id();
        // Prepare query
        $ordersQuery = Order::where('tx_user_id', $userId)
            ->whereIn('tx_type', ['topup', 'retopup', 'subcription']);
        // Get total amount from matching orders
        $totalamount = $ordersQuery->sum('order_amount');
        // Then get the orders for the view
        $orders = $ordersQuery->orderBy('added_on', 'desc')->get();
        return view('user.envest.topup_history', compact('orders', 'totalamount'));
    }


    public function orderHistoryData(Request $request)
    {
        $userId = auth()->id();
        $orders = Order::where('tx_user_id', $userId)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->orderBy('added_on', 'desc');
        return DataTables::of($orders)

            ->addIndexColumn()

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    if ($row->expire_date >= now()) {
                        return 'Active';
                    }

                    return 'Expired';
                }

                return 'Pending';
            })

            ->editColumn('added_on', function ($row) {

                return \Carbon\Carbon::parse($row->added_on)
                    ->format('d M Y h:i A');
            })

            ->make(true);
    }

public function productHistory()
{

    $orders = Order::with('items.product')
        ->where('u_code',auth()->id())
        ->where('tx_type','product')
        ->latest('added_on')
        ->get();

    return view('user.orders.product_history',compact('orders'));
}
public function productHistoryData(Request $request)
{

    $orders = Order::where('u_code', auth()->id())
        ->where('tx_type', 'product')
        ->select('id','tx_id','order_amount','order_bv','added_on','status')
        ->orderBy('added_on','desc');

    return DataTables::of($orders)

        ->addIndexColumn()

        ->editColumn('order_amount', function ($row) {

            return number_format($row->order_amount,2);

        })

        ->editColumn('order_bv', function ($row) {

            return number_format($row->order_bv,2);

        })

        ->editColumn('added_on', function ($row) {

            return \Carbon\Carbon::parse($row->added_on)
                ->format('d M Y h:i A');

        })

        ->addColumn('view', function ($row) {

            $url = route('order.invoice',['id'=>$row->tx_id]);

            return '<a href="'.$url.'" class="btn btn-sm btn-primary">View</a>';

        })

        ->editColumn('status', function ($row) {

            if($row->status==0) return "Pending";
            if($row->status==1) return "Dispatch";
            if($row->status==2) return "Delivered";
            if($row->status==3) return "Cancelled";
            if($row->status==4) return "Returned";

            return "Unknown";

        })

        ->rawColumns(['view'])

        ->make(true);
}

    public function invest(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'package' => 'nullable|exists:pin_details,pin_type',
            'tx_hash' => 'required|string',
        ]);
        $user = auth()->user();
        $pinDetail = PinDetail::where('pin_type', $request->package)->first();
        $verify_hash = $this->addFundConfirm($request->tx_hash, 'usdt', $request->amount);
        if (!$pinDetail || !$verify_hash) {
            return response()->json(['error' => 'Invalid package or transaction'], 400);
        }
        $customFields = json_decode($pinDetail->custom_fields, true);
        $customFieldMap = CustomField::where('module_name', 'pin_details')
            ->whereIn('name', ['roi', 'pin rate'])
            ->pluck('id', 'name');
        $roiFieldId = $customFieldMap['roi'] ?? null;
        $roiValue = $roiFieldId && isset($customFields[$roiFieldId]) ? $customFields[$roiFieldId] : 0;
        /*$userWallet = UserWallet::firstOrNew(['u_code' => $user->id]);
        $currentAmount = $userWallet->c1 ?? 0;
        if ($currentAmount < $request->amount) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }
        $userWallet->c1 = $currentAmount - $request->amount;
        $userWallet->save();*/
        $maxId = Order::max('active_id');
        $newActiveId = $maxId ? $maxId + 1 : 1;
        $existingOrder = Order::where('tx_user_id', $user->id)
            ->where('package_name', $pinDetail->pin_type)
            ->exists();
        $txType = $existingOrder ? 'retopup' : 'topup';
        $order = Order::create([
            'u_code' => $user->id,
            'active_id' => $newActiveId,
            'tx_id' => $user->username,
            'tx_user_id' => $user->id,
            'tx_type' => $txType,
            'order_mrp' => $request->amount,
            'tx_hash' => $request->tx_hash,
            'order_amount' => $request->amount,
            'order_bv' => $request->amount,
            'package_name' => $pinDetail->pin_type,
            'roi' => $txType == 'retopup' ? 0.30 : $roiValue,
            'times' => 0,
            'roi_bv' => 0,
            'expire_date' => now()->addYear(),
            'status' => 1,
            'payment_status' => 1,
            'payout_id' => 0,
            'added_on' => now(),
            'updated_on' => now(),
        ]);
        // Process direct income if applicable
        $directUpline = User::where('username', $user->sponsor_id)->where('status', 1)->first();
        if ($directUpline) {
            $directPercent = round($request->amount * 0.10, 2);
            $directWallet = UserWallet::firstOrNew(['u_code' => $directUpline->id]);
            $directWallet->c1 = ($directWallet->c1 ?? 0) + $directPercent;
            $directWallet->c3 = ($directWallet->c3 ?? 0) + $directPercent;
            $directWallet->save();
            Transaction::create([
                'u_code' => $directUpline->id,
                'tx_u_code' => $user->id,
                'tx_type' => 'income',
                'debit_credit' => 'credit',
                'source' => 'direct',
                'wallet_type' => 'main_wallet',
                'ftm_amnt' => $directPercent,
                'amount' => $directPercent,
                'tx_charge' => 0.00,
                'payment_type' => 'direct',
                'payment_slip' => '',
                'date' => now(),
                'status' => 1,
                'open_wallet' => $directWallet->c1 - $directPercent,
                'closing_wallet' => $directWallet->c1,
                'remark' => "10% sponser income from user {$user->username}",
                'request_amount' => $directPercent,
                'paidAmount' => $directPercent,
                'added_on' => now(),
                'updated_on' => now(),
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Investment successful',
            'order_id' => $order->id,
            'roi' => $order->roi,
        ]);
    }
    public function addFundConfirm($tr_hash, $tx_type, $amount)
    {
        $type = strtolower($tx_type);
        // Only allow USDT
        if ($type !== 'usdt') {
            Log::error('Unsupported token type: ' . $type);
            return false;
        }
        // Get receiving address from settings
        $receivingAddress = strtolower(AdvanceInfo::where('label', 'company_smart_chain_address')->value('value'));
        $usdtTokenAddress = strtolower("0x55d398326f99059fF775485246999027B3197955");
        // Check if transaction already recorded
        if (Order::where('tx_record', $tr_hash)->exists()) {
            Log::error('Duplicate transaction hash: ' . $tr_hash);
            return false;
        }
        // API call to Etherscan v2 (or similar endpoint)
        $apiKey = 'FZ2EYJVYU2363TSE6R4FJHNEW7UNR9PDQI'; // Store securely in .env ideally
        $chainId = 56; // BSC Mainnet
        $url = "https://api.etherscan.io/v2/api"
            . "?chainid={$chainId}"
            . "&module=transaction"
            . "&action=gettxreceiptstatus"
            . "&txhash={$tr_hash}"
            . "&apikey={$apiKey}";
        try {
            $response = Http::get($url);
            $receipt = $response->json();
            Log::error('Etherscan v2 Receipt: ' . json_encode($receipt));
            if (isset($receipt['result']['status'])) {
                // Transaction confirmed, you can create the Order here if needed
                Log::info('Transaction confirmed and ready: ' . $tr_hash);
                return true;
            } else {
                Log::error('Transaction not confirmed or invalid: ' . $tr_hash);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Etherscan API error: ' . $e->getMessage());
            return false;
        }
    }

    private function activeDirects($userid)
    {
        return User::where('u_sponsor', $userid)
            ->where('status', 1)
            ->count();
    }

    private function getTotalIncome($userId)
    {
        return Transaction::where('u_code', $userId)
            ->where('tx_type', 'income')
            ->whereIn('source', ['roi', 'level', 'reward', 'royalty', 'stake_level', 'direct', 'binary'])
            ->sum('amount');
    }

    private function getTotalOrder($userId)
    {
        return Order::where('tx_user_id', $userId)
            ->whereIn('tx_type', ['topup', 'retopup'])
            ->sum('order_amount');
    }
}
