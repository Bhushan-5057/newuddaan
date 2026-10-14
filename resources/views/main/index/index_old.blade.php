@extends('main.layouts.app')

@section('content')

    <!--=====================================

                                                                                                                                BANNER PART START

                                                                                                                    =======================================-->

    <section class="banner-part">

        <div class="container">

            <div class="row">

                <div class="col-lg-3">

                    <div class="banner-category">

                        <div class="banner-category-head">

                            <i class="fas fa-bars"></i>

                            <span>top categories</span>

                        </div>

                        <ul class="banner-category-list">

                            @foreach ($categories as $category)

                                <li class="banner-category-item">

                                    <a href="{{ route('category.view', $category->slug) }}">

                                        <i class="flaticon-vegetable"></i>

                                        <span>{{ $category->name }}</span>

                                    </a>



                                    @if ($category->subCategories->count() > 0 || $category->products->count() > 0)

                                        <div class="banner-category-dropdown">

                                            <h5>{{ $category->name }} Items</h5>

                                            <div class="banner-sub-category">



                                                @if ($category->subCategories->count() > 0)

                                                    @foreach ($category->subCategories->chunk(5) as $chunk)

                                                        <ul>

                                                            <li class="mb-2"><strong>Sub Categories</strong></li>

                                                            @foreach ($chunk as $sub)

                                                                <li>

                                                                    <a

                                                                        href="{{ route('category.view', [$category->slug, $sub->slug]) }}">

                                                                        {{ $sub->name }}

                                                                    </a>

                                                                </li>

                                                            @endforeach

                                                        </ul>

                                                    @endforeach

                                                @endif



                                                @if ($category->products->count() > 0)

                                                    <ul>

                                                        <li class="mb-2"><strong>Top Products</strong></li>

                                                        @foreach ($category->products as $prod)

                                                            <li>

                                                                <a href="{{ route('product.details', $prod->slug) }}">

                                                                    {{ Str::limit($prod->name, 20) }}

                                                                </a>

                                                            </li>

                                                        @endforeach

                                                        @if ($category->products->count() >= 6)

                                                            <li><a href="{{ route('category.view', $category->slug) }}"

                                                                    class="text-success">View All...</a></li>

                                                        @endif

                                                    </ul>

                                                @endif



                                            </div>

                                        </div>

                                    @endif

                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>



                <div class="col-lg-9">

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="home-grid-slider slider-dots">

                                @php

                                    // Soft & Elegant Pastel Colors (થોડા ડાર્ક શેડમાં)

                                    $bgColors = [

                                        '#e1eee1', // Soft Sage Green (ઓર્ગેનિક લુક માટે)

                                        '#f9eada', // Muted Peach (ફ્રૂટ્સ માટે)

                                        '#e2edf3', // Pale Sky Blue (ડેરી કે ડ્રિંક્સ માટે)

                                        '#f5e2ed', // Dusty Rose (સ્પેશિયલ ઓફર માટે)

                                        '#f7f0cf', // Soft Honey Gold (ગ્રોસરી આઈટમ્સ માટે)

                                    ];

                                @endphp


