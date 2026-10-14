@extends('user.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>


    .withdrawal-dashboard {
       
        /* background: linear-gradient(135deg, #f5f7fa 0%, #f8f9fa 100%); */
        min-height: 100vh;
    }

    .page-header {
        margin-bottom: 30px;
        position: relative;
    }

    .page-header h4 {
        font-weight: 700;
        color: var(--dark);
        position: relative;
        display: inline-block;
        font-size: 1.8rem;
    }

    .page-header h4::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--primary);
        border-radius: 2px;
    }

    .page-header .text-muted {
        color: #718096 !important;
        font-weight: 500;
    }

    .summary-card {
        background: var(--glass-effect);
        backdrop-filter: var(--blur-effect);
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
      
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--primary-dark));
        z-index: -1;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(138, 43, 226, 0.2);
    }

    .export-btn {
        background: var(--primary-gradient);;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 25px;
        width: 100%;
        box-shadow: 0 4px 15px rgba(138, 43, 226, 0.3);
        position: relative;
        overflow: hidden;
    }

    .export-btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .export-btn:hover::after {
        left: 100%;
    }

    .export-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(138, 43, 226, 0.4);
    }

    .export-btn i {
        font-size: 1.2rem;
    }

    .summary-item {
        margin-bottom: 18px;
        padding-bottom: 18px;
        border-bottom: 1px solid rgba(237, 242, 247, 0.7);
        position: relative;
    }

    .summary-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .summary-item strong {
        font-weight: 600;
        color: #4A5568;
        display: block;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .summary-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--primary);
        display: flex;
        align-items: center;
    }

    .summary-value i {
        margin-right: 10px;
        font-size: 1.4rem;
    }

    .table-card {
        background: var(--glass-effect);
        backdrop-filter: var(--blur-effect);
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .table-card:hover {
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .table-title {
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        font-size: 1.4rem;
        position: relative;
        padding-left: 15px;
    }

    .table-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 5px;
        height: 20px;
        background: var(--primary);
        border-radius: 3px;
    }

    .filter-controls {
        display: flex;
        gap: 15px;
    }

    .filter-select {
        border: 1px solid rgba(138, 43, 226, 0.2);
        border-radius: 8px;
        padding: 8px 15px;
        font-size: 0.9rem;
        background: rgba(255, 255, 255, 0.8);
        color: var(--dark);
        transition: var(--transition);
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.1);
    }

    #fundConvertTable {
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    #fundConvertTable thead th {
        background-color: rgba(138, 43, 226, 0.1);
        color: var(--dark);
        font-weight: 700;
        padding: 15px;
        border-bottom: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    #fundConvertTable tbody tr {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        transition: var(--transition);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    }

    #fundConvertTable tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    #fundConvertTable tbody td {
        padding: 15px;
        vertical-align: middle;
        color: #4A5568;
        border-bottom: none;
        position: relative;
    }

    #fundConvertTable tbody td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    #fundConvertTable tbody td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .status-badge {
        padding: 8px 15px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        text-transform: capitalize;
    }

    .status-badge i {
        margin-right: 8px;
        font-size: 0.8rem;
    }

    .status-pending {
        background: rgba(249, 202, 36, 0.15);
        color: #F9CA24;
    }

    .status-completed {
        background: rgba(32, 191, 107, 0.15);
        color: #20BF6B;
    }

    .status-rejected {
        background: rgba(255, 107, 107, 0.15);
        color: #FF6B6B;
    }

    .status-processing {
        background: rgba(45, 152, 218, 0.15);
        color: #2D98DA;
    }

    .action-btn {
        border: none;
        background: rgba(138, 43, 226, 0.1);
        color: var(--primary);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .action-btn:hover {
        background: rgba(138, 43, 226, 0.2);
        transform: translateY(-2px);
    }

    .action-btn i {
        font-size: 1rem;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #718096;
    }

    .empty-state i {
        font-size: 4rem;
        color: rgba(138, 43, 226, 0.2);
        margin-bottom: 20px;
    }

    .empty-state h5 {
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .empty-state p {
        margin-bottom: 20px;
    }

    .refresh-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 20px rgba(138, 43, 226, 0.3);
        cursor: pointer;
        z-index: 100;
        transition: var(--transition);
    }

    .refresh-btn:hover {
        transform: rotate(180deg) scale(1.1);
        box-shadow: 0 8px 25px rgba(138, 43, 226, 0.4);
    }

    .refresh-btn i {
        font-size: 1.5rem;
    }

    /* Animation classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(138, 43, 226, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(138, 43, 226, 0); }
        100% { box-shadow: 0 0 0 0 rgba(138, 43, 226, 0); }
    }

    /* Responsive styles */
    @media (max-width: 1200px) {
        .withdrawal-dashboard {
            margin-left: 0;
            padding: 20px;
         
        }
    }

    @media (max-width: 768px) {
        .summary-card {
            margin-bottom: 20px;
        }
        
        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .filter-controls {
            width: 100%;
            flex-direction: column;
            gap: 10px;
        }

        .filter-select {
            width: 100%;
        }

       

        .status-badge {
            margin-left: auto;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        :root {
            --dark: #F8F9FA;
            --light: #2C3A47;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .withdrawal-dashboard {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }

        .summary-card, .table-card {
            background: rgba(30, 30, 45, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
        }

        .summary-item strong, .table-title, #fundConvertTable thead th {
            color: #e2e8f0;
        }

        #fundConvertTable tbody tr {
            background: rgba(30, 30, 45, 0.9);
            color: #e2e8f0;
        }

        #fundConvertTable tbody td {
            color: #cbd5e0;
        }

        .filter-select {
            background: rgba(30, 30, 45, 0.8);
            color: #e2e8f0;
            border-color: rgba(138, 43, 226, 0.3);
        }

        .empty-state {
            color: #a0aec0;
        }

        .empty-state h5 {
            color: #e2e8f0;
        }
    }

     @media (max-width: 992px) {
            .genealogy-header {
                padding: 20px;
            }
        }
     @media (max-width: 576px) {
            
            .genealogy-header h4 {
                font-size: 1.3rem;
            }
            
            .genealogy-header .breadcrumb {
                font-size: 0.85rem;
            }
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

</style>
	@php
    $incometypes = \App\Models\WalletType::where('wallet_type', 'income')
        ->where('binary', 1)
        ->where('status', true)
        ->get();
@endphp
<div class="withdrawal-dashboard animate__animated animate__fadeIn">
    <!-- Page Header -->
        <div class="genealogy-tabs-container">
    <div class="genealogy-tabs col-md-4">
         <a href="{{ route('user.walletinternal.index') }}" class="genealogy-tab {{ request()->routeIs('user.walletinternal.index') ? 'active' : '' }}">
            <i class="fas fa-hand-holding-usd"></i> Fund Convert
        </a>
        <a href="{{ route('fund.convert.history') }}" class="genealogy-tab {{ request()->routeIs('fund.convert.history') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i> Fund Convert History
        </a>
        
    </div>
</div>

<div class="card">
    <div class="card-body">
    <div class="container-fluid">
 <div class="genealogy-header">
            <h4>Fund Convert History</h4>
            
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.user') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="">Fund</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fund Convert History</li>
                </ol>
            
        </div>

    <div class="row">
 

        <!-- Table Section -->
        <div class=" col-md-12">
            <div class="table-card animate__animated animate__fadeInRight">
                <div class="table-header">
                    <h5 class="table-title">Fund Convert History</h5>
                
                </div>
                
                <div class="table-responsive">
                    <table id="fundConvertTable" class="table">
                      <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Txn Type</th>
                            <th>Credit/Debit</th>
                            <th>Wallet</th>
                            <th>Amount</th>
                            <th>Remark</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                        <tbody>
                            <!-- DataTable will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

   
</div>


    </div>
    </div>
</div>
   



@endsection

@section('scripts')

<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>$(function() {
    $('#fundConvertTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("fund.convert.history.data") }}',
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'tx_type', name: 'tx_type' },
        { data: 'debit_credit', name: 'debit_credit' },
        { data: 'wallet_type', name: 'wallet_type' },
        { data: 'amount', name: 'amount' },
        { data: 'remark', name: 'remark' },
        { data: 'date', name: 'date' }
    ]
});

});
</script>
@endsection






