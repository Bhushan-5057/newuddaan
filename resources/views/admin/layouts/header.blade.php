<!DOCTYPE html>
<html lang="en" class="layout-navbar-fixed layout-compact dark-style layout-menu-fixed" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description" content="" />
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
    <!-- <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" /> -->
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

    .app-brand-link {
        z-index: 999 !important;
    }


    .dataTables_wrapper .dataTables_length select {

        background-color: #283144 !important;

    }
</style>

<style>
    .zoom-image {
        transition: transform 0.3s ease-in-out;
        cursor: zoom-in;
    }

    .zoom-image:hover {
        transform: scale(1.8);
        z-index: 999;
        position: relative;
    }
</style>


<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo mt-2">
                    <a href="{{ route('dashboard.admin') }}" class="app-brand-link">
                        <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="Logo"
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
                    <li class="menu-item ">
                        <a href="{{ route('dashboard.admin') }}" class="menu-link ">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboards">Dashboards</div>
                        </a>
                    </li>
                    <!--
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Users &amp; Info</span>
                    </li> -->
                    @if (auth()->user()->hasPermission('user', 'can_view'))
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                               <i class="menu-icon tf-icons bx bx-group"></i>
                                <div data-i18n="Users">Users</div>

                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="{{ route('adminusers.index') }}" class="menu-link">
                                        <div data-i18n="All Users">All Users</div>
                                    </a>
                                </li>

                                {{-- Added Manual Matching Income link --}}
                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('Registion Form', 'can_view'))
                        <li class="menu-item">
                            <a href="{{ route('admin.custom_fields.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-cog"></i>
                                <div data-i18n="Registion Form">Registion Form</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        @if (auth()->user()->hasPermission('Create Sub Admin', 'can_view'))
                            <li class="menu-item">

                                <a href="{{ route('admin.subadmins') }}" class="menu-link">
                                    <i class="bx bx-user-plus me-1"></i>

                                    <div data-i18n=" Create Sub Admin">Create Sub Admin</div>
                                </a>
                            </li>
                        @endif
                    @endif


                     @if (auth()->user()->hasPermission('Admin Login History', 'can_view'))
                        <li class="menu-item">
                            <a href="{{ route('admin.login.history') }}" class="menu-link">
                              <i class="menu-icon tf-icons bx bx-log-in-circle"></i>
                                <div data-i18n="Admin Login History">Admin Login History</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('KYC Details', 'can_view'))
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                              <i class="menu-icon tf-icons bx bx-id-card"></i>
                                <div data-i18n="KYC Details">KYC Details</div>
                                @if (isset($pendingKycCount) && $pendingKycCount > 0)
                                    <div class="badge rounded-pill bg-danger" style="margin-left: 5px;">
                                        {{ $pendingKycCount }}</div>
                                @endif
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="{{ route('admin.kyc.index') }}" class="menu-link">
                                        <div data-i18n="Pending Kyc">Pending Kyc</div>
                                        @if (isset($pendingKycCount) && $pendingKycCount > 0)
                                            <div class="badge rounded-pill bg-danger" style="margin-left: 5px;">
                                                {{ $pendingKycCount }}</div>
                                        @endif
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.kyc.approvedindex') }}" class="menu-link">
                                        <div data-i18n="Approved Kyc">Approved Kyc</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.kyc.rejectindex') }}" class="menu-link">
                                        <div data-i18n="Rejected Kyc">Rejected Kyc</div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    @endif

                    @if (auth()->user()->hasPermission('Mail Template', 'can_view'))
                        <li class="menu-item">
                            <a href="{{ route('admin.mailtemplates.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-cog"></i>
                                <div data-i18n="Mail Template">Mail Template</div>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('fund', 'can_view'))

                        <li class="menu-item">

                            <a href="javascript:void(0);" class="menu-link menu-toggle">

                                <i class="menu-icon tf-icons bx bx-chart"></i>

                                <div data-i18n="Fund">Fund</div>
                                @if ($pendingfundrequest > 0)
                                    <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                        {{ $pendingfundrequest ?? 'not set' }}
                                    </div>
                                @endif


                            </a>
                            
                            
                             @if (auth()->user()->hasPermission('add Power', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.power.index') }}" class="menu-link">

                                            <div data-i18n="Add Power">Add Power</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                            @if (auth()->user()->hasPermission('Add Power History', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.powerhistory.index') }}" class="menu-link">

                                            <div data-i18n="Add Power History">Add Power History</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif
                            @if (auth()->user()->hasPermission('add fund', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.fund.index') }}" class="menu-link">

                                            <div data-i18n="Add Fund">Add Fund</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                            @if (auth()->user()->hasPermission('Transfer Fund History', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.transferfundtransactions.index') }}"
                                            class="menu-link">

                                            <div data-i18n="Transfer Fund History">Transfer Fund History</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                            @if (auth()->user()->hasPermission('Retrieve Fund', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.retrievefund.index') }}" class="menu-link">

                                            <div data-i18n="Retrieve Fund">Retrieve Fund</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                            @if (auth()->user()->hasPermission('Retrieve Fund History', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.retrievefundtransactions.index') }}"
                                            class="menu-link">

                                            <div data-i18n="Retrieve Fund History">Retrieve Fund History</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                            @if (auth()->user()->hasPermission('Pending Fund Request', 'can_view'))

                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.transferfundtransactionsrequest.index') }}"
                                            class="menu-link">

                                            <div data-i18n="Pending Fund Request">Pending Fund Request</div>
                                            @if ($pendingfundrequest > 0)
                                                <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                                    {{ $pendingfundrequest ?? 'not set' }}
                                                </div>
                                            @endif

                                        </a>

                                    </li>

                                </ul>

                            @endif

                            @if (auth()->user()->hasPermission('Approved Fund Request', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.approvedfundrequest.index') }}" class="menu-link">

                                            <div data-i18n="Approved Fund Request">Approved Fund Request</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                            @if (auth()->user()->hasPermission('Cancelled Fund Request', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.cancelledfundrequest.index') }}" class="menu-link">

                                            <div data-i18n="Cancelled Fund Request">Cancelled Fund Request</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                            @if (auth()->user()->hasPermission('Fund Convert History', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.fund.convert.history') }}" class="menu-link">

                                            <div data-i18n="Fund Convert History">Fund Convert History</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                        </li>

                    @endif


                    @if (auth()->user()->hasPermission('Product Management', 'can_view'))

                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-package"></i>
                                <div data-i18n="Products">Products</div>
                            </a>

                            {{-- Category --}}
                            @if (auth()->user()->hasPermission('Add Category', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.category.index') }}" class="menu-link">
                                            <div data-i18n="Category">Category</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            {{-- Sub Category --}}
                            @if (auth()->user()->hasPermission('Sub Category Management', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.subcategory.index') }}" class="menu-link">
                                            <div data-i18n="Sub Category">Sub Category</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            {{-- Product --}}
                            @if (auth()->user()->hasPermission('Add Product', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.product.index') }}" class="menu-link">
                                            <div data-i18n="Add Product">Add Product</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('admin.product.list') }}" class="menu-link">
                                            <div data-i18n="Product List">Product List</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('admin.stock.index') }}" class="menu-link">
                                            <div data-i18n="Stock Management">Stock Management</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            {{-- Combo Product --}}
                            @if (auth()->user()->hasPermission('Combo Product', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.combo.product.index') }}" class="menu-link">
                                            <div data-i18n="Combo Product">Combo Product</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            {{-- Stock Report --}}
                            @if (auth()->user()->hasPermission('Stock Report', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.stock.report') }}" class="menu-link">
                                            <div data-i18n="Stock Report">Stock Report</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            {{-- Invoice --}}
                            @if (auth()->user()->hasPermission('Invoice Management', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.invoice.index') }}" class="menu-link">
                                            <div data-i18n="Invoice">Invoice</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                        </li>

                    @endif
                    @if (auth()->user()->hasPermission('Franchise Management', 'can_view'))

                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-store"></i>
                                <div data-i18n="Franchise">Franchise</div>

                                @if ($pendingFranchiseOrders > 0)
                                    <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                        {{ $pendingFranchiseOrders ?? 'not set' }}
                                    </div>
                                @endif

                                @if (isset($pendingFranchiseFundRequestCount) && $pendingFranchiseFundRequestCount > 0)
                                    <div class="badge rounded-pill bg-danger" style="margin-left: 5px;">
                                        {{ $pendingFranchiseFundRequestCount }}</div>
                                @endif
                            </a>


                            {{-- Add Franchise --}}
                            @if (auth()->user()->hasPermission('Add Franchise', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.franchise.create') }}" class="menu-link">
                                            <div data-i18n="Add Franchise">Add Franchise</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            {{-- Franchise List --}}
                            @if (auth()->user()->hasPermission('Franchise List', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.franchise.index') }}" class="menu-link">
                                            <div data-i18n="Franchise List">Franchise List</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif


                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="{{ route('admin.franchise.orders') }}" class="menu-link">
                                        <div data-i18n="Franchise Orders">Franchise Orders</div>

                                        @if ($pendingFranchiseOrders > 0)
                                            <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                                {{ $pendingFranchiseOrders ?? 'not set' }}
                                            </div>
                                        @endif
                                    </a>
                                </li>
                            </ul>


                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="{{ route('admin.franchise.fund.requests') }}" class="menu-link">
                                        <div data-i18n="Fund Requests">Fund Requests</div>
                                        @if (isset($pendingFranchiseFundRequestCount) && $pendingFranchiseFundRequestCount > 0)
                                            <div class="badge rounded-pill bg-danger" style="margin-left: 5px;">
                                                {{ $pendingFranchiseFundRequestCount }}</div>
                                        @endif
                                    </a>
                                </li>
                            </ul>


                        </li>

                    @endif



                    {{-- Product Management Section --}}
                    
                     @if (auth()->user()->hasPermission('Product Orders', 'can_view'))
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-cart"></i>
                            <div data-i18n="Product Orders">Product Orders</div>

                            {{-- લાઈવ નોટિફિકેશન કાઉન્ટ --}}
                            @if (isset($pendingProductOrdersCount) && $pendingProductOrdersCount > 0)
                                <div class="badge rounded-pill bg-danger ms-2">
                                    {{ $pendingProductOrdersCount }}
                                </div>
                            @endif
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{ route('admin.product.orders', 'pending') }}" class="menu-link">
                                    <div data-i18n="Pending">Pending</div>
                                    @if (isset($pendingProductOrdersCount) && $pendingProductOrdersCount > 0)
                                        <div class="badge bg-warning text-dark ms-2">{{ $pendingProductOrdersCount }}
                                        </div>
                                    @endif
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.product.orders', 'completed') }}" class="menu-link">
                                    <div data-i18n="Completed">Completed</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.product.orders', 'canceled') }}" class="menu-link">
                                    <div data-i18n="Canceled/Return">Canceled/Return</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                         @endif


                    @if (auth()->user()->hasPermission('Pin Details', 'can_view'))
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-chart"></i>
                                <div data-i18n="Pin Details">Pin Details</div>
                            </a>
                            @if (auth()->user()->hasPermission('Pin Details Add Custom Fields', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.pin-details.custom_fields.index') }}"
                                            class="menu-link">
                                            <div data-i18n="Add Custom Fields">Add Custom Fields</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (auth()->user()->hasPermission('Pin Details List', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.pindetails.index') }}" class="menu-link">
                                            <div data-i18n="Pin Details List">Pin Details List</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('Plan', 'can_view'))
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-chart"></i>
                                <div data-i18n="Plan">Plan</div>
                            </a>
                            @if (auth()->user()->hasPermission('Plan Add Custom Fields', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.plan.custom_fields.index') }}" class="menu-link">
                                            <div data-i18n="Add Custom Fields">Add Custom Fields</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (auth()->user()->hasPermission('Plan List', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.plan.index') }}" class="menu-link">
                                            <div data-i18n="Plan List">Plan List</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('Withdrawal', 'can_view'))
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-chart"></i>
                                <div data-i18n="Withdrawal">Withdrawal</div>
                                @if ($pendingwithdrwalsrequest > 0)
                                    <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                        {{ $pendingwithdrwalsrequest ?? 'not set' }}
                                    </div>
                                @endif
                            </a>
                            @if (auth()->user()->hasPermission('Pending Withdrawal Requests', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.withdrwalstransactionsrequest.index') }}"
                                            class="menu-link">
                                            <div data-i18n="Pending Request"> Pending Request</div>
                                            @if ($pendingwithdrwalsrequest > 0)
                                                <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                                    {{ $pendingwithdrwalsrequest ?? 'not set' }}
                                                </div>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (auth()->user()->hasPermission('Approved Withdrawal Requests', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.approvedwithdrwalsrequest.index') }}"
                                            class="menu-link">
                                            <div data-i18n="Approved Request"> Approved Request</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (auth()->user()->hasPermission('Cancelled Withdrawal Requests', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.cancelledwithdrwalsrequest.index') }}"
                                            class="menu-link">
                                            <div data-i18n="Cancelled Request"> Cancelled Request</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('Principal Withdrawal', 'can_view'))

                        <li class="menu-item">

                            <a href="javascript:void(0);" class="menu-link menu-toggle">

                                <i class="menu-icon tf-icons bx bx-chart"></i>

                                <div data-i18n="Principal Withdrawal">Principal Withdrawal</div>
                                @if ($pendingPrincipalwithdrwalsrequest > 0)
                                    <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                        {{ $pendingPrincipalwithdrwalsrequest ?? 'not set' }}
                                    </div>
                                @endif

                            </a>

                            @if (auth()->user()->hasPermission('Pending Principal Withdrawal Requests', 'can_view'))

                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.principalwithdrwalstransactionsrequest.index') }}"
                                            class="menu-link">

                                            <div data-i18n="Pending Principal Withdrawal Request"> Pending Principal
                                                Withdrawal Request</div>
                                            @if ($pendingPrincipalwithdrwalsrequest > 0)
                                                <div style="color: red;" class="badge bg-warning text-dark ms-2">
                                                    {{ $pendingPrincipalwithdrwalsrequest ?? 'not set' }}
                                                </div>
                                            @endif

                                        </a>

                                    </li>

                                </ul>

                            @endif

                            @if (auth()->user()->hasPermission('Approved Principal Withdrawal Requests', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.approvedprincipalwithdrwalsrequest.index') }}"
                                            class="menu-link">

                                            <div data-i18n="Approved Principal Withdrawal Request"> Approved Principal
                                                Withdrawal Request</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                            @if (auth()->user()->hasPermission('Cancelled Principal Withdrawal Requests', 'can_view'))
                                <ul class="menu-sub">

                                    <li class="menu-item">

                                        <a href="{{ route('admin.cancelledprincipalwithdrwalsrequest.index') }}"
                                            class="menu-link">

                                            <div data-i18n="Cancelled Principal Withdrawal Request"> Cancelled
                                                Principal Withdrawal Request</div>

                                        </a>

                                    </li>

                                </ul>
                            @endif

                        </li>

                    @endif
                    
                         @if (auth()->user()->hasPermission('Order History', 'can_view'))
                            <li class="menu-item">
                                <a href="{{ route('admin.topup') }}" class="menu-link">
                                    <!-- Topup માટે Wallet આઇકન -->
                                    <i class="menu-icon tf-icons bx bx-wallet-alt"></i>
                                    <div data-i18n="Admin Topup">Admin Topup</div>
                                </a>
                            </li>
                        @endif
                        
                        @if (auth()->user()->hasPermission('Order History', 'can_view'))
                            <li class="menu-item">
                                <a href="{{ route('admin.topup.history') }}" class="menu-link">
                                    <!-- Order History માટે History/Receipt આઇકન -->
                                    <i class="menu-icon tf-icons bx bx-history"></i>
                                    <div data-i18n="Order History">Order History</div>
                                </a>
                            </li>
                        @endif
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                          <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
                            <div data-i18n="Incomes">Incomes</div>
                        </a>
                        <ul class="menu-sub">
                            @foreach ($incometype as $type)
                                <li class="menu-item">
                                    <a href="{{ route('admin.income.history', $type->slug) }}" class="menu-link">
                                        <div data-i18n="{{ $type->name }}">{{ $type->name }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @if (auth()->user()->hasPermission('Settings', 'can_view'))
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-cog"></i>
                                <div data-i18n="Settings">Settings</div>
                            </a>
                            @if (auth()->user()->hasPermission('Company Info', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.companyinfo.index') }}" class="menu-link">
                                            <div data-i18n="Company Info">Company Info</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (auth()->user()->hasPermission('Advance Info', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.advanceinfo.index') }}" class="menu-link">
                                            <div data-i18n="Advance Info">Advance Info</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            
                             @if (auth()->user()->hasPermission('Rank Data', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.ranks.index') }}" class="menu-link">
                                            <div data-i18n="Rank's Data">Rank's Data</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif

@if (auth()->user()->hasPermission('ClubMember', 'can_view'))
    <li class="menu-item">
        <a href="{{ route('admin.rank') }}" class="menu-link">
            <!-- Rank Achievers માટે Trophy આઇકન -->
            <i class="menu-icon tf-icons bx bx-trophy text-warning"></i>
            <div data-i18n="Rank Achievers">Rank Achievers</div>
        </a>
    </li>
@endif

@if (auth()->user()->hasPermission('ClubMember', 'can_view'))
    <li class="menu-item">
        <a href="{{ route('admin.royalty.report') }}" class="menu-link">
            <!-- Royalty Achievers માટે Crown આઇકન -->
            <i class="menu-icon tf-icons bx bx-crown text-primary"></i>
            <div data-i18n="Royalty Achievers">Royalty Achievers</div>
        </a>
    </li>
@endif


                    @if (auth()->user()->hasPermission('Bonanza', 'can_view'))
                        <li class="menu-item">
                            <a href="{{ route('admin.bonanza.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-cog"></i>
                                <div data-i18n="Bonanza">Bonanza</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('admin.bonanza.achievers') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-cog"></i>
                                <div data-i18n="Bonanza Achievers">Bonanza Achievers</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('Income Cron', 'can_view'))
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                               <i class="menu-icon tf-icons bx bx-time-five"></i>
                                <div data-i18n="Income Cron">Income Cron</div>
                            </a>
                            <ul class="menu-sub">
                                {{-- Binary Matching --}}
                                <li class="menu-item">
                                    <a href="{{ route('admin.command.matching.closing') }}" class="menu-link">
                                        <div data-i18n="Binary Matching">Binary Matching</div>
                                    </a>
                                </li>



                                {{-- Rank Closing --}}
                                <li class="menu-item">
                                    <a href="{{ route('admin.command.rank.closing') }}"
                                        class="menu-link text-warning">
                                        <div data-i18n="Rank Achievement Closing">Rank Achievement Closing</div>
                                    </a>
                                </li>

                         
                                <li class="menu-item">
                                    <a href="{{ route('admin.command.monthly.income.release') }}"
                                        class="menu-link text-info">
                                        <div data-i18n="Royalty Income">Royalty Income</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.command.withdrawal.auto') }}"
                                        class="menu-link text-danger">
                                        <div data-i18n="Auto Withdrawal">Auto Withdrawal</div>
                                    </a>
                                </li>





                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('Payment Methods', 'can_view'))
                        <li class="menu-item">
                            <a href="{{ route('admin.paymentmethods.index') }}" class="menu-link">
                              <i class="menu-icon tf-icons bx bx-credit-card"></i>
                                <div data-i18n="Payment Methods">Payment Methods</div>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('Support', 'can_view'))
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                               <i class="menu-icon tf-icons bx bx-support"></i>
                                <div data-i18n="Support">Support</div>
                            </a>
                            @if (auth()->user()->hasPermission('Support List', 'can_view'))
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.support.pending') }}" class="menu-link">
                                            <div data-i18n="Support List">Support List</div>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('Notice Board', 'can_view'))
                        <li class="menu-item">
                            <a href="{{ route('admin.noticeboard.index') }}" class="menu-link">
                             <i class="menu-icon tf-icons bx bx-news"></i>
                                <div data-i18n="News And Events">News And Events</div>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('Income Reports', 'can_view'))
                        <li class="menu-item">
                            <a href="{{ route('admin.income.report') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                                <div data-i18n="Income Report">Income Report</div>
                            </a>
                        </li>
                    @endif
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


                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- Language -->
                                <!--/ Language -->
                                <!-- Style Switcher -->
                                <!-- / Style Switcher-->
                                <!-- Quick links  -->
                                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                                    {{--       <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="bx bx-grid-alt bx-sm"></i>
                                    </a> --}}
                                    <div class="dropdown-menu dropdown-menu-end py-0">
                                        <div class="dropdown-menu-header border-bottom">
                                            <div class="dropdown-header d-flex align-items-center py-3">
                                                <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                                                <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Add shortcuts"><i class="bx bx-sm bx-plus-circle"></i></a>
                                            </div>
                                        </div>
                                        <div class="dropdown-shortcuts-list scrollable-container">
                                            <div class="row row-bordered overflow-visible g-0">
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-calendar fs-4"></i>
                                                    </span>
                                                    <a href="app-calendar.html" class="stretched-link">Calendar</a>
                                                    <small class="text-muted mb-0">Appointments</small>
                                                </div>
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-food-menu fs-4"></i>
                                                    </span>
                                                    <a href="app-invoice-list.html" class="stretched-link">Invoice
                                                        App</a>
                                                    <small class="text-muted mb-0">Manage Accounts</small>
                                                </div>
                                            </div>
                                            <div class="row row-bordered overflow-visible g-0">
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-user fs-4"></i>
                                                    </span>
                                                    <a href="app-user-list.html" class="stretched-link">User App</a>
                                                    <small class="text-muted mb-0">Manage Users</small>
                                                </div>
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-check-shield fs-4"></i>
                                                    </span>
                                                    <a href="app-access-roles.html" class="stretched-link">Role
                                                        Management</a>
                                                    <small class="text-muted mb-0">Permission</small>
                                                </div>
                                            </div>
                                            <div class="row row-bordered overflow-visible g-0">
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-pie-chart-alt-2 fs-4"></i>
                                                    </span>
                                                    <a href="index.html" class="stretched-link">Dashboard</a>
                                                    <small class="text-muted mb-0">User Profile</small>
                                                </div>
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-cog fs-4"></i>
                                                    </span>
                                                    <a href="pages-account-settings-account.html"
                                                        class="stretched-link">Setting</a>
                                                    <small class="text-muted mb-0">Account Settings</small>
                                                </div>
                                            </div>
                                            <div class="row row-bordered overflow-visible g-0">
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-help-circle fs-4"></i>
                                                    </span>
                                                    <a href="pages-faq.html" class="stretched-link">FAQs</a>
                                                    <small class="text-muted mb-0">FAQs & Articles</small>
                                                </div>
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-window-open fs-4"></i>
                                                    </span>
                                                    <a href="modal-examples.html" class="stretched-link">Modals</a>
                                                    <small class="text-muted mb-0">Useful Popups</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <!-- Quick links -->
                                <!-- Notification -->
                                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                                    {{--   <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="bx bx-bell bx-sm"></i>
                                        <span class="badge bg-danger rounded-pill badge-notifications">5</span>
                                    </a> --}}
                                    <ul class="dropdown-menu dropdown-menu-end py-0">
                                        <li class="dropdown-menu-header border-bottom">
                                            <div class="dropdown-header d-flex align-items-center py-3">
                                                <h5 class="text-body mb-0 me-auto">Notification</h5>
                                                <a href="javascript:void(0)"
                                                    class="dropdown-notifications-all text-body"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Mark all as read"><i
                                                        class="bx fs-4 bx-envelope-open"></i></a>
                                            </div>
                                        </li>
                                        <li class="dropdown-notifications-list scrollable-container">
                                            <ul class="list-group list-group-flush">
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="../../assets/img/avatars/1.png" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Congratulation Lettie 🎉</h6>
                                                            <p class="mb-0">Won the monthly best seller gold badge
                                                            </p>
                                                            <small class="text-muted">1h ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Charles Franklin</h6>
                                                            <p class="mb-0">Accepted your connection</p>
                                                            <small class="text-muted">12hr ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="../../assets/img/avatars/2.png" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">New Message ✉️</h6>
                                                            <p class="mb-0">You have new message from Natalie</p>
                                                            <small class="text-muted">1h ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-success"><i
                                                                        class="bx bx-cart"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Whoo! You have new order 🛒</h6>
                                                            <p class="mb-0">ACME Inc. made new order $1,154</p>
                                                            <small class="text-muted">1 day ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="../../assets/img/avatars/9.png" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Application has been approved 🚀</h6>
                                                            <p class="mb-0">Your ABC project application has been
                                                                approved.</p>
                                                            <small class="text-muted">2 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-success"><i
                                                                        class="bx bx-pie-chart-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Monthly report is generated</h6>
                                                            <p class="mb-0">July monthly financial report is
                                                                generated
                                                            </p>
                                                            <small class="text-muted">3 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="../../assets/img/avatars/5.png" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Send connection request</h6>
                                                            <p class="mb-0">Peter sent you connection request</p>
                                                            <small class="text-muted">4 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="../../assets/img/avatars/6.png" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">New message from Jane</h6>
                                                            <p class="mb-0">Your have new message from Jane</p>
                                                            <small class="text-muted">5 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-warning"><i
                                                                        class="bx bx-error"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">CPU is running high</h6>
                                                            <p class="mb-0">CPU Utilization Percent is currently at
                                                                88.63%,</p>
                                                            <small class="text-muted">5 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="dropdown-menu-footer border-top">
                                            <a href="javascript:void(0);"
                                                class="dropdown-item d-flex justify-content-center p-3">
                                                View all notifications
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ Notification -->
                                <!-- User -->
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                        data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <img src="{{ Auth::guard('admin')->user()?->profile_image ? asset('storage/' . Auth::guard('admin')->user()?->profile_image) : asset('assets/img/avatars/1.png') }}"
                                                alt class="rounded-circle" />
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="pages-account-settings-account.html">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar-online">
                                                            <img src="{{ Auth::guard('admin')->user()?->profile_image ? asset('storage/' . Auth::guard('admin')->user()?->profile_image) : asset('assets/img/avatars/1.png') }}"
                                                                alt class="rounded-circle" />
                                                        </div>
                                                    </div>
                                                    @auth('admin')
                                                        <div class="flex-grow-1">
                                                            <span
                                                                class="fw-medium d-block lh-1">{{ Auth::guard('admin')->user()->name }}</span>
                                                            <small>{{ ucfirst(Auth::guard('admin')->user()->role) }}</small>
                                                        </div>
                                                    @endauth
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                                <i class="bx bx-user me-2"></i>
                                                <span class="align-middle">My Profile</span>
                                            </a>
                                        </li>
                                        {{--  
                                        <li>
                                            <a class="dropdown-item" href="pages-account-settings-account.html">
                                                <i class="bx bx-cog me-2"></i>
                                                <span class="align-middle">Settings</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="pages-account-settings-billing.html">
                                                <span class="d-flex align-items-center align-middle">
                                                    <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                                    <span class="flex-grow-1 align-middle">Billing</span>
                                                    <span
                                                        class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="pages-faq.html">
                                                <i class="bx bx-help-circle me-2"></i>
                                                <span class="align-middle">FAQ</span>
                                            </a>
                                        </li>
  --}}
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.change-password.form') }}">
                                                <i class="bx bx-lock-open-alt me-2"></i>
                                                <span class="align-middle">Forgot Password</span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"><i
                                                    class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </a>
                                            <form id="admin-logout-form" action="{{ route('admin.logout') }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ User -->
                            </ul>
                        </div>

                    </div>
                </nav>
                <!-- / Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
