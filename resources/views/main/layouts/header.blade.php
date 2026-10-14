<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />



<head>
    <!--=====================================
                META TAG PART START
    =======================================-->
    <!-- REQUIRE META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- ============================================= -->
    <!-- START: ADVANCED SEO META TAGS                 -->
    <!-- ============================================= -->
    
    <!-- Primary Meta Tags -->
    <title>{{ company_info('company_name') }} - Herbal Wellness & Health Supplements | Buy Ayurvedic Products Online</title>
    <meta name="description" content="Discover premium herbal wellness products, Ayurvedic health supplements, and BV-based nutrition at {{ company_info('company_name') }}. Shop authentic herbal care products online. Best prices & quality guaranteed.">
    <meta name="keywords" content="{{ company_info('company_name') }}, herbal wellness products, ayurvedic supplements, health supplements India, BV products, herbal care, wellness products online, buy ayurvedic products, natural health supplements, herbal nutrition">
    <meta name="robots" content="index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Geo Location (if targeting specific region) -->
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    
    <!-- Author & Copyright -->
    <meta name="author" content="{{ company_info('company_name') }}">
    <meta name="copyright" content="{{ date('Y') }} {{ company_info('company_name') }}. All Rights Reserved.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ company_info('company_name') }} - Herbal Wellness & Health Supplements">
    <meta property="og:description" content="Shop premium herbal wellness products, Ayurvedic supplements, and BV-based nutrition at {{ company_info('company_name') }}. Trusted quality since inception.">
    <meta property="og:image" content="{{ asset('uploads/' . company_info('logo')) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ company_info('company_name') }}">
    <meta property="og:locale" content="en_IN">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ company_info('company_name') }} - Herbal Wellness & Health Supplements">
    <meta name="twitter:description" content="Shop authentic herbal wellness products, Ayurvedic supplements, and BV-based nutrition. Best quality health products at {{ company_info('company_name') }}.">
    <meta name="twitter:image" content="{{ asset('uploads/' . company_info('logo')) }}">
    
    <!-- Mobile App Links (Optional but good for SEO) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- ============================================= -->
    <!-- END: ADVANCED SEO META TAGS                   -->
    <!-- ============================================= -->

    <!-- TEMPLATE META -->
    <meta name="name" content="{{ company_info('company_name') }}">
    <meta name="title" content="{{ company_info('company_name') }}">
    <meta name="keywords" content="{{ company_info('company_name') }}">
    <!--=====================================
                META-TAG PART END
    =======================================-->

    <!-- WEBPAGE TITLE -->
    <title>{{ company_info('title') }}</title>

    <!--=====================================
                CSS LINK PART START
    =======================================-->

    <!-- FAVICON -->
    <link rel="icon" href="{{ asset('uploads/' . company_info('favicon')) }}">
    
    <!-- Additional Favicon for Better SEO/Browser Support -->
    <link rel="apple-touch-icon" href="{{ asset('uploads/' . company_info('favicon')) }}">
    <link rel="shortcut icon" href="{{ asset('uploads/' . company_info('favicon')) }}" type="image/x-icon">

    <!-- FONTS -->
    <link rel="stylesheet" href="{{ asset('main/fonts/flaticon/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('main/fonts/icofont/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('main/fonts/fontawesome/fontawesome.min.css') }}">

    <!-- VENDOR -->
    <link rel="stylesheet" href="{{ asset('main/vendor/venobox/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('main/vendor/slickslider/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('main/vendor/niceselect/nice-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('main/vendor/bootstrap/bootstrap.min.css') }}">

    <!-- CUSTOM -->
    <link rel="stylesheet" href="{{ asset('main/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('main/css/home-standard.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    
    
    <style>
    /* Add to Cart બટનને આકર્ષક બનાવવા */
    .product-add {
        background: #28a745 !important;
        /* ગ્રીન કલર */
        color: white !important;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        border: none;
        font-weight: bold;
        text-transform: uppercase;
    }

    .product-add:hover {
        background: #218838 !important;
    }
