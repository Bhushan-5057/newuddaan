@extends('user.layouts.app')

@section('content')
 
            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12">
                    <h4 class="main-title">Checkout</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li>
                            <a class="f-s-14 f-w-500" href="{{ route('home') }}">
                                <span><i class="ph-duotone ph-stack f-s-16"></i> Home</span>
                            </a>
                        </li>
                        <li>
                            <a class="f-s-14 f-w-500" href="#">E-shop</a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Checkout</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb end -->

            <form action="{{ route('order.place') }}" method="POST" id="checkout-form">
                @csrf

                @php
                    // Calculate user BV and eligibility
                    $userBV = 0;
                    $isEligible = false;
                    if (auth()->check()) {
                        $userBV = \App\Models\Order::where('u_code', auth()->id())
                            ->whereIn('tx_type', ['topup', 'retopup'])
                            ->sum('order_bv');
                        $isEligible = $userBV >= 100;
                    }
                    
                    // Calculate totals for summary
                    $subtotalWithoutGST = 0;
                    $totalGST = 0;
                    $bogoFreeItems = 0;
                    $bogoDiscount = 0;
                    $totalItems = 0;
                    $totalBV = 0;
                    $subTotal = 0;
                    $hasBogoCategories = false;
                    
                    foreach ($carts as $item) {
                        $gstPercent = $item->product->gst_percent ?? 18;
                        $itemTotal = $item->dp * $item->quantity;
                        $gstAmount = ($itemTotal * $gstPercent) / (100 + $gstPercent);
                        $baseAmount = $itemTotal - $gstAmount;
                        
                        // Check if product's category has BOGO enabled
                        $categoryBogoActive = $item->product->category && $item->product->category->bogo_status == 1;
                        
                        $subTotal += $itemTotal;
                        $totalBV += ($item->product->product_bv ?? 0) * $item->quantity;
                        
                        // Apply BOGO only if user is eligible AND category has BOGO enabled
                        if ($isEligible && $categoryBogoActive) {
                            $freeItems = $item->quantity;
                            $bogoFreeItems += $freeItems;
                            $bogoDiscount += $freeItems * $item->dp;
                            $gstAmount = ($itemTotal * $gstPercent) / (100 + $gstPercent);
                            $baseAmount = $itemTotal - $gstAmount;
                            $totalBV += ($item->product->product_bv ?? 0) * $freeItems;
                            $hasBogoCategories = true;
                        }
                        
                        $subtotalWithoutGST += $baseAmount;
                        $totalGST += $gstAmount;
                        $totalItems += $item->quantity;
                    }
                    
                    $bogoSavings = $bogoDiscount;
                    $totalItemsWithBOGO = $totalItems + $bogoFreeItems;
                    $totalBVWithBOGO = $isEligible ? $totalBV : $totalBV;
                @endphp

                <!-- BV Status Banner -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert {{ $isEligible ? 'alert-success' : 'alert-warning' }} mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <i class="fas {{ $isEligible ? 'fa-check-circle' : 'fa-lock' }}"></i>
                                    <strong>Your BV Status:</strong>
                                    {{ number_format($userBV, 2) }} / 100
                                    @if($isEligible)
                                        <span class="badge bg-success ms-2">Premium Access</span>
                                    @else
                                        <span class="badge bg-warning text-dark ms-2">Need {{ number_format(100 - $userBV, 2) }} more BV</span>
                                    @endif
                                </div>
                                @if(!$isEligible)
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Earn BV through topups to unlock BOGO offers
                                    </small>
                                @endif
                            </div>
                            @if(!$isEligible)
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ min(100, ($userBV / 100) * 100) }}%"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Your Order (MLM ID: {{ auth()->user()->username }})</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="40%">Product Details</th>
                                                <th width="15%">Price</th>
                                                <th width="15%">Qty</th>
                                                <th width="25%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($carts as $key => $item)
                                                @php
                                                    $gstPercent = $item->product->gst_percent ?? 18;
                                                    $itemTotal = $item->dp * $item->quantity;
                                                    $gstAmount = ($itemTotal * $gstPercent) / (100 + $gstPercent);
                                                    $baseAmount = $itemTotal - $gstAmount;
                                                    
                                                    // Check both conditions for BOGO
                                                    $categoryBogoActive = $item->product->category && $item->product->category->bogo_status == 1;
                                                    $canBuy = $isEligible;
                                                    $bogoActive = $isEligible && $categoryBogoActive;
                                                    $bogoTotalQuantity = $bogoActive ? $item->quantity * 2 : $item->quantity;
                                                @endphp
                                                <tr class="{{ !$canBuy ? 'table-warning' : '' }} mb-3">
                                                    <td><strong>{{ $key + 1 }}</strong></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <img src="{{ asset('storage/' . $item->product->imgs) }}" 
                                                                 alt="product" 
                                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                            <div>
                                                                <h6 class="mb-1">
                                                                    {{ $item->product->name }}
                                                                    @if(!$canBuy)
                                                                        <span class="badge bg-warning text-dark ms-1">
                                                                            <i class="fas fa-lock"></i> Premium
                                                                        </span>
                                                                    @endif
                                                                    @if($bogoActive)
                                                                        <span class="badge bg-success ms-1">
                                                                            <i class="fas fa-gift"></i> BOGO
                                                                        </span>
                                                                    @endif
                                                                    @if(!$categoryBogoActive && $isEligible)
                                                                        <span class="badge bg-secondary ms-1">
                                                                            <i class="fas fa-times"></i> No BOGO
                                                                        </span>
                                                                    @endif
                                                                </h6>
                                                                <small class="text-muted d-block">
                                                                    Category: {{ $item->product->category->name ?? 'N/A' }}
                                                                </small>
                                                                <small class="text-muted d-block">
                                                                    Code: {{ $item->product->p_code ?? 'N/A' }} | 
                                                                    HSN: {{ $item->product->hsn_code ?? 'N/A' }}
                                                                </small>
                                                                <small class="text-muted d-block">
                                                                    GST: {{ $gstPercent }}% | 
                                                                    GST Value: ₹{{ number_format($gstAmount, 2) }}
                                                                </small>
                                                                <small class="text-muted d-block">
                                                                    Taxable: ₹{{ number_format($baseAmount, 2) }} | 
                                                                    BV: {{ number_format(($item->product->product_bv ?? 0) * $item->quantity, 2) }}
                                                                </small>
                                                                @if($bogoActive)
                                                                    <div class="mt-1">
                                                                        <small class="text-success">
                                                                            <i class="fas fa-gift"></i> 
                                                                            Buy 1 Get 1 FREE! You'll receive {{ $bogoTotalQuantity }} items
                                                                        </small>
                                                                    </div>
                                                                @elseif($isEligible && !$categoryBogoActive)
                                                                    <div class="mt-1">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-info-circle"></i> 
                                                                            BOGO offer not available for this category
                                                                        </small>
                                                                    </div>
                                                                @endif
                                                                @if(!$canBuy)
                                                                    <small class="text-warning">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                        Need {{ number_format(100 - $userBV, 2) }} more BV
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <strong>₹{{ number_format($item->dp, 2) }}</strong>
                                                        @if($bogoActive)
                                                            <br><small class="text-success">(BOGO Active)</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                                    onclick="{{ $canBuy ? "updateCheckoutQty({$item->id}, 'minus')" : 'return false;' }}"
                                                                    {{ !$canBuy ? 'disabled' : '' }}>−</button>
                                                            <input type="text" class="form-control form-control-sm text-center" 
                                                                   id="checkout-page-qty-{{ $item->id }}"
                                                                   value="{{ $item->quantity }}" readonly
                                                                   style="width: 50px;">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                                    onclick="{{ $canBuy ? "updateCheckoutQty({$item->id}, 'plus')" : 'return false;' }}"
                                                                    {{ !$canBuy ? 'disabled' : '' }}>+</button>
                                                        </div>
                                                        @if($bogoActive)
                                                            <div class="mt-1">
                                                                <small class="text-success">
                                                                    <i class="fas fa-gift"></i> 
                                                                    {{ $item->quantity }} Purchased + {{ $item->quantity }} FREE
                                                                </small>
                                                            </div>
                                                        @endif
                                                        <div class="mt-2">
                                                            @if($canBuy)
                                                                <button type="button" class="btn btn-sm btn-danger"
                                                                        onclick="removeCheckoutItem({{ $item->id }})">
                                                                    <i class="fas fa-trash"></i> Remove
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                                    <i class="fas fa-lock"></i> Locked
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="text-success fw-bold"
                                                            id="checkout-total-{{ $item->id }}"
                                                            data-price="{{ $item->dp }}">
                                                            ₹{{ number_format($itemTotal, 2) }}
                                                        </h6>
                                                        @if($bogoActive)
                                                            <small class="text-muted">
                                                                ({{ $bogoTotalQuantity }} items for price of {{ $item->quantity }})
                                                            </small>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Schedule - Hidden field -->
                <input type="hidden" name="delivery_schedule" value="All Time">

                <!-- Delivery Address -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Delivery Address & Contact</h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#address-add">
                                    <i class="fas fa-plus"></i> Add New
                                </button>
                            </div>
                            <div class="card-body">
                                @php
                                    $selectedAddressId = old(
                                        'delivery_contact_id',
                                        $delivery_contacts->where('is_default', 1)->first()->id ??
                                            ($delivery_contacts->first()->id ?? ''),
                                    );
                                @endphp

                                @if($delivery_contacts->count() > 0)
                                    <div class="row">
                                        @foreach($delivery_contacts as $contact)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="border rounded p-3 mb-3 {{ $selectedAddressId == $contact->id ? 'border-success bg-success bg-opacity-10' : 'border-secondary' }}"
                                                     onclick="selectAddress({{ $contact->id }})"
                                                     id="addr-{{ $contact->id }}"
                                                     style="cursor: pointer;">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-{{ $contact->title == 'home' ? 'home' : 'building' }}"></i>
                                                            {{ ucfirst($contact->title) }}
                                                            @if($contact->is_default)
                                                                <span class="badge bg-primary ms-1">Default</span>
                                                            @endif
                                                        </h6>
                                                        <a href="javascript:void(0)"
                                                           onclick="event.stopPropagation(); deleteAddress({{ $contact->id }})"
                                                           class="text-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                    <p class="mb-1 text-muted">{{ $contact->address }}</p>
                                                    <p class="mb-1 text-muted">{{ $contact->city }}, {{ $contact->state }} - {{ $contact->pincode }}</p>
                                                    <p class="mb-1"><strong>Phone:</strong> {{ $contact->contact_no }}</p>
                                                    @if($contact->email)
                                                        <p class="mb-0"><strong>Email:</strong> {{ $contact->email }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                        <h5>No Delivery Address Found</h5>
                                        <p class="text-muted">Please add a delivery address to proceed with your order.</p>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#address-add">
                                            <i class="fas fa-plus"></i> Add Delivery Address
                                        </button>
                                    </div>
                                @endif

                                <input type="hidden" name="delivery_contact_id" id="delivery_contact_id"
                                       value="{{ $selectedAddressId }}">
                                <input type="hidden" name="shipping_charge" id="shipping_charge_input" value="0">

                                @error('delivery_contact_id')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Payment Method</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="border rounded p-3 active">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="payment_method" 
                                                       id="payment_bank" 
                                                       value="bank"
                                                       checked>
                                                <label class="form-check-label" for="payment_bank">
                                                    <strong>Pay via Bank Transfer</strong>
                                                    <br>
                                                    <small class="text-muted">Pay using UPI, Net Banking or NEFT/RTGS</small>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Bank Details -->
                                        <div class="bg-light p-3 rounded mt-3">
                                            <h6 class="mb-3">Bank Details</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-sm mb-0">
                                                        <tr>
                                                            <td><strong>Account Holder:</strong></td>
                                                            <td>{{ company_info('bill_company_bank_holder_name', '') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Bank Name:</strong></td>
                                                            <td>{{ company_info('bill_company_bank_name', '') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Account Number:</strong></td>
                                                            <td>{{ company_info('bill_company_bank_account', '') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>IFSC Code:</strong></td>
                                                            <td>{{ company_info('bill_company_bank_ifsc', '') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Branch:</strong></td>
                                                            <td>{{ company_info('bill_company_bank_branch', '') }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                @if(company_info('bill_company_bank_qr_image', ''))
                                                    <div class="col-md-6 text-center">
                                                        <p><strong>Scan to Pay:</strong></p>
                                                        <img src="{{ asset('uploads/' . company_info('bill_company_bank_qr_image')) }}" 
                                                             alt="QR Code" 
                                                             style="max-width: 180px; border-radius: 10px; border: 2px solid #ddd;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Finalize Order</h5>
                                <h5 class="text-success mb-0">
                                    <span id="finalize-total-text">₹{{ number_format($subTotal, 2) }}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div>
                                            <div class="d-flex justify-content-between py-2 border-bottom">
                                                <span>Taxable Amount</span>
                                                <span id="checkout-taxable">₹{{ number_format($subtotalWithoutGST, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between py-2 border-bottom">
                                                <span>Total GST</span>
                                                <span id="checkout-gst">₹{{ number_format($totalGST, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between py-2 border-bottom">
                                                <span>CGST</span>
                                                <span id="checkout-cgst">₹{{ number_format($totalGST / 2, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between py-2 border-bottom">
                                                <span>SGST</span>
                                                <span id="checkout-sgst">₹{{ number_format($totalGST / 2, 2) }}</span>
                                            </div>
                                            @if($hasBogoCategories && $bogoSavings > 0)
                                                <div class="d-flex justify-content-between py-2 border-bottom text-success">
                                                    <span>BOGO Free Items Value</span>
                                                    <span id="checkout-bogo">+ ₹{{ number_format($bogoSavings, 2) }} ({{ $bogoFreeItems }} FREE)</span>
                                                </div>
                                            @endif
                                            <div class="d-flex justify-content-between py-2 border-bottom">
                                                <span>Items</span>
                                                <span id="checkout-items">{{ $hasBogoCategories ? $totalItemsWithBOGO : $totalItems }} items</span>
                                            </div>
                                            <div class="d-flex justify-content-between py-2 border-bottom">
                                                <span>Sub Total</span>
                                                <span id="checkout-subtotal">₹{{ number_format($subTotal, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between py-2 border-bottom">
                                                <span>Total BV</span>
                                                <span id="checkout-bvpv">{{ number_format($totalBVWithBOGO, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between py-2 border-bottom">
                                                <span>Shipping Charge</span>
                                                <span id="shipping-charge">₹0.00</span>
                                            </div>
                                            <div class="d-flex justify-content-between py-3 mt-2 border-top">
                                                <strong style="font-size:18px;">Total Amount</strong>
                                                <strong id="checkout-grand" class="text-success" style="font-size:18px;">
                                                    ₹{{ number_format($subTotal, 2) }}
                                                </strong>
                                            </div>
                                            <div id="shipping-response-box"></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Order Notes (Optional)</label>
                                            <textarea class="form-control" name="order_notes" rows="4" 
                                                      placeholder="Any instructions for delivery?">{{ old('order_notes') }}</textarea>
                                            @error('order_notes')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <!-- BOGO Status Summary -->
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="mb-2">BOGO Offer Status</h6>
                                                @if($isEligible)
                                                    @php
                                                        $bogoCategories = $carts->filter(function($item) {
                                                            return $item->product->category && $item->product->category->bogo_status == 1;
                                                        });
                                                    @endphp
                                                    @if($bogoCategories->count() > 0)
                                                        <div class="text-success">
                                                            <i class="fas fa-check-circle"></i> 
                                                            BOGO offers available for {{ $bogoCategories->count() }} product(s)
                                                        </div>
                                                        <small class="text-muted">
                                                            Products from categories with BOGO enabled will get free items
                                                        </small>
                                                    @else
                                                        <div class="text-warning">
                                                            <i class="fas fa-exclamation-triangle"></i> 
                                                            No BOGO offers available for items in your cart
                                                        </div>
                                                        <small class="text-muted">
                                                            This offer applies only to specific product categories
                                                        </small>
                                                    @endif
                                                @else
                                                    <div class="text-warning">
                                                        <i class="fas fa-lock"></i> 
                                                        Need 100 BV to unlock BOGO offers
                                                    </div>
                                                    <small class="text-muted">
                                                        Current BV: {{ number_format($userBV, 2) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkout-check" required>
                                        <label class="form-check-label" for="checkout-check">
                                            By making this purchase you agree to our <a href="#">Terms and Conditions</a>.
                                        </label>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg" id="place-order-btn">
                                        <i class="fas fa-shopping-bag me-2"></i> Place Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

    <!-- Address Add Modal -->
    <div class="modal fade" id="address-add" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Delivery Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('address.store') }}" method="POST" id="address-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Address Title</label>
                                    <select class="form-select" name="title" required>
                                        <option value="home">Home</option>
                                        <option value="business">Business</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input class="form-control" type="tel" name="contact_no"
                                           pattern="[0-9]{10}" maxlength="10" minlength="10"
                                           placeholder="Enter mobile number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" type="email" name="email"
                                           placeholder="Enter email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pincode</label>
                                    <input class="form-control" type="text" name="pincode"
                                           maxlength="6" minlength="6" placeholder="Enter pincode" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input class="form-control" type="text" name="city"
                                           placeholder="Enter city" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">State</label>
                                    <input class="form-control" type="text" name="state"
                                           placeholder="Enter state" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Full Address</label>
                                    <textarea class="form-control" name="address" rows="3"
                                              placeholder="Enter full address" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Delivery Info</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Address form submission via AJAX
            $('#address-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('#address-form button[type="submit"]').html('Saving...').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#address-add').modal('hide');
                        location.reload();
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Address added successfully');
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorMsg = '';
                            $.each(errors, function(key, value) {
                                errorMsg += value[0] + '\n';
                            });
                            alert(errorMsg);
                        } else {
                            alert('Something went wrong');
                        }
                    },
                    complete: function() {
                        $('#address-form button[type="submit"]').html('Save Delivery Info').prop('disabled', false);
                    }
                });
            });

            // Payment method selection
            $('.payment-method').on('click', function() {
                $('.payment-method').removeClass('border-success bg-success bg-opacity-10');
                $(this).addClass('border-success bg-success bg-opacity-10');
                $(this).find('input[type="radio"]').prop('checked', true);
            });

            // Validate checkout before submission
            $('#place-order-btn').on('click', function(e) {
                let hasLockedProducts = $('.table-warning').length > 0;
                let deliverySelected = $('#delivery_contact_id').val() !== '';
                let termsChecked = $('#checkout-check').is(':checked');

                if (hasLockedProducts) {
                    e.preventDefault();
                    alert('You have locked products in your cart. Please remove them to proceed.');
                    return false;
                }

                if (!deliverySelected) {
                    e.preventDefault();
                    alert('Please select a delivery address.');
                    return false;
                }

                if (!termsChecked) {
                    e.preventDefault();
                    alert('Please agree to the Terms and Conditions.');
                    return false;
                }
            });
        });

        function selectAddress(id) {
            $('.border-success').removeClass('border-success bg-success bg-opacity-10');
            $('#addr-' + id).addClass('border-success bg-success bg-opacity-10');
            $('#delivery_contact_id').val(id);
            $('#shipping-response-box').html('');
        }

        function deleteAddress(id) {
            if (!confirm('Are you sure you want to delete this address?')) {
                return;
            }
            $.ajax({
                url: '/address/delete/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    $('#addr-' + id).closest('.col-md-6').remove();
                    let firstAddress = $('.border-success').first();
                    if (firstAddress.length) {
                        let newId = firstAddress.attr('id').replace('addr-', '');
                        selectAddress(newId);
                    } else {
                        $('#delivery_contact_id').val('');
                        $('#shipping-response-box').html(
                            '<div class="alert alert-danger">Please add a delivery address to proceed.</div>'
                        );
                    }
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Address deleted successfully');
                    }
                },
                error: function(xhr) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Something went wrong');
                    }
                }
            });
        }

        function updateCheckoutQty(cartId, action) {
            // Your existing qty update logic here
        }

        function removeCheckoutItem(cartId) {
            if (confirm('Are you sure you want to remove this item?')) {
                // Your existing remove logic here
            }
        }
    </script>
@endsection