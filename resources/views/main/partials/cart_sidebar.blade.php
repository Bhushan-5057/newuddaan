@auth('web')
@php
    $sidebarCarts = \App\Models\Cart::with('product')
        ->where('u_code', auth()->user()->id)
        ->get();
    $totalItems = $sidebarCarts->sum('quantity');
    $totalPrice = $sidebarCarts->sum(fn($i) => $i->dp * $i->quantity);
@endphp

<aside class="cart-sidebar">
    <div class="cart-header">
        <div class="cart-total">
            <i class="fas fa-shopping-basket"></i>
<span>

    total item (

    <span id="sidebar-total-items">

        {{ $totalItems }}

    </span>

    )

</span>
        </div>
        <button class="cart-close">
            <i class="icofont-close"></i>
        </button>
    </div>

    <ul class="cart-list">
        @forelse($sidebarCarts as $item)
            <li class="cart-item" data-id="{{ $item->id }}">
                <div class="cart-media">
                    <a href="{{ route('product.details', $item->product->slug) }}">
                        <img src="{{ asset('storage/' . $item->product->imgs) }}">
                    </a>
                    <a href="javascript:void(0)"
                       class="cart-delete"
                       onclick="removeSidebarItem({{ $item->id }})">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </div>

                <div class="cart-info-group">
                    <div class="cart-info">
                        <h6>{{ $item->product->name }}</h6>
                        <p>Unit Price : ₹{{ $item->dp }}</p>
                    </div>

                    <div class="cart-action-group">
                    
                        
                          <div class="qty-box justify-content-center"
                                                        data-cart-id="{{ $item->id }}">
                                            
                                                        <button type="button"
                                                            class="qty-btn"
                                                            onclick="updateCheckoutQty({{ $item->id }}, 'minus')">
                                            
                                                            −
                                            
                                                        </button>
                                            
                                                        <input type="text"
                                                            class="qty-input text-center"
                                                      
                                                            id="sidebar-qty-{{ $item->id }}"
                                                            value="{{ $item->quantity }}"
                                                            readonly>
                                            
                                                        <button type="button"
                                                            class="qty-btn"
                                                            onclick="updateCheckoutQty({{ $item->id }}, 'plus')">
                                            
                                                            +
                                            
                                                        </button>
                                            
                                                    </div>

                        <h6
    id="sidebar-item-total-{{ $item->id }}">

    ₹{{ number_format(
        $item->dp * $item->quantity,
        2
    ) }}

</h6>
                    </div>
                </div>
            </li>
        @empty
            <li class="text-center mt-5">Your cart is empty</li>
        @endforelse
    </ul>

    <div class="cart-footer">
        <a class="cart-checkout-btn" href="{{ route('cart.index') }}">
            <span class="checkout-label">Proceed to Checkout</span>
           <span
    class="checkout-price" id="sidebar-total-price">₹{{ number_format($totalPrice, 2) }}</span>
        </a>
    </div>
</aside>
@endauth