@forelse($bannerProducts as $key => $product)
    @php
        $discount = $product->mrp > 0 ? round((($product->mrp - $product->dp) / $product->mrp) * 100) : 0;
        $isDiscounted = $discount >= 50;
        $canBuy = $isEligible || !$isDiscounted;
    @endphp
    
    <div class="banner-wrap" style="background-color: {{ $bgColors[$key] ?? '#f3f9f3' }}; border-radius: 15px; min-height: 400px; display: flex; align-items: center;">
        <div class="row align-items-center w-100">
            <div class="col-md-6 col-lg-6">
                <div class="banner-content" style="padding: 50px;">
                    @if($isDiscounted && !$isEligible)
                        <div class="badge bg-warning text-dark mb-3">
                            <i class="fas fa-lock"></i> Premium Product (Need 100+ BV)
                        </div>
                    @endif
                    
                    <h4 style="color: #28a745; font-size: 14px; font-weight: 700; text-transform: uppercase;">
                        Top Quality Herbal
                    </h4>
                    <h2 style="font-size: 36px; line-height: 1.2; margin: 15px 0;">
                        {{ $product->name }}
                    </h2>
                    <p style="color: #555; margin-bottom: 25px;">
                        {{ Str::limit($product->p_dese, 120) }}
                    </p>

                    <div class="mb-4">
                        <span style="font-size: 28px; font-weight: 800; color: #28a745;">
                            {{ company_info('currancy', '₹') }}{{ $product->dp }} (BV : {{$product->product_bv}})
                        </span>
                        <del style="margin-left: 10px; color: #888;">
                            {{ company_info('currancy', '₹') }}{{ $product->mrp }}
                        </del>
                        @if($discount > 0)
                            <span class="badge bg-danger ms-2">-{{ $discount }}%</span>
                        @endif
                    </div>

                    @if($canBuy)
                        <a href="{{ route('product.details', $product->slug) }}" 
                           class="btn btn-inline" 
                           style="background: #28a745; color: white; padding: 12px 30px; border-radius: 5px;">
                            <i class="fas fa-shopping-basket"></i>
                            <span>shop now</span>
                        </a>
                    @else
                        <button class="btn btn-secondary" disabled style="padding: 12px 30px; border-radius: 5px;">
                            <i class="fas fa-lock"></i>
                            <span>Locked - Need {{ 100 - ($userBV ?? 0) }} more BV</span>
                        </button>
                        <div class="mt-2">
                            <small class="text-muted">
                                Your BV: {{ $userBV ?? 0 }} / 100 required
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6 col-lg-6 text-center">
                <div class="banner-image" style="padding: 30px;">
                    <img src="{{ asset('storage/' . $product->imgs) }}" 
                         alt="{{ $product->name }}" 
                         style="max-width: 100%; max-height: 320px; object-fit: contain; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));">
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="banner-wrap" style="background: #f3f9f3; height: 400px; display: flex; align-items: center; justify-content: center;">
        <h3>Check out our new Herbal collections!</h3>
    </div>
