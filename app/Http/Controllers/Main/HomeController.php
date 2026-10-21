<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\SubCategory;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\UserWallet;
use App\Models\DeliveryContact;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{




    public function index()
    {

        // માત્ર 'banner' સ્લગ વાળી કેટેગરીની છેલ્લી 5 પ્રોડક્ટ્સ મેળવો
        $bannerProducts = Product::whereHas('category', function ($q) {
            $q->where('slug', 'beauty'); // અહીં તમારી કેટેગરીનો સાચો સ્લગ લખવો
        })
            ->where('status', 1)
            ->latest()
            ->take(5)
            ->get();
        // Fetch categories with subcategories and limit products to 6 per category
        $categories = Category::with(['subCategories', 'products' => function ($query) {
            $query->where('status', 1)->latest()->take(6);
        }])->where('status', 1)->get();

        // Fetch top 10 latest products
        $latestProducts = Product::where('status', 1)
            ->latest()
            ->take(10)
            ->get();

        // Fetch top 8 popular products based on PV (Point Value)
        $popularProducts = Product::where('status', 1)
            ->orderBy('pv', 'desc')
            ->take(8)
            ->get();

        return view('main.index.index', compact('categories', 'latestProducts', 'popularProducts', 'bannerProducts'));
    }

    public function about()
    {
        return view('main.pages.about');
    }

public function shop(Request $request)
{
    $categories = Category::withCount('products')->where('status', 1)->get();

    $query = Product::where('status', 1);

    if ($request->filled('min_price')) {
        $query->where('dp', '>=', $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('dp', '<=', $request->max_price);
    }

    if ($request->sort == 'new') {
        $query->latest();
    } elseif ($request->sort == 'low_high') {
        $query->orderBy('dp', 'asc');
    } elseif ($request->sort == 'high_low') {
        $query->orderBy('dp', 'desc');
    } else {
        $query->latest();
    }

    // ૨૦ ની જગ્યાએ ૧૦૦ અથવા બધીજ (get) મેળવો
    $products = $query->get(); 

    return view('main.product.shop', compact('products', 'categories'));
}

    public function getCounts()
    {
        $u_code = auth('web')->id();

        if (!$u_code) {
            return response()->json([
                'cart_count' => 0,
                'wish_count' => 0,
                'total_price' => 0
            ]);
        }

        $cartItems = \App\Models\Cart::where('u_code', $u_code)->get();

        return response()->json([
            'cart_count'  => $cartItems->sum('quantity'),
            'wish_count'  => \App\Models\Wishlist::where('u_code', $u_code)->count(),
            'total_price' => $cartItems->sum(fn($i) => $i->dp * $i->quantity)
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Redirect to home if search query is empty
        if (!$query) {
            return redirect()->route('dashboard.user');
        }

        $user = auth()->guard('web')->user();
        $userId = $user ? $user->id : null;

        // Search products by name, code, or description
        $productsQuery = Product::where('status', 1)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('p_code', 'LIKE', "%{$query}%")
                    ->orWhere('p_dese', 'LIKE', "%{$query}%");
            });

        // Apply discount filter
        $products = $this->getFilteredProducts($userId, $productsQuery)
            ->paginate(12);

        return view('main.product.search_results', compact('products', 'query'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->input('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $user = auth()->guard('web')->user();
        $userId = $user ? $user->id : null;

        // Fetch top 5 matching products for live suggestions
        $productsQuery = Product::where('status', 1)
            ->where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'slug', 'imgs', 'dp');

        // Apply discount filter
        $products = $this->getFilteredProducts($userId, $productsQuery)
            ->limit(5)
            ->get();

        return response()->json($products);
    }


    
        public function productDetails($slug)
    {
        // 1. Fetch product details by slug
        $product = Product::with(['category', 'subCategory'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        // 2. Fetch related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->take(10)
            ->get();

        return view('main.product.details', compact('product', 'relatedProducts'));
    }


      public function categoryProducts(Request $request, $slug)
    {
        // ૧. પહેલા ચેક કરો કે આ મેઈન કેટેગરી છે કે સબ-કેટેગરી
        $category = Category::where('slug', $slug)->first();
        $subCategory = null;
        $title = "";

        if ($category) {
            // જો મેઈન કેટેગરી હોય
            $query = Product::where('category_id', $category->id);
            $title = $category->name;
        } else {
            // જો સબ-કેટેગરી હોય
            $subCategory = SubCategory::where('slug', $slug)->firstOrFail();
            $query = Product::where('sub_category_id', $subCategory->id);
            $category = $subCategory; // બ્લેડમાં એરર ન આવે તે માટે
            $title = $subCategory->name;
        }

        $query->where('status', 1);

        // ૨. પ્રાઈસ ફિલ્ટર
        if ($request->filled('min_price')) {
            $query->where('dp', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('dp', '<=', $request->max_price);
        }

        // ૩. સોર્ટિંગ લોજિક
        if ($request->sort == 'new') {
            $query->latest();
        } elseif ($request->sort == 'low_high') {
            $query->orderBy('dp', 'asc');
        } elseif ($request->sort == 'high_low') {
            $query->orderBy('dp', 'desc');
        } else {
            $query->latest();
        }

        // ૪. પેજીનેશન અને કેટેગરી લિસ્ટ (સાઈડબાર માટે)
        $perPage = $request->get('show', 20); // તમે ૨૦ કીધું હતું તે મુજબ
        $products = $query->paginate($perPage);

        // View Composer વાપરતા હોવ તો આ લાઈનની જરૂર નથી
        $categories = Category::withCount('products')->where('status', 1)->get();

        return view('main.product.shop', compact('category', 'products', 'categories', 'title'));
    }



    public function cartstore(Request $request)
    {
        // ચેક કરો જો યુઝર લોગિન નથી
        if (!auth()->check()) {
            return response()->json(['status' => 'login', 'message' => 'Please login first']);
        }

        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $user = auth()->user();

        $cartItem = Cart::where('u_code', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'u_code'     => $user->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'dp'         => $product->dp,
                'product_bv' => $product->product_bv,
                'pv'         => $product->pv
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully!'
        ]);
    }



    public function cartupdate(Request $request)
    {

        $cart = Cart::where(
            'id',
            $request->cart_id
        )
            ->where(
                'u_code',
                auth()->id()
            )
            ->firstOrFail();

        /*
    |--------------------------------------------------------------------------
    | UPDATE QUANTITY
    |--------------------------------------------------------------------------
    */

        if ($request->has('type')) {

            if ($request->type == 'plus') {

                $cart->quantity += 1;
            } elseif ($request->type == 'minus') {

                if ($cart->quantity > 1) {

                    $cart->quantity -= 1;
                }
            }
        } else {

            $cart->quantity =
                max(
                    1,
                    (int) $request->quantity
                );
        }

        $cart->save();

        /*
    |--------------------------------------------------------------------------
    | ALL CART ITEMS
    |--------------------------------------------------------------------------
    */

        $cartItems = Cart::with('product')
            ->where(
                'u_code',
                auth()->id()
            )
            ->get();

        /*
    |--------------------------------------------------------------------------
    | GST CALCULATION
    |--------------------------------------------------------------------------
    */

        $subtotalWithoutGST = 0;

        $totalGST = 0;

        $totalBV = 0;

        $totalPV = 0;

        foreach ($cartItems as $item) {

            $gstPercent =
                $item->product->gst_percent ?? 18;

            $itemTotal =
                $item->dp * $item->quantity;

            $gstAmount =
                ($itemTotal * $gstPercent)
                / (100 + $gstPercent);

            $baseAmount =
                $itemTotal - $gstAmount;

            $subtotalWithoutGST +=
                $baseAmount;

            $totalGST +=
                $gstAmount;

            $totalBV +=
                ($item->product->product_bv ?? 0)
                * $item->quantity;

            $totalPV +=
                ($item->product->pv ?? 0)
                * $item->quantity;
        }

        /*
    |--------------------------------------------------------------------------
    | CURRENT ITEM TOTAL
    |--------------------------------------------------------------------------
    */

        $currentItemTotal =
            $cart->dp * $cart->quantity;

        /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

        return response()->json([

            'status' => true,

            'quantity' =>
            $cart->quantity,

            'item_total' =>
            $currentItemTotal,

            'cart_total' =>
            $cartItems->sum(

                fn($i) =>

                $i->dp *
                    $i->quantity
            ),

            'cart_items' =>
            $cartItems->sum('quantity'),

            'row_bv' => ($cart->product->product_bv ?? 0)
                * $cart->quantity,

            'row_pv' => ($cart->product->pv ?? 0)
                * $cart->quantity,

            'total_bv' =>
            $totalBV,

            'total_pv' =>
            $totalPV,

            'taxable' =>
            $subtotalWithoutGST,

            'total_gst' =>
            $totalGST,

            'cgst' =>
            $totalGST / 2,

            'sgst' =>
            $totalGST / 2,
        ]);
    }


 

    public function wishlistIndex()
    {
        $user = auth()->user();
        // Fetch wishlist items linked to user ID
        $wishlists = Wishlist::with('product')->where('u_code', $user->id)->get();

        return view('main.product.wishlist', compact('wishlists'));
    }
    


    public function wishlistStore(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['status' => 'login', 'message' => 'Please login first.']);
        }

        $user = auth()->user();
        $productId = $request->product_id;

        // ચેક કરો કે પ્રોડક્ટ પહેલેથી વિશલિસ્ટમાં છે?
        $wishlist = \App\Models\Wishlist::where('u_code', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete(); // જો હોય તો રીમુવ કરો (Toggle logic)
            return response()->json(['status' => 'removed', 'message' => 'Removed from wishlist.']);
        } else {
            \App\Models\Wishlist::create([
                'u_code' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json(['status' => 'added', 'message' => 'Added to wishlist!']);
        }
    }


    public function wishlistRemove($id)
    {
        Wishlist::where('id', $id)->where('u_code', auth()->user()->id)->delete();
        return back()->with('success', 'Product removed from wishlist.');
    }

    public function checkout()
    {
        $user = auth()->user();

        $walletBalance = UserWallet::where('u_code', auth()->id())->value('c4') ?? 0;

        // કાર્ટ ડેટા પ્રોડક્ટ સાથે ફેચ કરો
        $carts = Cart::with('product')->where('u_code', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('dashboard.user')->with('error', 'Your cart is empty.');
        }

        // યુઝરના સેવ કરેલા ડિલિવરી એડ્રેસ મેળવો
        $delivery_contacts = DeliveryContact::where('u_code', $user->id)->get();

        // ટોટલ કેલ્ક્યુલેશન
        $subTotal = $carts->sum(fn($i) => $i->dp * $i->quantity);
        $totalBV = $carts->sum(fn($i) => ($i->product->product_bv ?? 0) * $i->quantity);
        $totalPV = $carts->sum(fn($i) => ($i->product->pv ?? 0) * $i->quantity);

        return view('main.product.checkout', compact('carts', 'subTotal', 'totalBV', 'totalPV', 'delivery_contacts', 'walletBalance'));
    }
    
        
    public function cartIndex()
    {
        $user = auth()->user();
        // Fetch wishlist items linked to user ID
        $wishlists = Cart::with('product')->where('u_code', $user->id)->get();

        return view('main.product.cart', compact('wishlists'));
    }

    public function cartRemove(Request $request, $id)
    {
        $user = auth()->user();

        $cartItem = Cart::where('id', $id)
            ->where('u_code', $user->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $cartItem->delete();

        $cartItems = Cart::where('u_code', $user->id)->get();

        return response()->json([
            'status'     => true,
            'message'    => 'Item removed from cart',
            'cart_items' => $cartItems->sum('quantity'),
            'cart_total' => number_format(
                $cartItems->sum(fn($i) => $i->dp * $i->quantity),
                2
            )
        ]);
    }



public function delivery_contacts(Request $request)
{
    $request->validate([
        'title'      => 'required|string|max:100',
        'contact_no' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
        'address'    => 'required|string',
        'city'       => 'required|string|max:100',
        'state'      => 'required|string|max:100',
        'pincode'    => ['required', 'digits:6'],
        'email'      => 'nullable|email|max:150',
    ], [
        'contact_no.regex' => 'Contact number must be a valid 10-digit Indian mobile starting with 6, 7, 8 or 9.',
        'pincode.digits'   => 'Pincode must be exactly 6 digits.',
    ]);

    DeliveryContact::create([

        'u_code'     => auth()->id(),

        'title'      => $request->title,

        'address'    => $request->address,

        'city'       => $request->city,

        'state'      => $request->state,

        'country'    => 'India',

        'pincode'    => $request->pincode,

        'email'      => $request->email,

        'contact_no' => $request->contact_no,

        'is_default' => 0,

    ]);

    return back()->with(
        'success',
        'Delivery address saved successfully!'
    );
}



    public function delete_delivery_address(Request $request, $id)
    {
        $address = DeliveryContact::findOrFail($id);

        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Address deleted successfully',
        ]);
    }


public function placeOrder(Request $request)
{
    $request->validate([
        'delivery_contact_id' => 'required|exists:delivery_contacts,id',
        'payment_method'      => 'required|in:bank',
        'order_notes'         => 'nullable|string|max:1000',
    ], [
        'delivery_contact_id.required' => 'Please select a delivery address.',
    ]);

    $user = auth()->user();

    // Check user eligibility for BOGO
    $userBV = Order::where('u_code', $user->id)
        ->whereIn('tx_type', ['topup', 'retopup'])
        ->sum('order_bv');
    $isEligible = $userBV >= 100;

    $carts = Cart::with('product')->where('u_code', $user->id)->get();

    if ($carts->isEmpty()) {
        return redirect()->route('dashboard.user')->with('error', 'Your cart is empty!');
    }

    DB::beginTransaction();

    try {
        $delivery = DeliveryContact::where('u_code', $user->id)
            ->findOrFail($request->delivery_contact_id);

        // Calculate totals with BOGO logic
        $totalMRP = 0;
        $totalBV = 0;
        $totalPV = 0;
        $totalFreeItems = 0;
        $totalDiscount = 0; // <-- FIXED: Initialize this variable
        $orderItemsData = [];
        $bogoDetails = [];

        foreach ($carts as $cart) {
            $product = $cart->product;
            $quantity = $cart->quantity;
            $price = $cart->dp;
            $productBV = $product->product_bv ?? 0;
            $productPV = $product->pv ?? 0;

            // Apply BOGO logic if user is eligible
            if ($isEligible) {
                // BOGO: For every 1 item purchased, 1 free item added
                // So total quantity = purchased quantity * 2
                $freeItems = $quantity; // 1 free for each purchased
                $totalQuantity = $quantity + $freeItems; // Double the quantity
                
                // Price remains same (only for purchased items)
                $itemTotal = $quantity * $price; // Only pay for purchased items
                $discountAmount = $freeItems * $price; // Free items value
                
                $totalDiscount += $discountAmount;
                $totalFreeItems += $freeItems;
                
                // Store BOGO details for notes
                $bogoDetails[] = "{$product->name}: {$quantity} purchased + {$freeItems} free = {$totalQuantity} total";
            } else {
                // No BOGO - pay for all items
                $totalQuantity = $quantity;
                $itemTotal = $quantity * $price;
                $freeItems = 0;
                $discountAmount = 0;
            }

            $totalMRP += $itemTotal; // This is the amount customer pays
            $totalBV += $productBV * $totalQuantity; // BV on FULL quantity (including free)
            $totalPV += $productPV * $totalQuantity; // PV on FULL quantity (including free)

            // Store item data for order creation
            $orderItemsData[] = [
                'cart' => $cart,
                'purchased_qty' => $quantity,
                'free_qty' => $freeItems,
                'total_qty' => $totalQuantity,
                'item_total' => $itemTotal,
                'discount' => $discountAmount,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | ORDER SAVE
        |--------------------------------------------------------------------------
        */

        $order = new Order();
        $order->u_code = $user->id;
        $order->tx_type = 'product';
        $order->tx_id = 'ORD-' . strtoupper(Str::random(10));
        $order->tx_user_id = $user->username;
        $order->order_mrp = $totalMRP; // Amount customer pays
        $order->order_amount = $totalMRP; // Amount customer pays
        $order->shipping_charge = 0;
        $order->shipping_status = 'pending';
        $order->package_name = 'Product Order';
        $order->delivery_schedule = $request->delivery_schedule ?? 'All Time';
        $order->order_bv = $totalBV; // BV on FULL quantity (including free)
        $order->pv = $totalPV; // PV on FULL quantity (including free)
        $order->roi = 0;
        $order->times = 0;
        $order->roi_bv = 0;
        $order->expire_date = now()->addYears(1);
        // Keep city / state / pincode on the order so the invoice can show them.
        $order->order_address = trim(implode(', ', array_filter([
            $delivery->address,
            $delivery->city,
            $delivery->state,
            $delivery->pincode ? ('PIN: ' . $delivery->pincode) : null,
            $delivery->country ?? null,
        ])));
        $order->order_details = "Contact: " . $delivery->contact_no . " | Email: " . ($delivery->email ?? 'N/A');
        
        // Store BOGO information in order_notes
        $orderNotes = "Note: " . $request->order_notes;
        if ($isEligible && !empty($bogoDetails)) {
            $orderNotes .= "\n\n=== BOGO OFFER APPLIED (Buy 1 Get 1 Free) ===\n";
            $orderNotes .= "You purchased " . count($carts) . " product(s) and got " . $totalFreeItems . " item(s) FREE!\n";
            $orderNotes .= "You only paid for: ₹" . number_format($totalMRP, 2) . "\n";
            $orderNotes .= "Free items value: ₹" . number_format($totalDiscount, 2) . "\n";
            $orderNotes .= "Total items received: " . ($carts->sum('quantity') + $totalFreeItems) . "\n\n";
            $orderNotes .= "Product Details:\n";
            $orderNotes .= implode("\n", $bogoDetails);
        }
        $order->order_notes = $orderNotes;
        
        $order->status = 0; // Pending
        $order->payment_status = 0; // Pending verification for bank transfer
        $order->payment_method = 'bank';
        $order->payment_details = 'Bank Transfer - Awaiting verification';
        $order->payment_response = null;
        $order->payout_status = 0;
        $order->closing_status = 0;
        $order->added_on = now();
        $order->updated_on = now();
        
        $order->save();

        /*
        |--------------------------------------------------------------------------
        | ORDER ITEMS
        |--------------------------------------------------------------------------
        */

        foreach ($orderItemsData as $itemData) {
            $cart = $itemData['cart'];
            
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->u_code = $user->id;
            $orderItem->product_id = $cart->product_id;
            
            // Store the total quantity (including free items)
            $orderItem->qty = $itemData['total_qty']; // Total quantity including free
            $orderItem->price = $cart->dp; // Price per unit (original price)
            $orderItem->bv = $cart->product->product_bv ?? 0; // BV per unit
            $orderItem->pv = $cart->product->pv ?? 0; // PV per unit
            $orderItem->item_status = 0;
            $orderItem->added_on = now();
            $orderItem->updated_on = now();
            $orderItem->save();

            /*
            |--------------------------------------------------------------------------
            | PRODUCT STOCK DECREASE
            |--------------------------------------------------------------------------
            */
            
            // Decrease stock by TOTAL quantity (including free items)
            if ($cart->product && $cart->product->qty >= $itemData['total_qty']) {
                $cart->product->decrement('qty', $itemData['total_qty']);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR CART
        |--------------------------------------------------------------------------
        */
        

        Cart::where('u_code', $user->id)->delete();

        DB::commit();

        return redirect()->route('order.invoice', ['id' => $order->tx_id])
            ->with('success', 'Order placed successfully! BOGO offer applied - you got ' . $totalFreeItems . ' item(s) FREE!');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->withInput()->with('error', 'Database Error: ' . $e->getMessage());
    }
}


}
