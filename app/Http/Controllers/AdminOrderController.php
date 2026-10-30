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

use App\Models\Order;
use App\Models\OrderItem;

use Illuminate\Support\Str;

use App\Models\MailTemplate;

use Illuminate\Support\Facades\Mail;

use App\Mail\UserRegisteredMail;

use App\Models\Transaction;

use App\Models\FranchiseOrder;

use App\Models\FranchiseOrderItem;

use App\Models\TransactionFranchise;

use App\Models\RewardTracking;

use App\Models\RankReward;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use App\Models\PinDetail;

use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use App\Helpers\BvHelper;
use App\Helpers\IncomeHelper;
use App\Traits\BinaryPlacementTrait;

use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

use Illuminate\Support\Facades\Http;





class AdminOrderController extends Controller

{

    use BinaryPlacementTrait;

    public function productOrderPage($status)

    {

        $title = match ($status) {

            'pending'   => 'Pending Orders',

            'completed' => 'Completed Orders',

            'canceled'  => 'Canceled & Return Orders',

            default     => 'Product Orders'
        };

        return view('admin.orders.product_list', compact('status', 'title'));
    }



    public function productOrderData(Request $request, $status)

    {

        // સ્ટેટસ ફિલ્ટર

        $query = Order::with('user')->where('tx_type', 'product');



        if ($status == 'pending') $query->whereIn('status', [1, 0 ,5]);

        elseif ($status == 'completed') $query->where('status', 2);

        elseif ($status == 'canceled') $query->whereIn('status', [3, 4]); // 3: Canceled, 4: Return



        // ડેટ ફિલ્ટર (Weekly, Monthly, 15 Days, 3 Months)

        if ($request->filled('date_range')) {

            $date = $request->date_range;

            if ($date == 'weekly') $query->where('added_on', '>=', Carbon::now()->subDays(7));

            elseif ($date == '15days') $query->where('added_on', '>=', Carbon::now()->subDays(15));

            elseif ($date == 'monthly') $query->where('added_on', '>=', Carbon::now()->startOfMonth());

            elseif ($date == '3months') $query->where('added_on', '>=', Carbon::now()->subMonths(3));
        }



        // કસ્ટમ ડેટ ફિલ્ટર

        if ($request->filled('from_date') && $request->filled('to_date')) {

            $query->whereBetween('added_on', [

                Carbon::parse($request->from_date)->startOfDay(),

                Carbon::parse($request->to_date)->endOfDay()

            ]);
        }







        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('status_label', function ($row) {

                return match ($row->status) {

                    0 => '<span class="badge bg-warning">Pending</span>',

                    1 => '<span class="badge bg-info">Shipped</span>',

                    2 => '<span class="badge bg-success">Delivered</span>',

                    3 => '<span class="badge bg-danger">Canceled</span>',

                    4 => '<span class="badge bg-dark">Returned</span>',
                    5 => '<span class="badge bg-dark">Packing In-Progress</span>',

                    default => '<span class="badge bg-secondary">Unknown</span>',
                };
            })

            ->addColumn('action', function ($row) {
                $viewBtn = '<a href="' . route('admin.productorders.view', $row->id) . '" class="btn btn-sm btn-primary me-1">View</a>';

                // Cancelled (3) / Returned (4) orders can be permanently deleted
                if (in_array((int) $row->status, [3, 4], true)) {
                    $deleteUrl = route('admin.productorders.delete', $row->id);
                    $viewBtn .= '<button type="button" class="btn btn-sm btn-danger btn-delete-order" data-url="' . $deleteUrl . '">Delete</button>';
                }

                return $viewBtn;
            })

            ->editColumn('added_on', function ($row) {

                return \Carbon\Carbon::parse($row->added_on)->format('d-m-Y h:i A');
            })

            ->rawColumns(['status_label', 'action'])

            ->make(true);
    }
    
    
    
        public function printSingleInvoice($id)
{
    $order = Order::with(['user', 'items.product'])->findOrFail($id);

    $html = view('admin.orders.invoice_pdf', compact('order'))->render();

    $pdf = Pdf::loadHTML($html);
    $pdf->setPaper('A4', 'portrait');

    return $pdf->stream('invoice_' . $order->tx_id . '.pdf');
}




    public function productviewOrder($id)

