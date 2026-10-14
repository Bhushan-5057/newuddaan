@foreach($products as $product)
    @php
        $isWish = auth('web')->check()
            ? \App\Models\Wishlist::where('u_code', auth('web')->user()->id)
                ->where('product_id', $product->id)
                ->exists()
            : false;
        $discount = $product->mrp > 0 ? (($product->mrp - $product->dp) / $product->mrp) * 100 : 0;
    @endphp
    <div class="col-xxl-3 col-md-4 col-sm-6 product-item">
        <div class="card overflow-hidden">
            <div class="card-body p-0">
                <div class="product-content-box">
                    <div class="product-grid">
                        <div class="product-image">
                            <a class="image" href="{{ route('product.details', $product->slug) }}">
                                <img alt="{{ $product->name }}" class="product-img-main" src="{{ asset('storage/' . $product->imgs) }}">
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
                                    <button type="button" class="btn btn-sm btn-outline-secondary qty-btn" onclick="changeQty('minus', this)" style="padding: 2px 8px;">−</button>
                                    <input type="text" class="form-control form-control-sm text-center qty-input" style="width:45px; padding: 2px;" value="1" readonly>
                                    <button type="button" class="btn btn-sm btn-outline-secondary qty-btn" onclick="changeQty('plus', this)" style="padding: 2px 8px;">+</button>
                                </div>
                                <button type="button" class="btn btn-primary flex-grow-1 add-cart-btn" onclick="addToCartAjax(this)" style="padding: 6px 12px; font-size: 14px;">
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