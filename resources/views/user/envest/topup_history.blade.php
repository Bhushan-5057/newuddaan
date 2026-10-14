@extends('user.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" rel="stylesheet">

<style>

    /* Header Styles */
    .genealogy-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 16px;
        padding: 30px;
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
    
    .genealogy-header h4 {
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
          color: white;
    }
    
    .genealogy-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 15px;
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
    
    /* Stats Card */
    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        border-left: 4px solid var(--primary);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card .stats-value {
        font-weight: 700;
        font-size: 1.8rem;
        color: var(--primary);
        margin-bottom: 5px;
    }
    
    .stats-card .stats-label {
        color: #718096;
        font-size: 0.95rem;
        margin-bottom: 10px;
    }
    
    .stats-card .stats-change {
        display: flex;
        align-items: center;
        color: #38a169;
        font-size: 0.9rem;
    }
    
    .stats-card .stats-change.negative {
        color: #e53e3e;
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
  .genealogy-tabs {
        display: flex;
        background: white;
        border-radius: 12px;
        box-shadow: 0 6px 30px -10px rgba(0, 0, 0, 0.1);
        min-width: fit-content;

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
        background: var(--primary-light);
    }
    
    .genealogy-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: var(--primary-light);
    }
    
    .genealogy-tab i {
        margin-right: 8px;
        font-size: 0.9em;
    }
    
    /* Main Content Card */
    .content-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }
    
    .content-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }
    
    .content-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #edf2f7;
    }
    
    .content-card .card-title {
        font-weight: 700;
        font-size: 1.4rem;
        color: #2d3748;
        margin: 0;
    }
    
    .content-card .card-subtitle {
        color: #718096;
        font-size: 0.95rem;
        margin-top: 5px;
    }
    
    /* Table Styles */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .data-table thead th {
        background-color: #f8fafc;
        color: #4a5568;
        font-weight: 600;
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        font-size: 0.9rem;
    }
    
    .data-table tbody tr {
        transition: var(--transition);
    }
    
    .data-table tbody tr:not(:last-child) td {
        border-bottom: 1px solid #edf2f7;
    }
    
    .data-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .data-table tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        color: #4a5568;
        font-size: 0.95rem;
    }
    
    /* Transaction Item */
    .transaction-item {
        display: flex;
        align-items: center;
    }
    
    .transaction-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        background: var(--primary-light);
        color: var(--primary);
    }
    
    .transaction-icon.deposit {
        background: rgba(67, 233, 123, 0.1);
        color: #43e97b;
    }
    
    .transaction-icon.withdrawal {
        background: rgba(255, 117, 140, 0.1);
        color: #ff758c;
    }
    
    .transaction-details h6 {
        font-weight: 600;
        margin-bottom: 3px;
        color: #2d3748;
    }
    
    .transaction-details small {
        color: #a0aec0;
        font-size: 0.85rem;
    }
    
    /* Status Badge */
    .status-badge {
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .status-badge i {
        margin-right: 5px;
        font-size: 0.7rem;
    }
    
    .status-completed {
        background: rgba(67, 233, 123, 0.1);
        color: #43e97b;
    }
    
    .status-pending {
        background: rgba(255, 195, 113, 0.1);
        color: #ffc371;
    }
    
    .status-failed {
        background: rgba(255, 117, 140, 0.1);
        color: #ff758c;
    }
    
    .status-use {
       background: rgb(222 180 13 / 10%);
    color: #6a756e;
    }
    
    /* Amount Styles */
    .amount {
        font-weight: 600;
        color: #2d3748;
    }
    
    /* Date Styles */
    .date-cell {
        display: flex;
        flex-direction: column;
    }
    
    .date {
        font-weight: 500;
        color: #2d3748;
    }
    
    .time {
        font-size: 0.85rem;
        color: #a0aec0;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .genealogy-header {
            padding: 25px;
        }
        
        .content-card {
            padding: 25px;
        }
        
        .data-table thead th, 
        .data-table tbody td {
            padding: 12px 15px;
        }
    }
    
    @media (max-width: 768px) {
        .genealogy-header h4 {
            font-size: 1.5rem;
        }
        
        .content-card .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .content-card .card-title {
            margin-bottom: 15px;
        }
        
        .data-table {
            display: block;
            overflow-x: auto;
        }
    }
    
    @media (max-width: 576px) {
        .genealogy-header {
            padding: 20px;
        }
        
        .genealogy-header h4 {
            font-size: 1.3rem;
        }
        
        .genealogy-header .breadcrumb {
            font-size: 0.85rem;
        }
        
        .content-card {
            padding: 20px;
        }
        
        .transaction-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .transaction-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
</style>

<div class="container-fluid">


    <!-- Header Section -->
    <div class="genealogy-header">
        <h4>Topup History</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.user') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="">Invest</a></li>
            <li class="breadcrumb-item active" aria-current="page">Topup History</li>
        </ol>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-value text-dark">{{ company_info('currency_bv', '$') }}{{ number_format($totalamount, 2) }}</div>
                    <div class="stats-label">Total purchase Amount</div>
                  
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-value">24</div>
                    <div class="stats-label">Total Orders</div>
                    <div class="stats-change">
                        <i class="mdi mdi-arrow-up"></i> 5 new this month
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-value">{{ company_info('currency_bv', '$') }}1,250</div>
                    <div class="stats-label">Pending Orders</div>
                    <div class="stats-change negative">
                        <i class="mdi mdi-alert-circle-outline"></i> 2 pending
                    </div>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-card">
        <div class="card-header">
            <div>
                <h5 class="card-title">Recent Transactions</h5>
                <p class="card-subtitle">All your purchase  orders in one place</p>
            </div>
           
        </div>

        <div class="table-responsive">
            <table id="topupHistoryTable" class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Transaction</th>
                        <!--<th>Tx Type</th>-->
                        <th>Amount</th>
                        <th>Package Name</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

<script>
$(document).ready(function () {

    var table = $('#topupHistoryTable').DataTable({

        processing: true,
        serverSide: true,
        ajax: '{{ route("user.order.history.data") }}',

        columns: [

            {
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },

            {
                data: 'tx_type',
                render: function(data, type, row) {

                    let icon = data === 'topup'
                        ? 'mdi-arrow-bottom-left'
                        : 'mdi-refresh';

                    let label = data === 'topup'
                        ? 'Topup'
                        : 'Retopup';

                    let iconClass = data === 'topup'
                        ? 'topup'
                        : 'retopup';

                    return `
                    <div class="transaction-item">
                        <div class="transaction-icon ${iconClass}">
                            <i class="mdi ${icon}"></i>
                        </div>

                        <div class="transaction-details">
                            <h6>${label}</h6>
                            <small>Order ID: ${row.tx_id}</small>
                        </div>
                    </div>
                    `;
                }
            },

            {
                data: 'order_amount',
                render: function(data) {

                    return `<span class="amount">
                    {{ company_info('currency_bv','$') }}${parseFloat(data).toFixed(2)}
                    </span>`;
                }
            },

            {
                data: 'package_name'
            },

            {
                data: 'added_on'
            },

            {
                data: 'status',
                render: function(data){

                    let badge = '';

                    if(data === 'Active'){
                        badge = 'status-completed';
                    }
                    else if(data === 'Expired'){
                        badge = 'status-failed';
                    }
                    else{
                        badge = 'status-pending';
                    }

                    return `<span class="status-badge ${badge}">${data}</span>`;
                }
            }

        ],

        order: [[4,'desc']],

        language:{
            paginate:{
                previous:"<i class='mdi mdi-chevron-left'></i>",
                next:"<i class='mdi mdi-chevron-right'></i>"
            }
        }

    });

});
</script>
@endsection