@endforelse

                            </div>

                        </div>

                    </div>

                </div>

                <section class="section intro-part">

                    <div class="container">

                        <div class="row intro-content">

                            <!--<div class="col-sm-6 col-lg-3">-->

                            <!--    <div class="intro-wrap">-->

                            <!--        <div class="intro-icon">-->

                            <!--            <i class="fas fa-truck"></i>-->

                            <!--        </div>-->

                            <!--        <div class="intro-content">-->

                            <!--            <h5>Free Home Delivery</h5>-->

                            <!--            <p>Enjoy fast and free doorstep delivery on all orders above a minimum amount.</p>-->

                            <!--        </div>-->

                            <!--    </div>-->

                            <!--</div>-->

                            <!--<div class="col-sm-6 col-lg-3">-->

                            <!--    <div class="intro-wrap">-->

                            <!--        <div class="intro-icon">-->

                            <!--            <i class="fas fa-sync-alt"></i>-->

                            <!--        </div>-->

                            <!--        <div class="intro-content">-->

                            <!--            <h5>Instant Return Policy</h5>-->

                            <!--            <p>Not satisfied? Return your products easily within 7 days for a hassle-free-->

                            <!--                refund.</p>-->

                            <!--        </div>-->

                            <!--    </div>-->

                            <!--</div>-->

                            <div class="col-sm-6 col-lg-6">

                                <div class="intro-wrap">

                                    <div class="intro-icon">

                                        <i class="fas fa-headset"></i>

                                    </div>

                                    <div class="intro-content">

                                        <h5>Quick Support System</h5>

                                        <p>Our dedicated support team is available 24/7 to help you with any queries or

                                            issues.</p>

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-6 col-lg-6">

                                <div class="intro-wrap">

                                    <div class="intro-icon">

                                        <i class="fas fa-lock"></i>

                                    </div>

                                    <div class="intro-content">

                                        <h5>Secure Payment Way</h5>

                                        <p>100% safe and encrypted payments through trusted gateways for your peace of mind.

                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                <!--=====================================

                                                                                                                                INTRO PART END

                                                                                             =======================================-->

                <section class="section newitem-part">

                    <div class="container">

                        <div class="row">

                            <div class="col">

                                <div class="section-heading">

                                    <h2>collected new items</h2>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col">

                                <ul class="new-slider slider-arrow">

                                @foreach ($latestProducts as $product)
                                @php 
                                    $discount = $product->mrp > 0 ? round((($product->mrp - $product->dp) / $product->mrp) * 100) : 0;
                                    $isDiscounted = $discount >= 50;
                                    $canBuy = $isEligible || !$isDiscounted;
                                    $userBV = auth()->check() ? \App\Models\Order::where('tx_user_id', auth()->id())->whereIn('tx_type', ['topup', 'retopup'])->sum('order_bv') : 0;
                                    
                                    $isWishlisted = false;
                                    if (auth('web')->check()) {
                                        $isWishlisted = \App\Models\Wishlist::where('u_code', auth('web')->user()->id)
                                            ->where('product_id', $product->id)
                                            ->exists();
                                    }
                                @endphp
                                <li>
                                    <div class="product-card">
                                        <div class="product-media">
                                            <div class="product-label">
                                                @if($discount >= 50)
                                                    <label class="label-text" style="background: #ff6b6b;">{{ $discount }}% OFF</label>
                                                @endif
                                                @if(!$canBuy)
                                                    <label class="label-text" style="background: #6c757d;">
                                                        <i class="fas fa-lock"></i> Premium
                                                    </label>
                                                @endif
                                            </div>
                                            <button class="product-wish wish {{ $isWishlisted ? 'active' : '' }}" 
                                                    onclick="toggleWishlist(this, {{ $product->id }})">
                                                <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"></i>
                                            </button>
                            
                                            <a class='product-image' href='{{ route('product.details', $product->slug) }}'>
                                                <img src="{{ asset('storage/' . $product->imgs) }}" alt="product">
                                            </a>
                            
                                            <div class="product-widget">
                                                <a title="Quick View" href="javascript:void(0)" class="fas fa-eye"
                                                   onclick='openQuickView({!! json_encode($product) !!})'>
                                                </a>
                                            </div>
                                        </div>
                            
                                        <div class="product-content">
                                            <h6 class="product-name">
                                                <a href='{{ route('product.details', $product->slug) }}'>{{ $product->name }}</a>
                                            </h6>
                                            <h6 class="product-price">
                                                <span>{{ company_info('currancy', '₹') }}{{ $product->dp }} (BV : {{$product->product_bv}})</span>
                                            </h6>
                                            
                                            @if(!$canBuy)
                                                <div class="alert alert-warning p-1 small text-center">
                                                    <i class="fas fa-lock"></i> 
                                                    Need {{ 100 - $userBV }} more BV
                                                    <div class="progress mt-1" style="height: 4px;">
                                                        <div class="progress-bar bg-warning" style="width: {{ min(100, ($userBV / 100) * 100) }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="product-action-group mb-3 mt-3">
                                                <div class="qty-box" data-product-id="{{ $product->id }}">
                                                    <button type="button" class="qty-btn" onclick="changeQty('minus', this)">−</button>
                                                    <input type="text" class="qty-input" value="1" readonly>
                                                    <button type="button" class="qty-btn" onclick="changeQty('plus', this)">+</button>
                                                </div>
                            
                                                @if($canBuy)
                                                    <button type="button" class="btn btn-success w-100 py-2 mt-2 add-cart-btn"
                                                            onclick="addToCartAjax(this)">
                                                        <i class="fas fa-shopping-basket me-2"></i> ADD
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-secondary w-100 py-2 mt-2" disabled>
                                                        <i class="fas fa-lock me-2"></i> LOCKED
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                                </ul>

                            </div>

                        </div>

                 <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2">
                    @forelse ($popularProducts as $product)
                        @php
                            $isWishlisted = false;
                            if (auth('web')->check()) {
                                $isWishlisted = \App\Models\Wishlist::where('u_code', auth('web')->user()->id)
                                    ->where('product_id', $product->id)
                                    ->exists();
                            }
                
                            $discount = $product->mrp > 0 ? (($product->mrp - $product->dp) / $product->mrp) * 100 : 0;
                            $isDiscounted = $discount >= 50;
                            
                            // Check user eligibility
                            $userBV = 0;
                            $isEligible = false;
                            if (auth('web')->check()) {
                                $userBV = \App\Models\Order::where('tx_user_id', auth('web')->user()->id)
                                    ->whereIn('tx_type', ['topup', 'retopup'])
                                    ->sum('order_bv');
                                $isEligible = $userBV >= 100;
                            }
                            
                            $canBuy = $isEligible || !$isDiscounted;
                            $remainingBV = max(0, 100 - $userBV);
                        @endphp

        <div class="col">
            <div class="feature-card">
                <div class="feature-media">
                    <div class="feature-label">
                        @if ($discount > 0)
                            <label class="label-text off">-{{ round($discount) }}%</label>
                        @endif
                        @if ($isDiscounted && !$isEligible)
                            <label class="label-text" style="background: #6c757d; color: white;">
                                <i class="fas fa-lock"></i> Premium
                            </label>
                        @endif
                    </div>

                    <button class="feature-wish wish {{ $isWishlisted ? 'active' : '' }}"
                        onclick="toggleWishlist(this, {{ $product->id }})">
                        <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"></i>
                    </button>

                    <a class='feature-image' href='{{ route('product.details', $product->slug) }}'>
                        <img src="{{ asset('storage/' . $product->imgs) }}" alt="product">
                    </a>

                    <div class="feature-widget">
                        <a title="Quick View" href="javascript:void(0)" class="fas fa-eye"
                            onclick='openQuickView({!! json_encode($product) !!})'>
                        </a>
                    </div>
                </div>

                <div class="feature-content">
                    <h6 class="feature-name">
                        <a href='{{ route('product.details', $product->slug) }}'>{{ $product->name }}</a>
                    </h6>
                    
                    <div class="feature-rating">
                        <i class="active icofont-star"></i>
                        <i class="active icofont-star"></i>
                        <i class="active icofont-star"></i>
                        <i class="active icofont-star"></i>
                        <i class="icofont-star"></i>
                        <a href="javascript:void(0)">(0 Reviews)</a>
                    </div>

                    <h6 class="feature-price">
                        <del>{{ company_info('currancy', '₹') }}{{ $product->mrp }} </del>
                        <span>{{ company_info('currancy', '₹') }}{{ $product->dp }}<small>/piece</small></span>
                        <span class="badge bg-info text-dark">BV: {{$product->product_bv}}</span>
                    </h6>

                    @if($isDiscounted && !$isEligible)
                        <div class="alert alert-warning p-2 small text-center mb-2">
                            <i class="fas fa-lock me-1"></i>
                            <strong>Premium Product</strong>
                            <br>
                            <small>Need {{ $remainingBV }} more BV to unlock</small>
                            <div class="progress mt-1" style="height: 5px;">
                                <div class="progress-bar bg-warning" 
                                     style="width: {{ min(100, ($userBV / 100) * 100) }}%">
                                </div>
                            </div>
                            <small class="text-muted">Your BV: {{ $userBV }} / 100</small>
                        </div>
                    @endif

                    <p class="feature-desc">{{ Str::limit($product->p_dese, 100) }}</p>

                    <div class="qty-box" data-product-id="{{ $product->id }}">
                        <button type="button" class="qty-btn" onclick="changeQty('minus', this)">−</button>
                        <input type="text" class="qty-input" value="1" readonly>
                        <button type="button" class="qty-btn" onclick="changeQty('plus', this)">+</button>
                    </div>

                    @if($canBuy)
                        <button type="button"
                            class="btn btn-success w-100 py-2 fw-bold shadow-sm add-cart-btn"
                            onclick="addToCartAjax(this)">
                            <i class="fas fa-shopping-basket me-2"></i> Add To Cart
                        </button>
                    @else
                        <button type="button"
                            class="btn btn-secondary w-100 py-2 fw-bold shadow-sm"
                            disabled
                            title="Need {{ $remainingBV }} more BV to purchase this product">
                            <i class="fas fa-lock me-2"></i> Locked
                        </button>
                        <small class="d-block text-center text-muted mt-1">
                            <i class="fas fa-info-circle"></i> 
                            Need {{ $remainingBV }} more BV
                        </small>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-4">
            <p class="text-muted">No featured items available at the moment.</p>
        </div>
    @endforelse
