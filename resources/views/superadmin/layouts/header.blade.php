<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <!-- <title>Dashboard - Analytics | Frest - Bootstrap Admin Template</title> -->
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/' . company_info('favicon')) }}" type="image/png" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>
<style>
    .iti--allow-dropdown input,
    .iti--allow-dropdown input[type=text],
    .iti--separate-dial-code input,
    .iti--separate-dial-code input[type=tel],
    .iti--separate-dial-code input[type=text] {
        padding-right: 100px !important;
    }
</style>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo mt-2">
                    <a href="{{ route('dashboard.superadmin') }}" class="app-brand-link">
                        <img src="{{ asset('uploads/' . company_info('logodark')) }}" alt="Logo"
                            style="width: {{ company_info('logo_width', '40px') }};"
                            height="{{ company_info('logo_height', '40px') }}">
                    </a>
                    <!-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
                        <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
                    </a> -->
                </div>
                <div class="menu-divider mt-0"></div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <!-- Dashboards -->
                    <li class="menu-item">
                        <a href="{{ route('dashboard.superadmin') }}" class="menu-link ">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboards">Dashboards</div>
                        </a>
                    </li>
                    <!-- Charts & Maps -->
                    <!-- <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Users &amp; Info</span>
                    </li> -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Users">Users</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadminusers.index') }}" class="menu-link">
                                    <div data-i18n="All Users">All Users</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('company.settings') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Role Permissions">Role Permissions</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('custom_fields.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Registion Form">Registion Form</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('superadmin.mailtemplates.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Mail Template">Mail Template</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Fund">Fund</div>
                            @if($pendingFundRequestCount > 0)
                                        <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                            {{ $pendingFundRequestCount ?? 'not set' }}
                                        </div>
                                    @endif
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('fund.index') }}" class="menu-link">
                                    <div data-i18n="Add Fund">Add Fund</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.transferfundtransactions.index') }}" class="menu-link">
                                    <div data-i18n="Transfer Fund History">Transfer Fund History</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('retrievefund.index') }}" class="menu-link">
                                    <div data-i18n="Retrieve Fund">Retrieve Fund</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.retrievefundtransactions.index') }}" class="menu-link">
                                    <div data-i18n="Retrieve Fund History">Retrieve Fund History</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.transferfundtransactionsrequest.index') }}"
                                    class="menu-link">
                                    <div data-i18n="Pending Fund Request">Pending Fund Request</div>
                                    @if($pendingFundRequestCount > 0)
                                        <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                            {{ $pendingFundRequestCount ?? 'not set' }}
                                        </div>
                                    @endif
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.approvedfundrequest.index') }}" class="menu-link">
                                    <div data-i18n="Approved Fund Request">Approved Fund Request</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.cancelledfundrequest.index') }}" class="menu-link">
                                    <div data-i18n="Cancelled Fund Request">Cancelled Fund Request</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.fund.convert.history') }}" class="menu-link">
                                    <div data-i18n="Fund Convert History">Fund Convert History</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Pin Details">Pin Details</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('pin-details.custom_fields.index') }}" class="menu-link">
                                    <div data-i18n="Add Custom Fields">Add Custom Fields</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.pindetails.index') }}" class="menu-link">
                                    <div data-i18n="Pin Details List">Pin Details List</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Plan">Plan</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('plan.custom_fields.index') }}" class="menu-link">
                                    <div data-i18n="Add Custom Fields">Add Custom Fields</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.plan.index') }}" class="menu-link">
                                    <div data-i18n="Plan List">Plan List</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Withdrawal">Withdrawal</div>
                            @if($pendingWithdrawalCount > 0)
                                <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                    {{ $pendingWithdrawalCount ?? 'not set' }}
                                </div>
                            @endif
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.withdrwalstransactionsrequest.index') }}"
                                    class="menu-link">
                                    <div data-i18n="Pending Request"></div>
                                    @if($pendingWithdrawalCount > 0)
                                        <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                            {{ $pendingWithdrawalCount ?? 'not set' }}
                                        </div>
                                    @endif
                                </a>
                            </li>
                            <!-- <li class="menu-item">
    <a href="{{ route('superadmin.withdrwalstransactionsrequest.index') }}" class="menu-link">
        <div data-i18n="Pending Request">
            Pending Request
            @if($pendingWithdrawalCount > 0)
                <span class="badge bg-danger ms-2">{{ $pendingWithdrawalCount }}</span>
            @endif
        </div>
    </a>
