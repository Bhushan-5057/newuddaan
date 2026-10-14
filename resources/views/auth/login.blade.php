<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-assets-path="../../assets/"

    data-template="vertical-menu-template" data-theme="theme-default">

<head>

    <meta charset=" utf-8" />

    <meta name="viewport"

        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <!-- ============================================= -->
    <!-- START: ADVANCED SEO META TAGS                 -->
    <!-- ============================================= -->
    
    <!-- Primary Meta Tags -->
    <title>{{ company_info('company_name') }} Login - Secure Account Access | Herbal Wellness & Health Supplements</title>
    <meta name="description" content="Login to your {{ company_info('company_name') }} account to access premium herbal wellness products, Ayurvedic supplements, and manage your orders. Secure and easy sign-in.">
    <meta name="keywords" content="{{ company_info('company_name') }} login, herbal wellness login, ayurvedic supplements account, health products login, customer login, secure shopping login, wellness account access">
    <meta name="robots" content="index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Author & Copyright -->
    <meta name="author" content="{{ company_info('company_name') }}">
    <meta name="copyright" content="{{ date('Y') }} {{ company_info('company_name') }}. All Rights Reserved.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ company_info('company_name') }} Login - Secure Account Access">
    <meta property="og:description" content="Login to your {{ company_info('company_name') }} account. Shop herbal wellness products, Ayurvedic supplements, and manage your orders securely.">
    <meta property="og:image" content="{{ asset('uploads/' . company_info('logodark')) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ company_info('company_name') }}">
    <meta property="og:locale" content="en_IN">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ company_info('company_name') }} Login - Secure Account Access">
    <meta name="twitter:description" content="Login to your account for premium herbal wellness products, Ayurvedic supplements, and secure shopping at {{ company_info('company_name') }}.">
    <meta name="twitter:image" content="{{ asset('uploads/' . company_info('logodark')) }}">
    
    <!-- Mobile App Links -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- ============================================= -->
    <!-- END: ADVANCED SEO META TAGS                   -->
    <!-- ============================================= -->

    <title>{{ company_info('title') }} Login</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/' . company_info('favicon')) }}" type="image/png" />
    
    <!-- Additional Favicon for SEO/Browser Support -->
    <link rel="apple-touch-icon" href="{{ asset('uploads/' . company_info('favicon')) }}">
    <link rel="shortcut icon" href="{{ asset('uploads/' . company_info('favicon')) }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link

        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"

        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="../../assets/vendor/libs/@form-validation/umd/styles/index.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />

    <!-- ============================================= -->
    <!-- START: JSON-LD STRUCTURED DATA FOR LOGIN PAGE -->
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
                    "url": "{{ asset('uploads/' . company_info('logodark')) }}"
                },
                "description": "Premium herbal wellness products and Ayurvedic health supplements provider.",
                "email": "{{ company_info('company_email') }}",
                "telephone": "{{ company_info('company_mobile') }}",
                "address": {
                    "@type": "PostalAddress",
                    "addressCountry": "IN"
                }
            },
            {
                "@type": "WebPage",
                "name": "Login - {{ company_info('company_name') }}",
                "description": "Secure login page for {{ company_info('company_name') }} customers to access their accounts and shop herbal wellness products.",
                "url": "{{ url()->current() }}",
                "isPartOf": {
                    "@type": "WebSite",
                    "name": "{{ company_info('company_name') }}",
                    "url": "{{ url('/') }}"
                },
                "about": {
                    "@type": "Thing",
                    "name": "Customer Login"
                },
                "breadcrumb": {
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
                            "name": "Login",
                            "item": "{{ url()->current() }}"
                        }
                    ]
                }
            }
        ]
    }
    </script>
    <!-- ============================================= -->
    <!-- END: JSON-LD STRUCTURED DATA                 -->
    <!-- ============================================= -->

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
</head>

<body>

    <!-- Content -->



    <div class="container-xxl">

        <div class="authentication-wrapper authentication-basic container-p-y">

            <div class="authentication-inner py-4">

                <!-- Register -->

                <div class="card">

                    <div class="card-body">

                        <!-- Logo -->

                        <div class="app-brand justify-content-center">

                            <a href="{{ url('/') }}" class="app-brand-link gap-2" title="{{ company_info('company_name') }} - Home">
                                <img src="{{ asset('uploads/' . company_info('logodark')) }}" alt="{{ company_info('company_name') }} - Herbal Wellness Brand Logo"
                            style="width: {{ company_info('logo_width', '40px') }};"
                            height="{{ company_info('logo_height', '40px') }}">

                            </a>

                        </div>

                        <!-- /Logo -->

                        <h4 class="mb-2">Welcome to {{ company_info('company_name') }}</h4>

                        <p class="mb-4">Please sign-in to your account</p>



                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">

                            @csrf

                            <div class="mb-3">
                                <label for="email_username" class="form-label">Email or User Id</label>
                                

                                <!-- Email / Username Field -->
<input type="text" class="form-control" id="email_username" name="email_username"
    placeholder="Enter your email or username" autofocus
    value="{{ request('email_username') ?? session('auto_fill_email') ?? old('email_username') }}" />


                                
                          
                                @error('email_username')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="Password">Password</label>
                                
                                      <a href="{{ route('password.request') }}"  title="Reset your password">  <small>Forgot Password?</small></a>
                                </div>
                                <div class="input-group input-group-merge">
                                
                                        
                                        <!-- Password Field -->
<input type="password" id="password" class="form-control" name="password"
    placeholder="password" aria-describedby="password"
    value="{{ request('password') ?? session('auto_fill_password') }}" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">

                                <div class="form-check">

                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />

                                    <label class="form-check-label" for="remember-me"> Remember Me </label>

                                </div>

                            </div>

                            <div class="mb-3">

                                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>

                            </div>

                        </form>





                        <p class="text-center">

                            <span>New on our platform?</span>

                            <a href="{{ route('register') }}" title="Create a new account at {{ company_info('company_name') }}">

                                <span>Create an account</span>

                            </a>

                        </p>





                    </div>

                </div>

                <!-- /Register -->

            </div>

        </div>

    </div>



    <!-- / Content -->



    <!-- Core JS -->

    <!-- build:js assets/vendor/js/core.js -->



    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>

    <script src="../../assets/vendor/libs/popper/popper.js"></script>

    <script src="../../assets/vendor/js/bootstrap.js"></script>

    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../../assets/vendor/libs/hammer/hammer.js"></script>

    <script src="../../assets/vendor/libs/i18n/i18n.js"></script>

    <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>



    <!-- endbuild -->



    <!-- Vendors JS -->

    <script src="../../assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>

    <script src="../../assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>

    <script src="../../assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>



    <!-- Main JS -->

    <script src="../../assets/js/main.js"></script>



    <!-- Page JS -->

    <script src="../../assets/js/pages-auth.js"></script>

</body>

</html>