</div>

                    </div>

                </section>

          

                <section class="section feature-part">

                    <div class="container">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="section-heading">

                                    <h2>our featured items</h2>

                                </div>

                            </div>

                        </div>

                        <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2">

                            @forelse ($popularProducts as $product)

                                @php

                                    $isWishlisted = false;

                                    if (auth('web')->check()) {

                                        $isWishlisted = \App\Models\Wishlist::where('u_code', auth('web')->user()->id)

                                            ->where('product_id', $product->id)

                                            ->exists();

                                    }

                                    $discount =

                                        $product->mrp > 0 ? (($product->mrp - $product->dp) / $product->mrp) * 100 : 0;

                                @endphp

                                <div class="col">

                                    <div class="feature-card">

                                        <div class="feature-media">

                                            <div class="feature-label">

                                                {{-- <label class="label-text feat">feature</label> --}}

                                                @if ($discount > 0)

                                                    <label class="label-text off">-{{ round($discount) }}%</label>

                                                @endif

                                            </div>



                                            <button class="feature-wish wish {{ $isWishlisted ? 'active' : '' }}"

                                                onclick="toggleWishlist(this, {{ $product->id }})">

                                                <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"></i>

                                            </button>



                                            <a class='feature-image'

                                                href='{{ route('product.details', $product->slug) }}'>

                                                <img src="{{ asset('storage/' . $product->imgs) }}" alt="product">

                                            </a>



                                            <div class="feature-widget">

                                                <a title="Quick View" href="javascript:void(0)" class="fas fa-eye"

                                                    onclick='openQuickView({!! json_encode($product) !!})'>

                                                </a>

                                            </div>

                                        </div>

                                        <div class="feature-content">

                                            <h6 class="feature-name">

                                                <a

                                                    href='{{ route('product.details', $product->slug) }}'>{{ $product->name }}</a>

                                            </h6>

                                            <div class="feature-rating">

                                                <i class="active icofont-star"></i>

                                                <i class="active icofont-star"></i>

                                                <i class="active icofont-star"></i>

                                                <i class="active icofont-star"></i>

                                                <i class="icofont-star"></i>

                                                <a href="javascript:void(0)">(0 Reviews)</a>

                                            </div>

                                            <h6 class="feature-price">

                                                <del>{{ company_info('currancy', '₹') }}{{ $product->mrp }} </del>

                                                <span>{{ company_info('currancy', '₹') }}{{ $product->dp }}<small>/piece</small></span> 
                                                 (BV : {{$product->product_bv}})

                                            </h6>
                                                   <p> (BV : {{$product->product_bv}})</p> 

                                            <p class="feature-desc">{{ Str::limit($product->p_dese, 100) }}</p>











                                <div class="qty-box" data-product-id="{{ $product->id }}">

                                                <button type="button" class="qty-btn" onclick="changeQty('minus', this)">−</button>

                                                <input type="text" class="qty-input" value="1" readonly>

                                                <button type="button" class="qty-btn" onclick="changeQty('plus', this)">+</button>

                                            </div>



                                            <button type="button"

                                                class="btn btn-success w-100 py-2 fw-bold shadow-sm add-cart-btn"

                                                onclick="addToCartAjax(this)">

                                                <i class="fas fa-shopping-basket me-2"></i> Add To Cart

                                            </button>





                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="col-12 text-center py-4">

                                    <p class="text-muted">No featured items available at the moment.</p>

                                </div>

                            @endforelse

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="row">

                                    <div class="col-lg-12">

                                        <div class="section-btn-25">

                                            <a class="btn btn-outline" href="{{ route('product.shop') }}?sort=trending">

                                                <i class="fas fa-eye"></i>

                                                <span>view all featured items</span>

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

                <!--=====================================

                                                                                                                                FEATURE PART END

                                                                                                                    =======================================-->



                <!--=====================================

                            CATEGORY PART START

                =======================================-->

                <section class="section category-part">

                    <div class="container">

                        <div class="row">

                            <div class="col-12">

                                <div class="section-heading">

                                    <h2>shop by categories</h2>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="category-slider slider-arrow">

                                    @foreach ($categories as $cat)

                                        <div class="category-wrap">

                                            <div class="category-media">

                                                <img src="{{ $cat->image ? asset('storage/' . $cat->image) : asset('main/images/category/groceries.jpg') }}"

                                                    alt="{{ $cat->name }}" style="height: 150px; object-fit: cover;">



                                                <div class="category-overlay">

                                                    <a href="{{ route('category.view', $cat->slug) }}">

                                                        <i class="fas fa-link"></i>

                                                    </a>

                                                </div>

                                            </div>

                                            <div class="category-meta">

                                                <h4>{{ $cat->name }}</h4>

                                                <p>({{ $cat->products_count ?? $cat->products->count() }} items)</p>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="section-btn-50">

                                    <a class='btn btn-outline' href="{{ route('product.shop') }}">

                                        <i class="fas fa-eye"></i>

                                        <span>view all category</span>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

            

                {{-- <section class="section brand-part">

                    <div class="container">

                        <div class="row">

                            <div class="col-12">

                                <div class="section-heading">

                                    <h2>shop by brands</h2>

                                </div>

                            </div>

                        </div>

                        <div class="brand-slider slider-arrow">

                            <div class="brand-wrap">

                                <div class="brand-media">

                                    <img src="{{ asset('main/images/brand/01.jpg') }}" alt="brand">

                                    <div class="brand-overlay">

                                        <a href='brand-single.html'><i class="fas fa-link"></i></a>

                                    </div>

                                </div>

                                <div class="brand-meta">

                                    <h4>natural greeny</h4>

                                    <p>(45 items)</p>

                                </div>

                            </div>

                            <div class="brand-wrap">

                                <div class="brand-media">

                                    <img src="{{ asset('main/images/brand/02.jpg') }}" alt="brand">

                                    <div class="brand-overlay">

                                        <a href='brand-single.html'><i class="fas fa-link"></i></a>

                                    </div>

                                </div>

                                <div class="brand-meta">

                                    <h4>vegan lover</h4>

                                    <p>(45 items)</p>

                                </div>

                            </div>

                            <div class="brand-wrap">

                                <div class="brand-media">

                                    <img src="{{ asset('main/images/brand/03.jpg') }}" alt="brand">

                                    <div class="brand-overlay">

                                        <a href='brand-single.html'><i class="fas fa-link"></i></a>

                                    </div>

                                </div>

                                <div class="brand-meta">

                                    <h4>Harbal foody</h4>

                                    <p>(45 items)</p>

                                </div>

                            </div>

                            <div class="brand-wrap">

                                <div class="brand-media">

                                    <img src="{{ asset('main/images/brand/04.jpg') }}" alt="brand">

                                    <div class="brand-overlay">

                                        <a href='brand-single.html'><i class="fas fa-link"></i></a>

                                    </div>

                                </div>

                                <div class="brand-meta">

                                    <h4>ecomart limited</h4>

                                    <p>(45 items)</p>

                                </div>

                            </div>

                            <div class="brand-wrap">

                                <div class="brand-media">

                                    <img src="{{ asset('main/images/brand/05.jpg') }}" alt="brand">

                                    <div class="brand-overlay">

                                        <a href='brand-single.html'><i class="fas fa-link"></i></a>

                                    </div>

                                </div>

                                <div class="brand-meta">

                                    <h4>fresh fortune</h4>

                                    <p>(45 items)</p>

                                </div>

                            </div>

                            <div class="brand-wrap">

                                <div class="brand-media">

                                    <img src="{{ asset('main/images/brand/06.jpg') }}" alt="brand">

                                    <div class="brand-overlay">

                                        <a href='brand-single.html'><i class="fas fa-link"></i></a>

                                    </div>

                                </div>

                                <div class="brand-meta">

                                    <h4>econature</h4>

                                    <p>(45 items)</p>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="section-btn-50">

                                    <a class='btn btn-outline' href='brand-list.html'>

                                        <i class="fas fa-eye"></i>

                                        <span>view all brands</span>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </section> --}}

                <!--=====================================

                                                                                                                                BRAND PART END

                                                                                                                    =======================================-->











            @endsection

