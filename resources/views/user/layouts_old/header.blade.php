<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ company_info('company_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('uploads/' . company_info('favicon')) }}" type="image/png" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Plugins CSS -->
    <link href="{{ asset('userpanel/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('userpanel/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('userpanel/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('userpanel/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- Loader CSS -->
    <link href="{{ asset('userpanel/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('userpanel/assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('userpanel/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('userpanel/assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
    <!-- Core CSS -->
    <link href="{{ asset('userpanel/assets/css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('userpanel/assets/css/icons.css') }}" rel="stylesheet" />
    <!-- <link href="{{ asset('userpanel/assets/css/new.css') }}" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('userpanel/assets/css/all_custom.css') }}" rel="stylesheet" /> -->
    <!-- Extra & Theme Styles -->
    <!-- <link href="{{ asset('userpanel/assets/css/custom.css') }}" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('userpanel/assets/css/style1.css') }}" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('userpanel/assets/css/extra.css') }}" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('userpanel/assets/css/dark-theme.css') }}" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('userpanel/assets/css/semi-dark.css') }}" rel="stylesheet" /> -->
    <!-- <link href="{{ asset('userpanel/assets/css/header-colors.css') }}" rel="stylesheet" /> -->
    <!-- jQuery and Bootstrap JS -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <!-- Chart Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" charset="utf-8"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js" charset="utf-8">
    </script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script> -->
    <!-- <link rel="stylesheet" href="../../assets/vendor/css/pages/page-profile.css" /> -->
    <!-- Custom Inline CSS -->
    <style>
        body {
            background: #110a36;

            color: #fff;
            font-family: 'Quicksand', sans-serif;
        }



        /* variables-css  */
        :root {
            /* --first: linear-gradient(180deg, #005fc9, #005fc9); */
            /* --first:  linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)); */
            --second: #fd961a;
            --text1: #ff9a0c;
            --text2: #fff;
            --text3_light: #00070f;
            --black: #000;
            --box: linear-gradient(135deg, #fd961a 0%, #ff9a0c 100%);
        }

        :root {
            /* 🎨 Brand Colors */
            /* --primary: linear-gradient(135deg, #667eea 0%, #005fc9 100%); */
            --primary: linear-gradient(135deg, #fb347d, #fc7071);
            --secondary: linear-gradient(135deg, #fd961a 0%, #ff9a0c 100%);
            --accent: #fd961a;
            --accent-light: #ffb347;
            --accent-dark: #e57c00;

            /* 🖋️ Text Colors */
            --text-color: #fff;

            --text-muted: #d1d1d1;

            /* 🌈 Gradient Variants */
            /* --primary-gradient: linear-gradient(135deg, #667eea 0%, #005fc9 100%); */
            --primary-gradient: linear-gradient(135deg, #fb347d, #fc7071);
            --secondary-gradient: linear-gradient(135deg, #fd961a 0%, #ff9a0c 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
            --warning-gradient: linear-gradient(135deg, #ffc371 0%, #ff5f6d 100%);
            --primary-light: #ffb347;

            /* 🧊 Card & Glass Effects */
            --card-bg: rgba(0, 0, 0, 0.6);
            --glass-effect: rgba(255, 255, 255, 0.15);
            --blur-effect: blur(10px);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);

            /* 🕹️ Transitions & Shadows */
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --neumorphic-shadow: 8px 8px 16px #151515, -8px -8px 16px #2a2a2a;
        }



        .page-breadcrumb {
            margin-left: 0 !important;
            padding-top: 20px !important;
            /* Assuming this was also part of the original inline style */
        }

        .sidebar-header {
            background: var(--first);
        }

        .sidebar-wrapper .metismenu a .parent-icon {
            font-size: 14px;
        }

        .parent-icon i {
            background: #fff;
            width: 22px;
            height: 22px;
            color: #263443;
            line-height: 22px;
            text-align: center;
            border-radius: 40px;
            font-size: 16px;
        }

        .page-footer .topbar .navbar {
            background: #2d325a;
        }

        .topbar .navbar {
            background: #2d325a;
        }

        .sidebar-wrapper {
            /*background:*/
            /*    linear-gradient(rgb(0 0 0), rgb(0 0 0 / 79%)),*/
            /*     transparent black overlay */
            /*    url('{{ asset('userpanel/assets/images/bg-themes/11.png') }}') no-repeat center center;*/
            /*background-size: cover;*/
            /*background-attachment: fixed;*/
            color: #fff;
            /* optional for contrast */
        }

        .sidebar-wrapper .metismenu a {
            color: var(--text2);
            font-size: 14px;
        }

        .sidebar-wrapper .metismenu ul a {
            font-size: 14px;
        }

        .sidebar-wrapper .metismenu .mm-active>a {
            background: linear-gradient(135deg, #fb347d, #fc7071);

            color: #fff;
            font-size: 14px;
            border: none;
            border-radius: 4px;
        }

        .sidebar-wrapper .metismenu a:hover {
            background: linear-gradient(135deg, #fb347d, #fc7071);

            color: #fff;
        }

        .sidebar-wrapper .metismenu ul {
            background: #2d325a;
            border: none;
        }

        .sidebar-wrapper .metismenu a:focus {
            color: #fff;
            border: none;
            text-decoration: none;
            background: linear-gradient(135deg, #fb347d, #fc7071);
        }
    </style>
</head>

<body>






    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="Logo"
                        style="height:50px; width:50px;">
                </div>
                <div>
                    <!--<h4 class="logo-text">Rocker</h4>-->
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <div class="proflie_user text-center py-3">
                        @if (!empty(auth()->user()->profile_img))
                            <img src="{{ asset('uploads/' . auth()->user()->profile_img) }}" alt="Profile"
                                class="rounded-circle" style="height:60px; width:60px; object-fit:cover;">
                        @else
                            <img src="{{ asset('uploads/' . company_info('logo')) }}" alt="Logo" class=""
                                style="height:60px;  object-fit:cover;">
                        @endif
                        @php
                            $name = Auth::user()->name;
                            $shortName = strlen($name) > 12 ? substr($name, 0, 6) . '...' . substr($name, -5) : $name;
                        @endphp
                        <h4 class="mb-1 text-white">{{ $shortName }}</h4>
                        <span class="text-white d-block">{{ Auth::user()->username }}</span>
                    </div>
                </li>
                <li>
                    <a href="{{ route('dashboard.user') }}" class="">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                        <div class="menu-title">My Profile</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('user.profile') }}">
                                <i class="bx bx-right-arrow-alt"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.profile.edit') }}">
                                <i class="bx bx-right-arrow-alt"></i> Edit Profile
                            </a>
                        </li>
                        {{-- New KYC link added below --}}

                        @if (auth()->user()->hasPermission('KYC', 'can_view'))
                            <li>
                                <a href="{{ route('user.kyc.show') }}">
                                    <i class="bx bx-right-arrow-alt"></i> KYC
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('Account Setting', 'can_view'))
                            <li>
                                <a href="{{ route('user.accountsettings.create') }}">
                                    <i class="bx bx-right-arrow-alt"></i> Account Setting
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>


                @if (auth()->user()->hasPermission('My Genealogy', 'can_view'))
                    <li>
                        <a class="has-arrow" href="javascript:;">
                            <div class="parent-icon"><i class="fa fa-users" aria-hidden="true"></i></div>
                            <div class="menu-title">My Genealogy</div>
                        </a>
                        <ul>
                            @if (auth()->user()->hasPermission('Direct Users', 'can_view'))
                                <li>
                                    <a href="{{ route('user.genealogy.direct-users') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Direct
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->hasPermission('Genealogy Team', 'can_view'))
                                <li>
                                    <a href="{{ route('user.team.users') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Team Users
                                    </a>
                                </li>
                            @endif

                            @if ($RegistrationType == 'binary')
                                @if (auth()->user()->hasPermission('Genealogy Tree', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.tree.view') }}">
                                            <i class="bx bx-right-arrow-alt"></i> Binary Tree
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermission('Left Team', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.genealogy.left-team') }}">
                                            <i class="bx bx-right-arrow-alt"></i> Left Team
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermission('Right Team', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.genealogy.right-team') }}">
                                            <i class="bx bx-right-arrow-alt"></i> Right Team
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermission('Tree', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.universal.tree') }}">
                                            <i class="bx bx-right-arrow-alt"></i> Tree
                                        </a>
                                    </li>
                                @endif
                            @endif


                            @if (auth()->user()->hasPermission('Matrix', 'can_view'))
                                <li>
                                    <a href="{{ route('user.matrix.tree') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Matrix
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->hasPermission('Single Leg Team', 'can_view'))
                                <li>
                                    <a href="{{ route('user.singleleg.tree') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Single Leg
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif


                @if (auth()->user()->hasPermission('Fund', 'can_view'))
                    <li>
                        <a class="has-arrow" href="javascript:;">
                            <div class="parent-icon"><i class='bx bx-bookmark-heart'></i></div>
                            <div class="menu-title">Fund</div>
                        </a>
                        <ul>
                            @if (auth()->user()->hasPermission('add fund', 'can_view') || Advance_info('Registration_Type', 'normal') === 'dapp')
                                <li>
                                    <a href="{{ route('user.fund.index') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Add Fund
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->hasPermission('add fund history', 'can_view') ||
                                    Advance_info('Registration_Type', 'normal') === 'dapp')
                                <li>
                                    <a href="{{ route('user.addfund.history') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Add Fund History
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->hasPermission('add fund request', 'can_view'))
                                <li>
                                    <a href="{{ route('user.fundrequest.index') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Fund Request
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->hasPermission('fund request history', 'can_view'))
                                <li>
                                    <a href="{{ route('user.fundrequest.history') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Fund Request History
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->hasPermission('fund transfer', 'can_view'))
                                <li>
                                    <a href="{{ route('user.fundtransfer.index') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Transfer Fund
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.fundtransfer.history') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Fund Transfer History
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->hasPermission('fund convert', 'can_view'))
                                <li>
                                    <a href="{{ route('user.walletinternal.index') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Fund Convert
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('fund.convert.history') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Fund Convert History
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
                @if (auth()->user()->hasPermission('Withdrawal', 'can_view'))
                    <li>
                        <a class="has-arrow" href="javascript:;">
                            <div class="parent-icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                            <div class="menu-title">Withdrawal</div>
                        </a>
                        <ul>
                            @if (auth()->user()->hasPermission('Withdrawal_view', 'can_view'))
                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


                                @php $deposit_status = auth()->user()->deposit_status ?? 0; @endphp


                                @if (isset($deposit_status))
                                    @if ($deposit_status == 1)
                                        <li>
                                            <a href="{{ route('user.payout.index') }}">
                                                <i class="bx bx-right-arrow-alt"></i> Withdrawal
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="mb-2 nav-link" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal">
                                                <i class="bx bx-right-arrow-alt"></i> Withdrawal
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            @endif
                            @if (auth()->user()->hasPermission('Withdrawal Report', 'can_view'))
                                <li><a href="{{ route('user.withdrawals') }}"><i class="bx bx-right-arrow-alt"></i>
                                        Withdrawal Report</a></li>
                            @endif
                            @if (auth()->user()->hasPermission('Principal Withdrawal', 'can_view'))
                                <li><a href="{{ route('user.principal.withdraw.index') }}"><i
                                            class="bx bx-right-arrow-alt"></i> Principal Withdrawal</a></li>
                            @endif
                            @if (auth()->user()->hasPermission('Principal Withdrawal Report', 'can_view'))
                                <li><a href="{{ route('user.principal.withdraw') }}"><i
                                            class="bx bx-right-arrow-alt"></i> Principal Withdrawal Report</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->hasPermission('Invest', 'can_view'))
                    <li>
                        <a class="has-arrow" href="javascript:;">
                            <div class="parent-icon"><i class="bx bx-message-square-edit"></i></div>
                            <div class="menu-title">Topup</div>
                        </a>
                        <ul>

                            @if (auth()->user()->hasPermission('Topup', 'can_view'))
                                <li>
                                    <a href="{{ route('user.topup.index') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Member Topup
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->hasPermission('Orders', 'can_view'))
                                <li>
                                    <a href="{{ route('user.order.history') }}">
                                        <i class="bx bx-right-arrow-alt"></i> Topup History
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                
                @if (auth()->user()->hasPermission('Income Details', 'can_view'))
                    <li>
                        <a class="has-arrow" href="javascript:;">
                            <div class="parent-icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                            <div class="menu-title">Payout Reports</div>
                        </a>
                        <ul>
                            @foreach ($incometype as $type)
                                @if (auth()->user()->hasPermission('User Income', 'can_view'))
                                    <li>
                                        <a href="{{ route('user.income.details', $type->slug) }}">
                                            <i class="bx bx-right-arrow-alt"></i> {{ $type->name }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->hasPermission('Income Report', 'can_view'))
                    <li>
                        <a href="{{ route('user.income.report') }}">
                            <div class="parent-icon"><i class="fa-solid fa-sack-dollar" aria-hidden="true"></i></div>
                            <div class="menu-title">Income Report</div>
                        </a>
                    </li>
                @endif


                <li>
                    <a href="{{ route('user.royalty.achievement') }}">
                        <div class="parent-icon"><i class="fa-solid fa-crown" aria-hidden="true"></i></div>
                        <div class="menu-title">Royalty and Rewards</div>
                    </a>
                </li>

                @if (auth()->user()->hasPermission('Reward', 'can_view'))
                    <li>
                        <a href="{{ route('user.bonanza.index') }}">
                            <div class="parent-icon"><i class="fa fa-trophy" aria-hidden="true"></i></div>
                            <div class="menu-title">TOUR REWARD (bonanza)</div>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->hasPermission('Support', 'can_view'))
                    <li>
                        <a href="{{ route('user.support.index') }}">
                            <div class="parent-icon"><i class="fa fa-life-ring" aria-hidden="true"></i></div>
                            <div class="menu-title">Support</div>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('logout') }}" aria-expanded="false"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="parent-icon"><i class="fa fa-sign-out" aria-hidden="true"></i></div>
                        <div class="menu-title">Logout</div>
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="ghost1 ">
                    </div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item dropdown dropdown-large">
                                <!--<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">7</span>
         <i class='bx bx-bell'></i>
        </a>-->
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Notifications</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i
                                                        class="bx bx-group"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Customers<span
                                                            class="msg-time float-end">14 Sec
                                                            ago</span></h6>
                                                    <p class="msg-info">5 new user registered</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-danger text-danger"><i
                                                        class="bx bx-cart-alt"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Orders <span class="msg-time float-end">2
                                                            min
                                                            ago</span></h6>
                                                    <p class="msg-info">You have recived new orders</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-success text-success"><i
                                                        class="bx bx-file"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">24 PDF File<span
                                                            class="msg-time float-end">19 min
                                                            ago</span></h6>
                                                    <p class="msg-info">The pdf files generated</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-warning text-warning"><i
                                                        class="bx bx-send"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Time Response <span
                                                            class="msg-time float-end">28 min
                                                            ago</span></h6>
                                                    <p class="msg-info">5.1 min avarage time response</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-info text-info"><i
                                                        class="bx bx-home-circle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Product Approved <span
                                                            class="msg-time float-end">2 hrs ago</span></h6>
                                                    <p class="msg-info">Your new product has approved</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-danger text-danger"><i
                                                        class="bx bx-message-detail"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Comments <span
                                                            class="msg-time float-end">4 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">New customer comments recived</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-success text-success"><i
                                                        class='bx bx-check-square'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Your item is shipped <span
                                                            class="msg-time float-end">5 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">Successfully shipped your item</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i
                                                        class='bx bx-user-pin'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New 24 authors<span
                                                            class="msg-time float-end">1 day
                                                            ago</span></h6>
                                                    <p class="msg-info">24 new authors joined last week</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-warning text-warning"><i
                                                        class='bx bx-door-open'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Defense Alerts <span
                                                            class="msg-time float-end">2 weeks
                                                            ago</span></h6>
                                                    <p class="msg-info">45% less alerts last 4 weeks</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Notifications</div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large">
                                <!--<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
         <i class='bx bx-comment'></i>
        </a>-->
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Messages</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-message-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-1.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Daisy Anderson <span
                                                            class="msg-time float-end">5 sec
                                                            ago</span></h6>
                                                    <p class="msg-info">The standard chunk of lorem</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-2.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Althea Cabardo <span
                                                            class="msg-time float-end">14
                                                            sec ago</span></h6>
                                                    <p class="msg-info">Many desktop publishing packages</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-3.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Oscar Garner <span
                                                            class="msg-time float-end">8 min
                                                            ago</span></h6>
                                                    <p class="msg-info">Various versions have evolved over</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-4.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Katherine Pechon <span
                                                            class="msg-time float-end">15
                                                            min ago</span></h6>
                                                    <p class="msg-info">Making this the first true generator</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-5.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Amelia Doe <span
                                                            class="msg-time float-end">22 min
                                                            ago</span></h6>
                                                    <p class="msg-info">Duis aute irure dolor in reprehenderit</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-6.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Cristina Jhons <span
                                                            class="msg-time float-end">2 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">The passage is attributed to an unknown</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-7.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">James Caviness <span
                                                            class="msg-time float-end">4 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">The point of using Lorem</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-8.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Peter Costanzo <span
                                                            class="msg-time float-end">6 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">It was popularised in the 1960s</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-9.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">David Buckley <span
                                                            class="msg-time float-end">2 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">Various versions have evolved over</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-10.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Thomas Wheeler <span
                                                            class="msg-time float-end">2 days
                                                            ago</span></h6>
                                                    <p class="msg-info">If you are going to use a passage</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-11.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Johnny Seitz <span
                                                            class="msg-time float-end">5 days
                                                            ago</span></h6>
                                                    <p class="msg-info">All the Lorem Ipsum generators</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Messages</div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>


                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                            href="{{ route('user.profile') }}" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            @if (Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="user-img"
                                    alt="user avatar" style="width:50px;height:50px">
                            @else
                                <img src="{{ asset('assets/img/avatars/3.png') }}" class="user-img"
                                    alt="user avatar" style="width:50px;height:50px">
                            @endif
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">{{ $shortName }}</p>
                                <p class="designattion mb-0">{{ Auth::user()->email }}</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Google Translate Integration -->
                            <li class="px-3">
                                <div id="google_translate_element"></div>
                                <script type="text/javascript">
                                    function googleTranslateElementInit() {
                                        new google.translate.TranslateElement({
                                            pageLanguage: 'en'
                                        }, 'google_translate_element');
                                    }
                                </script>
                                <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                            </li>
                            <!-- Profile Link -->
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="bx bx-user"></i>
                                    <span> My Profile</span>
                                </a>
                            </li>
                            <!-- Optional: Account Setting -->
                            <li>
                                <a class="dropdown-item" href="{{ route('user.accountsettings.create') }}">
                                    <i class="bx bx-cog"></i>
                                    <span>Account Setting</span>
                                </a>
                            </li>
                            <!-- Support -->
                            <li>
                                <a class="dropdown-item" href="{{ route('user.support.index') }}">
                                    <i class="bx bx-help-circle"></i>
                                    <span>Support</span>
                                </a>
                            </li>
                            <!-- Logout -->
                            <li>
                                <a href="{{ route('logout') }}" class="ai-icon" aria-expanded="false"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="flaticon-083-share"></i>
                                    <span class="nav-text">Logout</span>
                                </a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">Important Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-dark">
                        For Withdrawal, you need to deposit 0.1 USDT for blockchain confirmation.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a class="btn btn-secondary" href="{{ route('user.fund.index') }}">Add Fund</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end header -->
        <div class="page-wrapper">
            <div class="page-content">
