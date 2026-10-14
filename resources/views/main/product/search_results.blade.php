@extends('main.layouts.app')
@section('content')

<section class="inner-section single-banner" style="background: url({{ asset('main/images/single-banner.jpg') }}) no-repeat center;">
    <div class="container">
        <h2>Search Results for: "{{ $query }}"</h2>
        <p>Found {{ $products->total() }} items</p>
    </div>
</section>

<section class="inner-section shop-part">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
            @forelse($products as $product)
                <div class="col">
                    <div class="product-card">
                        <div class="product-media">
                            <a href="{{ route('product.details', $product->slug) }}">
                                <img src="{{ asset('storage/' . $product->imgs) }}" alt="product">
                            </a>
                        </div>
                        <div class="product-content">
                            <h6><a href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a></h6>
                            <h6 class="product-price">
                                <span>{{ company_info('currancy', '₹') }}{{ $product->dp }}</span>
                            </h6>
                            </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h3>No products found for "{{ $query }}"</h3>
                    <a href="{{ route('home') }}" class="btn btn-inline mt-3">Back to Home</a>
                </div>
            @endforelse
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="bottom-pagination">
                    {{ $products->appends(['q' => $query])->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection