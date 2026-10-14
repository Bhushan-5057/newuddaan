@extends('superadmin.layouts.app')

@section('title', 'Income Analytics')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #8A2BE2;
            --primary-light: rgba(138, 43, 226, 0.15);
            --secondary: #FF6B6B;
            --success: #20BF6B;
            --dark: #2C3A47;
            --light: #F8F9FA;
            --gradient: linear-gradient(135deg, #8A2BE2 0%, #4B6CB7 100%);
            --glass: rgba(255, 255, 255, 0.25);
            --shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .income-dashboard {
            padding: 30px;


        }

        .w-50 {
            width: 50% !important;
        }

        /* Hero Section - Kept as requested */
        .payout-hero {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .payout-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            transform: rotate(30deg);
        }

        .payout-hero h1 {
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .payout-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
        }

        /* Enhanced Filter Card */
        .filter-card {
            /* background: rgba(255, 255, 255, 0.95); */
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: var(--transition);
        }

        .filter-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .filter-form label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            display: block;
            font-size: 0.9rem;
        }

        .filter-control {
            border: 1px solid rgba(138, 43, 226, 0.2);
            border-radius: 12px;
            padding: 12px 15px;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .filter-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.1);
        }

        /* Enhanced Action Buttons */
        .action-btn {
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;

            position: relative;
            overflow: hidden;
        }

        .action-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .action-btn:hover::after {
            left: 100%;
        }

        .view-btn {
            background: linear-gradient(135deg, var(--primary), #6a3093);
            color: white;
            box-shadow: 0 4px 15px rgba(138, 43, 226, 0.3);
        }

        .view-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(138, 43, 226, 0.4);
        }

        .export-btn {
            background: linear-gradient(135deg, var(--success), #1a936f);
            color: white;
            box-shadow: 0 4px 15px rgba(32, 191, 107, 0.3);
        }

        .export-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(32, 191, 107, 0.4);
        }

        /* Premium User Card */
        .user-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), #6a3093);
        }

        .user-card h5 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .user-card .username {
            font-weight: 800;
            font-size: 1.8rem;
            background: linear-gradient(90deg, var(--primary), #6a3093);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        /* Enhanced Income Grid */
        .income-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .income-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
        }

        .income-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), #6a3093);
        }

        .income-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .income-card h6 {
            font-weight: 600;
            color: #636e72;
            margin-bottom: 15px;
            font-size: 1rem;
            position: relative;
        }

        .income-card .amount {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--dark);
            margin: 0;
            position: relative;
            line-height: 1.2;
        }

        .income-card .currency {
            font-size: 1.3rem;
            color: var(--primary);
            font-weight: 700;
        }

        /* Premium Total Card */
        .total-card {
            background: linear-gradient(135deg, var(--primary), #6a3093);
            color: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 15px 35px rgba(138, 43, 226, 0.3);
            transition: var(--transition);
            grid-column: 1 / -1;
            position: relative;
            overflow: hidden;
        }

        .total-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            transform: rotate(30deg);
        }

        .total-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(138, 43, 226, 0.4);
        }

        .total-card h6 {
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            color: white;
        }

        .total-card .amount {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 0;
            position: relative;
            line-height: 1.2;
        }

        /* Floating Action Button */
        .action-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #6a3093);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(138, 43, 226, 0.4);
            cursor: pointer;
            z-index: 100;
            transition: var(--transition);
            border: none;
        }

        .action-fab:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 12px 30px rgba(138, 43, 226, 0.6);
        }

        .action-fab i {
            font-size: 1.8rem;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(138, 43, 226, 0.4);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(138, 43, 226, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(138, 43, 226, 0);
            }
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .income-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .income-dashboard {
                padding: 20px;
            }

            .payout-hero h1 {
                font-size: 2rem;
            }

            .filter-card {
                padding: 20px;
            }

            .action-btn {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .income-card {
                padding: 25px;
            }

            .total-card {
                padding: 30px;
            }
        }

        @media (max-width: 576px) {
            .payout-hero {
                padding: 25px;
            }

            .payout-hero h1 {
                font-size: 1.8rem;
            }

            .income-grid {
                grid-template-columns: 1fr;
            }

            .user-card .username {
                font-size: 1.5rem;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            :root {
                --dark: #f8f9fa;
                --light: #2d3436;
            }

            .filter-card,
            .user-card,
            .income-card {
                background: rgba(30, 30, 45, 0.9);
                border-color: rgba(255, 255, 255, 0.1);
                color: #e2e8f0;
            }

            .income-card h6,
            .user-card h5 {
                color: #b2bec3;
            }

            .income-card .amount {
                color: #f8f9fa;
            }

            .filter-control {
                background: rgba(30, 30, 45, 0.8);
                color: #e2e8f0;
                border-color: rgba(138, 43, 226, 0.3);
            }

            .filter-control::placeholder {
                color: #a0aec0;
            }
        }
    </style>

    <div class="income-dashboard animate__animated animate__fadeIn">
        <!-- Hero Section (unchanged as requested) -->
        <div class="payout-hero">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
            </div>
            <h1>Income Report</h1>

        </div>

        <!-- Enhanced Filter Card -->
        <div class="filter-card animate__animated animate__fadeInUp">
            <form method="GET" action="{{ route('superadmin.income.report') }}" class="filter-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Date Range</label>
                        <select name="range" id="range" class="filter-control">
                            <option value="">Overall</option>
                            <option value="today" {{ request('range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="yesterday" {{ request('range') == 'yesterday' ? 'selected' : '' }}>Yesterday
                            </option>
                            <option value="last7" {{ request('range') == 'last7' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom Range
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>From Date</label>
                        <input type="date" name="from" class="filter-control" value="{{ request('from') }}"
                            id="fromDate">
                    </div>

                    <div class="col-md-3">
                        <label>To Date</label>
                        <input type="date" name="to" class="filter-control" value="{{ request('to') }}"
                            id="toDate">
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <div class="w-50 d-flex align-items-end">
                            <button type="submit" class="action-btn view-btn">
                                <i class="mdi mdi-chart-bar"></i> View
                            </button>
                        </div>
                        <div class="w-50 d-flex align-items-end">
                            <a href="{{ route('superadmin.income.report.download', request()->all()) }}"
                                class="action-btn export-btn">
                                <i class="mdi mdi-file-excel"></i> Export
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>



        <!-- Enhanced Income Grid -->
        <div class="income-grid">
            @foreach ($incomeData as $box)
                <a href="{{ route('superadmin.' . $box['user_route']) }}">
                    <div class="income-card animate__animated animate__fadeInUp">
                        <h6>{{ $box['name'] }}</h6>
                        <p class="amount">
                            <span class="currency">{{ company_info('currency', '$') }}</span>
                            {{ number_format($box['amount'], 2) }}
                        </p>
                    </div>
                </a>
            @endforeach

            <!-- Premium Total Card -->
            <div class="total-card animate__animated animate__fadeIn">
                <h6>Total Income</h6>
                <p class="amount">
                    <span class="currency">{{ company_info('currency', '$') }}</span>
                    {{ number_format($grandTotal, 2) }}
                </p>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rangeSelect = document.getElementById('range');
            const fromInput = document.getElementById('fromDate');
            const toInput = document.getElementById('toDate');
            const refreshBtn = document.getElementById('refreshBtn');

            // Initialize date inputs
            function toggleCustomDates() {
                if (rangeSelect.value === 'custom') {
                    fromInput.removeAttribute('disabled');
                    toInput.removeAttribute('disabled');
                } else {
                    fromInput.setAttribute('disabled', true);
                    toInput.setAttribute('disabled', true);
                    fromInput.value = '';
                    toInput.value = '';
                }
            }

            // Run on page load
            toggleCustomDates();

            // On range select change
            rangeSelect.addEventListener('change', toggleCustomDates);

            // Refresh button functionality


            // Set max date for "to" input based on "from" input
            fromInput.addEventListener('change', function() {
                toInput.min = this.value;

                // If to date is before from date, reset it
                if (toInput.value && new Date(toInput.value) < new Date(this.value)) {
                    toInput.value = this.value;
                }
            });

            // Set min date for "from" input based on "to" input
            toInput.addEventListener('change', function() {
                fromInput.max = this.value;

                // If from date is after to date, reset it
                if (fromInput.value && new Date(fromInput.value) > new Date(this.value)) {
                    fromInput.value = this.value;
                }
            });
        });
    </script>
@endpush
