@extends('superadmin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')


    <style>
        .card-header {
            background: linear-gradient(200deg, rgb(108 208 255), rgb(5 34 143));
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }

        .analytics-item {
            padding: 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            transition: background 0.3s;
        }

        .analytics-item:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .text-muted {
            color: #6c757d !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }
    </style>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <!-- Website Analytics-->



                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-lg border-0 rounded-3">
                        <div class="card-header d-flex justify-content-between align-items-center ">
                            <h5 class="mb-0 text-white">User Analytics</h5>
                            <div class="dropdown">
                                <button class="btn p-0 text-white" type="button" id="analyticsOptions"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded fs-4"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="analyticsOptions">
                                    <a class="dropdown-item" href="#">Refresh</a>
                                    <a class="dropdown-item" href="#">Export</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-4">
                                <div class="col-4">
                                    <div class="analytics-item">
                                        <h6 class="text-muted">Total Users</h6>
                                        <h4 class="fw-bold text-primary">{{ $totalUsers }}</h4>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="analytics-item">
                                        <h6 class="text-muted">Active</h6>
                                        <h4 class="fw-bold text-success">{{ $activeUsers }}</h4>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="analytics-item">
                                        <h6 class="text-muted">Pending</h6>
                                        <h4 class="fw-bold text-warning">{{ $pendingUsers }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div id="analyticsBarCharts" style="min-height: 280px;"></div>
                        </div>
                    </div>
                </div>


                <!-- Referral, conversion, impression & income charts -->
                <div class="col-lg-6 col-md-12">
                    <div class="row">
                        <!-- Referral Chart-->
                     
    <div class="col-sm-6 col-12 mb-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body text-center">
                <h2 class="mb-1 text-success">{{ company_info('currency', '$') . ' ' . number_format($totalinvestmentamount, 2) }}</h2>
                <span class="text-muted">Total Investment</span>
                <div id="referralLineChart" style="min-height: 200px;"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-12 mb-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body text-center">
                <h2 class="mb-1 text-primary">{{ company_info('currency', '$') . ' ' . number_format($totalincome, 2) }}</h2>
                <span class="text-muted">Total Income</span>
                <div id="conversionBarchart" style="min-height: 200px;"></div>
            </div>
        </div>
    </div>


<style>
    .text-success {
        color: #28a745 !important; /* Green for Total Investment */
    }

    .text-primary {
        color: #007bff !important; /* Blue for Total Income */
    }

    .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
</style>
                        <!-- Conversion Chart-->


                        <!-- Growth Chart-->
                        <div class="col-sm-6 col-12">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar">
                                                        <span class="avatar-initial bg-label-success rounded-circle">
                                                            <i class="bx bx-dollar fs-4 text-white"></i>
                                                        </span>
                                                    </div>
                                                    <div class="card-info">
                                                        <h5 class="card-title mb-0 me-2">
                                                            {{ company_info('currency', '$') . ' ' . number_format($todayInvestment, 2) }}
                                                        </h5>
                                                        <small class="text-muted">Today Investment</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar">
                                                        <span class="avatar-initial bg-label-info rounded-circle">
                                                            <i class="bx bx-calendar fs-4 text-white"></i>
                                                        </span>
                                                    </div>
                                                    <div class="card-info">
                                                        <h5 class="card-title mb-0 me-2">
                                                            {{ company_info('currency', '$') . ' ' . number_format($yesterdayInvestment, 2) }}
                                                        </h5>
                                                        <small class="text-muted">Yesterday Investment</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar">
                                                        <span class="avatar-initial bg-label-warning rounded-circle">
                                                            <i class="bx bx-trending-up fs-4 text-white"></i>
                                                        </span>
                                                    </div>
                                                    <div class="card-info">
                                                        <h5 class="card-title mb-0 me-2">
                                                            {{ company_info('currency', '$') . ' ' . number_format($lastWeekInvestment, 2) }}
                                                        </h5>
                                                        <small class="text-muted">Last Week Investment</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Referral, conversion, impression & income charts -->


                <style>
                    .bg-label-success {
                        background-color: #28a745 !important;
                        /* Green */
                    }

                    .bg-label-info {
                        background-color: #17a2b8 !important;
                        /* Blue */
                    }

                    .bg-label-warning {
                        background-color: #ffc107 !important;
                        /* Yellow */
                    }

                    .text-white {
                        color: #ffffff !important;
                        /* White color for icons */
                    }
                </style>
                <!-- Activity -->
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-white">Income Report</h5>
                        </div>
                        <div class="card-body">
                            <ul class="p-0 mt-3">
                                @foreach ($incometypes as $income)
                                    @php
                                        $column = $income->wallet_column;
                                        $sumAmount = $incomeSums[$column] ?? 0;

                                        // Define icons and colors for each income type
                                        $icons = [
                                            'Direct Income' => ['icon' => 'bx bx-dollar', 'color' => 'bg-label-success'],
                                            'Level Income' => ['icon' => 'bx bx-layer', 'color' => 'bg-label-info'],
                                            'Monthly Salary' => ['icon' => 'bx bx-wallet', 'color' => 'bg-label-warning'],
                                            'Reward Income' => ['icon' => 'bx bx-gift', 'color' => 'bg-label-danger'],
                                            'Staking Income' => ['icon' => 'bx bx-trending-up', 'color' => 'bg-label-primary'],
                                        ];

                                        $icon = $icons[$income->name]['icon'] ?? 'bx bx-dollar';
                                        $colorClass = $icons[$income->name]['color'] ?? 'bg-label-secondary';
                                    @endphp

                                    <li class="d-flex mb-4 pb-2">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle {{ $colorClass }}">
                                                <i class="{{ $icon }} text-white"></i> <!-- Set icon color to white -->
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column w-100">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>{{ $income->name }}</span>
                                                <span
                                                    class="text-muted">{{ company_info('currency', '$') . ' ' . number_format($sumAmount, 2) }}</span>
                                            </div>
                                            <div class="progress" style="height: 6px">
                                                <div class="progress-bar {{ $colorClass }}"
                                                    style="width: {{ $sumAmount > 0 ? min($sumAmount / 1000 * 100, 100) : 0 }}%"
                                                    role="progressbar"
                                                    aria-valuenow="{{ $sumAmount > 0 ? min($sumAmount / 1000 * 100, 100) : 0 }}"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <style>
                    .bg-label-success {
                        background-color: #28a745 !important;
                        /* Green */
                    }

                    .bg-label-info {
                        background-color: #17a2b8 !important;
                        /* Blue */
                    }

                    .bg-label-warning {
                        background-color: #ffc107 !important;
                        /* Yellow */
                    }

                    .bg-label-danger {
                        background-color: #dc3545 !important;
                        /* Red */
                    }

                    .bg-label-primary {
                        background-color: #007bff !important;
                        /* Dark Blue */
                    }

                    .bg-label-secondary {
                        background-color: #6c757d !important;
                        /* Grey */
                    }

                    .text-white {
                        color: #ffffff !important;
                        /* White color for icons */
                    }
                </style>
                <!-- Activity -->
                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 mb-4">
                    <div class="card">
                        <div class="card-header ">
                            <h5 class="card-title mb-0 text-white">Withdrawal</h5>
                        </div>
                        <div class="card-body">
                            <ul class="p-0 m-0">
                                <li class="d-flex mb-4 mt-3 pb-2">
                                    <div class="avatar avatar-sm flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded-circle bg-label-success">
                                            <i class="bx bx-dollar text-white"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column w-100">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Approved</span>
                                            <span
                                                class="text-muted">{{ company_info('currency', '$') . ' ' . number_format($paidWithdrawal, 2) }}</span>
                                        </div>
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar bg-success" style="width: 100%" role="progressbar"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 pb-2">
                                    <div class="avatar avatar-sm flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded-circle bg-label-warning">
                                            <i class="bx bx-time text-white"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column w-100">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Pending</span>
                                            <span
                                                class="text-muted">{{ company_info('currency', '$') . ' ' . number_format($pendingWithdrawal, 2) }}</span>
                                        </div>
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar bg-warning"
                                                style="width: {{ $pendingWithdrawal > 0 ? min($pendingWithdrawal / $totalwithdrawal * 100, 100) : 0 }}%"
                                                role="progressbar"
                                                aria-valuenow="{{ $pendingWithdrawal > 0 ? min($pendingWithdrawal / $totalwithdrawal * 100, 100) : 0 }}"
                                                aria-valuemin="0" aria-valuemax="{{ $totalwithdrawal }}"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-2">
                                    <div class="avatar avatar-sm flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded-circle bg-label-danger">
                                            <i class="bx bx-x text-white"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column w-100">
                                        <div class="d-flex justify-content-between">
                                            <span>Rejected</span>
                                            <span
                                                class="text-muted">{{ company_info('currency', '$') . ' ' . number_format($rejectedWithdrawal, 2) }}</span>
                                        </div>
                                        <div class="progress" style="height: 6px">
                                            <div class="progress-bar bg-danger"
                                                style="width: {{ $rejectedWithdrawal > 0 ? min($rejectedWithdrawal / $totalwithdrawal * 100, 100) : 0 }}%"
                                                role="progressbar"
                                                aria-valuenow="{{ $rejectedWithdrawal > 0 ? min($rejectedWithdrawal / $totalwithdrawal * 100, 100) : 0 }}"
                                                aria-valuemin="0" aria-valuemax="{{ $totalwithdrawal }}"></div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <style>
                    .bg-label-success {
                        background-color: #28a745 !important;
                        /* Green */
                    }

                    .bg-label-warning {
                        background-color: #ffc107 !important;
                        /* Yellow */
                    }

                    .bg-label-danger {
                        background-color: #dc3545 !important;
                        /* Red */
                    }

                    .text-white {
                        color: #ffffff !important;
                        /* White color for icons */
                    }
                </style>
                <!--/ Activity -->
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-4">
                    <div class="card">

                        <div class="card-header">
                            <h4 class="card-title mb-0 text-white">New Customer List</h4>
                            <!-- <a href="{{route('superadminusers.index')}}" class="btn btn-secondary">See All</a> -->
                        </div>

                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="card-datatable table-responsive p-2">
                                    <table class="table " id="RecentUsersTable">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Join Date</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- / Content -->
@endsection

@section('scripts')

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>




    <script>

        $(document).ready(function () {

            $('#RecentUsersTable').DataTable({

                processing: true,

                serverSide: true,

                pageLength: 5, // This controls how many rows are displayed

                lengthChange: false,

                searching: false,

                paging: false,

                info: false,

                ajax: '{{ route('superadmin.dashboard.recentUserData') }}',

                columns: [

                    { data: 'name', name: 'name' },

                    { data: 'username', name: 'username' },

                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at' },

                ],

                order: [[2, 'desc']],

            });



        });



    </script>
    <script>
        $(document).ready(function () {
            $('.table').DataTable();
        });
    </script>




    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                chart: {
                    type: 'bar',
                    height: 280,
                    toolbar: { show: false },
                    background: 'transparent',
                },
                series: [{
                    name: 'Users',
                    data: [
                        {{ $totalUsers }},
                        {{ $activeUsers }},
                        {{ $pendingUsers }}
                    ]
                }],
                xaxis: {
                    categories: ['Total Users', 'Active Users', 'Pending Users'],
                    labels: {
                        style: { fontSize: '14px', colors: ['#6c757d'] }
                    }
                },
                colors: ['#4bbee1', '#28a745', '#ffc107'],
                plotOptions: {
                    bar: {
                        columnWidth: '40%',
                        distributed: true,
                        borderRadius: 8,
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        colors: ['#fff']
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " users";
                        }
                    }
                },
                grid: {
                    borderColor: '#e0e0e0',
                    strokeDashArray: 4,
                }
            };

            var chart = new ApexCharts(document.querySelector("#analyticsBarCharts"), options);
            chart.render();
        });
    </script>



@endsection