</style>
<style>
    .qty-box {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 12px 0;
    }

    .qty-btn {
        width: 42px;
        height: 38px;
        border: 2px solid #28a745;
        background: #fff;
        color: #28a745;
        font-size: 20px;
        font-weight: bold;
        border-radius: 6px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .qty-btn:hover {
        background: #28a745;
        color: #fff;
    }

    .qty-input {
        width: 50px;
        height: 38px;
        border: 2px solid #28a745;
        text-align: center;
        font-weight: bold;
        font-size: 15px;
        border-radius: 6px;
        background: #fff;
    }
</style>

    <!-- ============================================= -->
    <!-- START: JSON-LD STRUCTURED DATA               -->
    <!-- ============================================= -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "name": "{{ company_info('company_name') }}",
                "url": "{{ url('/') }}",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{ asset('uploads/' . company_info('logo')) }}"
                },
                "description": "Premium herbal wellness products, Ayurvedic health supplements, and BV-based nutrition provider.",
                "email": "{{ company_info('company_email') }}",
                "telephone": "{{ company_info('company_mobile') }}",
                "address": {
                    "@type": "PostalAddress",
                    "addressCountry": "IN"
                },
                "sameAs": [
                    "https://www.facebook.com/{{ company_info('company_name') }}",
                    "https://www.instagram.com/{{ company_info('company_name') }}",
                    "https://www.youtube.com/{{ company_info('company_name') }}"
                ]
            },
            {
                "@type": "WebSite",
                "name": "{{ company_info('company_name') }}",
                "url": "{{ url('/') }}",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "{{ url('/') }}/search?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                },
                "description": "Shop herbal wellness products, Ayurvedic supplements, and health nutrition online."
            },
            {
                "@type": "BreadcrumbList",
                "itemListElement": [
                    {
                        "@type": "ListItem",
                        "position": 1,
                        "name": "Home",
                        "item": "{{ url('/') }}"
                    }
                    @if(request()->path() != '/')
                    ,{
                        "@type": "ListItem",
                        "position": 2,
                        "name": "{{ ucfirst(str_replace('-', ' ', request()->path())) }}",
                        "item": "{{ url()->current() }}"
                    }
                    @endif
                ]
            }
        ]
    }
    </script>
    <!-- ============================================= -->
    <!-- END: JSON-LD STRUCTURED DATA                 -->
    <!-- ============================================= -->

    <!--=====================================
                CSS LINK PART END
    =======================================-->
</head>