    {

        // ઓર્ડરની સાથે આઈટમ્સ અને પ્રોડક્ટ રિલેશન લોડ કરો

        $order = Order::with(['user', 'items.product'])->findOrFail($id);



        return view('admin.orders.productview', compact('order'));
    }

    /**
     * Permanently delete a cancelled/returned product order and its items.
     */
    public function productDeleteOrder($id)
    {
        $order = Order::findOrFail($id);

        if (!in_array((int) $order->status, [3, 4], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Only cancelled or returned orders can be deleted.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            OrderItem::where('order_id', $order->id)->delete();
            $order->delete();
            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order deleted successfully.',
                ]);
            }

            return back()->with('success', 'Order deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete order.',
                ], 500);
            }

            return back()->with('error', 'Failed to delete order.');
        }
    }


public function productupdateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);

    // Lock completed & cancelled orders
    if (in_array($order->status, [2, 3])) {
        return back()->with('error', 'This order is locked and cannot be changed.');
    }

    $status = $request->status;
    
    // Validate payout_id for delivered status
    if ($status == 2) {
        $request->validate([
            'payout_id' => 'nullable|string|max:100'
        ]);
        
        // Store payout_id if provided
        if ($request->filled('payout_id')) {
            $order->payout_id = $request->payout_id;
            $order->payment_status = 1;
        }
    }
    
    $order->status = $status;

    OrderItem::where('order_id', $order->id)->update([
        'item_status' => $status
    ]);

    if ($status == 5) {
        $order->date_accept = now();
    }

    if ($status == 1) {
        $order->date_dispatch = now();
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER DELIVERED → CREDIT BV
    |--------------------------------------------------------------------------
    */
    if ($status == 2) {

        DB::beginTransaction();

        try {

            $order->date_approve = now();

            $userWallet = UserWallet::firstOrNew([
                'u_code' => $order->u_code
            ]);

            $product_bv = $order->order_bv;
            $amount = $order->order_amount;
            $opening_bv = $userWallet->c2 ?? 0;
            $closing_bv = $opening_bv + $product_bv;

            /*
            |--------------------------------------------------------------------------
            | PRODUCT BV CREDIT
            |--------------------------------------------------------------------------
            */
            Transaction::create([
                'order_id'       => $order->id,
                'u_code'         => $order->u_code,
                'tx_u_code'      => $order->u_code,
                'tx_type'        => 'product_bv',
                'debit_credit'   => 'credit',
                'source'         => 'product_purchase',
                'wallet_type'    => 'bv_wallet',
                'amount'         => $product_bv,
                'payment_type'   => 'product_delivery_bv',
                'date'           => now(),
                'status'         => 1,
                'open_wallet'    => $opening_bv,
                'closing_wallet' => $closing_bv,
                'remark'         => "BV credited for Order #{$order->tx_id}",
                'added_on'       => now(),
            ]);

            $userWallet->c2 = $closing_bv;
            $userWallet->save();

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */
            $receiver = User::find($order->u_code);

            if (!$receiver) {
                DB::commit();
                $order->updated_on = now();
                $order->save();
                return back()->with('error', 'User not found.');
            }

            /*
            |--------------------------------------------------------------------------
            | TOPUP CHECK
            |--------------------------------------------------------------------------
            */
            $hasTopup = Order::where('u_code', $receiver->id)
                ->whereIn('tx_type', ['topup', 'retopup'])
                ->exists();

            $existingOrder = Order::where('tx_user_id', $receiver->id)
                ->whereIn('tx_type', ['topup', 'retopup'])
                ->exists();

            $haveuser = $existingOrder ? 'haveuser' : 'notuser';

            /*
            |--------------------------------------------------------------------------
            | UPLINE
            |--------------------------------------------------------------------------
            */
            $directUpline = User::where('username', $receiver->sponsor_id)
                ->where('status', 1)
                ->first();

            $userWalletActive = UserWallet::firstOrNew(['u_code' => $receiver->id]);
            $productBV = $order->order_bv;

            /*
            |--------------------------------------------------------------------------
            | TOPUP SPLIT
            |--------------------------------------------------------------------------
            */
            $bvSplit = BvHelper::calculateTopupSplit($receiver->id, $productBV);
            $topupBV = $bvSplit['topup_bv'];
            $retopupBV = $bvSplit['retopup_bv'];

            /*
            |--------------------------------------------------------------------------
            | BONUS ACTIVE
            |--------------------------------------------------------------------------
            */
            if ($productBV >= 50) {
                $receiver->bonus_status = 1;
                $receiver->bonus_hit_date = now();
                $receiver->save();
            }

            /*
            |--------------------------------------------------------------------------
            | CASHBACK BONUS
            |--------------------------------------------------------------------------
            */
            if (!$hasTopup && $productBV >= 100 && $receiver->is_free_id != 1) {
                IncomeHelper::giveCashbackBonus($order, $productBV);
            }

            /*
            |--------------------------------------------------------------------------
            | FREE IDS
            |--------------------------------------------------------------------------
            */
            if (!$hasTopup && $productBV >= 100) {
                IncomeHelper::createFreeIds($receiver);
            }

            /*
            |--------------------------------------------------------------------------
            | USER ACTIVATION
            |--------------------------------------------------------------------------
            */
            if ($haveuser === 'notuser') {

                $maxId = Order::max('active_id');
                $newActiveId = $maxId ? $maxId + 1 : 1;

                $userWalletActive->active_id = $newActiveId;
                $userWalletActive->save();

                $receiver->status = 1;
                $receiver->activate_date = now();
                $receiver->save();

                if ($directUpline) {

                    $directWallet = UserWallet::firstOrNew([
                        'u_code' => $directUpline->id
                    ]);

                    $directWallet->c9 = ($directWallet->c9 ?? 0) + 1;
                    $directWallet->c10 = max(($directWallet->c10 ?? 0) - 1, 0);
                    $directWallet->save();

                    $currentSponsorUsername = $receiver->sponsor_id;

                    while ($currentSponsorUsername) {

                        $uplineUser = User::where('username', $currentSponsorUsername)->first();
                        if (!$uplineUser) break;

                        $uplineWallet = UserWallet::firstOrNew([
                            'u_code' => $uplineUser->id
                        ]);

                        $uplineWallet->c12 = ($uplineWallet->c12 ?? 0) + 1;
                        $uplineWallet->save();

                        $currentSponsorUsername = $uplineUser->sponsor_id;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | TOPUP ORDER
            |--------------------------------------------------------------------------
            */
            if ($topupBV > 0) {

                $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER';
                $txid = $prefix . mt_rand(10000000, 99999999);

                Order::create([
                    'u_code'         => $receiver->id,
                    'active_id'      => 0,
                    'tx_id'          => $txid,
                    'tx_user_id'     => $receiver->id,
                    'tx_type'        => 'topup',
                    'order_mrp'      => $amount,
                    'order_amount'   => $amount,
                    'order_bv'       => $topupBV,
                    'package_name'   => 'Auto Topup',
                    'roi'            => 0,
                    'times'          => 0,
                    'roi_bv'         => 0,
                    'expire_date'    => Carbon::now()->addMonth(),
                    'status'         => 1,
                    'payment_status' => 1,
                    'added_on'       => now(),
                    'updated_on'     => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | RETOPUP ORDER
            |--------------------------------------------------------------------------
            */
            if ($retopupBV > 0) {

                $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER';
                $txid = $prefix . mt_rand(10000000, 99999999);

                Order::create([
                    'u_code'         => $receiver->id,
                    'active_id'      => 0,
                    'tx_id'          => $txid,
                    'tx_user_id'     => $receiver->id,
                    'tx_type'        => 'retopup',
                    'order_mrp'      => $amount,
                    'order_amount'   => $amount,
                    'order_bv'       => $retopupBV,
                    'package_name'   => 'Auto Retopup',
                    'roi'            => 0,
                    'times'          => 0,
                    'roi_bv'         => 0,
                    'expire_date'    => Carbon::now()->addMonth(),
                    'status'         => 1,
                    'payment_status' => 1,
                    'added_on'       => now(),
                    'updated_on'     => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | COMPLETE
            |--------------------------------------------------------------------------
            */
            $order->updated_on = now();
            $order->save();

            DB::commit();

            return back()->with('success', 'Order delivered successfully. BV credited to user wallet.');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Order Delivery Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Delivery Failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER CANCELLED / RETURNED → REFUND AMOUNT
    |--------------------------------------------------------------------------
    */
    if (in_array($status, [3, 4])) {

        if ($status == 3) {
            $order->date_reject = now();
        }

        $refundAmount = $order->order_amount;
        $userWallet = UserWallet::firstOrNew(['u_code' => $order->u_code]);

        $opening_wallet = $userWallet->c4 ?? 0;
        $closing_wallet = $opening_wallet + $refundAmount;

        Transaction::create([
            'order_id'         => $order->id,
            'u_code'           => $order->u_code,
            'tx_u_code'        => $order->u_code,
            'tx_type'          => 'refund',
            'debit_credit'     => 'credit',
            'source'           => 'order_refund',
            'wallet_type'      => 'c4_wallet',
            'amount'           => $refundAmount,
            'payment_type'     => 'order_cancel_refund',
            'date'             => now(),
            'status'           => 1,
            'open_wallet'      => $opening_wallet,
            'closing_wallet'   => $closing_wallet,
            'remark'           => "Refund for Order #{$order->tx_id}",
            'added_on'         => now(),
        ]);

        $userWallet->c4 = $closing_wallet;
        $userWallet->save();
    }

    $order->updated_on = now();
    $order->save();

    return back()->with('success', 'Order status updated successfully.');
}
public function admintopup()
    {
        return view('admin.orders.topup');
    }

    // AJAX દ્વારા યુઝરનું નામ ચકાસવા માટે
    public function checkUser(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if ($user) {
            return response()->json(['success' => true, 'name' => $user->name]);
        }
        return response()->json(['success' => false, 'message' => 'User not found']);
    }

    public function adminAddTopup(Request $request)
    {
        $request->validate([
            'username'     => 'required|exists:users,username',
            'order_amount' => 'required|numeric|min:0',
            'order_bv'     => 'required|numeric|min:0',
        ]);

        $receiver = User::where('username', $request->username)->first();

        if (!$receiver) {
            return back()->with('error', 'User not found.');
        }

        $amount = $request->order_amount;
        $productBV = $request->order_bv;

        DB::beginTransaction();

        try {
            /* Topup Check */
            $hasTopup = Order::where('u_code', $receiver->id)->whereIn('tx_type', ['topup', 'retopup'])->exists();
            $existingOrder = Order::where('tx_user_id', $receiver->id)->whereIn('tx_type', ['topup', 'retopup'])->exists();
            $haveuser = $existingOrder ? 'haveuser' : 'notuser';

            /* Upline & Wallet Initialization */
            $directUpline = User::where('username', $receiver->sponsor_id)->where('status', 1)->first();
            $userWalletActive = UserWallet::firstOrNew(['u_code' => $receiver->id]);

            /* BV Split Calculation */
            $bvSplit = BvHelper::calculateTopupSplit($receiver->id, $productBV);
            $topupBV = $bvSplit['topup_bv'] ?? 0;
            $retopupBV = $bvSplit['retopup_bv'] ?? 0;

            /* Bonus Active Check */
            if ($productBV >= 50) {
                $receiver->bonus_status = 1;
                $receiver->bonus_hit_date = now();
                $receiver->save();
            }

            /* Cashback Bonus */
            if (!$hasTopup && $productBV >= 100 && $receiver->is_free_id != 1) {
                IncomeHelper::giveCashbackBonus(
                    (object)['u_code' => $receiver->id, 'order_amount' => $amount, 'id' => 0],
                    $productBV
                );
            }

            /* Free IDs Creation */
            if (!$hasTopup && $productBV >= 100) {
                IncomeHelper::createFreeIds($receiver);
            }

            /* User Activation & Upline Count Updates */
            if ($haveuser === 'notuser') {
                $maxId = Order::max('active_id');
                $newActiveId = $maxId ? $maxId + 1 : 1;

                $userWalletActive->active_id = $newActiveId;
                $userWalletActive->save();

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

            $prefix = AdvanceInfo::where('label', 'user_gen_prefix')->value('value') ?? 'USER';

            /* Create Topup Order */
            if ($topupBV > 0) {
                $txid = $prefix . mt_rand(10000000, 99999999);

                Order::create([
                    'u_code'         => $receiver->id,
                    'active_id'      => $userWalletActive->active_id ?? 0,
                    'tx_id'          => $txid,
                    'tx_user_id'     => $receiver->id,
                    'tx_type'        => 'topup',
                    'order_mrp'      => $amount,
                    'order_amount'   => $amount,
                    'order_bv'       => $topupBV,
                    'package_name'   => 'Admin Manual Topup',
                    'roi'            => 0,
                    'times'          => 0,
                    'roi_bv'         => 0,
                    'expire_date'    => Carbon::now()->addMonth(),
                    'status'         => 1,
                    'payment_status' => 1,
                    'added_on'       => now(),
                    'updated_on'     => now(),
                ]);
            }

            /* Create Retopup Order */
            if ($retopupBV > 0) {
                $txid = $prefix . mt_rand(10000000, 99999999);

                Order::create([
                    'u_code'         => $receiver->id,
                    'active_id'      => $userWalletActive->active_id ?? 0,
                    'tx_id'          => $txid,
                    'tx_user_id'     => $receiver->id,
                    'tx_type'        => 'retopup',
                    'order_mrp'      => $amount,
                    'order_amount'   => $amount,
                    'order_bv'       => $retopupBV,
                    'package_name'   => 'Admin Manual Retopup',
                    'roi'            => 0,
                    'times'          => 0,
                    'roi_bv'         => 0,
                    'expire_date'    => Carbon::now()->addMonth(),
                    'status'         => 1,
                    'payment_status' => 1,
                    'added_on'       => now(),
                    'updated_on'     => now(),
                ]);
            }

            DB::commit();

            return back()->with('success', 'Topup/Retopup process completed successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Admin Topup Failed', [
                'username' => $request->username,
                'error'    => $e->getMessage()
            ]);

            return back()->with('error', 'Process Failed: ' . $e->getMessage());
        }
    }

    public function orderHistory(Request $request)

    {

        $baseQuery = Order::whereIn('tx_type', ['topup', 'retopup']);



        $totalOrderAmount = $baseQuery->sum('order_amount');

        $totalOrderCount  = $baseQuery->count();



        return view('admin.orders.topup_history', compact(

            'totalOrderAmount',

            'totalOrderCount'

        ));
    }

    public function orderHistoryData(Request $request)

    {

        $orders = Order::with('user')

            ->whereIn('tx_type', ['topup', 'retopup']);



        // ✅ USERNAME FILTER

        if ($request->filled('username')) {

            $orders->whereHas('user', function ($q) use ($request) {

                $q->where('username', 'like', '%' . $request->username . '%');
            });
        }



        // ✅ DATE FILTER

        if ($request->filled('from_date') && $request->filled('to_date')) {

            $orders->whereBetween('added_on', [

                Carbon::parse($request->from_date)->startOfDay(),

                Carbon::parse($request->to_date)->endOfDay()

            ]);
        }



        // FILTERED TOTALS

        $filteredOrderAmount = (clone $orders)->sum('order_amount');

        $filteredOrderCount  = (clone $orders)->count();



        return DataTables::of($orders)

            ->addIndexColumn()



            ->addColumn('user_info', function ($order) {

                return $order->user

                    ? $order->user->username

                    : $order->u_code;
            })



            ->addColumn('status_label', function ($order) {

                return match ($order->status) {

                    0 => '<span class="badge bg-warning">Pending</span>',

                    1 => '<span class="badge bg-success">Approved</span>',

                    2 => '<span class="badge bg-danger">Rejected</span>',

                    default => '<span class="badge bg-secondary">Unknown</span>',
                };
            })



            ->addColumn('action', function ($order) {

                return '<a href="' . route('admin.orders.view', $order->id) . '" 

                     class="btn btn-sm btn-info">View</a>';
            })



            ->editColumn('added_on', function ($order) {

                return Carbon::parse($order->added_on)->format('d M Y h:i A');
            })



            ->with([

                'filteredOrderAmount' => number_format($filteredOrderAmount, 2),

                'filteredOrderCount'  => $filteredOrderCount,

            ])



            ->rawColumns(['status_label', 'action'])

            ->make(true);
    }







    public function viewOrder($id)

    {

        $order = Order::with('user')->findOrFail($id);

        return view('admin.orders.view', compact('order'));
    }



    public function getClubAchievers()

    {

        // એપ્રિલ થી એપ્રિલનું ફાઇનાન્શિયલ વર્ષ સેટ કરો

        $currentYear = date('Y');

        $startYear = (date('m') < 4) ? $currentYear - 1 : $currentYear;

        $startDate = $startYear . "-04-01 00:00:00";

        $endDate = ($startYear + 1) . "-03-31 23:59:59";



        // તેવા યુઝર્સ જેમણે આ વર્ષમાં ૪ કે તેથી વધુ વાર Diamond Rank મેળવ્યો હોય

        $achievers = User::where('status', 1)

            ->whereHas('rankHistory', function ($query) use ($startDate, $endDate) {

                $query->where('rank', 'Diamond')

                    ->whereBetween('created_at', [$startDate, $endDate]);
            }, '>=', 4)

            ->withCount(['rankHistory' => function ($q) use ($startDate, $endDate) {

                $q->where('rank', 'Diamond')->whereBetween('created_at', [$startDate, $endDate]);
            }])

            ->get();



        return view('admin.club.achievers', compact('achievers', 'startDate', 'endDate'));
    }



    public function distributeClubIncome(Request $request)

    {

        $request->validate([

            'amount' => 'required|numeric|min:1',

            'achiever_ids' => 'required|array'

        ]);



        $amountPerUser = $request->amount;

        $achieverIds = $request->achiever_ids;



        \DB::beginTransaction();

        try {

            foreach ($achieverIds as $userId) {

                $wallet = \App\Models\UserWallet::firstOrNew(['u_code' => $userId]);

                $openingBalance = $wallet->c1 ?? 0;



                $wallet->c1 = ($wallet->c1 ?? 0) + $amountPerUser;

                $wallet->c17 = ($wallet->c17 ?? 0) + $amountPerUser;

                $wallet->save();



                Transaction::create([

                    'u_code' => $userId,

                    'tx_type' => 'income',

                    'source' => 'reward',

                    'debit_credit' => 'credit',

                    'amount' => $amountPerUser,

                    'open_wallet' => $openingBalance,

                    'closing_wallet' => $wallet->c1,

                    'date' => now(),

                    'status' => 1,

                    'remark' => "Yearly Club Member Income Distributed by Admin",

                ]);
            }

            \DB::commit();

            return back()->with('success', 'Club income distributed successfully to selected members!');
        } catch (\Exception $e) {

            \DB::rollBack();

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }





    public function rewardAchievers(Request $request)

    {

        $totalAchievers = RankReward::count();



        return view('admin.rewards.achievers', compact('totalAchievers'));
    }



    public function rewardAchieversData(Request $request)

    {

        $query = RankReward::with('user')->orderBy('created_at', 'desc');



        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('user_info', function ($row) {

                return $row->user ? $row->user->username . ' (' . $row->user->name . ')' : 'Unknown';
            })

            ->addColumn('rank', function ($row) {

                return $row->rank ?? '-';
            })

            ->addColumn('reward', function ($row) {

                return  company_info('currency', '$') . number_format($row->reward, 2);
            })

            ->addColumn('status_label', function ($row) {

                return $row->status == 'Achieved'

                    ? '<span class="badge bg-success">Achieved</span>'

                    : '<span class="badge bg-danger">Pending</span>';
            })

            ->addColumn('date', function ($row) {

                return $row->created_at ? $row->created_at->format('d-M-Y') : '-';
            })

            ->rawColumns(['status_label'])

            ->make(true);
    }



    public function royaltyAchievers(Request $request)

    {

        $totalAchievers = RewardTracking::count();



        return view('admin.rewards.royalty_achievers', compact('totalAchievers'));
    }



    public function royaltyAchieversData(Request $request)

    {

        $query = RewardTracking::with('user')->orderBy('created_at', 'desc');



        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('user_info', function ($row) {

                return $row->user ? $row->user->username . ' (' . $row->user->name . ')' : 'Unknown';
            })

            ->addColumn('rank', function ($row) {

                return $row->rank ?? '-';
            })

            ->addColumn('royalty', function ($row) {

                return $row->royalty ? $row->royalty . '%' : '-';
            })

            ->addColumn('status_label', function ($row) {

                return $row->status == 'Achieved'

                    ? '<span class="badge bg-success">Active</span>'

                    : '<span class="badge bg-danger">Inactive</span>';
            })

            ->addColumn('date', function ($row) {

                return $row->created_at ? $row->created_at->format('d-M-Y') : '-';
            })

            ->rawColumns(['status_label'])

            ->make(true);
    }
}
