@extends('main.layouts.app')

{{-- ============================================================ --}}
{{-- START: SEO META TAGS & STRUCTURED DATA                      --}}
{{-- ============================================================ --}}
@section('meta')
    {{-- Primary Meta Tags --}}
    <title>
        @if(isset($product) && $product->slug)
            Buy {{ $product->name }} Online | Herbal Wellness & Health Supplements | New Uddan International
        @else
            New Uddan International Marketing - Herbal & Wellness Products
        @endif
    </title>
    <meta name="description" content="@if(isset($product) && $product->slug) Shop for {{ $product->name }}. Premium herbal wellness products, Ayurvedic health supplements, and high-BV nutrition. Best prices at New Uddan International Marketing Pvt Ltd. @else Discover top-quality herbal wellness, Ayurvedic supplements, and BV-based health products at New Uddan International Marketing. Shop now for authentic nutrition. @endif">
    <meta name="keywords" content="New Uddan International Marketing, herbal wellness products, buy ayurvedic supplements, health supplements India, BV products, nutrition store, herbal care, wellness products online, @if(isset($product) && $product->slug) {{ $product->name }}, @endif best health products">
    <meta name="robots" content="index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@if(isset($product) && $product->slug) Buy {{ $product->name }} Online - Herbal Wellness @else New Uddan International Marketing @endif">
    <meta property="og:description" content="@if(isset($product) && $product->slug) Buy {{ $product->name }} at best price. Herbal, Ayurvedic & wellness supplements from New Uddan International. @else Explore top herbal wellness and health supplements. Trusted by thousands. @endif">
    <meta property="og:image" content="@if(isset($product) && $product->imgs) {{ asset('storage/'.$product->imgs) }} @else {{ asset('main/images/logo/og-image.jpg') }} @endif">
    <meta property="og:site_name" content="New Uddan International Marketing Private Limited">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@if(isset($product) && $product->slug) {{ $product->name }} - New Uddan International @else New Uddan International - Herbal Wellness @endif">
    <meta name="twitter:description" content="Shop for authentic herbal wellness products, Ayurvedic supplements, and BV-based nutrition. New Uddan International Marketing Pvt Ltd.">
    <meta name="twitter:image" content="@if(isset($product) && $product->imgs) {{ asset('storage/'.$product->imgs) }} @else {{ asset('main/images/logo/twitter-image.jpg') }} @endif">

    {{-- JSON-LD Structured Data: Breadcrumb & Product Listing --}}
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@graph": [
            {
              "@type": "BreadcrumbList",
              "itemListElement": [
                {
                  "@type": "ListItem",
                  "position": 1,
                  "name": "Home",
                  "item": "{{ url('/') }}"
                },
                {
                  "@type": "ListItem",
                  "position": 2,
                  "name": "Products",
                  "item": "{{ url('/products') }}"
                }
                @if(isset($product) && $product->slug)
                ,{
                  "@type": "ListItem",
                  "position": 3,
                  "name": "{{ $product->name }}",
                  "item": "{{ url()->current() }}"
                }
                @endif
              ]
            },
            {
              "@type": "ItemList",
              "itemListElement": [
                @foreach($latestProducts->take(5) as $index => $item)
                {
                  "@type": "ListItem",
                  "position": {{ $loop->iteration }},
                  "name": "{{ $item->name }}",
                  "url": "{{ route('product.details', $item->slug) }}"
                }@if(!$loop->last),@endif
                @endforeach
              ],
              "name": "Featured Herbal & Wellness Products",
              "description": "Top-rated products from New Uddan International Marketing.",
              "url": "{{ url('/') }}"
            }
          ]
        }
    </script>
@endsection
{{-- ============================================================ --}}
{{-- END: SEO META TAGS & STRUCTURED DATA                        --}}
{{-- ============================================================ --}}


@section('content')


<style>
    .home-index-slider .banner-img img{
    max-width:100%;
    transition:0.4s;
    filter: drop-shadow(0 15px 25px rgba(0,0,0,.15));
}

.home-index-slider .banner-img img:hover{
    transform:scale(1.05);
}