<body>
    <div class="backdrop"></div>
    <a class="backtop fas fa-arrow-up" href="#"></a>

    {{-- <div class="header-top alert fade show">
        <p>20% Discount All New Customers <a href='register.html'>get register</a></p>
        <button data-bs-dismiss="alert"><i class="fas fa-times"></i></button>
    </div> --}}


    <!--=====================================
                    HEADER PART START
        =======================================-->
    <header class="header-part">
        <div class="container">
            <div class="header-content">
                <div class="header-media-group">
                    <button class="header-user">
                        <i class="mdi mdi-menu" style="font-size:24px"></i>
                    </button>
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="{{ company_info('company_name') }} - Herbal Wellness Brand Logo">
                    </a>

                    <button class="header-src">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <a class="header-logo" href="{{ url('/') }}">
                    <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="{{ company_info('company_name') }} Logo - Buy Herbal Products Online">
                </a>

                @auth('web')
                <a class="header-widget" href="{{ route('dashboard.user') }}" title="My Dashboard">
                    @if (Auth::user()->profile_image)
                    <img alt="{{ Auth::user()->name }} Profile Avatar" class="img-fluid b-r-10"
                        src="{{ asset('storage/' . Auth::user()->profile_image) }}">
                    @else
                    <img alt="User Avatar Placeholder" class="img-fluid b-r-10" src="{{ asset('assets/images/2.png') }}">
                    @endif

                    <span>dashboard</span>
                </a>
                @else
                <a class="header-widget" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#login-modal"
                    title="My Account">
                    <img src="{{ asset('assets/images/2.png') }}" alt="Login or Join {{ company_info('company_name') }}">
                    <span>join</span>
                </a>
                @endauth
                <form class="header-form" action="{{ route('product.search') }}" method="GET"
                    style="position: relative;">
                    <input type="text" id="search-input" name="q" placeholder="Search anything..."
                        autocomplete="off" aria-label="Search products">
                    <button type="submit" aria-label="Search"><i class="fas fa-search"></i></button>

                    <div id="suggestion-box"
                        style="position: absolute; width: 100%; background: white; z-index: 999; top: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 0 0 8px 8px; display: none;">
                        <ul id="suggestion-list" class="list-group list-group-flush"></ul>
                    </div>
                </form>
                

                    
                    <div class="header-widget-group">
                    
                        <a class="header-widget" href="{{ route('login') }}" title="Login to {{ company_info('company_name') }}">
                            <i class="fas fa-sign-in-alt"></i>
                        </a>
                    
                        <a class="header-widget" href="{{ route('register') }}" title="Register at {{ company_info('company_name') }}">
                            <i class="fas fa-user-plus"></i>
                        </a>
                    
                    </div>
               {{-- <div class="header-widget-group">
                    @auth('web')
                    <a class="header-widget" href="{{ route('wishlist.index') }}" title="Wishlist">
                        <i class="fas fa-heart"></i>
                        <sup
                            class="ajax-wish-count">{{ \App\Models\Wishlist::where('u_code', auth('web')->id())->count() }}</sup>
                    </a>

                    <button class="header-widget header-cart" title="Cartlist">
                        <i class="fas fa-shopping-basket"></i>
                        <sup class="ajax-cart-count">0</sup>
                        <span>
                            total price
                            <small class="ajax-cart-total">{{ company_info('currancy', '₹') }} 0.00</small>
                        </span>
                    </button>
                    @else
                    <a class="header-widget" href="javascript:void(0)" data-bs-toggle="modal"
                        data-bs-target="#login-modal">
                        <i class="fas fa-heart"></i><sup>0</sup>
                    </a>
                    <button class="header-widget header-cart" data-bs-toggle="modal" data-bs-target="#login-modal">
                        <i class="fas fa-shopping-basket"></i><sup>0</sup>
                        <span>total price<small>₹ 0.00</small></span>
                    </button>
                    @endauth
                </div>  --}}
            </div>
        </div>
    </header>

    <!--=====================================
                    HEADER PART END
        =======================================-->


    <!--=====================================
                    NAVBAR PART START
        =======================================-->
    <nav class="navbar-part" aria-label="Main Navigation">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="navbar-content">
                        <ul class="navbar-list">

                            <li class="navbar-item">
                                <a class="navbar-link" href="{{ route('home') }}">home</a>
                            </li>
                            
                             @auth('web')

                            <li class="navbar-item dropdown">
                                <a class="navbar-link dropdown-arrow" href="#">shop</a>
                                <ul class="dropdown-position-list">
                                    <li><a href="{{ route('product.shop') }}">All Products</a></li>
                                    @foreach ($categories->take(5) as $cat)
                                    <li><a
                                            href="{{ route('category.view', $cat->slug) }}">{{ $cat->name }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                              @endauth

                            <li class="navbar-item dropdown">
                                <a class="navbar-link dropdown-arrow" href="#">my account</a>
                                <ul class="dropdown-position-list">
                                    @auth('web')
                                    <li><a href="{{ route('user.profile') }}">My Profile</a></li>
                                    <li><a href="{{ route('user.product.history') }}">Order History</a></li>
                                    <li><a href="{{ route('cart.index') }}">My Cart</a></li>
                                    <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                                    <li>
                                        <a href="javascript:void(0)"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                        <form id="logout-form" action="{{ route('user.logout') }}" method="POST"
                                            style="display: none;">@csrf</form>
                                    </li>
                                    @else
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                    <li><a href="{{ route('register') }}">Register</a></li>
                                    @endauth
                                </ul>
                            </li>

                            <li class="navbar-item">
                                <a class="navbar-link" href="{{ route('about.index') }}">about us</a>
                            </li>

                            <li class="navbar-item">
                                <a class="navbar-link" href="{{ route('contact.index') }}">contact us</a>
                            </li>
                         

                        </ul>
                        
                                <div class="navbar-info-group">
                                <div class="navbar-info">
                                    <i class="icofont-ui-touch-phone"></i>
                                    <p>
                                        <small>call us</small>
                                        <span>{{ company_info('company_mobile') }}</span>
                                    </p>
                                </div>
                                <div class="navbar-info">
                                    <i class="icofont-ui-email"></i>
                                    <p>
                                        <small>email us</small>
                                        <span>{{ company_info('company_email') }}</span>
                                    </p>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!--=====================================
                    NAVBAR PART END
        =======================================-->



    <!--=====================================
                CATEGORY SIDEBAR PART START
        =======================================-->
    <aside class="category-sidebar" aria-label="Product Categories">
        <div class="category-header">
            <h4 class="category-title">
                <i class="fas fa-align-left"></i>
                <span>categories</span>
            </h4>
            <button class="category-close"><i class="icofont-close"></i></button>
        </div>

        <ul class="category-list">
            @foreach ($categories as $cat)
            <li class="category-item">
                <a class="category-link {{ $cat->subCategories->count() > 0 ? 'dropdown-link' : '' }}"
                    href="{{ $cat->subCategories->count() > 0 ? 'javascript:void(0)' : route('category.view', $cat->slug) }}">

                    @if ($cat->icon)
                    <img src="{{ asset('storage/' . $cat->icon) }}" width="20"
                        style="margin-right: 10px;" alt="{{ $cat->name }} Category Icon">
                    @else
                    <i class="flaticon-vegetable"></i>
                    @endif

                    <span>{{ $cat->name }}</span>
                </a>

                @if ($cat->subCategories->count() > 0)
                <ul class="dropdown-list">
                    <li>
                        <a href="{{ route('category.view', $cat->slug) }}" style="font-weight: bold;">
                            All {{ $cat->name }}
                        </a>
                    </li>

                    @foreach ($cat->subCategories as $sub)
                    <li>
                        <a href="{{ route('subcategory.view', $sub->slug) }}">
                            {{ $sub->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>

        <div class="category-footer">
            <p>&copy; All Rights Reserved by <a href="#">{{ company_info('company_name') }}</a></p>
        </div>
    </aside>
    <!--=====================================
                CATEGORY SIDEBAR PART END
        =======================================-->


    <!--=====================================
                  CART SIDEBAR PART START
        =======================================-->

    <div id="cart-sidebar-wrapper">
        @include('main.partials.cart_sidebar')
    </div>

    <!--=====================================
                    CART SIDEBAR PART END
        =======================================-->


    <!--=====================================
                  NAV SIDEBAR PART START
        =======================================-->
    <aside class="nav-sidebar" aria-label="Mobile Navigation">
        <div class="nav-header">
            <a href="{{ route('home') }}">
                <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="{{ company_info('company_name') }} - Home">
            </a>
            <button class="nav-close"><i class="icofont-close"></i></button>
        </div>

        <div class="nav-content">
            @auth('web')
            <div class="nav-profile">
                <a class="nav-user" href="{{ route('user.profile') }}">
                        @if (Auth::user()->profile_image)
                    <img alt="{{ Auth::user()->name }} Profile Photo" class="img-fluid b-r-10"
                        src="{{ asset('storage/' . Auth::user()->profile_image) }}">
                    @else
                    <img alt="Default User Avatar" class="img-fluid b-r-10" src="{{ asset('assets/img/avatars/3.png') }}">
                    @endif
                    <!-- <img src="{{ asset('main/images/user.png') }}" alt="user"> -->
                </a>
                <h4 class="nav-name"><a href="{{ route('user.profile') }}">{{ auth('web')->user()->name }}</a></h4>
            </div>
            @else
            <div class="nav-btn">
                <a class="btn btn-inline" href="{{ route('login') }}">
                    <i class="fa fa-unlock-alt"></i><span>join here</span>
                </a>
            </div>
            @endauth

            <ul class="nav-list">
                <li><a class="nav-link" href="{{ route('home') }}"><i class="icofont-home"></i>Home</a></li>
                <li><a class="nav-link" href="{{ route('product.shop') }}"><i class="icofont-food-cart"></i>Shop
                        All</a></li>

                <li>
                    <a class="nav-link dropdown-link" href="#"><i class="icofont-bag-alt"></i>My Account</a>
                    <ul class="dropdown-list">


                        <li><a href="{{ route('user.profile') }}">My Profile</a></li>
                        <li><a href="{{ route('user.product.history') }}">Order History</a></li>
                        <li><a href="{{ route('cart.index') }}">My Cart</a></li>
                        <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                    </ul>
                </li>


                </li>

                </li>
                <li><a class="nav-link" href="{{ url('/contact') }}"><i class="icofont-contacts"></i>Contact Us</a>
                </li>

                @auth('web')
                <li>
                    <a class="nav-link" href="javascript:void(0)"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="icofont-logout"></i>Logout
                    </a>
                </li>
                @endauth
            </ul>

            <div class="nav-info-group">
                <div class="nav-info">
                    <i class="icofont-ui-touch-phone"></i>
                    <p><small>call us</small><span>{{ company_info('mobile') }}</span></p>
                </div>
            </div>

            <div class="nav-footer">
                <p>&copy; All Rights Reserved by <a href="#">{{ company_info('company_name') }}</a></p>
            </div>
        </div>
    </aside>


    <!--=====================================
                  NAV SIDEBAR PART END
        =======================================-->


    <!--=====================================
                    MOBILE-MENU PART START
        =======================================-->
    <div class="mobile-menu">
        <a href="{{ route('home') }}" title="Home Page">
            <i class="fas fa-home"></i><span>Home</span>
        </a>
        <button class="cate-btn" title="Category List">
            <i class="fas fa-list"></i><span>category</span>
        </button>
        <button class="cart-btn" title="Cartlist">
            <i class="fas fa-shopping-basket"></i><span>cart</span>
            <sup class="ajax-cart-count">0</sup>
        </button>
        <a href="{{ url('/wishlist') }}" title="Wishlist">
            <i class="fas fa-heart"></i><span>wishlist</span>
            <sup class="ajax-wish-count">0</sup>
        </a>
        <a href="{{ url('/compare') }}" title="Compare List">
            <i class="fas fa-random"></i><span>compare</span>
            <sup class="ajax-compare-count">0</sup>
        </a>
    </div>

    <!--=====================================
                    MOBILE-MENU PART END
        =======================================-->


    <!--=====================================
                    PRODUCT VIEW START
        =======================================-->

    <!--=====================================
                    PRODUCT VIEW END
        =======================================-->