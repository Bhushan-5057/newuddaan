<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryContact;
use App\Models\UserWallet;
use App\Helpers\CashfreeHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $cashfree;

    public function __construct()
    {
        $this->cashfree = new CashfreeHelper();
    }

    public function initiatePayment(Request $request)
    {
        $request->validate([
            'delivery_contact_id' => 'required|exists:delivery_contacts,id',
            'order_notes' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $carts = Cart::with('product')->where('u_code', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }

        $delivery = DeliveryContact::where('u_code', $user->id)->findOrFail($request->delivery_contact_id);
        
        $totalMRP = $carts->sum(fn($i) => $i->dp * $i->quantity);
        $totalBV = $carts->sum(fn($i) => ($i->product->product_bv ?? 0) * $i->quantity);
        $totalPV = $carts->sum(fn($i) => ($i->product->pv ?? 0) * $i->quantity);

        // Generate unique order ID
        $orderId = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(8)) . '-' . $user->id;

        // Prepare Cashfree order data
        $orderData = [
            'order_id' => $orderId,
            'order_amount' => (float)$totalMRP,
            'order_currency' => 'INR',
            'order_note' => 'Order from ' . config('app.name'),
            'customer_details' => [
                'customer_id' => (string)$user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->mobile ?? '9999999999',
            ],
            'order_meta' => [
                'return_url' => route('payment.callback') . '?order_id=' . $orderId,
                'notify_url' => route('payment.webhook'),
            ],
        ];

        // Create order in Cashfree
        $response = $this->cashfree->createOrder($orderData);

        if ($response && isset($response['payment_session_id'])) {
            // Store order data in session
            session([
                'temp_order' => [
                    'user_id' => $user->id,
                    'delivery_contact_id' => $delivery->id,
                    'order_id' => $orderId,
                    'total_amount' => $totalMRP,
                    'total_bv' => $totalBV,
                    'total_pv' => $totalPV,
                    'order_notes' => $request->order_notes,
                    'carts' => $carts->toArray(),
                    'delivery_address' => $delivery->address,
                    'contact_no' => $delivery->contact_no,
                ]
            ]);

            // Return payment page view
            return view('main.payment.redirect', [
                'payment_session_id' => $response['payment_session_id'],
                'order_id' => $orderId
            ]);
        }

        return back()->with('error', 'Unable to initiate payment. Please try again.');
    }

    public function paymentCallback(Request $request)
    {
        $orderId = $request->get('order_id');
        
        // Verify payment status
        $response = $this->cashfree->getOrderStatus($orderId);
        
        if ($response && isset($response['order_status']) && $response['order_status'] == 'PAID') {
            // Payment successful
            return $this->createOrderAfterPayment($orderId, $response);
        }
        
        return redirect()->route('cart.index')->with('error', 'Payment failed or was cancelled. Please try again.');
    }

    private function createOrderAfterPayment($orderId, $paymentData)
    {
        $tempOrder = session('temp_order');
        
        if (!$tempOrder || $tempOrder['order_id'] != $orderId) {
            return redirect()->route('home')->with('error', 'Invalid order session.');
        }

        DB::beginTransaction();

        try {
            $user = auth()->user();
            
            // Create order
            $order = new Order();
            $order->u_code = $user->id;
            $order->tx_type = 'product';
            $order->tx_id = $orderId;
            $order->tx_user_id = $user->username;
            $order->order_mrp = $tempOrder['total_amount'];
            $order->order_amount = $tempOrder['total_amount'];
            $order->package_name = 'Delivery';
            $order->order_bv = $tempOrder['total_bv'];
            $order->pv = $tempOrder['total_pv'];
            $order->order_address = $tempOrder['delivery_address'];
            $order->order_details = "Contact: " . $tempOrder['contact_no'];
            $order->order_notes = "Note: " . ($tempOrder['order_notes'] ?? '');
            $order->delivery_schedule = 'All Time';
            $order->roi = 0;
            $order->times = 0;
            $order->roi_bv = 0;
            $order->status = 0;
            $order->payment_status = 1;
            $order->payment_method = 'cashfree';
            $order->payment_details = json_encode($paymentData);
            $order->closing_status = 0;
            $order->payout_status = 0;
            $order->added_on = now();
            $order->updated_on = now();
            $order->expire_date = now()->addYears(1);
            $order->save();

            // Create order items
            foreach ($tempOrder['carts'] as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'u_code' => $user->id,
                    'product_id' => $cart['product_id'],
                    'qty' => $cart['quantity'],
                    'price' => $cart['dp'],
                    'bv' => $cart['product_bv'] ?? 0,
                    'pv' => $cart['pv'] ?? 0,
                    'added_on' => now(),
                    'item_status' => 0,
                ]);
            }

            // Clear cart
            Cart::where('u_code', $user->id)->delete();
            
            // Clear session
            session()->forget('temp_order');

            DB::commit();

            return redirect()->route('order.invoice', ['id' => $order->tx_id])
                ->with('success', 'Payment successful! Order placed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Creation Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Order creation failed. Please contact support.');
        }
    }

    public function webhook(Request $request)
    {
        // Handle Cashfree webhooks
        $payload = $request->all();
        
        Log::info('Cashfree Webhook Received:', $payload);
        
        // Process webhook data
        if (isset($payload['order_id']) && isset($payload['order_status'])) {
            if ($payload['order_status'] == 'PAID') {
                // Update order status if needed
                // You can implement order status update here
            }
        }
        
        return response()->json(['status' => 'received']);
    }
}