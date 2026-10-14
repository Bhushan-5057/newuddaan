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
    <link href="{{ asset('franchise/assets/vendor/animation/animate.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&amp;display=swap"
        rel="stylesheet">

    <!--flag Icon css-->
    <link href="{{ asset('franchise/assets/vendor/flag-icons-master/flag-icon.css') }}" rel="stylesheet" type="text/css">

    <!-- tabler icons-->
    <link href="{{ asset('franchise/assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">

    <!-- apexcharts css-->
    <link href="{{ asset('franchise/assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet" type="text/css">

    <!-- glight css -->
    <link href="{{ asset('franchise/assets/vendor/glightbox/glightbox.min.css') }}" rel="stylesheet">

    <!-- Bootstrap css-->
    <link href="{{ asset('franchise/assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- simplebar css-->
    <link href="{{ asset('franchise/assets/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="{{ asset('franchise/assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('franchise/assets/css/responsive.css') }}" rel="stylesheet" type="text/css">
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

<body>
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
                    <span class="h-45 w-45 d-flex-center b-r-10 position-relative bg-danger m-auto">

               
                        @if (Auth::user()->profile_image)
                            <img alt="avatar" class="img-fluid b-r-10"
                                src="{{ asset('storage/' . Auth::user()->profile_image) }}">
                        @else
                            <img alt="avatar" class="img-fluid b-r-10" src="{{ asset('assets/img/avatars/3.png') }}">
                        @endif

                        <span
                            class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                    </span>

                    <div class="flex-grow-1 ps-2">
                    
                        <h6 class="text-primary mb-0">
                            {{ $shortName ?? Auth::user()->name }}
                        </h6>

                 
                        <p class="text-muted f-s-12 mb-0">
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    {{-- Settings Dropdown --}}
                    <div class="dropdown profile-menu-dropdown">
                        <a role="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                            <i class="fa fa-cog fs-5"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li class="dropdown-item">
                                <a class="f-w-500" href="{{ route('franchise.profile') }}">
                                    <i class="fa fa-user-circle pe-1 f-s-20"></i>
                                    Profile Details
                                </a>
                            </li>

                            <li class="dropdown-item">
                                <a class="f-w-500" href="{{route('franchise.orders.index')}}">
                                    <i class="fa fa-gear pe-1 f-s-20"></i>
                                     Order
                                </a>
                            </li>

                        

                            <li class="app-divider-v dotted py-1"></li>

                            <li class="dropdown-item">
                                <a class="mb-0 text-danger" href="{{ route('logout') }}"
                                    onclick="confirmLogout()">
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

                    <li class="no-sub">
                        <a href="{{ route('franchise.dashboard') }}">
                            <span class="menu-icon-box primary">
                                <i class="fa fa-home"></i>
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
                                <a href="{{ route('franchise.profile') }}">Profile</a>
                            </li>

                            <li>
                                <a href="{{ route('franchise.password') }}">Password Change</a>
                            </li>

                        
                        </ul>
                    </li>
                

                    <li class="menu-title"><span>FUND </span></li>

               
                        <li>
                            <a data-bs-toggle="collapse" href="#fund" aria-expanded="false">
                                <span class="menu-icon-box success">
                                    <i class="fa fa-bookmark"></i>
                                </span>
                                Fund
                            </a>

                            <ul class="collapse" id="fund">

                              
                                    <li>
                                        <a href="{{route('franchise.fund.request')}}">Fund Request</a>
                                    </li>
                            

                            

                            </ul>
                        </li>
                   
          
           


                    <li class="menu-title"><span> Orders </span></li>

                           
                        <li>
                            <a data-bs-toggle="collapse" href="#topup" aria-expanded="false">
                                <span class="menu-icon-box warning">
                                    <i class="fa fa-shopping-cart"></i>
                                </span>
                                Orders

                            </a>

                            <ul class="collapse" id="topup">

                              
                                    <li>
                                        <a href="{{route('franchise.orders.create')}}">
                                           Create Order
                                        </a>
                                    </li>
                              

                              
                                    <li>
                                        <a href="{{route('franchise.orders.index')}}">
                                          Orders List
                                        </a>
                                    </li>
                              

                            </ul>
                        </li>
        



           


          

                    <li class="menu-title"><span>Others</span></li>
                   
                    <li class="no-sub mb-5">
                        <a href="{{ route('logout') }}"
                         onclick="confirmLogout()" >
                            <span class="menu-icon-box danger">
                                <i class="fa fa-sign-out"></i>
                            </span>
                            Logout
                        </a>
                    </li>

                 


                </ul>
            </div>

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
                            <div class="col-8 col-sm-6 d-flex align-items-center header-left p-0">
                                <span class="header-toggle ">
                                    <i class="ph ph-squares-four"></i>
                                </span>


                            </div>

                            <div class="col-4 col-sm-6 d-flex align-items-center justify-content-end header-right p-0">

                                <ul class="d-flex align-items-center">
                                    {{-- 
                                    <li class="header-language">
                                        <div class="flex-shrink-0 dropdown" id="lang_selector">
                                            <a aria-expanded="false" class="d-block head-icon ps-0"
                                                data-bs-toggle="dropdown" href="#">
                                                <div class="lang-flag lang-en ">
                                                    <span class="flag rounded-circle overflow-hidden">
                                                        <i class=""></i>
                                                    </span>
                                                </div>
                                            </a>
                                            <ul class="dropdown-menu language-dropdown header-card border-0">
                                                <li class="lang lang-en selected dropdown-item p-2"
                                                    data-bs-placement="top" data-bs-toggle="tooltip" title="US">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-usa flag-icon-squared rounded-circle f-s-20"></i>
                                                        <span class="ps-2">US</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-pt dropdown-item p-2" title="FR">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-fra flag-icon-squared rounded-circle f-s-20"></i>
                                                        <span class="ps-2">France</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-es dropdown-item p-2" title="UK">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-gbr flag-icon-squared rounded-circle f-s-20"></i>
                                                        <span class="ps-2">UK</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-es dropdown-item p-2" title="IT">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-ita flag-icon-squared rounded-circle f-s-20"></i>
                                                        <span class="ps-2">Italy</span>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                    </li> --}}



                                    <li class="header-dark">
                                        <div class="sun-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                                            <i class="ph ph-moon-stars"></i>
                                        </div>
                                        <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                                            <i class="ph ph-sun-dim"></i>
                                        </div>
                                    </li>



                                </ul>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Header Section ends -->

                <!-- Body main section starts -->
                <main>
                    <div class="container-fluid">
