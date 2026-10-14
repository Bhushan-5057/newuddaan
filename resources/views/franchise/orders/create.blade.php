@extends('franchise.layouts.app')
@section('title', 'Place New Order')

@section('content')
    <div class="container-fluid py-4 px-lg-5">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">🛒 Place New Order</h2>
                <p class="text-muted small mb-0">Select products and enter quantity to restock your inventory.</p>
            </div>
            <div class="text-end">
                <div class="badge bg-light-primary text-primary p-3 shadow-sm">
                    <span class="fs-6 fw-bold" id="total_items">0</span> Items Selected
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('franchise.orders.store') }}" id="orderForm">
            @csrf

            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm product-card">

                            {{-- ઈમેજ સેક્શન - Carousel જો એકથી વધુ ઈમેજ હોય --}}
                            <div class="text-center p-2 bg-light-subtle rounded-top" style="height:220px;">
                                @php
                                    // મુખ્ય ઈમેજ અને ગેલેરી ઈમેજને એક એરેમાં ભેગી કરો
                                    $images = [];
                                    if ($product->imgs) {
                                        $images[] = $product->imgs;
                                    }

                                    if ($product->more_imgs && is_array($product->more_imgs)) {
                                        $images = array_merge($images, $product->more_imgs);
                                    }
                                @endphp

                                @if (count($images) > 1)
                                    {{-- Carousel શરૂ --}}
                                    <div id="carouselProduct{{ $product->id }}" class="carousel slide h-100"
                                        data-bs-ride="false">
                                        <div class="carousel-inner h-100">
                                            @foreach ($images as $key => $img)
                                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }} h-100">
                                                    <img src="{{ asset('storage/' . $img) }}" class="d-block w-100 h-100"
                                                        style="object-fit: contain;" alt="Product Image">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon bg-dark rounded-circle"
                                                aria-hidden="true" style="width: 20px; height: 20px;"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon bg-dark rounded-circle"
                                                aria-hidden="true" style="width: 20px; height: 20px;"></span>
                                        </button>
                                    </div>
                                @else
                                    {{-- જો એક જ ઈમેજ હોય --}}
                                    <img src="{{ asset('storage/' . $product->imgs) }}" class="img-fluid h-100 w-auto"
                                        style="object-fit: contain;" alt="{{ $product->name }}">
                                @endif
                            </div>

                            {{-- બાકીનું કાર્ડ કન્ટેન્ટ (Body) --}}
                            <div class="card-body p-4">
                                <div class="mb-2">
                                    <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $product->name }}</h6>
                                    <span class="badge bg-light text-muted border fw-normal">Code:
                                        #{{ $product->p_code }}</span>
                                </div>

                                <div class="row g-2 mb-3 small text-center">
                                    <div class="col-6 border-end">
                                        <small class="text-muted d-block">MRP</small>
                                        <span class="text-decoration-line-through text-muted small">
                                            {{ company_info('currency', '₹') }}{{ number_format($product->mrp, 2) }}
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block text-success fw-bold">DP Price</small>
                                        <span class="fw-bold text-success">
                                            {{ company_info('currency', '₹') }}{{ number_format($product->dp, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="input-group input-group-sm mb-2">
                                    <input type="number" name="qty[]"
                                        class="form-control border-secondary-subtle product-qty" placeholder="Qty"
                                        min="0" max="{{ $product->qty }}" data-id="{{ $product->id }}">
                                    <input type="hidden" name="product_id[]" value="{{ $product->id }}"
                                        class="product-id-input" disabled>
                                    <button type="button" class="btn btn-dark btn-add-item">Add</button>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted">Stock: {{ $product->qty }} | BV:
                                        {{ $product->product_bv }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div
                class="sticky-bottom bg-white border-top p-3 mt-5 shadow-lg rounded-top d-flex justify-content-between align-items-center">
                <div class="text-muted small d-none d-md-block">
                    * Only products with an "Added" status will be submitted.
                </div>
                <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow">
                    ✅ Confirm & Submit Order
                </button>
            </div>

        </form>
    </div>

    <style>
        .product-card {
            transition: transform 0.2s, shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .bg-light-primary {
            background-color: #f1faff;
        }

        .selected-product {
            border: 2px solid #009ef7 !important;
            background-color: #f1faff;
        }

        .btn-add-item.added {
            background-color: #50cd89 !important;
            border-color: #50cd89 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButtons = document.querySelectorAll('.btn-add-item');
            const totalItemsBadge = document.getElementById('total_items');

            addButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.card');
                    const qtyInput = card.querySelector('.product-qty');
                    const idInput = card.querySelector('.product-id-input');
                    const qtyValue = parseInt(qtyInput.value);

                    if (isNaN(qtyValue) || qtyValue <= 0) {
                        qtyInput.classList.add('is-invalid');
                        return;
                    }

                    qtyInput.classList.remove('is-invalid');

                    if (this.innerText === 'Add') {
                        // Product Add કરવાની પ્રોસેસ
                        this.innerText = 'Added';
                        this.classList.add('added');
                        card.classList.add('selected-product');
                        idInput.disabled = false; // હવે આ પ્રોડક્ટ સબમિટ થશે
                        qtyInput.readOnly = true;
                    } else {
                        // Product Remove કરવાની પ્રોસેસ
                        this.innerText = 'Add';
                        this.classList.remove('added');
                        card.classList.remove('selected-product');
                        idInput.disabled = true; // હવે આ પ્રોડક્ટ સબમિટ નહીં થાય
                        qtyInput.readOnly = false;
                        qtyInput.value = '';
                    }

                    // અપડેટ કાઉન્ટર
                    const count = document.querySelectorAll('.btn-add-item.added').length;
                    totalItemsBadge.innerText = count;
                });
            });

            // Form validation before submit
            document.getElementById('orderForm').addEventListener('submit', function(e) {
                const count = document.querySelectorAll('.btn-add-item.added').length;
                if (count === 0) {
                    e.preventDefault();
                    alert('Please add at least one product to your order.');
                }
            });
        });
    </script>
@endsection
