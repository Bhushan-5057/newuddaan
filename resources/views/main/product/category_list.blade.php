@extends('main.layouts.app')

@section('content')
    <section class="single-banner inner-section"
        style="background: url({{ asset('main/images/single-banner.jpg') }}) no-repeat center;">
        <div class="container">
            <h2>{{ $category->name ?? 'Shop' }}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shop</li>
            </ol>
        </div>
    </section>

    <section class="inner-section shop-part">
        <div class="container">
            <div class="row content-reverse">
                <div class="col-lg-3">
                    <div class="shop-widget-promo">
                        <a href="#"><img src="{{ asset('main/images/promo/shop/01.jpg') }}" alt="promo"></a>
                    </div>

                    <div class="shop-widget">
                        <h6 class="shop-widget-title">Filter by Price</h6>
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="shop-widget-group">
                                <input type="number" name="min_price" placeholder="Min - 00"
                                    value="{{ request('min_price') }}">
                                <input type="number" name="max_price" placeholder="Max - 5k"
                                    value="{{ request('max_price') }}">
                            </div>
                            <div class="mt-3">
                                <label class="small text-muted">Quick Range:</label>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    <a href="{{ url()->current() }}?min_price=0&max_price=500"
                                        class="badge bg-light text-dark border">Under ₹500</a>
                                    <a href="{{ url()->current() }}?min_price=500&max_price=2000"
                                        class="badge bg-light text-dark border">₹500 - ₹2000</a>
                                </div>
                            </div>
                            <button type="submit" class="shop-widget-btn mt-3">
                                <i class="fas fa-search"></i><span>search</span>
                            </button>
                        </form>
                    </div>

                    <div class="shop-widget">
                        <h6 class="shop-widget-title">Filter by Category</h6>
                        <ul class="shop-widget-list shop-widget-scroll">
                            @foreach ($categories as $cat)
                                <li>
                                    <div class="shop-widget-content">
                                        <input type="checkbox" id="cat-{{ $cat->id }}"
                                            onchange="window.location.href='{{ route('category.view', $cat->slug) }}'"
                                            {{ isset($category) && $category->id == $cat->id ? 'checked' : '' }}>
                                        <label for="cat-{{ $cat->id }}">{{ $cat->name }}</label>
                                    </div>
                                    <span class="shop-widget-number">({{ $cat->products_count }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="top-filter">
                                <div class="filter-show">
                                    <label class="filter-label">Show :</label>
                                    <select class="form-select filter-select" onchange="filterChange('show', this.value)">
                                        <option value="12" {{ request('show') == 12 ? 'selected' : '' }}>12</option>
                                        <option value="24" {{ request('show') == 24 ? 'selected' : '' }}>24</option>
                                        <option value="36" {{ request('show') == 36 ? 'selected' : '' }}>36</option>
                                    </select>
                                </div>
                                <div class="filter-short">
                                    <label class="filter-label">Sort by :</label>
                                    <select class="form-select filter-select" onchange="filterChange('sort', this.value)">
                                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>
                                            Default</option>
                                        <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>New Items
                                        </option>
                                        <option value="low_high" {{ request('sort') == 'low_high' ? 'selected' : '' }}>
                                            Price: Low to High</option>
                                        <option value="high_low" {{ request('sort') == 'high_low' ? 'selected' : '' }}>
                                            Price: High to Low</option>
                                    </select>
                                </div>
                                <div class="filter-action">
                                    <button class="view-btn active" title="Three Column" onclick="changeView('grid')"><i
                                            class="fas fa-th"></i></button>
                                    <button class="view-btn" title="One Column" onclick="changeView('list')"><i
                                            class="fas fa-th-list"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="product-container" class="row row-cols-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 grid-view">
                        @forelse($products as $product)
                            @php $discount = $product->mrp > 0 ? (($product->mrp - $product->dp) / $product->mrp) * 100 : 0; @endphp
                            <div class="col product-item-col">
                                <div class="product-card">
                                    <div class="product-media">
                                        <div class="product-label">
                                            {{-- <label class="label-text new">new</label> --}}
                                            @if ($discount > 0)
                                                <label class="label-text off">-{{ round($discount) }}%</label>
                                            @endif
                                        </div>
                                        @php
                                            $isWishlisted = false;
                                            if (auth()->check()) {
                                                $isWishlisted = \App\Models\Wishlist::where(
                                                    'u_code',
                                                    auth()->user()->id,
                                                )
                                                    ->where('product_id', $product->id)
                                                    ->exists();
                                            }
                                        @endphp

                                        <button class="product-wish wish {{ $isWishlisted ? 'active' : '' }}"
                                            onclick="toggleWishlist(this, {{ $product->id }})">
                                            <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart"></i>
                                        </button>
                                        <a class='product-image' href="{{ route('product.details', $product->slug) }}">
                                            <img src="{{ asset('storage/' . $product->imgs) }}" alt="product">
                                        </a>

                                        <div class="product-widget">
                                            <a title="Quick View" href="javascript:void(0)" class="fas fa-eye"
                                                onclick='openQuickView({!! json_encode($product) !!})'>
                                            </a>
                                        </div>
                                    </div>


                                    <div class="modal fade" id="product-view-{{ $product->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <button class="modal-close icofont-close" data-bs-dismiss="modal"></button>
                                                <div class="product-view">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-6">
                                                            <div class="view-gallery">
                                                                <div class="view-label-group">
                                                                    <label class="view-label new">new</label>
                                                                    @if ($discount > 0)
                                                                        <label
                                                                            class="view-label off">-{{ round($discount) }}%</label>
                                                                    @endif
                                                                </div>

                                                                <div class="view-img-container mb-3">
                                                                    <img id="main-view-img-{{ $product->id }}"
                                                                        src="{{ asset('storage/' . $product->imgs) }}"
                                                                        alt="product" class="img-fluid w-100">
                                                                </div>

                                                                @if ($product->more_imgs && count($product->more_imgs) > 0)
                                                                    <div class="view-thumb-row d-flex flex-wrap gap-2">
                                                                        <div class="thumb-item"
                                                                            style="width: 60px; cursor: pointer;">
                                                                            <img src="{{ asset('storage/' . $product->imgs) }}"
                                                                                class="img-thumbnail"
                                                                                onclick="changeImage('{{ asset('storage/' . $product->imgs) }}', '{{ $product->id }}')">
                                                                        </div>

                                                                        @foreach ($product->more_imgs as $m_img)
                                                                            <div class="thumb-item"
                                                                                style="width: 60px; cursor: pointer;">
                                                                                <img src="{{ asset('storage/' . $m_img) }}"
                                                                                    class="img-thumbnail"
                                                                                    onclick="changeImage('{{ asset('storage/' . $m_img) }}', '{{ $product->id }}')">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6">
                                                            <div class="view-details">
                                                                <h3 class="view-name">
                                                                    <a
                                                                        href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
                                                                </h3>
                                                                <div class="view-meta">
                                                                    <p>SKU:<span>{{ $product->p_code }}</span></p>
                                                                    <p>BRAND:<a
                                                                            href="#">{{ company_info('company_name') }}</a>
                                                                    </p>
                                                                </div>
                                                                <div class="view-rating">
                                                                    <i class="active icofont-star"></i>
                                                                    <i class="active icofont-star"></i>
                                                                    <i class="active icofont-star"></i>
                                                                    <i class="active icofont-star"></i>
                                                                    <i class="icofont-star"></i>
                                                                    <a href="#">(0 reviews)</a>
                                                                </div>
                                                                <h3 class="view-price">
                                                                    <del>{{ company_info('currancy', '₹') }}{{ $product->mrp }}</del>
                                                                    <span>{{ company_info('currancy', '₹') }}{{ $product->dp }}<small>/per
                                                                            piece</small></span>
                                                                </h3>
                                                                <p class="view-desc">{{ $product->p_dese }}</p>

                                                                <div class="view-list-group">
                                                                    <label class="view-list-title">Points:</label>
                                                                    <ul class="view-tag-list">
                                                                        <li><a href="#">BV:
                                                                                {{ $product->product_bv }}</a></li>
                                                                        <li><a href="#">PV: {{ $product->pv }}</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                                <form action="#" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="product_id"
                                                                        value="{{ $product->id }}">
                                                                    <div class="view-add-group">
                                                                        <button type="submit" class="product-add"
                                                                            title="Add to Cart">
                                                                            <i class="fas fa-shopping-basket"></i>
                                                                            <span>add to cart</span>
                                                                        </button>
                                                                        <div class="product-action">
                                                                            <button type="button" class="action-minus"
                                                                                title="Quantity Minus"><i
                                                                                    class="icofont-minus"></i></button>
                                                                            <input class="action-input"
                                                                                title="Quantity Number" type="text"
                                                                                name="quantity" value="1">
                                                                            <button type="button" class="action-plus"
                                                                                title="Quantity Plus"><i
                                                                                    class="icofont-plus"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </form>

                                                                <div class="view-action-group">
                                                                    <a class="view-wish wish" href="#"
                                                                        title="Add Your Wishlist">
                                                                        <i class="icofont-heart"></i>
                                                                        <span>add to wish</span>
                                                                    </a>
                                                                    <a class='view-compare' href='#'
                                                                        title='Compare This Item'>
                                                                        <i class="fas fa-random"></i>
                                                                        <span>Compare This</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h6 class="product-name">
                                            <a
                                                href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
                                        </h6>
                                        <h6 class="product-price">
                                            <del>{{ company_info('currancy', '₹') }}{{ $product->mrp }}</del>
                                            <span>{{ company_info('currancy', '₹') }}{{ $product->dp }}</span>
                                        </h6>
                                        <!-- QTY -->
                                        <div class="product-action-group mb-3 mt-3">
                                            <div class="qty-box" data-product-id="{{ $product->id }}">
                                                <button type="button" class="qty-btn"
                                                    onclick="changeQty('minus', this)">−</button>
                                                <input type="text" class="qty-input" value="1" readonly>
                                                <button type="button" class="qty-btn"
                                                    onclick="changeQty('plus', this)">+</button>
                                            </div>

                                            <button type="button" class="btn btn-success w-100 py-2 mt-2 add-cart-btn"
                                                onclick="addToCartAjax(this)">
                                                <i class="fas fa-shopping-basket me-2"></i> ADD
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-12 text-center mt-5">
                                <h4>No products found.</h4>
                            </div>
                        @endforelse
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="bottom-paginate">
                                <p class="page-info">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                                    of {{ $products->total() }} Results</p>
                                {{ $products->appends(request()->input())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function filterChange(key, value) {
            let url = new URL(window.location.href);
            url.searchParams.set(key, value);
            window.location.href = url.href;
        }

        function changeView(type) {
            const container = document.getElementById('product-container');
            const btns = document.querySelectorAll('.view-btn');

            btns.forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');

            if (type === 'list') {
                container.classList.remove('row-cols-2', 'row-cols-md-3', 'row-cols-lg-3', 'row-cols-xl-3');
                container.classList.add('row-cols-1', 'list-view');
            } else {
                container.classList.add('row-cols-2', 'row-cols-md-3', 'row-cols-lg-3', 'row-cols-xl-3');
                container.classList.remove('row-cols-1', 'list-view');
            }
        }
    </script>

    <style>
        /* List View Custom Design */
        .list-view .product-card {
            display: flex;
            align-items: center;
            text-align: left;
            padding: 15px;
        }

        .list-view .product-media {
            width: 200px;
            margin-right: 20px;
        }

        .list-view .product-content {
            flex: 1;
        }

        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endsection
