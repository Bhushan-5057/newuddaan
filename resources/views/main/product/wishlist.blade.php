@extends('user.layouts.app')
@section('content')

<!-- Breadcrumb start -->
<div class="row m-1">
    <div class="col-12 ">
        <h4 class="main-title">Wishlist</h4>
        <ul class="app-line-breadcrumbs mb-3">
            <li class="">
                <a class="f-s-14 f-w-500" href="{{ route('dashboard.user') }}">
                    <span>
                        <i class="ph-duotone ph-stack f-s-16"></i> Dashboard
                    </span>
                </a>
            </li>
            <li>
                <a class="f-s-14 f-w-500" href="#">E-shop</a>
            </li>
            <li class="active">
                <a class="f-s-14 f-w-500" href="#">Wishlist</a>
            </li>
        </ul>
    </div>
</div>
<!-- Breadcrumb end -->

<!-- Wishlist start -->
<div class="row wishlist-container">
    @forelse($wishlists as $key => $item)
    <div class="col-sm-6 col-lg-4 col-xl-3 mb-4">
        <div class="card overflow-hidden h-100">
            <div class="card-body p-0 d-flex flex-column justify-content-between">
                <div class="product-content-box position-relative">
                    <div class="product-grid">
                        <div class="product-image">
                            <a class="image" href="{{ route('product.details', $item->product->slug) }}">
                                <img alt="{{ $item->product->name }}" class="pic-1 img-fluid" src="{{ asset('storage/' . $item->product->imgs) }}">
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <a class="m-0 f-s-20 f-w-500 text-truncate" href="{{ route('product.details', $item->product->slug) }}" title="{{ $item->product->name }}">
                                {{ $item->product->name }}
                            </a>
                        </div>
                        
                        <!-- Product Status (Stock In/Out) -->
                        <p class="mb-2">
                            <span class="badge {{ $item->product->qty > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $item->product->qty > 0 ? 'Stock In' : 'Stock Out' }}
                            </span>
                        </p>
                        
                        <div class="pricing-box">
                            <h6>{{ company_info('currancy', '₹') }}{{ $item->product->dp }}</h6>
                        </div>
                    </div>

                    <!-- Remove from Wishlist Icon (Trash/Heart) -->
                    <a href="{{ route('wishlist.remove', $item->id) }}" class="bg-danger h-45 w-45 d-flex align-items-center justify-content-center b-r-50 wishlist-like-icon position-absolute" style="top: 15px; right: 15px; border-radius: 50%; width: 40px; height: 40px; text-align: center;" title="Remove">
                        <i class="ti ti-heart-broken text-light fs-5"></i>
                    </a>
                </div>

                <!-- Add to Cart / Out of stock action button -->
                <div class="p-3 pt-0 grid-btn-box">
                    @if($item->product->qty > 0)
                        <button type="button" class="btn btn-primary w-100 add-cart-btn" onclick="addToCartAjax(this)">
                            <i class="fas fa-shopping-basket me-1"></i> ADD TO CART
                        </button>
                    @else
                        <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center my-5">
        <div class="card p-5">
            <h4 class="text-secondary">Your wishlist is empty!</h4>
            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    </div>
    @endforelse
</div>
<!-- Wishlist end -->

@endsection