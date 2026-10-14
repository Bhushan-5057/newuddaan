@extends('user.layouts.app')

@section('content')
    <style>
        .hidden {
            display: none;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .form-select-lg,
        .input-group-lg .form-control {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }

        .invalid-feedback {
            font-size: 0.85rem;
        }

        .input-group-text {
            transition: all 0.3s ease;
        }

        .input-group:focus-within .input-group-text {
            background-color: #e9ecef;
        }

        .badge {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .alert {
            border-radius: 8px;
        }

        /* Animation for form elements */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-body>* {
            animation: fadeIn 0.5s ease forwards;
        }

        .card-body>*:nth-child(1) {
            animation-delay: 0.1s;
        }

        .card-body>*:nth-child(2) {
            animation-delay: 0.2s;
        }

        .card-body>*:nth-child(3) {
            animation-delay: 0.3s;
        }

        .card-body>*:nth-child(4) {
            animation-delay: 0.4s;
        }

        .card-body>*:nth-child(5) {
            animation-delay: 0.5s;
        }
    </style>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
            --warning-gradient: linear-gradient(135deg, #ffc371 0%, #ff5f6d 100%);
            --card-shadow: 0 6px 30px -10px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .genealogy-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .genealogy-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .genealogy-header::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -30px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .genealogy-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .genealogy-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin-top: 10px;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .genealogy-header .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: var(--transition);
        }

        .genealogy-header .breadcrumb-item a:hover {
            color: white;
            text-decoration: underline;
        }

        .genealogy-header .breadcrumb-item.active {
            color: white;
        }

        .genealogy-header .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.6);
        }

        .filter-card {
            /* background: white; */
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            border: none;
            transition: var(--transition);
        }

        .filter-card:hover {
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15);
        }

        .filter-card .card-title {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .filter-card .card-title i {
            margin-right: 10px;
            color: #667eea;
        }

        .filter-card .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: #a0aec0;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
        }

        .filter-card .btn {
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
            border: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .filter-card .btn i {
            margin-right: 8px;
        }

        .filter-card .btn-primary {
            background: var(--primary-gradient);
        }

        .filter-card .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .filter-card .btn-danger {
            background: var(--danger-gradient);
        }

        .filter-card .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 117, 140, 0.4);
        }

        .users-card {
            /* background: white; */
            border-radius: 12px;
            padding: 0;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
            transition: var(--transition);
        }

        .users-card:hover {
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15);
        }

        .users-card .card-header {
            /* background: white; */
            color: #4a5568;
            padding: 20px 25px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .users-card .card-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .users-card .card-header .badge {
            background: var(--secondary-gradient);
            color: #4a5568;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .users-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .users-table thead th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            font-size: 0.9rem;
        }

        .users-table tbody tr {
            transition: var(--transition);
        }

        .users-table tbody tr:not(:last-child) td {
            border-bottom: 1px solid #edf2f7;
        }

        .users-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .users-table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4a5568;
            font-weight: 600;
            margin-right: 12px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-name {
            font-weight: 500;
            color: #2d3748;
        }

        .user-username {
            font-size: 0.85rem;
            color: #718096;
        }

        .input-group-text {
            height: 50px !important;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .status-badge i {
            margin-right: 5px;
            font-size: 0.7rem;
        }

        .status-active {
            background: rgba(67, 233, 123, 0.1);
            color: #38a169;
        }

        .status-inactive {
            background: rgba(255, 117, 140, 0.1);
            color: #e53e3e;
        }

        .package-badge {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .package-badge i {
            margin-right: 5px;
            font-size: 0.8rem;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #a0aec0;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #e2e8f0;
        }

        .no-data h5 {
            font-weight: 600;
            color: #718096;
            margin-bottom: 10px;
        }

        .no-data p {
            color: #a0aec0;
            max-width: 400px;
            margin: 0 auto;
        }

        .date-cell {
            display: flex;
            flex-direction: column;
        }

        .join-date {
            font-weight: 500;
            color: #2d3748;
        }

        .join-time {
            font-size: 0.8rem;
            color: #a0aec0;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .genealogy-header {
                padding: 20px;
            }

            .filter-card {
                padding: 20px;
            }

            .users-card .card-header {
                padding: 15px 20px;
            }

            .users-table thead th,
            .users-table tbody td {
                padding: 12px 15px;
            }
        }

        @media (max-width: 768px) {
            .filter-card .row>div {
                margin-bottom: 15px;
            }

            .filter-card .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .users-table thead {
                display: none;
            }

            .users-table tbody tr {
                display: block;
                margin-bottom: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
                padding: 10px;
            }

            .users-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                border-bottom: 1px solid #edf2f7;
            }

            .users-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #4a5568;
                margin-right: 15px;
                font-size: 0.85rem;
            }

            .user-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .user-avatar {
                margin-right: 0;
                margin-bottom: 8px;
            }
        }

        @media (max-width: 576px) {

            .genealogy-header h4 {
                font-size: 1.3rem;
            }

            .genealogy-header .breadcrumb {
                font-size: 0.85rem;
            }

            .filter-card {
                padding: 15px;
            }

            .users-card .card-header h5 {
                font-size: 1.1rem;
                margin-bottom: 10px;
            }

            .users-card .card-header .badge {
                margin-bottom: 10px;
            }

            .breadcrumb li {
                font-size: 11px !important;
            }
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #ff758c 0%, #ff7eb3 100%);
            --warning-gradient: linear-gradient(135deg, #ffc371 0%, #ff5f6d 100%);
            --card-shadow: 0 6px 30px -10px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Genealogy Navigation Tabs */
        .genealogy-tabs {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .genealogy-tab {
            flex: 1;
            text-align: center;
            padding: 15px 20px;
            color: #718096;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            border-bottom: 3px solid transparent;
        }

        .genealogy-tab:hover {
            color: #4a5568;
            background: rgba(102, 126, 234, 0.05);
        }

        .genealogy-tab.active {
            color: #9066ea;
            border-bottom-color: #9066ea;
            background: rgb(148 102 234 / 10%);
        }

        .genealogy-tab i {
            margin-right: 8px;
            font-size: 0.9em;
        }

        /* Rest of your existing styles... */
        .genealogy-header {
            background: var(--primary-gradient);
            color: white !important;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        /* Tab Navigation Container */
        .genealogy-tabs-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;

        }

        /* Hide scrollbar but keep functionality */
        .genealogy-tabs-container::-webkit-scrollbar {
            display: none;
        }

        /* Tab Navigation */
        .genealogy-tabs {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 30px -10px rgba(0, 0, 0, 0.1);
            min-width: fit-content;
        }

        /* Individual Tabs */
        .genealogy-tab {
            width: 180px;
            /* Fixed width for all tabs */
            min-width: 180px;
            /* Minimum width to prevent shrinking */
            text-align: center;
            padding: 15px 10px;
            color: #718096;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
        }

        .genealogy-tab:hover {
            color: #4a5568;
            background: rgba(102, 126, 234, 0.05);
        }



        .genealogy-tab i {
            margin-right: 8px;
            font-size: 0.9em;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .genealogy-tabs {
                border-radius: 0;
                box-shadow: none;
                border-bottom: 1px solid #e2e8f0;
            }

            .genealogy-tab {
                width: 160px;
                /* Slightly smaller on mobile */
                min-width: 160px;
                padding: 12px 8px;
                font-size: 0.9rem;
            }

            .genealogy-tab i {
                display: block;
                margin: 0 auto 5px;
                font-size: 1.1em;
            }
        }

        @media (max-width: 480px) {
            .genealogy-tab {
                width: 140px;
                min-width: 140px;
                padding: 10px 5px;
            }
        }

        .users-card .card-header {
            margin-bottom: 20px;
        }
    </style>
    <div class="container-fluid">
        <!-- Genealogy Navigation Tabs -->
        <div class="genealogy-tabs-container">
            <div class="genealogy-tabs col-md-4">
                <a href="{{ route('user.topup.index') }}"
                    class="genealogy-tab {{ request()->routeIs('user.topup.index') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Topup
                </a>
                <a href="{{ route('user.order.history') }}"
                    class="genealogy-tab {{ request()->routeIs('user.order.history') ? 'active' : '' }}">
                    <i class="fas fa-user-friends"></i> Orders
                </a>

            </div>
        </div>


        <!-- Header with Breadcrumb -->
        <div class="genealogy-header">
            <h4>Topup</h4>



        </div>


        <div class="row">
            <!-- Main Form Column -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 text-primary">Topup Form</h5>
                    </div>
                    <div class="card-body p-4">


                        <form method="POST" action="" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="topupType" value="{{ $topupType }}">

                            <!-- Wallet Selection -->
                            <!--<div class="mb-4">-->
                            <!--    <label for="wallet" class="form-label fw-semibold">Select Wallet</label>-->
                            <!--    <select name="wallet" id="wallet" class="form-select form-select-lg" required>-->
                            <!--        @foreach ($wallets as $wallet)
    -->
                            <!--            <option value="{{ $wallet->wallet_column }}">-->
                            <!--                {{ $wallet->name }} - {{ company_info('currency', '$') }} {{ number_format($userWallet->{$wallet->wallet_column} ?? 0, 2) }}-->
                            <!--            </option>-->
                            <!--
    @endforeach-->
                            <!--    </select>-->
                            <!--    <div class="invalid-feedback">Please select a wallet</div>-->
                            <!--</div>-->

                            <!-- Username Input -->
                            <div class="mb-4">

                                @if ($username_req == 'yes')
                                    <label for="username" class="form-label fw-semibold">Enter Username</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                        <input type="text" name="username" id="username" class="form-control" required
                                            autocomplete="off" placeholder="Enter username">
                                    </div>
                                @else
                                    <input type="hidden" name="username" id="username" class="form-control"
                                        value="{{ Auth::user()->username }}">
                                @endif



                                <div id="user-name" class="mt-2 fw-medium"></div>
                                @error('username')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Package Selection (for pin/pin amount) -->
                            @if ($topupType === 'pin' || $topupType === 'pin amount')
                                <div class="mb-4">
                                    <label for="package" class="form-label fw-semibold">Select Package</label>
                                    <select id="package" name="package" class="form-select form-select-lg" required>
                                        @foreach ($packages as $package)
                                            @if ($topupType === 'pin')
                                                <option value="{{ $package['id'] }}">{{ company_info('currency', '$') }}
                                                    {{ $package['min'] }}</option>
                                            @else
                                                <option value="{{ $package['id'] }}" data-min="{{ $package['min'] }}"
                                                    data-max="{{ $package['max'] }}">
                                                    {{ company_info('currency', '$') }} {{ $package['min'] }} to
                                                    {{ company_info('currency', '$') }} {{ $package['max'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Amount Input (for amount/pin amount) -->
                            @if ($topupType === 'amount' || $topupType === 'pin amount')
                                <div class="mb-4">
                                    <label for="packageAmount" class="form-label fw-semibold">Enter Amount</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light">{{ company_info('currency', '$') }}</span>
                                        <input type="number" id="packageAmount" name="packageAmount" class="form-control"
                                            @if ($topupType === 'amount') min="{{ $packages[0]['min'] }}" max="{{ $packages[0]['max'] }}"
                                               @else
                                                   min="{{ $packages[0]['min'] }}" max="{{ $packages[0]['max'] }}" @endif
                                            required placeholder="Enter amount">
                                    </div>
                                    <div class="text-danger fs-6 fw-bold mt-1 d-none" id="amountError">
                                        @if ($topupType === 'amount')
                                            Amount must be between {{ company_info('currency', '$') }}
                                            {{ $packages[0]['min'] }} and {{ company_info('currency', '$') }}
                                            {{ $packages[0]['max'] }}.
                                        @else
                                            Please enter a valid amount within the selected package range.
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label>Wallet Addsress:</label>
                                <div id="walletAddress">Not connected</div>
                            </div>
                            <input type="hidden" id="usdtRecipient" name="usdtRecipient"
                                value="{{ company_info('company_smart_chain_address', '0xa1bd...11dc1') }}">
                            <input type="hidden" id="AccountRecipient" name="AccountRecipient" value="">
                            <div class="mb-3">
                                <label>Balance:</label>
                                <div id="maticBalance" class="hidden">0</div>
                                <div id="usdtBalance">0</div>
                            </div>

                            <p class="mt-3" id="statusMessage" style="color: black;"></p>
                            <div class="mb-3">
                                <button type="button" class="btn btn-success hidden" id="sendUsdt">Topup</button>
                                <button type="button" class="btn btn-primary" id="connectButton">Connect Wallet</button>
                                <button type="button" class="btn btn-danger hidden" id="Disconnect">Disconnect</button>
                            </div>
                            <!-- Submit Button -->
                            <!--<div class="d-grid mt-4">-->
                            <!--    <button type="submit" class="btn btn-primary btn-lg shadow">-->
                            <!--        <i class="fas fa-arrow-up-from-bracket me-2"></i> Topup Now-->
                            <!--    </button>-->
                            <!--</div>-->
                        </form>
                    </div>
                </div>
            </div>

            <!-- Steps Card Column -->
            <div class="col-lg-4">
                <div class="card bg-gradient-primary text-white shadow-lg border-0">
                    <div class="card-header bg-transparent border-bottom border-white-50 py-3">
                        <h5 class="mb-0"><i class="fas fa-list-check me-2"></i> STEPS FOR TOPUP</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3 d-flex align-items-start">
                                <span class="badge bg-white text-primary rounded-circle p-2 me-3"><i
                                        class="fas fa-check"></i></span>
                                <span>You Can Topup Any User ID</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <span class="badge bg-white text-primary rounded-circle p-2 me-3"><i
                                        class="fas fa-check"></i></span>
                                <span>Enter Username Which You Want To Topup</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <span class="badge bg-white text-primary rounded-circle p-2 me-3"><i
                                        class="fas fa-check"></i></span>
                                <span>Select Package Then Click On Topup</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Help Card -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 text-primary"><i class="fas fa-question-circle me-2"></i> Need Help?</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">If you encounter any issues with your topup, please contact our support team.</p>
                        <a href="{{ route('user.support.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-headset me-2"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation bootstrap
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            // Amount validation + button enable/disable
            const type = @json($topupType);
            const input = document.getElementById('packageAmount');
            const error = document.getElementById('amountError');
            const sendBtn = document.getElementById('sendUsdt');

            function validateAmount() {
                let value = parseFloat(input.value);
                let min = parseFloat(input.min || 0);
                let max = parseFloat(input.max || Infinity);

                if (type === 'pin amount') {
                    const selectedOption = document.querySelector('#package option:checked');
                    if (selectedOption) {
                        min = parseFloat(selectedOption.getAttribute('data-min')) || min;
                        max = parseFloat(selectedOption.getAttribute('data-max')) || max;
                    }
                }

                if (value < min || value > max || isNaN(value)) {
                    error.classList.remove('d-none');
                    input.classList.add('is-invalid');
                    sendBtn.disabled = true; // ❌ disable button
                } else {
                    error.classList.add('d-none');
                    input.classList.remove('is-invalid');
                    sendBtn.disabled = false; // ✅ enable button
                }
            }

            if (input && error && sendBtn) {
                input.addEventListener('input', validateAmount);
                validateAmount(); // run once on load

                if (type === 'pin amount') {
                    const packageSelect = document.getElementById('package');
                    if (packageSelect) {
                        packageSelect.addEventListener('change', function() {
                            const selected = this.options[this.selectedIndex];
                            input.min = selected.getAttribute('data-min');
                            input.max = selected.getAttribute('data-max');
                            validateAmount();
                        });
                    }
                }
            }
        });

        // Username lookup with debounce
        $(document).ready(function() {
            let timer;

            function shortenAddress(address) {
                if (!address) return '';
                return address.slice(0, 6) + '...' + address.slice(-5);
            }
            $('#username').on('keyup', function() {
                clearTimeout(timer);
                let username = $(this).val();

                if (username.length > 2) {
                    timer = setTimeout(function() {
                        $.ajax({
                            url: "{{ route('user.getUserName') }}",
                            type: "GET",
                            data: {
                                username: username
                            },
                            beforeSend: function() {
                                $('#user-name').html(
                                    '<span class="text-info"><i class="fas fa-spinner fa-spin me-2"></i>Checking...</span>'
                                );
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#user-name').html(
                                        `<span class="text-success"><i class="fas fa-check-circle me-2"></i>Valid user: ${shortenAddress(response.name)} </span>`
                                    );
                                } else {
                                    $('#user-name').html(
                                        '<span class="text-danger"><i class="fas fa-times-circle me-2"></i>User not found</span>'
                                    );
                                }
                            },
                            error: function() {
                                $('#user-name').html(
                                    '<span class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error checking username</span>'
                                );
                            }
                        });
                    }, 500); // 0.5 second delay after typing stops
                } else {
                    $('#user-name').html('');
                }
            });
        });
    </script>
    <script>
        const csrfToken = "{{ csrf_token() }}";
        const panelPath = "{{ company_info('base_url') ?? 'https://veripay.live/' }}";
        const panelInvestPath = "{{ route('user.topup.apistore') }}";
    </script>

    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/4.2.2/web3.min.js"
        integrity="sha512-YD0p/mrfISfNjG4WS/8Dx1h6hZx0/IgCFJfQR8lSXf/y29Jy+JcZj1s65/Ar22xBwvK8xe1u1BQ/w2E2Edr22Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="module" src="{{ asset('assets/wallet/app.js?v=0.000012') }}"></script>
@endsection
