<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\FranchiseOrder;
use App\Models\TransactionFranchise;
use App\Models\FranchiseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /** Franchise order list */
    public function index()
    {
        $orders = FranchiseOrder::with('items')
            ->where('franchise_id', auth('franchise')->id())
            ->latest()
            ->get();

        return view('franchise.orders.index', compact('orders'));
    }

    /** Product list for order */
    public function create()
    {
        $products = Product::where('status', 1)->get();
        return view('franchise.orders.create', compact('products'));
    }

    /** Place order */
    public function store(Request $request)
    {
        // પેલા ચેક કરો કે કોઈ પ્રોડક્ટ મોકલી છે કે નહીં
        if (!$request->has('product_id')) {
            return back()->with('error', 'Please select at least one product.');
        }

        $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'qty.*' => 'nullable|integer|min:0' // આને nullable કરી દો
        ]);

        DB::transaction(function () use ($request) {
            $franchise = auth('franchise')->user();

            // ઓર્ડર ક્રિએટ કરો (ખાલી અમાઉન્ટ સાથે)
            $order = FranchiseOrder::create([
                'f_code' => $franchise->username,
                'franchise_id' => $franchise->id,
                'status' => 0,
                'order_amount' => 0,
                'order_bv' => 0,
                'order_mrp' => 0,
                'order_address' => $franchise->address 
            ]);

            $total = $bv = $mrp = 0;
            $hasItems = false;

            foreach ($request->product_id as $i => $pid) {
                $qty = $request->qty[$i];

                // જો ક્વોન્ટિટી 0 થી વધુ હોય તો જ આઈટમ એડ કરવી
                if ($qty > 0) {
                    $product = Product::findOrFail($pid);

                    $sub = $product->dp * $qty;
                    $subMrp = $product->mrp * $qty;
                    $subBv = $product->product_bv * $qty;

                    FranchiseOrderItem::create([
                        'franchise_id' => $franchise->id,
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $qty,
                        'product_bv' => $subBv,
                        'sub_total' => $sub,
                        'sub_mrp' => $subMrp
                    ]);

                    $total += $sub;
                    $bv += $subBv;
                    $mrp += $subMrp;
                    $hasItems = true;
                }
            }

            // જો યુઝરે બધી ક્વોન્ટિટી 0 નાખી હોય તો ઓર્ડર ડીલીટ કરી દેવો અથવા ભૂલ બતાવવી
            if (!$hasItems) {
                throw new \Exception('No products were selected with valid quantity.');
            }

            $order->update([
                'order_amount' => $total,
                'order_bv' => $bv,
                'order_mrp' => $mrp
            ]);
        });

        return redirect()->route('franchise.orders.index')
            ->with('success', 'Order placed successfully. Waiting for admin approval');
    }

    public function show($id)
{
    $franchise = auth('franchise')->user();

    $order = FranchiseOrder::with('items.product')
        ->where('franchise_id', $franchise->id)
        ->findOrFail($id);

    return view('franchise.orders.show', compact('order'));
}


public function fundRequest()
{
    $franchise = auth('franchise')->user();

    $history = TransactionFranchise::where('u_code', $franchise->username)
        ->where('tx_type', 'fund_request')
        ->latest()
        ->get();

    return view('franchise.fund.request', compact('franchise', 'history'));
}

public function fundRequestStore(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'payment_screenshot' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'remark' => 'nullable|string|max:255',
    ]);

    $franchise = auth('franchise')->user();

    $imagePath = null;
    if ($request->hasFile('payment_screenshot')) {
        $imagePath = $request->file('payment_screenshot')
            ->store('fund_proofs', 'public');
    }

    TransactionFranchise::create([
        'u_code'          => $franchise->username,
        'tx_type'         => 'fund_request',
        'debit_credit'    => 'credit',
        'amount'          => $request->amount,
        'open_wallet'     => $franchise->balance,
        'closing_wallet'  => $franchise->balance,
        'remark'          => $request->remark,
        'img'             => $imagePath,
        'status'          => 0, // Pending
        'reject_reason'   => null,
    ]);

    return back()->with('success', 'Fund request submitted successfully!');
}

}