.home-index-slider .banner-part{
    min-height:550px;
    display:flex;
    align-items:center;
}
</style>

        <!--=====================================
                    BANNER PART START
        =======================================-->
    <section class="home-index-slider slider-arrow slider-dots">

    @php
        $bgColors = [
            '#e1eee1',
            '#f9eada',
            '#e2edf3',
            '#f5e2ed',
            '#f7f0cf',
        ];
    @endphp

    @forelse($bannerProducts as $key => $product)

        @php
            $discount = $product->mrp > 0
                ? round((($product->mrp - $product->dp) / $product->mrp) * 100)
                : 0;

   
        @endphp

        <div class="banner-part"
            style="background: {{ $bgColors[$key % count($bgColors)] }}">

            <div class="container">
                <div class="row align-items-center">

                    {{-- Content --}}
                    <div class="col-md-6 col-lg-6">
                        <div class="banner-content">

                  

                            <h6 class="text-success fw-bold text-uppercase mb-3">
                                Herbal & Wellness Collection
                            </h6>

                            <h1>{{ $product->name }}</h1>

                            <p>
                                {{ Str::limit(strip_tags($product->p_dese), 120) }}
                            </p>

                            <div class="mb-4">
                                <span class="fw-bold fs-3 text-success">
                                    {{ company_info('currancy','₹') }}{{ $product->dp }}
                                </span>

                                <del class="ms-2 text-muted">
                                    {{ company_info('currancy','₹') }}{{ $product->mrp }}
                                </del>

                                @if($discount > 0)
                                    <span class="badge bg-danger ms-2">
                                        {{ $discount }}% OFF
                                    </span>
                                @endif

                                <div class="mt-2">
                                    <small class="text-dark">
                                        BV : {{ $product->product_bv }}
                                    </small>
                                </div>
                            </div>

                            <div class="banner-btn">

                         
                                    <a href="{{ route('login') }}"
                                        class="btn btn-inline">
                                        <i class="fas fa-shopping-basket"></i>
                                        <span>Shop Now</span>
                                    </a>
                        

                            </div>

                        </div>
                    </div>

                    {{-- Product Image --}}
                    <div class="col-md-6 col-lg-6">
                        <div class="banner-img text-center">
                            <img src="{{ asset('storage/'.$product->imgs) }}"
                                alt="Buy {{ $product->name }} Online - Herbal Wellness Product by New Uddan International"
                                title="{{ $product->name }} - Herbal Supplement"
                                style="max-height:400px;object-fit:contain;">
                        </div>
                    </div>

                </div>
            </div>
        </div>

    @empty

        <div class="banner-part">
            <div class="container text-center py-5">
                <h3>No Featured Products Found</h3>
            </div>
        </div>

    @endforelse

</section>
        <!--=====================================
                    BANNER PART END
        =======================================-->


<!--=====================================
            SUGGEST PART START
=======================================-->
<section class="section suggest-part mt-5">
    <div class="container">



        <ul class="suggest-slider slider-arrow">

            @foreach ($categories as $cat)

                <li class="ms-3">
                    <a class="suggest-card"
                        href="#">

                        <img src="{{ $cat->image
                            ? asset('storage/'.$cat->image)
                            : asset('main/images/category/groceries.jpg') }}"
                            alt="{{ $cat->name }} - Category for Health & Wellness Products">

                        <h5>
                            {{ $cat->name }}

                            <span>
                                {{ $cat->products_count ?? $cat->products->count() }}
                                Items
                            </span>
                        </h5>

                    </a>
                </li>

            @endforeach

        </ul>



    </div>
</section>
<!--=====================================
            SUGGEST PART END
=======================================-->


    <section class="section newitem-part">

                    <div class="container">

                        <div class="row">

                            <div class="col">

                                <div class="section-heading">

                                    <h2>Our Product</h2>

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
                                  
                                @endphp
                                <li>
                                    <div class="product-card">
                                        <div class="product-media">
                                      
                                     
                            
                                            <a class='product-image' href='{{ route('product.details', $product->slug) }}'>
                                                <img src="{{ asset('storage/' . $product->imgs) }}" 
                                                     alt="Buy {{ $product->name }} - Health Supplement & Wellness Product" 
                                                     title="{{ $product->name }} - Herbal Wellness">
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
                                            
                                  
                                  
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                                </ul>

                            </div>

                        </div>

              

                    </div>

                </section>


        <!--=====================================
                    PROMOTION PART START
        =======================================-->
        <div class="section promo-part">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="promo-img">
                            <a href="#"><img src="{{ asset('main/images/promo/home/03.jpg') }}" alt="Promotional Banner - New Uddan International Herbal Products"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--=====================================
                    PROMOTION PART END
        =======================================-->




