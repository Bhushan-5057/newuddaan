@extends('user.layouts.app')

@section('content')
<!-- Core Styling Modules CDN -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<div class="container-fluid py-4  ">
    
    <!-- Navigation Tabs Layout Section (Bootstrap Nav Pills) -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="nav nav-pills gap-2">
                <!--<a href="{{ route('user.payout.index') }}" class="nav-link py-2 px-3 {{ request()->routeIs('user.payout.index') ? 'active bg-primary ' : 'border text-secondary' }}">-->
                <!--    <i class="fas fa-users me-2"></i> Withdrawal-->
                <!--</a>-->
                <a href="{{ route('user.withdrawals') }}" class="nav-link py-2 px-3 {{ request()->routeIs('user.withdrawals') ? 'active bg-primary ' : 'border text-secondary' }}">
                    <i class="fas fa-user-friends me-2"></i> Withdrawal Report
                </a>
            </div>
        </div>
    </div>


    <!-- Summary Statistics Dashboard Row Grid -->
    <div class="row g-3 mb-4">
        {{-- PENDING COUNTER --}}
        <div class="col-md-4">
            <div class="p-3 border rounded shadow-sm h-100 d-flex flex-column justify-content-center">
                <strong class="text-warning small text-uppercase tracking-wider mb-1">
                    <i class="fas fa-hourglass-half me-1"></i> Pending Withdrawal
                </strong>
                <span class="fs-4 fw-bold text-warning">
                    {{ company_info('currency','$') }} {{ number_format($pendingAmount, 2) }}
                </span>
            </div>
        </div>

        {{-- APPROVED COUNTER --}}
        <div class="col-md-4">
            <div class="p-3 border rounded shadow-sm h-100 d-flex flex-column justify-content-center">
                <strong class="text-success small text-uppercase tracking-wider mb-1">
                    <i class="fas fa-check-circle me-1"></i> Approved Withdrawal
                </strong>
                <span class="fs-4 fw-bold text-success">
                    {{ company_info('currency','$') }} {{ number_format($approvedAmount, 2) }}
                </span>
            </div>
        </div>

        {{-- REJECTED COUNTER --}}
        <div class="col-md-4">
            <div class="p-3 border rounded shadow-sm h-100 d-flex flex-column justify-content-center">
                <strong class="text-danger small text-uppercase tracking-wider mb-1">
                    <i class="fas fa-times-circle me-1"></i> Rejected Withdrawal
                </strong>
                <span class="fs-4 fw-bold text-danger">
                    {{ company_info('currency','$') }} {{ number_format($rejectedAmount, 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- KYC Notification Status Panel -->
    <div class="row mb-4">
        @if($kycBank)
            <div class="col-12">
                <div class="alert alert-success d-flex align-items-center justify-content-between shadow-sm p-3 mb-0">
                    <div>
                        <strong class="d-block mb-1"><i class="fas fa-university me-2"></i> Bank Verified:</strong>
                        <span class="small">
                            {{ $kycBank->bank_name }} | 
                            {{ $kycBank->account_holder_name }} | 
                            {{ substr($kycBank->account_no, 0, 2) }}XXXX{{ substr($kycBank->account_no, -2) }} | 
                            IFSC: {{ $kycBank->ifsc_code }}
                        </span>
                    </div>
                    <span class="badge bg-success px-3 py-2 text-uppercase">Approved</span>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center justify-content-between shadow-sm p-3 mb-0">
                    <div>
                        <strong class="d-block mb-1"><i class="fas fa-exclamation-triangle me-2"></i> Bank KYC Pending</strong>
                        <span class="small">Please complete your Bank KYC parameter keys configuration to settle withdrawal payouts.</span>
                    </div>
                    <a href="{{ route('user.kyc.show') }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                        Complete KYC
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Data Grid Historical Representation Panel Wrapper -->
    <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeInRight">
        <div class="card-header border-bottom p-3 d-flex align-items-center justify-content-between bg-transparent">
            <h5 class="card-title mb-0 h6 fw-bold text-secondary text-uppercase tracking-wider">
                <i class="fas fa-history me-2 text-primary"></i> Withdrawal History
            </h5>
            <!-- Sync Data Trigger Item -->
            <button class="btn btn-outline-primary btn-sm rounded-circle shadow-sm" id="refreshBtn" title="Sync Live Ledger Data" style="width: 35px; height: 35px; padding: 0;">
                <i class="mdi mdi-refresh fs-5"></i>
            </button>
        </div>
        
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="withdrawalTable" class="table table-striped table-hover align-middle w-100 border-light">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="small fw-bold text-uppercase text-secondary">S No.</th>
                            <th class="small fw-bold text-uppercase text-secondary">Transaction ID</th>
                            <th class="small fw-bold text-uppercase text-secondary">Amount</th>
                            <th class="small fw-bold text-uppercase text-secondary">Charge</th>
                            <th class="small fw-bold text-uppercase text-secondary">Payable</th>
                            <th class="small fw-bold text-uppercase text-secondary">Account</th>
                            <th class="small fw-bold text-uppercase text-secondary">Status</th>
                            <th class="small fw-bold text-uppercase text-secondary">Date</th>
                        </tr>
                    </thead>
                    <tbody class="small text-dark">
                        <!-- Datatables Ajax Injector Payload -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Presentation Context Modal Component -->
<div class="modal fade" id="withdrawalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="mdi mdi-information-outline me-2 text-primary"></i>Withdrawal Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-secondary small" id="withdrawalDetails">
                <!-- Javascript target payload elements injected dynamically -->
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary px-4 btn-sm rounded-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables & Layout Vendors Bundles -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable Server Configuration
    const table = $('#withdrawalTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('user.withdrawals.list') }}",
            data: function(d) {
                d.status = $('#statusFilter').val();
                d.date_range = $('#dateFilter').val();
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false,
                className: 'text-center fw-bold'
            },
            { data: 'id', name: 'id' },
            { 
                data: 'amount', 
                name: 'amount',
                className: 'fw-bold',
                render: function(data) {
                    return '{{ company_info("currency", "$") }}' + parseFloat(data).toFixed(2);
                }
            },
            { 
                data: 'tx_charge', 
                name: 'tx_charge',
                className: 'text-secondary',
                render: function(data) {
                    return '{{ company_info("currency", "$") }}' + parseFloat(data).toFixed(2);
                }
            },
            { 
                data: 'paidAmount', 
                name: 'paidAmount',
                className: 'text-success fw-bold',
                render: function(data) {
                    return '{{ company_info("currency", "$") }}' + parseFloat(data).toFixed(2);
                }
            },
            { data: 'account', name: 'account' },
            { data: 'status_button', name: 'status_button', className: 'text-center' },
            { 
                data: 'date', 
                name: 'date',
                render: function(data) {
                    return new Date(data).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }
        order: [[7, 'desc']],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'></i>",
                next: "<i class='mdi mdi-chevron-right'></i>"
            },
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            zeroRecords: '<div class="text-center py-4 text-muted"><i class="mdi mdi-database-remove display-4 opacity-50 mb-2 d-block"></i><h5 class="fw-bold mb-1">No Withdrawals Found</h5><p class="small mb-0">You haven\'t requested any withdrawals yet.</p></div>',
            infoEmpty: '',
            emptyTable: '<div class="text-center py-4 text-muted"><i class="mdi mdi-database-remove display-4 opacity-50 mb-2 d-block"></i><h5 class="fw-bold mb-1">No Withdrawals Found</h5><p class="small mb-0">You haven\'t requested any withdrawals yet.</p></div>'
        },
        drawCallback: function() {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded gap-1');
            $('tbody tr').addClass('animate__animated animate__fadeIn');
        },
        responsive: true
    });

    // Event Watch Filter Mappings
    $('#statusFilter, #dateFilter').change(function() {
        table.ajax.reload();
    });

    // Refresh Action Trigger
    $('#refreshBtn').click(function() {
        const icon = $(this).find('i');
        icon.addClass('fa-spin');
        table.ajax.reload(null, false);
        
        setTimeout(() => {
            icon.removeClass('fa-spin');
        }, 1000);
        
        // Dynamic Toast feedback
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Data refreshed successfully',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        });
    });
});
</script>
@endsection