@extends('main.layouts.app')

@section('content')
    <!-- Breadcrumb start -->
    <div class="row m-1">
        <div class="col-12 ">
            <h4 class="main-title">Product</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a class="f-s-14 f-w-500" href="{{ route('dashboard.user') }}">
                        <span>
                            <i class="ph-duotone  ph-stack f-s-16"></i> Home
                        </span>
                    </a>
                </li>
                <li>
                    <a class="f-s-14 f-w-500" href="#">E-shop</a>
                </li>
                <li class="active">
                    <a class="f-s-14 f-w-500" href="#">Product</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Breadcrumb end -->

    <!-- Product start -->
    <div class="row">

        <!-- Filters start -->
        <div class="col-xxl-3 col-lg-4 product-box productbox">
            <div class="card">
                <div class="card-header">
                    <h5>Filters</h5>
                </div>
                <div class="card-body p-0">
                    <div class="accordion accordion-flush app-accordion accordion-light-primary"
                         id="accordion-flush-sort-by">

                        <!-- Filter by Price -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading-price">
                                <button aria-controls="collapse_price" aria-expanded="true"
                                        class="accordion-button bg-none p-1"
                                        data-bs-target="#collapse_price" data-bs-toggle="collapse"
                                        type="button">
                                    <span class="m-0 mt-1">Filter by Price</span>
                                </button>
                            </h2>
                            <div aria-labelledby="flush-heading-price"
                                 class="accordion-collapse collapse show"
                                 data-bs-parent="#accordion-flush-sort-by"
                                 id="collapse_price">
                                <div class="p-3">
                                    <form action="{{ url()->current() }}" method="GET" id="price-filter">
                                        <div class="d-flex gap-2 mb-3">
                                            <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                                            <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i>Search</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Filter by Category -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading-cat">
                                <button aria-controls="collapse_cat" aria-expanded="false"
                                        class="accordion-button bg-none p-1"
                                        data-bs-target="#collapse_cat" data-bs-toggle="collapse"
                                        type="button">
                                    <span class="m-0 mt-1">Categories</span>
                                </button>
                            </h2>
                            <div aria-labelledby="flush-heading-cat"
                                 class="accordion-collapse collapse show"
                                 data-bs-parent="#accordion-flush-sort-by"
                                 id="collapse_cat">
                                <div class="accordion-body p-2">
                                    @foreach ($categories as $cat)
                                        <div class="p-2 d-flex align-items-center gap-2">
                                            <label class="check-box">
                                                <input type="checkbox" 
                                                       onchange="window.location.href='{{ route('category.view', $cat->slug) }}'"
                                                       {{ isset($category) && $category->id == $cat->id ? 'checked' : '' }}>
                                                <span class="checkmark outline-secondary ms-2"></span>
                                            </label>
                                            <a class="f-s-15 f-w-500 text-secondary" href="{{ route('category.view', $cat->slug) }}">
                                                {{ $cat->name }} <span class="text-muted">({{ $cat->products_count }})</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Sort By -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading-three">
                                <button aria-controls="collapse_three" aria-expanded="false"
                                        class="accordion-button bg-none p-1"
                                        data-bs-target="#collapse_three" data-bs-toggle="collapse"
                                        type="button">
                                    <span class="m-0 mt-1">Sort By</span>
                                </button>
                            </h2>
                            <div aria-labelledby="flush-heading-three"
                                 class="accordion-collapse collapse show"
                                 data-bs-parent="#accordion-flush-sort-by"
                                 id="collapse_three">
                                <div class="p-3">
                                    <select class="form-select" onchange="filterChange('sort', this.value)">
                                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>New Items</option>
                                        <option value="low_high" {{ request('sort') == 'low_high' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="high_low" {{ request('sort') == 'high_low' ? 'selected' : '' }}>Price: High to Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="d-flex">
                    <div class="text-start m-3">
                        <a class="btn btn-sm btn-info" href="{{ route('cart') }}" role="button">Cart</a>
                        
                    </div>
                    
                    <div class="text-end m-3">
                        <a class="btn btn-sm btn-primary" href="{{ route('product.shop') }}" role="button">Clear all</a>
                        <a class="btn btn-sm btn-secondary" href="#" role="button">Apply</a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Filters end -->

        <!-- product box start -->
        <div class="col-xxl-9 col-lg-8">
            <div class="product-wrapper-grid">
                <div class="row" id="shop-container">
                    @forelse($products as $product)
                        @php
                            $isWish = auth('web')->check()
                                ? \App\Models\Wishlist::where('u_code', auth('web')->user()->id)
                                    ->where('product_id', $product->id)
                                    ->exists()
                                : false;
                            $discount =
                                $product->mrp > 0 ? (($product->mrp - $product->dp) / $product->mrp) * 100 : 0;
                        @endphp
                        <div class="col-xxl-3 col-md-4 col-sm-6 product-item">
                            <div class="card overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="product-content-box">
                                        <div class="product-grid">
                                            <div class="product-image">
                                                <a class="image" href="{{ route('product.details', $product->slug) }}">
                                                    <img alt="{{ $product->name }}"
                                                         class="product-img-main"
                                                         src="{{ asset('storage/' . $product->imgs) }}">
                                                </a>
                                                <div class="product-label">
                                                    @if ($product->created_at >= now()->subDays(7))
                                                        <span class="badge bg-primary">new</span>
                                                    @endif
                                                    @if ($discount > 0)
                                                        <span class="badge bg-danger">-{{ round($discount) }}%</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <a class="m-0 f-s-18 f-w-500" href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
                                                <!-- Wishlist Button -->
                                                <a class="bg-danger h-30 w-30 d-flex-center b-r-20 product-wish {{ $isWish ? 'active' : '' }}"
                                                   href="javascript:void(0)"
                                                   onclick="toggleWishlist(this, {{ $product->id }})"
                                                   style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background: {{ $isWish ? '#dc3545' : '#f8f9fa' }}; transition: all 0.3s ease;">
                                                    <i class="{{ $isWish ? 'fa-solid' : 'fa-regular' }} fa-heart f-s-16" style="color: {{ $isWish ? '#fff' : '#dc3545' }};"></i>
                                                </a>
                                            </div>
                                            <p class="text-secondary" style="font-size: 13px; margin-top: 5px;">{{ Str::limit($product->short_desc ?? 'Description', 30) }}</p>
                                            <div class="pricing-box">
                                                <h6>
                                                    {{ company_info('currancy', '₹') }}{{ $product->dp }}
                                                    @if($product->mrp > 0)
                                                        <span style="font-size: 14px;">(<del>{{ company_info('currancy', '₹') }}{{ $product->mrp }}</del>)</span>
                                                        @if($discount > 0)
                                                            <span class="text-success ms-2" style="font-size: 13px;">{{ round($discount) }}% off</span>
                                                        @endif
                                                    @endif
                                                </h6>
                                            </div>
                                            <div class="product-action-group mt-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="qty-box d-flex align-items-center gap-1" data-product-id="{{ $product->id }}">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary qty-btn"
                                                                onclick="changeQty('minus', this)" style="padding: 2px 8px;">−</button>
                                                        <input type="text" class="form-control form-control-sm text-center qty-input" style="width:45px; padding: 2px;" value="1" readonly>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary qty-btn"
                                                                onclick="changeQty('plus', this)" style="padding: 2px 8px;">+</button>
                                                    </div>
                                                    <button type="button" class="btn btn-primary flex-grow-1 add-cart-btn"
                                                            onclick="addToCartAjax(this)" style="padding: 6px 12px; font-size: 14px;">
                                                        <i class="fas fa-shopping-basket me-1"></i> ADD
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <h4>No products found.</h4>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
     
            </div>
        </div>
        <!-- product box end -->
    </div>
    <!-- Product end -->

    <!-- Only page-specific JavaScript (minimal) -->
    <script>
        // Filter function - page specific only
        function filterChange(key, value) {
            let url = new URL(window.location.href);
            url.searchParams.set(key, value);
            window.location.href = url.href;
        }

        // Grid/List view toggle - page specific only
        $(document).ready(function() {
            const container = $('#shop-container');

            $('#grid-3-btn').click(function() {
                $('.btn-secondary, .btn-primary').removeClass('active');
                $(this).addClass('active');
                container.removeClass('row-cols-1 row-cols-2 list-view').addClass(
                    'row-cols-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3');
                container.find('.col-xxl-3').removeClass('col-xxl-4 col-md-6').addClass('col-xxl-3 col-md-4');
            });

            $('#grid-2-btn').click(function() {
                $('.btn-secondary, .btn-primary').removeClass('active');
                $(this).addClass('active');
                container.removeClass('row-cols-1 row-cols-3 row-cols-md-3 list-view').addClass(
                    'row-cols-2');
                container.find('.col-xxl-3').removeClass('col-xxl-3 col-md-4 col-sm-6').addClass('col-xxl-4 col-md-6');
            });

            $('#list-btn').click(function() {
                $('.btn-secondary, .btn-primary').removeClass('active');
                $(this).addClass('active');
                container.removeClass('row-cols-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3').addClass(
                    'row-cols-1 list-view');
                container.find('.col-xxl-3').removeClass('col-xxl-3 col-md-4 col-sm-6').addClass('col-xxl-12');
            });
        });
    </script>
@endsection