<section class="section countdown-part">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <div class="section-heading">
                    <h2>Featured Products</h2>
                </div>
            </div>
        </div>

     @foreach($popularProducts->take(4) as $product)

    @php
        $discount = $product->mrp > 0
            ? round((($product->mrp - $product->dp) / $product->mrp) * 100)
            : 0;
    @endphp

    <div class="row align-items-center mb-5">

        @if($loop->odd)

            {{-- Product 1,3,5... --}}
            <div class="col-lg-6">
                <div class="countdown-content">

                    <h3>{{ $product->name }}</h3>

                    <p>{{ Str::limit(strip_tags($product->p_dese),120) }}</p>

                    <h4 class="mb-3">
                        <del class="text-muted">
                            {{ company_info('currancy','₹') }}{{ $product->mrp }}
                        </del>

                        <span class="text-success fw-bold ms-2">
                            {{ company_info('currancy','₹') }}{{ $product->dp }}
                        </span>

                        @if($discount > 0)
                            <span class="badge bg-danger ms-2">
                                {{ $discount }}% OFF
                            </span>
                        @endif
                    </h4>

                    <p class="fw-bold">
                        BV : {{ $product->product_bv }}
                    </p>

                   <a title="Quick View" href="javascript:void(0)" class="btn btn-inline"
                                                   onclick='openQuickView({!! json_encode($product) !!})'>
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                    </a>
                    
                  

                </div>
            </div>

            <div class="col-lg-5 offset-lg-1">
                <div class="countdown-img text-center">
                    <img src="{{ asset('storage/'.$product->imgs) }}"
                        alt="Featured Product: {{ $product->name }} - Buy Herbal Wellness Supplement Online"
                        title="{{ $product->name }} - Featured Product">

                    @if($discount > 0)
                        <div class="countdown-off">
                            <span>{{ $discount }}%</span>
                            <span>OFF</span>
                        </div>
                    @endif
                </div>
            </div>

        @else

            {{-- Product 2,4,6... --}}
            <div class="col-lg-5">
                <div class="countdown-img text-center">
                    <img src="{{ asset('storage/'.$product->imgs) }}"
                        alt="Featured Product: {{ $product->name }} - Ayurvedic Health Supplement"
                        title="{{ $product->name }} - Featured Product">

                    @if($discount > 0)
                        <div class="countdown-off">
                            <span>{{ $discount }}%</span>
                            <span>OFF</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6 offset-lg-1">
                <div class="countdown-content">

                    <h3>{{ $product->name }}</h3>

                    <p>{{ Str::limit(strip_tags($product->p_dese),120) }}</p>

                    <h4 class="mb-3">
                        <del class="text-muted">
                            {{ company_info('currancy','₹') }}{{ $product->mrp }}
                        </del>

                        <span class="text-success fw-bold ms-2">
                            {{ company_info('currancy','₹') }}{{ $product->dp }}
                        </span>

                        @if($discount > 0)
                            <span class="badge bg-danger ms-2">
                                {{ $discount }}% OFF
                            </span>
                        @endif
                    </h4>

                    <p class="fw-bold">
                        BV : {{ $product->product_bv }}
                    </p>
  <a title="Quick View" href="javascript:void(0)" class="btn btn-inline"
                                                   onclick='openQuickView({!! json_encode($product) !!})'>
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                    </a>
                    

                </div>
            </div>

        @endif

    </div>

@endforeach
    </div>
</section>





        <!--=====================================
                    PROMOTION PART START
        =======================================-->
        <!--<div class="section promo-part">-->
        <!--    <div class="container">-->
        <!--        <div class="row">-->
        <!--            <div class="col-sm-12 col-md-6 col-lg-6 px-xl-3">-->
        <!--                <div class="promo-img">-->
        <!--                    <a href="#"><img src="{{ asset('main/images/promo/home/01.jpg') }}" alt="promo"></a>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            <div class="col-sm-12 col-md-6 col-lg-6 px-xl-3">-->
        <!--                <div class="promo-img">-->
        <!--                    <a href="#"><img src="{{ asset('main/images/promo/home/02.jpg') }}" alt="promo"></a>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!--=====================================
                    PROMOTION PART END
        =======================================-->
















            @endsection