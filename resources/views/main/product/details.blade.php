@extends('user.layouts.app')

@section('content')

<style>
    .thumb-img-wrapper {
        transition: all 0.2s ease-in-out;
        border: 2px solid #e9ecef !important;
    }
    .thumb-img-wrapper:hover {
        border-color: #0d6efd !important; /* Blue border on hover */
    }
    .thumb-img-wrapper.active-thumb {
        border-color: #0d6efd !important; /* Active image border */
        box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
    }
</style>

            <!-- Breadcrumb start -->
            <div class="row m-1">
                <div class="col-12 ">
                    <h4 class="main-title">{{ $product->name }}</h4>
                    <ul class="app-line-breadcrumbs mb-3">
                        <li class="">
                            <a class="f-s-14 f-w-500" href="{{ route('dashboard.user') }}">
                                <span>
                                    <i class="ph-duotone ph-stack f-s-16"></i> Home
                                </span>
                            </a>
                        </li>
                        <li>
                            <a class="f-s-14 f-w-500" href="#">E-shop</a>
                        </li>
                        <li>
                            <a class="f-s-14 f-w-500" href="{{ route('category.view', $product->category->slug) }}">
                                {{ $product->category->name }}
                            </a>
                        </li>
                        <li class="active">
                            <a class="f-s-14 f-w-500" href="#">Product Details</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Breadcrumb end -->

            <!-- Product Details start -->
            <div class="row">
                <!-- Product Images -->
              <div class="col-md-6 col-xxl-3 order-md-2 order-xxl-1">
    <div class="card">
        <div class="card-body">
            <div class="product-main-image-box mb-3 text-center border rounded p-2 bg-white">
                <img id="mainProductImage" alt="product-image" class="img-fluid rounded" 
                     src="{{ asset('storage/' . $product->imgs) }}" 
                     style="max-height: 350px; object-fit: contain; width: 100%;">
            </div>

            <div class="product-thumbnails-grid">
                <div class="d-flex gap-2 flex-wrap justify-content-center">
                    <div class="thumb-img-wrapper border rounded p-1 active-thumb" style="cursor: pointer; width: 65px; height: 65px;">
                        <img class="img-fluid h-100 w-100 rounded thumb-img" 
                             src="{{ asset('storage/' . $product->imgs) }}" 
                             style="object-fit: cover;"
                             onclick="changeMainImage(this)">
                    </div>
                    
                    @if ($product->more_imgs)
                        @foreach ($product->more_imgs as $img)
                            <div class="thumb-img-wrapper border rounded p-1" style="cursor: pointer; width: 65px; height: 65px;">
                                <img class="img-fluid h-100 w-100 rounded thumb-img" 
                                     src="{{ asset('storage/' . $img) }}" 
                                     style="object-fit: cover;"
                                     onclick="changeMainImage(this)">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="mt-4">
                @php
                    $discount = $product->mrp > 0 ? (($product->mrp - $product->dp) / $product->mrp) * 100 : 0;
                @endphp
                @if ($discount > 0)
                    <h6 class="text-danger f-s-18"><b>{{ round($discount) }}% OFF</b></h6>
                @endif
                <p class="mb-0 f-s-15 text-secondary">Sold by:
                    <a class="link-primary text-decoration-underline">
                        {{ company_info('brand') }}
                    </a>
                </p>
                <div class="app-divider-v dotted pb-2"></div>
                <ul class="offer-details-list">
                    <li>
                        <i class="ti ti-tags text-primary f-s-18 me-1"></i> 
                        <b class="text-secondary">SKU:</b> {{ $product->p_code }}
                    </li>
                    <li>
                        <i class="ti ti-tags text-primary f-s-18 me-1"></i> 
                        <b class="text-secondary">GST:</b> {{ $product->gst_percent }}%
                    </li>
                    <li>
                        <i class="ti ti-tags text-primary f-s-18 me-1"></i> 
                        <b class="text-secondary">Points:</b> BV: {{ $product->product_bv }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

                <!-- Product Details -->
                <div class="col-xxl-6 order-xxl-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="product-details-contentbox">
                                <h4>{{ $product->name }}</h4>
                                
                                <div class="mt-4 product-details">
                                    <h3>
                                        {{ company_info('currancy', '₹') }}{{ $product->dp }}
                                        @if($product->mrp > 0)
                                            <span class="text-danger">({{ round($discount) }}% OFF)</span>
                                            <del class="text-muted f-s-16 ms-2">{{ company_info('currancy', '₹') }}{{ $product->mrp }}</del>
                                        @endif
                                    </h3>
                                </div>

                                <div class="app-divider-v dotted pb-2"></div>

                                <div class="product-detailbox mt-4">
                                    <div>
                                        <h5>Description:</h5>
                                        <div class="mt-3">
                                            <p>  {!! nl2br(e($product->p_dese)) !!}</p>
                                            <p>    {!! nl2br(e($product->other_dese)) !!}</p>
                                        </div>
                                    </div>
                                </div>
                                
                        

                        
                                <div class="product-details-btn text-end mt-4">
                                    <div class="d-flex gap-2 align-items-center justify-content-end flex-wrap">
                                        <div class="qty-box d-flex align-items-center gap-1 me-2" data-product-id="{{ $product->id }}">
                                            <button type="button" class="btn btn-sm btn-outline-secondary qty-btn"
                                                    onclick="changeQty('minus', this)">−</button>
                                            <input type="text" class="form-control form-control-sm text-center qty-input" 
                                                   style="width:50px;" value="1" readonly>
                                            <button type="button" class="btn btn-sm btn-outline-secondary qty-btn"
                                                    onclick="changeQty('plus', this)">+</button>
                                        </div>
                                        <button type="button" class="btn btn-primary add-cart-btn" onclick="addToCartAjax(this)">
                                            <i class="fas fa-shopping-basket me-1"></i> Add To Cart
                                        </button>
                                    
                                        @php
                                            $isWishlisted = false;
                                            if (auth('web')->check()) {
                                                $isWishlisted = \App\Models\Wishlist::where('u_code', auth('web')->user()->id)
                                                    ->where('product_id', $product->id)
                                                    ->exists();
                                            }
                                        @endphp
                                        <button type="button" class="btn btn-danger product-wish {{ $isWishlisted ? 'active' : '' }}" 
                                                onclick="toggleWishlist(this, {{ $product->id }})">
                                            <i class="{{ $isWishlisted ? 'fa-solid' : 'fa-regular' }} fa-heart me-1"></i> 
                                            {{ $isWishlisted ? 'Added to Wishlist' : 'Add to Wishlist' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-6 col-xxl-3 order-md-1 order-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="product-details-contentbox">
                          
                                <div class="mt-4">
                                    <h5>Specifications:</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td><strong>Product Code</strong></td>
                                                <td>{{ $product->p_code }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Category</strong></td>
                                                <td>{{ $product->category->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>GST</strong></td>
                                                <td>{{ $product->gst_percent }}%</td>
                                            </tr>
                                            <tr>
                                                <td><strong>BV Points</strong></td>
                                                <td>{{ $product->product_bv }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4">
                                    <h5>Related Products:</h5>
                                    <div class="product-details-table">
                                        <table class="table table-bottom-border align-middle products-data-table">
                                            <tbody>
                                                @forelse($relatedProducts->take(4) as $relProduct)
                                                    <tr class="border-0">
                                                        <td>
                                                            <div class="position-relative">
                                                                <img alt="product-image"
                                                                     class="w-45 h-45 position-absolute rounded"
                                                                     src="{{ asset('storage/' . $relProduct->imgs) }}">
                                                                <div class="mg-s-40">
                                                                    <h6 class="text-dark f-w-600 txt-ellipsis-1">
                                                                        {{ $relProduct->name }}
                                                                    </h6>
                                                                    <p class="text-secondary mb-0">#{{ $relProduct->p_code }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <h6 class="f-s-15 text-success">
                                                                {{ company_info('currancy', '₹') }}{{ $relProduct->dp }}
                                                            </h6>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted">No related products</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        @if($relatedProducts->count() > 0)
                                            <a class="btn btn-primary w-100" href="{{ route('category.view', $product->category->slug) }}" role="button">
                                                View All Products
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product Details end -->

            <!-- Description & Specification -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                                            data-bs-target="#description" type="button" role="tab">
                                        Description
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="specification-tab" data-bs-toggle="tab" 
                                            data-bs-target="#specification" type="button" role="tab">
                                        Specification
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content" id="productTabsContent">
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                    <div class="tab-descrip">
                                        <p>  {!! nl2br(e($product->p_dese)) !!}</p>
                                        <p>    {!! nl2br(e($product->other_dese)) !!}</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="specification" role="tabpanel">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 200px;">Product code</th>
                                                <td>{{ $product->p_code }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Category</th>
                                                <td>{{ $product->category->name }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">GST</th>
                                                <td>{{ $product->gst_percent }}%</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">BV Points</th>
                                                <td>{{ $product->product_bv }}</td>
                                            </tr>
                                            @if($product->mrp > 0)
                                                <tr>
                                                    <th scope="row">MRP</th>
                                                    <td>{{ company_info('currancy', '₹') }}{{ $product->mrp }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th scope="row">Selling Price</th>
                                                <td>{{ company_info('currancy', '₹') }}{{ $product->dp }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Related Items</h5>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                                    @foreach($relatedProducts as $relProduct)
                                        @php
                                            $isWishlisted = false;
                                            if (auth('web')->check()) {
                                                $isWishlisted = \App\Models\Wishlist::where('u_code', auth('web')->user()->id)
                                                    ->where('product_id', $relProduct->id)
                                                    ->exists();
                                            }
                                        @endphp
                                        <div class="col product-item">
                                            <div class="card overflow-hidden">
                                                <div class="card-body p-0">
                                                    <div class="product-content-box">
                                                        <div class="product-grid">
                                                            <div class="product-image">
                                                                <a class="image" href="{{ route('product.details', $relProduct->slug) }}">
                                                                    <img alt="{{ $relProduct->name }}"
                                                                         class="product-img-main"
                                                                         src="{{ asset('storage/' . $relProduct->imgs) }}">
                                                                </a>
                                                                <div class="product-label">
                                                                    @if ($relProduct->created_at >= now()->subDays(7))
                                                                        <span class="badge bg-primary">new</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="p-3">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <a class="m-0 f-s-18 f-w-500" href="{{ route('product.details', $relProduct->slug) }}">
                                                                    {{ Str::limit($relProduct->name, 20) }}
                                                                </a>
                                                                <a class="bg-danger h-30 w-30 d-flex-center b-r-20 product-wish {{ $isWishlisted ? 'active' : '' }}"
                                                                   href="javascript:void(0)"
                                                                   onclick="toggleWishlist(this, {{ $relProduct->id }})"
                                                                   style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background: {{ $isWishlisted ? '#dc3545' : '#f8f9fa' }}; transition: all 0.3s ease;">
                                                                    <i class="{{ $isWishlisted ? 'fa-solid' : 'fa-regular' }} fa-heart f-s-16" style="color: {{ $isWishlisted ? '#fff' : '#dc3545' }};"></i>
                                                                </a>
                                                            </div>
                                                            <div class="pricing-box">
                                                                <h6>
                                                                    {{ company_info('currancy', '₹') }}{{ $relProduct->dp }}
                                                                    @if($relProduct->mrp > 0)
                                                                        <span style="font-size: 14px;">(<del>{{ company_info('currancy', '₹') }}{{ $relProduct->mrp }}</del>)</span>
                                                                    @endif
                                                                </h6>
                                                            </div>
                                                            <div class="product-action-group mt-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="qty-box d-flex align-items-center gap-1" data-product-id="{{ $relProduct->id }}">
                                                                        <button type="button" class="btn btn-sm btn-outline-secondary qty-btn"
                                                                                onclick="changeQty('minus', this)" style="padding: 2px 8px;">−</button>
                                                                        <input type="text" class="form-control form-control-sm text-center qty-input" 
                                                                               style="width:45px; padding: 2px;" value="1" readonly>
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
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

 <script>
    function changeMainImage(element) {
        // ક્લિક કરેલી ઈમેજનો src મેળવો
        let newSrc = $(element).attr('src');
        
        // મુખ્ય ઈમેજ બોક્સના src ને બદલો
        $('#mainProductImage').attr('src', newSrc);
        
        // બધી ઈમેજીસ પરથી એક્ટિવ ક્લાસ હટાવો
        $('.thumb-img-wrapper').removeClass('active-thumb');
        
        // અત્યારે ક્લિક કરેલી ઈમેજ વાળા બોક્સને એક્ટિવ કરો
        $(element).closest('.thumb-img-wrapper').addClass('active-thumb');
    }
</script>

    @if(session('success') || session('error'))
        <script>
            $(document).ready(function() {
                @if(session('success'))
                    toastr.success('{{ session('success') }}');
                @endif
                @if(session('error'))
                    toastr.error('{{ session('error') }}');
                @endif
            });
        </script>
    @endif
@endsection