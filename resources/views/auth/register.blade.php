<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    
    <!-- ============================================= -->
    <!-- START: ADVANCED SEO META TAGS                 -->
    <!-- ============================================= -->
    
    <!-- Primary Meta Tags -->
    <title>{{ company_info('company_name') }} Register - Create Account | Herbal Wellness & Health Supplements</title>
    <meta name="description" content="Create your {{ company_info('company_name') }} account to shop premium herbal wellness products, Ayurvedic supplements, and start your health journey. Quick and secure registration.">
    <meta name="keywords" content="{{ company_info('company_name') }} register, create account, herbal wellness signup, ayurvedic supplements registration, health products account, new user registration, wellness account, join now">
    <meta name="robots" content="index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Author & Copyright -->
    <meta name="author" content="{{ company_info('company_name') }}">
    <meta name="copyright" content="{{ date('Y') }} {{ company_info('company_name') }}. All Rights Reserved.">
    
    <!-- Geo Location -->
    <meta name="geo.region" content="IN">
    <meta name="geo.placename" content="India">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ company_info('company_name') }} Register - Create Your Account">
    <meta property="og:description" content="Join {{ company_info('company_name') }} today. Access premium herbal wellness products, Ayurvedic supplements, and exclusive health offers.">
    <meta property="og:image" content="{{ asset('uploads/' . company_info('logodark')) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ company_info('company_name') }}">
    <meta property="og:locale" content="en_IN">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ company_info('company_name') }} Register - Create Your Account">
    <meta name="twitter:description" content="Create your account for premium herbal wellness products, Ayurvedic supplements, and secure shopping at {{ company_info('company_name') }}.">
    <meta name="twitter:image" content="{{ asset('uploads/' . company_info('logodark')) }}">
    
    <!-- Mobile App Links -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    
    <!-- ============================================= -->
    <!-- END: ADVANCED SEO META TAGS                   -->
    <!-- ============================================= -->
    
    <title>{{ company_info('title') }} Register</title>
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
    <link rel="stylesheet" href="../../assets/vendor/libs/@form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
    
    <!-- ============================================= -->
    <!-- START: JSON-LD STRUCTURED DATA FOR REGISTER   -->
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
                },
                "sameAs": [
                    "https://www.facebook.com/{{ company_info('company_name') }}",
                    "https://www.instagram.com/{{ company_info('company_name') }}",
                    "https://www.youtube.com/{{ company_info('company_name') }}"
                ]
            },
            {
                "@type": "WebPage",
                "name": "Register - {{ company_info('company_name') }}",
                "description": "Create a new account at {{ company_info('company_name') }} to access premium herbal wellness products, Ayurvedic supplements, and health nutrition.",
                "url": "{{ url()->current() }}",
                "isPartOf": {
                    "@type": "WebSite",
                    "name": "{{ company_info('company_name') }}",
                    "url": "{{ url('/') }}"
                },
                "about": {
                    "@type": "Thing",
                    "name": "User Registration"
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
                            "name": "Register",
                            "item": "{{ url()->current() }}"
                        }
                    ]
                },
                "potentialAction": {
                    "@type": "CreateAction",
                    "target": "{{ route('register') }}",
                    "description": "Register for a new account"
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
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <script src="../../assets/js/config.js"></script>
</head>

<body>
    <style>
    
    .authentication-wrapper.authentication-basic .authentication-inner {
        max-width: 56rem !important;
        position: relative;
    }

        .iti--allow-dropdown input, .iti--allow-dropdown input[type=text], .iti--separate-dial-code input,
        .iti--separate-dial-code input[type=tel], .iti--separate-dial-code input[type=text] {
            padding-right: 100px !important;
        }
        .passwordValidation { font-size: 13px; }
        .fv-plugins-message-container [data-field="password"] { display: none !important; }

        /* --- Agreement card --- */
        .agreement-card {
            max-width: 900px;
            margin: 30px auto 20px;
            padding: 40px 45px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-height: 480px;
            overflow-y: auto;
            font-family: "IBM Plex Sans", sans-serif;
            color: #222;
            line-height: 1.6;
        }
        .agreement-card h2 { text-align: center; font-weight: 700; letter-spacing: .5px; margin-bottom: 6px; }
        .agreement-card .cin { text-align: center; font-weight: 600; margin-bottom: 22px; }
        .agreement-card .office-block { margin-bottom: 22px; }
        .agreement-card .office-block strong { display: block; margin-bottom: 4px; }
        .agreement-card h3.title {
            text-align: center;
            font-style: italic;
            text-decoration: underline;
            font-weight: 700;
            margin: 20px 0 22px;
        }
        .agreement-card p { margin-bottom: 16px; text-align: justify; }
        .agreement-card .party-line { margin-bottom: 4px; }
        .agreement-card .and-sep { text-align: center; font-weight: 600; margin: 14px 0; }
        .agreement-card ol { padding-left: 22px; }
        .agreement-card ol li { margin-bottom: 12px; text-align: justify; }
        .agreement-card ol li strong { font-weight: 600; }

        /* --- City autocomplete --- */
        .city-suggest-wrapper { position: relative; }
        .city-suggest-list {
            position: absolute;
            z-index: 50;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #d9d9d9;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            border-radius: 0 0 4px 4px;
        }
        .city-suggest-list div {
            padding: 6px 12px;
            cursor: pointer;
        }
        .city-suggest-list div:hover { background: #f1f1f1; }

        /* Error styling */
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .is-valid {
            border-color: #28a745 !important;
        }
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
        }
        
        .input-group-text {
    cursor: pointer;
}
.input-group-text i {
    pointer-events: none;
}
    </style>

    <!-- Agreement Card -->
    <div class="agreement-card">
        <h2>{{ company_info('company_name') }}</h2>
        <div class="cin">CIN: {{ company_info('bill_company_cin') }}</div>

        <div class="office-block">
            <strong>Registered Office:</strong>
            {{ company_info('first_address') }}
           
            <!--Website: <a href="https://www.avikinternational.com" target="_blank">www.avikinternational.com</a>-->
        </div>

        <h3 class="title">AGREEMENT</h3>

        <p>
            This agreement is signed on ____________________ between {{ company_info('company_name') }}., a company
            registered under the Companies Act 2013 having its Registered office  {{ company_info('first_address') }} acting through its Director (<strong>{{ company_info('owner_name') }}</strong>)
            hereinafter called Company which expression shall, unless repugnant to the context, include its successors in
            business, administrators, liquidators and assigns or legal representatives) of the FIRST PARTY
        </p>

        <div class="and-sep">AND</div>

        <p class="party-line">
            Shri/Smt. ______________________________________ aged ______ years S/o D/o W/o ________________________
        </p>
        <p class="party-line">
            house_______________ post ____________________ street __________________________________ Tehsil
        </p>
        <p>
            _______________________________ District _____________________________ State ________________________
            (hereinafter called as Direct Seller which expression shall include my/our heirs, executors and administrators
            estates assigns and effects wherein the context so admits or requires) of the second party.
        </p>

        <p><strong>Definitions: -</strong></p>
        <p>The following words used in these presents shall have the meaning as defined here under:</p>

        <ol>
            <li><strong>&ldquo;ACT&rdquo;</strong> means the Consumer Protection Act, 1986 (68 of 1986).</li>
            <li><strong>&ldquo;Consumer&rdquo;</strong> &ndash; means who buys goods or services for personal (self) use
                and not for resale or commercial purpose and shall have the same meaning as provided under the Consumer
                Protection Act, 1986.</li>
            <li><strong>&ldquo;Prospect&rdquo;</strong> means a person to whom an offer or a proposal is made by the
                direct seller to join a direct selling opportunity.</li>
            <li><strong>&ldquo;Direct seller&rdquo;</strong> means a person appointed or authorized directly or
                indirectly by a Direct Selling Entity through a legally enforceable written contract to undertake direct
                selling business on principal to principal basis.</li>
            <li><strong>&ldquo;Network of Direct selling&rdquo;</strong> means a network of direct sellers at different
                levels of distribution who may recruit or introduce or sponsor further level of direct sellers who they
                then support: Explanation: &ldquo;Network of Direct selling&rdquo; shall mean any system of distribution
                or marketing adopted by a direct selling entity to undertake direct selling business and shall include
                the multi-level marketing method of distribution.</li>
            <li><strong>&ldquo;Direct selling&rdquo;</strong> means marketing, distribution and sale of Goods or
                providing of services as a part of network of Direct selling other than under a pyramid scheme. Provided
                that such sale of goods or services occurs otherwise than through a permanent retail location to the
                consumers, generally in their houses or at their workplace or through explanation and demonstration of
                such goods and services at a particular place.</li>
            <li><strong>&ldquo;Direct selling Entity&rdquo;</strong> means an entity not being engaged in a pyramid
                scheme, which sells or offers to sell goods or services through a direct seller. Provided that &ldquo;Direct
                selling Entity&rdquo; does not include any entity or business notified otherwise by the Government for the
                said purpose from time to time.</li>
        </ol>

        <p class="text-muted" style="font-size: 13px;">
            (This agreement is shown for reference/display purposes only and is not an editable or signable part of the
            registration form below.)
        </p>
    </div>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Register Card -->
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

                        <!-- Display General Errors -->
                        @if ($errors->any() && !$errors->has('title') && !$errors->has('first_name') && !$errors->has('unique_name') && !$errors->has('password') && !$errors->has('sponsor_display') && !$errors->has('side'))
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-3" action="{{ route('register') }}" method="POST" novalidate>
                            @csrf

                            <div class="row">
                                <!-- Title -->
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <select name="title" id="title" class="form-control @error('title') is-invalid @enderror" required>
                                        <option value="">Select</option>
                                        <option value="Mr." {{ old('title') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                        <option value="Mrs." {{ old('title') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                        <option value="Ms." {{ old('title') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                        <option value="Dr." {{ old('title') == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                    </select>
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- First Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                        id="first_name" name="first_name"
                                        placeholder="First Name" value="{{ old('first_name') }}" required>
                                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                        id="last_name" name="last_name"
                                        placeholder="Last Name" value="{{ old('last_name') }}">
                                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- State -->
                                <div class="col-md-6 mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <select name="state" id="state" class="form-control @error('state') is-invalid @enderror">
                                        <option value="">-- Select State --</option>
                                    </select>
                                    @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- City -->
                                <div class="col-md-6 mb-3 city-suggest-wrapper">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                        id="city" name="city"
                                        placeholder="City" value="{{ old('city') }}" autocomplete="off">
                                    <div id="citySuggestList" class="city-suggest-list"></div>
                                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Mobile -->
                                <div class="col-md-6 mb-3">
                                    <label for="mobile" class="form-label">Mobile No.</label>
                                    <input type="tel" inputmode="numeric" pattern="[0-9]*"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        class="form-control @error('mobile') is-invalid @enderror" 
                                        id="mobile" name="mobile" placeholder="Mobile No."
                                        maxlength="15" value="{{ old('mobile') }}">
                                    @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                           



                                <!-- Email - FIXED: Removed required validation -->
                               {{-- <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Id</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        id="email" name="email"
                                        placeholder="Email Id" value="{{ old('email') }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>  --}}


                                <!-- PAN -->
                                <div class="col-md-6 mb-3">
                                    <label for="pan" class="form-label">PAN</label>
                                    <input type="text" class="form-control @error('pan') is-invalid @enderror" 
                                        id="pan" name="pan"
                                        placeholder="PAN" maxlength="10" style="text-transform: uppercase;"
                                        value="{{ old('pan') }}" autocomplete="off">
                                    <div id="panFeedback" class="mt-1 passwordValidation"></div>
                                    @error('pan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Aadhaar -->
                                <div class="col-md-6 mb-3">
                                    <label for="aadhaar" class="form-label">Aadhar Number</label>
                                    <input type="text" class="form-control @error('aadhaar') is-invalid @enderror" 
                                        id="aadhaar" name="aadhaar"
                                        placeholder="Aadhar Number" maxlength="14"
                                        value="{{ old('aadhaar') }}" autocomplete="off">
                                    <div id="aadhaarFeedback" class="mt-1 passwordValidation"></div>
                                    @error('aadhaar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Unique Name (Username) -->
                                <div class="col-md-6 mb-3">
                                    <label for="unique_name" class="form-label">Username *</label>
                                    <input type="text" class="form-control @error('unique_name') is-invalid @enderror" 
                                        id="unique_name" name="unique_name"
                                        placeholder="Enter a unique name" value="{{ old('unique_name') }}" 
                                        autocomplete="off" required>
                                    <div id="uniqueNameFeedback" class="mt-1 passwordValidation"></div>
                                    @error('unique_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Sponsor ID -->
                                <div class="col-md-6 mb-3">
                                    <label for="sponsor_display" class="form-label">Sponsor Id{{ ($isSponsorRequired ?? false) ? ' *' : '' }}</label>
                                    <input type="text" id="sponsor_display" name="sponsor_display"
                                        class="form-control @error('sponsor_display') is-invalid @enderror"
                                        value="{{ old('sponsor_display', $referrer ?? '') }}"
                                        {{ ($isSponsorRequired ?? false) ? 'required' : '' }} autocomplete="off">
                                    <div id="sponsor-name" class="form-text mt-1"></div>
                                    @error('sponsor_display') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Parent (hidden, from referral link) --}}
                                @if (($RegistrationType ?? null) == 'binary' && !empty($refParent))
                                    <input type="hidden" name="parent" value="{{ $refParent }}">
                                @endif

                                <!-- Placement -->
                                @if (($RegistrationType ?? null) == 'binary')
                                    <div class="col-md-6 mb-3">
                                        <label for="side" class="form-label">Placement *</label>
                                        @if (!empty($refSide))
                                            <input type="hidden" name="side" value="{{ $refSide }}">
                                            <input type="text" class="form-control" value="{{ ucfirst($refSide) }}" readonly>
                                        @elseif (!empty($sponsorDefaultSide))
                                            <input type="hidden" name="side" value="{{ $sponsorDefaultSide }}">
                                            <input type="text" class="form-control" value="{{ ucfirst($sponsorDefaultSide) }}" readonly>
                                        @else
                                            <select name="side" id="side" class="form-control @error('side') is-invalid @enderror" required>
                                                <option value="">Select</option>
                                                <option value="left" {{ old('side') == 'left' ? 'selected' : '' }}>Left</option>
                                                <option value="right" {{ old('side') == 'right' ? 'selected' : '' }}>Right</option>
                                            </select>
                                        @endif
                                        @error('side') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                @endif

                                <!-- Password -->
                                <div class="col-md-6 mb-3 form-password-toggle">
                                    <label class="form-label" for="password">Password *</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                                            name="password"
                                            placeholder="Login Password*" aria-describedby="password" 
                                            autocomplete="new-password" required>
                                        <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <div id="passwordFeedback" class="mt-1 passwordValidation"></div>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6 mb-3 form-password-toggle">
                                    <label class="form-label" for="password_confirmation">Confirm Password *</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" placeholder="Confirm Login Password*"
                                            aria-describedby="password_confirmation" autocomplete="new-password" required>
                                        <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <div id="confirmFeedback" class="mt-1 passwordValidation"></div>
                                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                        type="checkbox" id="terms-conditions" name="terms" required/>
                                    <label class="form-check-label" for="terms-conditions">
                                        I agree to
                                        <a href="javascript:void(0);">privacy policy & terms</a>
                                    </label>
                                    @error('terms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <button class="btn btn-primary d-grid w-100 mt-3" type="submit">Sign up</button>
                        </form>

                        <p class="text-center">
                            <span>Already have an account?</span>
                            <a href="{{ route('login') }}" title="Sign in to your {{ company_info('company_name') }} account"><span>Sign in instead</span></a>
                        </p>
                    </div>
                </div>
                <!-- /Register Card -->
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="../../assets/vendor/js/menu.js"></script>
    <script src="../../assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
    <script src="../../assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="../../assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/pages-auth.js"></script>

    <script>
        // ==========================================================
        // Country State City — free online API
        // ==========================================================
        const CSC_API_BASE = "https://countriesnow.space/api/v0.1";
        const CSC_COUNTRY = "India";
        let CSC_CITIES_CACHE = [];

        function cscLoadStates() {
            const stateSelect = document.getElementById('state');
            const selectedState = "{{ old('state') }}";

            fetch(`${CSC_API_BASE}/countries/states`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ country: CSC_COUNTRY })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error || !data.data || !data.data.states) return;
                data.data.states.forEach(function (s) {
                    const opt = document.createElement('option');
                    opt.value = s.name;
                    opt.textContent = s.name;
                    if (s.name === selectedState) opt.selected = true;
                    stateSelect.appendChild(opt);
                });
                if (selectedState) cscLoadCities(selectedState);
            })
            .catch(() => {
                console.warn('Could not load states from Country State City API.');
            });
        }

        function cscLoadCities(state) {
            const suggestBox = document.getElementById('citySuggestList');
            if (!state) {
                CSC_CITIES_CACHE = [];
                suggestBox.style.display = 'none';
                return;
            }
            fetch(`${CSC_API_BASE}/countries/state/cities`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ country: CSC_COUNTRY, state: state })
            })
            .then(res => res.json())
            .then(data => {
                CSC_CITIES_CACHE = (!data.error && Array.isArray(data.data)) ? data.data : [];
            })
            .catch(() => {
                CSC_CITIES_CACHE = [];
                console.warn('Could not load cities from Country State City API.');
            });
        }

        document.addEventListener("DOMContentLoaded", cscLoadStates);

        // ==========================================================
        // City suggestion box
        // ==========================================================
        (function () {
            const cityInput = document.getElementById('city');
            const suggestBox = document.getElementById('citySuggestList');
            const stateSelect = document.getElementById('state');

            function renderSuggestions(term) {
                if (!stateSelect.value || !CSC_CITIES_CACHE.length) {
                    suggestBox.style.display = 'none';
                    return;
                }
                const lower = term.toLowerCase();
                const matches = CSC_CITIES_CACHE
                    .filter(city => city.toLowerCase().includes(lower))
                    .slice(0, 15);

                suggestBox.innerHTML = '';
                if (!matches.length) {
                    suggestBox.style.display = 'none';
                    return;
                }
                matches.forEach(function (city) {
                    const div = document.createElement('div');
                    div.textContent = city;
                    div.addEventListener('click', function () {
                        cityInput.value = city;
                        suggestBox.style.display = 'none';
                    });
                    suggestBox.appendChild(div);
                });
                suggestBox.style.display = 'block';
            }

            cityInput.addEventListener('input', function () {
                renderSuggestions(this.value);
            });

            cityInput.addEventListener('focus', function () {
                renderSuggestions(this.value);
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('.city-suggest-wrapper')) {
                    suggestBox.style.display = 'none';
                }
            });

            stateSelect.addEventListener('change', function () {
                cityInput.value = '';
                cscLoadCities(this.value);
            });
        })();

        // ==========================================================
        // Sponsor name lookup
        // ==========================================================
        $('#sponsor_display').on('keyup', function () {
            let sponsorId = $(this).val();
            if (sponsorId.length > 2) {
                $.ajax({
                    url: "{{ route('auth.getSponsorName') }}",
                    type: "GET",
                    data: { sponsor_id: sponsorId },
                    success: function (response) {
                        if (response.success) {
                            $('#sponsor-name').html(`<span class="text-success">Sponsor Name: ${response.name}</span>`);
                        } else {
                            $('#sponsor-name').html('<span class="text-danger">Sponsor not found</span>');
                        }
                    },
                    error: function () {
                        $('#sponsor-name').html('<span class="text-danger">Error checking sponsor</span>');
                    }
                });
            } else {
                $('#sponsor-name').html('');
            }
        });

        // ==========================================================
        // Live AJAX: Unique Name availability
        // ==========================================================
        let uniqueNameTimer = null;
        $('#unique_name').on('keyup', function () {
            const val = $(this).val();
            clearTimeout(uniqueNameTimer);
            if (val.length < 2) {
                $('#uniqueNameFeedback').html('<span class="text-warning">Name must be at least 2 characters long.</span>');
                return;
            }
            uniqueNameTimer = setTimeout(function () {
                $.ajax({
                    url: "{{ route('auth.checkUniqueName') }}",
                    type: "GET",
                    data: { unique_name: val },
                    success: function (res) {
                        $('#uniqueNameFeedback').html(
                            `<span class="${res.available ? 'text-success' : 'text-danger'}">${res.message}</span>`
                        );
                    },
                    error: function() {
                        $('#uniqueNameFeedback').html('<span class="text-danger">Error checking availability</span>');
                    }
                });
            }, 350);
        });

        // ==========================================================
        // Live AJAX: PAN Validation & Availability
        // ==========================================================
        let panTimer = null;
        $('#pan').on('input', function () {
            this.value = this.value.toUpperCase();
            const val = this.value;
            clearTimeout(panTimer);
            
            if (val.length > 0 && val.length < 10) {
                $('#panFeedback').html('<span class="text-warning">PAN must be 10 characters long.</span>');
                return;
            }
            
            if (val.length === 10) {
                const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                if (!panPattern.test(val)) {
                    $('#panFeedback').html('<span class="text-danger">Invalid PAN format. Example: ABCDE1234F</span>');
                    return;
                }
                
                panTimer = setTimeout(function () {
                    $.ajax({
                        url: "{{ route('auth.checkPan') }}",
                        type: "GET",
                        data: { pan: val },
                        success: function (res) {
                            $('#panFeedback').html(
                                `<span class="${res.available ? 'text-success' : 'text-danger'}">${res.message}</span>`
                            );
                        },
                        error: function() {
                            $('#panFeedback').html('<span class="text-danger">Error checking PAN</span>');
                        }
                    });
                }, 300);
            } else {
                $('#panFeedback').html('');
            }
        });

        // ==========================================================
        // Live AJAX: Aadhaar Validation & Availability
        // ==========================================================
        let aadhaarTimer = null;
        $('#aadhaar').on('input', function () {
            let value = this.value.replace(/\D/g, '');
            let formatted = value.match(/.{1,4}/g);
            this.value = formatted ? formatted.join(' ') : value;

            const digitsOnly = value;
            clearTimeout(aadhaarTimer);
            
            if (digitsOnly.length > 0 && digitsOnly.length < 12) {
                $('#aadhaarFeedback').html('<span class="text-warning">Aadhaar must be 12 digits.</span>');
                return;
            }
            
            if (digitsOnly.length === 12) {
                aadhaarTimer = setTimeout(function () {
                    $.ajax({
                        url: "{{ route('auth.checkAadhaar') }}",
                        type: "GET",
                        data: { aadhaar: digitsOnly },
                        success: function (res) {
                            $('#aadhaarFeedback').html(
                                `<span class="${res.available ? 'text-success' : 'text-danger'}">${res.message}</span>`
                            );
                        },
                        error: function() {
                            $('#aadhaarFeedback').html('<span class="text-danger">Error checking Aadhaar</span>');
                        }
                    });
                }, 300);
            } else {
                $('#aadhaarFeedback').html('');
            }
        });

        // ==========================================================
        // Password / Confirm Password validation
        // ==========================================================
        document.addEventListener("DOMContentLoaded", function () {
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("password_confirmation");
            const passwordFeedback = document.getElementById("passwordFeedback");
            const confirmFeedback = document.getElementById("confirmFeedback");

            function validatePassword() {
                let errors = [];
                if (password.value.length === 0) {
                    errors.push("Please enter your password.");
                }
                if (password.value.length > 0 && password.value.length < 6) {
                    errors.push("Password must be at least 6 characters.");
                }
                if (password.value.length > 0 && !/^[A-Za-z0-9]+$/.test(password.value)) {
                    errors.push("Please enter only alphabets and numbers.");
                }

                if (errors.length > 0 && password.value.length > 0) {
                    password.classList.add("is-invalid");
                    password.classList.remove("is-valid");
                    passwordFeedback.innerHTML = errors.map(e => `<div class="text-danger">${e}</div>`).join("");
                    return false;
                } else if (password.value.length > 0) {
                    password.classList.remove("is-invalid");
                    password.classList.add("is-valid");
                    passwordFeedback.innerHTML = `<div class="text-success">Password valid</div>`;
                    return true;
                }
                return false;
            }

            function validateConfirm() {
                const val = confirmPassword.value;
                if (val.length === 0) {
                    confirmPassword.classList.remove("is-valid", "is-invalid");
                    confirmFeedback.innerHTML = '';
                    return false;
                }
                
                let errors = [];
                if (val.length < 6) {
                    errors.push("Password must be at least 6 characters.");
                }
                if (!/^[A-Za-z0-9]+$/.test(val)) {
                    errors.push("Please enter only alphabets and numbers.");
                }
                
                if (errors.length === 0) {
                    if (val === password.value) {
                        confirmPassword.classList.remove("is-invalid");
                        confirmPassword.classList.add("is-valid");
                        confirmFeedback.innerHTML = `<div class="text-success">Passwords match</div>`;
                        return true;
                    } else {
                        confirmPassword.classList.add("is-invalid");
                        confirmPassword.classList.remove("is-valid");
                        confirmFeedback.innerHTML = `<div class="text-danger">Passwords do not match.</div>`;
                        return false;
                    }
                } else {
                    confirmPassword.classList.add("is-invalid");
                    confirmPassword.classList.remove("is-valid");
                    confirmFeedback.innerHTML = errors.map(e => `<div class="text-danger">${e}</div>`).join("");
                    return false;
                }
            }

            password.addEventListener("keyup", function() {
                validatePassword();
                if (confirmPassword.value.length > 0) {
                    validateConfirm();
                }
            });
            
            confirmPassword.addEventListener("keyup", validateConfirm);

            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(function (el) {
                el.addEventListener('click', function () {
                    const input = this.closest('.input-group').querySelector('input');
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bx-hide');
                        icon.classList.add('bx-show');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bx-show');
                        icon.classList.add('bx-hide');
                    }
                });
            });

            // ==========================================================
            // FORM SUBMIT HANDLER - Debug and ensure submission
            // ==========================================================
            const form = document.getElementById('formAuthentication');
            if (form) {
                // Remove any existing submit listeners that might block
                form.removeEventListener('submit', function() {});
                
                // Add a clean submit handler for debugging
                form.addEventListener('submit', function(e) {
                    console.log('Form is being submitted!');
                    // Let the form submit naturally
                });
            }
        });
    </script>
</body>
</html>