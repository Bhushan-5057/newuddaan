<!DOCTYPE html>
<html lang="en">


<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="{{ company_info('company_name') }}" name="description">
    <meta content="{{ company_info('company_name') }}" name="keywords">
    <meta content="la-themes" name="author">
    <link href="{{ asset('uploads/' . company_info('favicon')) }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('uploads/' . company_info('favicon')) }}" rel="shortcut icon" type="image/x-icon">
    <title>{{ company_info('company_name') }}</title>

    <!-- Animation css -->
    <link href="{{ asset('user/assets/vendor/animation/animate.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!--flag Icon css-->
    <link href="{{ asset('user/assets/vendor/flag-icons-master/flag-icon.css') }}" rel="stylesheet" type="text/css">

    <!-- tabler icons-->
    <link href="{{ asset('user/assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">

    <!-- apexcharts css-->
    <link href="{{ asset('user/assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet" type="text/css">

    <!-- glight css -->
    <link href="{{ asset('user/assets/vendor/glightbox/glightbox.min.css') }}" rel="stylesheet">

    <!-- Bootstrap css-->
    <link href="{{ asset('user/assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- simplebar css-->
    <link href="{{ asset('user/assets/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="{{ asset('user/assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('user/assets/css/responsive.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">


    <style>
        .menu-icon-box {
            width: 34px;
            height: 34px;
            min-width: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            /* rounded square */
            font-size: 15px;
            margin-right: 10px;
        }

        /* ðŸŽ¨ Color variants */
        .menu-icon-box.primary {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .menu-icon-box.success {
            background: #e9f7ef;
            color: #198754;
        }

        .menu-icon-box.warning {
            background: #fff4e5;
            color: #fd7e14;
        }

        .menu-icon-box.danger {
            background: #fdecea;
            color: #dc3545;
        }

        .menu-icon-box.info {
            background: #e3f2fd;
            color: #0dcaf0;
        }

        /* âœ¨ Optional hover effect */
        a:hover .menu-icon-box {
            filter: brightness(0.95);
        }
        
        .custom-logo img{
            height: 100px;
            width: 100px;
            object-fit: contain;
            padding-top: 50px;
            padding-bottom: 10px;
        }
        
        @media (max-width: 768px){
            .custom-logo img{
                padding-right: 65px !important;
            }
            
            .main-nav{
                margin-left: 0px !important;
            }
        }

        /* Keep logout always visible; do NOT lock main content scroll */
        .app-wrapper > nav {
            display: flex;
            flex-direction: column;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
        }

        .app-wrapper > nav > .app-logo {
            flex-shrink: 0;
        }

        .app-wrapper > nav > .app-nav {
            flex: 1 1 auto;
            min-height: 0;
            height: auto !important;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .app-wrapper > nav > .sidebar-logout-pin {
            flex-shrink: 0;
            border-top: 1px solid rgba(var(--primary), 0.15);
            background: rgba(var(--white), 1);
            padding: 0.5rem 0.75rem 0.75rem;
            z-index: 2;
        }

        .app-wrapper > nav > .sidebar-logout-pin a {
            padding: 0.6rem 1rem;
            font-weight: 500;
            color: rgba(var(--dark), 1);
            width: 100%;
            display: inline-flex;
            align-items: center;
            font-size: 16px;
            text-decoration: none;
            border-radius: 0.5rem;
        }

        /* Desktop collapsed: icons only, no label bleed */
        @media (min-width: 768px) {
            .app-wrapper .semi-nav:not(:hover) .sidebar-logout-pin a {
                font-size: 0;
                justify-content: center;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .app-wrapper .semi-nav:not(:hover) .sidebar-logout-pin .menu-icon-box,
            .app-wrapper .semi-nav:not(:hover) .app-nav .menu-icon-box {
                margin-right: 0;
                font-size: 15px !important;
            }

            .app-wrapper .semi-nav:not(:hover) .app-nav .menu-title {
                display: none;
            }

            .app-wrapper .semi-nav:not(:hover) .app-nav .main-nav > li:not(.menu-title) > a {
                justify-content: center;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                overflow: hidden;
            }

            .app-wrapper .semi-nav:not(:hover) .app-logo .nav-profile .flex-grow-1,
            .app-wrapper .semi-nav:not(:hover) .app-logo .nav-profile .dropdown {
                display: none;
            }

            /* Collapsed sidebar: match header logo size (40px). Open/hover: keep big 80px logo. */
            .app-wrapper .semi-nav:not(:hover) .app-logo > a.logo img {
                height: 40px !important;
                width: 40px !important;
                object-fit: contain;
            }

            .app-wrapper .semi-nav:hover .app-logo > a.logo img,
            .app-wrapper nav:not(.semi-nav) .app-logo > a.logo img {
                height: 80px !important;
                width: 80px !important;
                object-fit: contain;
            }

            .app-wrapper .semi-nav:not(:hover) .app-logo > a.logo {
                display: flex;
                justify-content: center;
                width: 100%;
            }

            .app-wrapper .semi-nav:hover .sidebar-logout-pin .menu-icon-box,
            .app-wrapper nav:not(.semi-nav) .sidebar-logout-pin .menu-icon-box,
            .app-wrapper .semi-nav:hover .app-nav .menu-icon-box,
            .app-wrapper nav:not(.semi-nav) .app-nav .menu-icon-box {
                margin-right: 10px;
                font-size: 15px !important;
            }
        }

        /* Mobile (matches theme): no semi-nav = closed; semi-nav = full drawer open.
           Keep labels + icons visible; never use the desktop icon-rail collapse. */
        @media (max-width: 767.98px) {
            .app-wrapper > nav:not(.semi-nav) {
                width: 0 !important;
                overflow: hidden !important;
                pointer-events: none;
            }

            .app-wrapper > nav.semi-nav,
            .app-wrapper > nav.semi-nav:hover {
                width: calc(var(--sidebar-width) - 1rem) !important;
                max-width: 90vw;
                overflow: hidden;
                pointer-events: auto;
                z-index: 1100;
            }

            .app-wrapper .app-content,
            .app-wrapper .semi-nav ~ .app-content {
                padding-left: 0 !important;
            }

            .app-wrapper .app-content header.header-main,
            .app-wrapper .semi-nav ~ .app-content header.header-main {
                padding-left: 0 !important;
            }

            /* Full labels whenever the mobile drawer is open */
            .app-wrapper > nav.semi-nav .app-nav .main-nav li a,
            .app-wrapper > nav.semi-nav .sidebar-logout-pin a {
                font-size: 16px !important;
                text-align: left !important;
                justify-content: flex-start !important;
                overflow: hidden;
            }

            .app-wrapper > nav.semi-nav .menu-icon-box {
                margin-right: 10px !important;
                font-size: 15px !important;
                flex-shrink: 0;
            }

            .app-wrapper > nav.semi-nav .app-nav .menu-title {
                display: block !important;
            }

            .app-wrapper > nav.semi-nav .app-logo,
            .app-wrapper > nav.semi-nav .app-logo .nav-profile {
                width: 100% !important;
                overflow: visible !important;
            }

            .app-wrapper > nav.semi-nav .app-logo .nav-profile .flex-grow-1,
            .app-wrapper > nav.semi-nav .app-logo .nav-profile .dropdown {
                display: block !important;
            }

            .app-wrapper > nav.semi-nav .sidebar-logout-pin {
                width: 100%;
            }
        }
    </style>
    
    
    <style>
        .menu-icon-box {
            width: 34px;
            height: 34px;
            min-width: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            /* rounded square */
            font-size: 15px;
            margin-right: 10px;
        }

        /* 🎨 Color variants */
        .menu-icon-box.primary {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .menu-icon-box.success {
            background: #e9f7ef;
            color: #198754;
        }

        .menu-icon-box.warning {
            background: #fff4e5;
            color: #fd7e14;
        }

        .menu-icon-box.danger {
            background: #fdecea;
            color: #dc3545;
        }

        .menu-icon-box.info {
            background: #e3f2fd;
            color: #0dcaf0;
        }

        /* ✨ Optional hover effect */
        a:hover .menu-icon-box {
            filter: brightness(0.95);
        }
    </style>





</head>

<body class="">
    <div class="app-wrapper">

        <div class="loader-wrapper">
            <div class="loader_24"></div>
        </div>

        <!-- Menu Navigation starts -->

        <nav>
            <div class="app-logo">
                <a class="logo d-inline-block" href="{{ route('dashboard.user') }}">
                    <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="Logo"
                        style="height:80px; width:80px;">
                </a>

                <span class="bg-light-primary toggle-semi-nav d-flex-center">
                    <i class="ti ti-chevron-right"></i>
                </span>

                <div class="d-flex align-items-center nav-profile p-3">
                    <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto">

                        {{-- Profile Image --}}
                        @if (Auth::check() && Auth::user()->profile_image)
                            <img alt="avatar" class="img-fluid b-r-10"
                                src="{{ asset('storage/' . Auth::user()->profile_image) }}">
                        @else
                            <img alt="avatar" class="img-fluid b-r-10" src="{{ asset('assets/img/avatars/3.png') }}">
                        @endif

                        <span
                            class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                    </span>

                    <div class="flex-grow-1 ps-2">
                        {{-- User Name --}}
                        <h6 class="text-primary mb-0">
                            {{ $shortName ?? (Auth::check() ? Auth::user()->name : '') }}
                        </h6>

                        {{-- Email / Role --}}
                        <p class="text-muted f-s-12 mb-0">
                            {{ Auth::check() ? Auth::user()->email : '' }}
                        </p>
                    </div>

                    {{-- Settings Dropdown --}}
                    <div class="dropdown profile-menu-dropdown">
                        <a role="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                            <i class="fa fa-cog fs-5"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li class="dropdown-item">
                                <a class="f-w-500" href="{{ route('user.profile') }}">
                                    <i class="fa fa-user-circle pe-1 f-s-20"></i>
                                    Profile Details
                                </a>
                            </li>

                            <li class="dropdown-item">
                                <a class="f-w-500" href="{{ route('user.accountsettings.create') }}">
                                    <i class="fa fa-gear pe-1 f-s-20"></i>
                                    Settings
                                </a>
                            </li>

                            <li class="dropdown-item">
                                <a class="f-w-500" href="{{ route('user.support.index') }}">
                                    <i class="fa fa-life-ring pe-1 f-s-20"></i>
                                    Support
                                </a>
                            </li>

                            <li class="app-divider-v dotted py-1"></li>

                            <li class="dropdown-item">
                                <a class="mb-0 text-danger" href="{{ route('user.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out pe-1 f-s-20"></i>
                                    Log Out
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>


            <div class="app-nav" id="app-simple-bar">
                <ul class="main-nav p-0 mt-2">

                    <li class="menu-title">
                        <span>Profile & Team</span>
                    </li>

                    <!--<li class="no-sub">-->
                    <!--    <a href="{{ route('home') }}">-->
                    <!--        <span class="menu-icon-box primary">-->
                    <!--            <i class="fa fa-house"></i>-->
                    <!--        </span>-->
                    <!--        Home-->
                    <!--    </a>-->
                    <!--</li>-->

                    <li class="no-sub">
                        <a href="{{ route('dashboard.user') }}">
                            <span class="menu-icon-box primary">
                                <i class="fa fa-gauge-high"></i>
                            </span>
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a data-bs-toggle="collapse" href="#apps" aria-expanded="false">

                            <span class="menu-icon-box warning">
                                <i class="fa fa-user"></i>
                            </span>

                            My Profile
                        </a>

                        <ul class="collapse" id="apps">
                            <li>
                                <a href="{{ route('user.profile') }}">Profile</a>
                            </li>

                            <li>
                                <a href="{{ route('user.profile.edit') }}">Edit Profile</a>
                            </li>
                            
                               <li>
                                <a href="{{ route('user.idcard') }}">ID Card</a>
                            </li>

                     

                            @if (auth()->user()->hasPermission('Account Setting', 'can_view'))
                                <li>
                                    <a href="{{ route('user.accountsettings.create') }}">
                                        Account Setting
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    
                         @if (auth()->user()->hasPermission('My Genealogy', 'can_view'))
                        <li>
                            <a data-bs-toggle="collapse" href="#genealogy" aria-expanded="false">

                                <span class="menu-icon-box danger">
                                    <i class="fa fa-tree"></i>
                                </span>


                                My Genealogy
                            </a>

                            <ul class="collapse" id="genealogy">

                                @if (auth()->user()->hasPermission('Direct Users', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.genealogy.direct-users') }}">
                                            Direct
                                        </a>
                                    </li>
                                @endif

                                @if (auth()->user()->hasPermission('Genealogy Tree', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.tree.view') }}">
                                            Binary Tree
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    
                 <style>
/* --- Approved / Default State --- */
.kyc-state-approved .menu-icon-box {
    color: #198754 !important; /* Elegant Green */
}

/* --- Rejected / Missing States (Red Alert) --- */
.kyc-blink-red a {
    animation: kycRedBlink 1.5s infinite alternate ease-in-out;
    font-weight: bold !important;
}
.kyc-blink-red .menu-icon-box {
    color: #dc3545 !important; /* Solid Red Icon */
}
@keyframes kycRedBlink {
    0% { background-color: rgba(220, 53, 69, 0.05); color: #dc3545; }
    100% { background-color: rgba(220, 53, 69, 0.2); color: #b02a37; box-shadow: inset 0 0 4px rgba(220, 53, 69, 0.3); }
}

/* --- Pending State (Warning Orange/Yellow) --- */
.kyc-blink-warning a {
    animation: kycWarningBlink 2s infinite alternate ease-in-out;
}
.kyc-blink-warning .menu-icon-box {
    color: #ffc107 !important; /* Warning Yellow Icon */
}
@keyframes kycWarningBlink {
    0% { background-color: rgba(255, 193, 7, 0.02); color: #ffc107; }
    100% { background-color: rgba(255, 193, 7, 0.15); color: #d39e00; }
}

/* --- General Active Polish --- */
.kyc-blink-red .menu-icon-box i, 
.kyc-blink-warning .menu-icon-box i {
    animation: kycIconPulse 1s infinite alternate ease-in-out;
    display: inline-block; /* Pulse effect માટે જરૂરી છે */
}
@keyframes kycIconPulse {
    0% { transform: scale(1); }
    100% { transform: scale(1.1); }
}
</style>
                    
                    @if (auth()->user()->hasPermission('KYC', 'can_view'))
                        @php
                            // AppServiceProvider માંથી આવતા $kycState ના આધારે ક્લાસ નક્કી થશે
                            $stateClass = '';
                            if (isset($kycState)) {
                                if ($kycState === 'rejected') {
                                    $stateClass = 'kyc-blink-red';
                                } elseif ($kycState === 'pending') {
                                    $stateClass = 'kyc-blink-warning';
                                } elseif ($kycState === 'missing') {
                                    $stateClass = 'kyc-blink-red';
                                } else {
                                    $stateClass = 'kyc-state-approved';
                                }
                            }
                        @endphp
                    
                        <!-- અહીં $stateClass ઉમેર્યો છે જેનાથી લિંક બ્લિંક થશે -->
                        <li class="no-sub {{ $stateClass }}">
                            <a href="{{ route('user.kyc.show') }}">
                                <span class="menu-icon-box success">
                                    <i class="fa-solid fa-id-card"></i>
                                </span>
                                KYC 
                                
                                <!-- યુઝરને તરત આઈડિયા આવે એના માટે નાનું બેજ -->
                                @if(isset($kycState) && $kycState === 'rejected')
                                    <span class="badge bg-danger ms-1 text-white" style="font-size: 10px;">Fix</span>
                                @elseif(isset($kycState) && $kycState === 'pending')
                                    <span class="badge bg-warning text-dark ms-1" style="font-size: 10px;">Pending</span>
                                @endif
                            </a>
                        </li>
                    @endif
               

                    <li class="menu-title"><span>Product </span></li>

                    @if (auth()->user()->hasPermission('Fund', 'can_view'))
                        <li>
                            <a data-bs-toggle="collapse" href="#fund" aria-expanded="false">
                                <span class="menu-icon-box success">
                                    <i class="fa fa-bookmark"></i>
                                </span>
                                Fund
                            </a>

                            <ul class="collapse" id="fund">

                                @if (auth()->user()->hasPermission('add fund', 'can_view') || Advance_info('Registration_Type', 'normal') === 'dapp')
                                    <li>
                                        <a href="{{ route('user.fund.index') }}">Add Fund</a>
                                    </li>
                                @endif

                                @if (auth()->user()->hasPermission('add fund history', 'can_view') ||
                                        Advance_info('Registration_Type', 'normal') === 'dapp')
                                    <li>
                                        <a href="{{ route('user.addfund.history') }}">Add Fund History</a>
                                    </li>
                                @endif

                                @if (auth()->user()->hasPermission('add fund request', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.fundrequest.index') }}">Fund Request</a>
                                    </li>
                                @endif

                                @if (auth()->user()->hasPermission('fund request history', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.fundrequest.history') }}">Fund Request History</a>
                                    </li>
                                @endif

                                @if (auth()->user()->hasPermission('fund transfer', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.fundtransfer.index') }}">Transfer Fund</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('user.fundtransfer.history') }}">Transfer History</a>
                                    </li>
                                @endif

                                @if (auth()->user()->hasPermission('fund convert', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.walletinternal.index') }}">Fund Convert</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('fund.convert.history') }}">Convert History</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif


  


                    @if (auth()->user()->hasPermission('Topup History', 'can_view'))
                        <li class="no-sub">
                            <a href="{{ route('user.order.history') }}">
                                <span class="menu-icon-box success">
                                    <i class="fa fa-credit-card"></i>
                                </span>
                                Invest History
                            </a>
                        </li>
                    @endif



                    @if (auth()->user()->hasPermission('Product', 'can_view'))
                        <li>
                            <a data-bs-toggle="collapse" href="#topup" aria-expanded="false">
                                <span class="menu-icon-box warning">
                                    <i class="fa fa-shopping-cart"></i>
                                </span>
                                Product

                            </a>

                            <ul class="collapse" id="topup">

                                @if (auth()->user()->hasPermission('Product Buy', 'can_view'))
                                    <li>
                                        <a href="{{ route('product.shop') }}">
                                            Product Buy
                                        </a>
                                    </li>
                                @endif

                                @if (auth()->user()->hasPermission('Orders', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.order.history') }}">
                                            Topup History
                                        </a>
                                    </li>
                                        @endif
                           @if (auth()->user()->hasPermission('Product History', 'can_view'))

                                    <li>
                                        <a href="{{ route('user.product.history') }}">
                                             Product History
                                        </a>
                                    </li>
                                    
                                    
                                @endif

                            </ul>
                        </li>
                    @endif



                    <li class="menu-title"><span>Income </span></li>

                    @if (auth()->user()->hasPermission('Income Details', 'can_view'))
                        <li>
                            <a data-bs-toggle="collapse" href="#payout-reports" aria-expanded="false">
                                <span class="menu-icon-box success">
                                    <i class="fa fa-money"></i>
                                </span>
                                Payout Reports
                            </a>

                            <ul class="collapse" id="payout-reports">
                                @if (auth()->user()->hasPermission('User Income', 'can_view'))
                                    @foreach ($incometype as $type)
                                        <li>
                                            <a href="{{ route('user.income.details', $type->slug) }}">
                                                {{ $type->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('Income Report', 'can_view'))
                        <li class="no-sub">
                            <a href="{{ route('user.income.report') }}">
                                <span class="menu-icon-box primary">
                                    <i class="fa fa-line-chart"></i>
                                </span>
                                Income Report
                            </a>
                        </li>
                    @endif
                    
                    
                @if (auth()->user()->hasPermission('Withdrawal_view', 'can_view'))
                        @php
                            $payoutKycReady = \App\Models\KycDetail::where('user_id', auth()->id())
                                ->where('kyc_status_identity', 'approved')
                                ->where('kyc_status_bank', 'approved')
                                ->exists();
                        @endphp
                        @if ($payoutKycReady)
                        <li class="no-sub">
                            <a href="{{ route('user.payout.index') }}">
                                <span class="menu-icon-box success">
                                    <i class="fa fa-wallet"></i>
                                </span>
                                Payout
                            </a>
                        </li>
                        @endif
                    @endif

                @if (auth()->user()->hasPermission('Withdrawal Report', 'can_view'))
                        <li class="no-sub">
                            <a href="{{ route('user.withdrawals') }}">
                                <span class="menu-icon-box primary">
                                    <i class="fa fa-credit-card"></i>
                                </span>
                                Withdrawal Report
                            </a>
                        </li>
                    @endif




                    <li class="menu-title"><span>Reward</span></li>
                    @if (auth()->user()->hasPermission('Reward', 'can_view'))
                        <li class="no-sub">
                            <a href="{{ route('user.rank.qualification') }}">
                                <span class="menu-icon-box warning">
                                    <i class="fa fa-crown"></i>
                                </span>
                                Rank & Rewards
                            </a>
                        </li>
                    @endif


                    <li class="menu-title"><span>Others</span></li>

                    @if (auth()->user()->hasPermission('News and Events', 'can_view'))
                        <li class="no-sub">
                            <a href="{{ route('user.newsevents.index') }}">
                                <span class="menu-icon-box success">
                                    <i class="fa fa-newspaper"></i>
                                </span>
                                News & Events
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('Support', 'can_view'))
                        <li class="no-sub">
                            <a href="{{ route('user.support.index') }}">
                                <span class="menu-icon-box info">
                                    <i class="fa fa-life-ring"></i>
                                </span>
                                Support
                            </a>
                        </li>
                    @endif

                </ul>
            </div>

            <div class="sidebar-logout-pin">
                <a href="{{ route('user.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="menu-icon-box danger">
                        <i class="fa fa-sign-out"></i>
                    </span>
                    Logout
                </a>
            </div>

            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <div class="menu-navs">
                <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
                <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
            </div>

        </nav>
        
        
        <!-- Menu Navigation ends -->

        <div class="app-content">
            <div class="">

                <!-- Header Section starts -->
                
                
           <header class="header-main">
    <div class="container-fluid">
        <div class="row">

            <!-- Left Side -->
            <div class="col-8 col-sm-6 d-flex align-items-center header-left p-0">

                <span class="header-toggle">
                    <i class="ph ph-squares-four"></i>
                </span>

                <!-- Logo -->
                <a href="{{ url('/') }}" class="ms-2 me-3">
                    <img src="{{ asset('uploads/' . company_info('logo')) }}"
                        alt="logo"
                        style="height:40px;">
                </a>

                <!-- Search -->
                <div class="header-searchbar w-100">
                    <form action="{{ route('product.search') }}"
                        method="GET"
                        class="mx-sm-3 app-form app-icon-form">

                        <div class="position-relative">

                            <input type="text"
                                id="search-input"
                                name="q"
                                class="form-control"
                                placeholder="Search products..."
                                autocomplete="off">

                            <i class="ti ti-search text-dark"></i>

                            <!-- Suggestion Box -->
                            <div id="suggestion-box"
                                class="card shadow position-absolute w-100 mt-1"
                                style="z-index:999;display:none;">
                                <ul id="suggestion-list"
                                    class="list-group list-group-flush">
                                </ul>
                            </div>

                        </div>
                    </form>
                </div>

            </div>

            <!-- Right Side -->
            <div class="col-4 col-sm-6 d-flex align-items-center justify-content-end header-right p-0">

                <ul class="d-flex align-items-center mb-0">

                    @auth('web')

                    <!-- Wishlist -->
                          
                    <li class="me-2">
                            <a class="head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2"
                               href="{{ route('cart') }}">
                        
                                <i class="ph ph-shopping-cart-simple"></i>
                        
                                <span class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification ajax-cart-count">
                                    0
                                </span>
                            </a>
                        </li>
                    
                    <!--<li class="me-2">-->
                    <!--    <a href="{{ route('wishlist.index') }}"-->
                    <!--        class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2">-->

                    <!--        <i class="ph ph-heart"></i>-->

                    <!--        <span class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification ajax-wish-count">-->
                    <!--            {{ \App\Models\Wishlist::where('u_code', auth('web')->id())->count() }}-->
                    <!--        </span>-->

                    <!--    </a>-->
                    <!--</li>-->

           
              

                    <!-- User -->
                    <li>
                        <a href="{{ route('dashboard.user') }}"
                            class="d-flex align-items-center">

                            @if(Auth::check() && Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                                    class="rounded-circle"
                                    width="40"
                                    height="40">
                            @else
                                <img src="{{ asset('assets/images/2.png') }}"
                                    class="rounded-circle"
                                    width="40"
                                    height="40">
                            @endif

                        </a>
                    </li>

                    @else

                    <!-- Wishlist Login -->
                    <li class="me-2">
                        <a href="javascript:void(0)"
                            data-bs-toggle="modal"
                            data-bs-target="#login-modal"
                            class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2">

                            <i class="ph ph-heart"></i>

                            <span class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification">
                                0
                            </span>

                        </a>
                    </li>

                    <!-- Cart Login -->
                    <li class="me-2">
                        <a href="javascript:void(0)"
                            data-bs-toggle="modal"
                            data-bs-target="#login-modal"
                            class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2">

                            <i class="ph ph-shopping-cart-simple"></i>

                            <span class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification">
                                0
                            </span>

                        </a>
                    </li>

                    <!-- Login Button -->
                    <li>
                        <a href="javascript:void(0)"
                            data-bs-toggle="modal"
                            data-bs-target="#login-modal"
                            class="btn btn-primary btn-sm">

                            Login

                        </a>
                    </li>

                    @endauth

                </ul>

            </div>

        </div>
    </div>
</header>





                
                
                
                
                <!-- Header Section ends -->

                <!-- Body main section starts -->
                <main>
                    <div class="container-fluid">
                        
                        <script>
    // Force dark theme by default - Run immediately
    (function() {
        // Set dark theme on body
        document.body.classList.add('dark');
        document.body.classList.remove('light');
        
        // Set data attribute for Bootstrap
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        
        // Store in localStorage for persistence
        localStorage.setItem('theme-mode', 'dark');
        
        // Override any existing theme toggle behavior
        document.addEventListener('DOMContentLoaded', function() {
            // Make sure dark theme is applied after page load
            document.body.classList.add('dark');
            document.body.classList.remove('light');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            
            // If there's a theme toggle button, update its state
            const themeToggle = document.querySelector('.header-dark');
            if (themeToggle) {
                const sunLogo = document.querySelector('.sun-logo');
                const moonLogo = document.querySelector('.moon-logo');
                if (sunLogo) sunLogo.classList.add('sun');
                if (moonLogo) moonLogo.classList.remove('moon');
            }
        });
    })();
</script>