</li> -->
                            <!-- <li class="menu-item">
                                <a href="{{ route('superadmin.withdrwalstransactionsrequest.index') }}"
                                    class="menu-link">
                                    <div data-i18n="Pending Request">
                                        Pending Request
                                        @if($pendingWithdrawalCount > 0)
                                            <div class="badge bg-danger ms-2">{{ $pendingWithdrawalCount }}</div>
                                        @endif
                                    </div>
                                </a>
                            </li> -->
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.approvedwithdrwalsrequest.index') }}" class="menu-link">
                                    <div data-i18n="Approved Request"> Approved Request</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.cancelledwithdrwalsrequest.index') }}" class="menu-link">
                                    <div data-i18n="Cancelled Request"> Cancelled Request</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('superadmin.topup.history') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Order History">Order History</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Settings">Settings</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.companyinfo.index') }}" class="menu-link">
                                    <div data-i18n="Company Info">Company Info</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.advanceinfo.index') }}" class="menu-link">
                                    <div data-i18n="Advance Info">Advance Info</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                   
                    <li class="menu-item">
                        <a href="{{ route('superadmin.paymentmethods.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Payment Methods">Payment Methods</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Pin Details">Support</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('superadmin.support.pending') }}" class="menu-link">
                                    <div data-i18n="Support List">Support List</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('superadmin.noticeboard.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="News And Events">News And Events</div>
                        </a>
                    </li>
             
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('superadmin.income.report') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Income Report">Income Report</div>
                        </a>
                    </li>
          
                    </li>
                </ul>
                </li>
                </ul>
            </aside>
            <!-- / Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="container-xxl">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="bx bx-menu bx-sm"></i>
                            </a>
                        </div>
                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <!-- Search -->
                            <div class="navbar-nav align-items-center">
                                <div class="nav-item navbar-search-wrapper mb-0">
                                    <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
                                        <i class="bx bx-search-alt bx-sm"></i>
                                        <span class="d-none d-md-inline-block">Search (Ctrl+/)</span>
                                    </a>
                                </div>
                            </div>
                            <!-- /Search -->
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- User -->
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                        data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <img src="{{ Auth::guard('superadmin')->user()?->profile_image ? asset('storage/' . Auth::guard('superadmin')->user()?->profile_image) : asset('assets/img/avatars/1.png') }}"
                                                alt class="rounded-circle" />
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="pages-account-settings-account.html">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar-online">
                                                            <img src="{{ Auth::guard('superadmin')->user()?->profile_image ? asset('storage/' . Auth::guard('superadmin')->user()?->profile_image) : asset('assets/img/avatars/1.png') }}"
                                                                alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    @auth('superadmin')
                                                        <div class="flex-grow-1">
                                                            <span class="fw-medium d-block lh-1">{{ Auth::guard('superadmin')->user()->name
                                                                                                }}</span>
                                                            <small>{{ ucfirst(Auth::guard('superadmin')->user()->role) }}</small>
                                                        </div>
                                                    @endauth
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('superadmin.profile') }}">
                                                <i class="bx bx-user me-2"></i>
                                                <span class="align-middle">My Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="pages-account-settings-account.html">
                                                <i class="bx bx-cog me-2"></i>
                                                <span class="align-middle">Settings</span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('superadmin.change-password.form') }}">
                                                <i class="bx bx-lock-open-alt me-2"></i>
                                                <span class="align-middle">Forgot Password</span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="event.preventDefault(); document.getElementById('superadmin-logout-form').submit();">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </a>
                                            <form id="superadmin-logout-form" action="{{ route('superadmin.logout') }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ User -->
                            </ul>
                        </div>
                        <!-- Search Small Screens -->
                        <div class="navbar-search-wrapper search-input-wrapper container-xxl d-none">
                            <input type="text" class="form-control search-input border-0" placeholder="Search..."
                                aria-label="Search..." />
                            <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
                        </div>
                    </div>
                </nav>
                <!-- / Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">