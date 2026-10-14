@extends('user.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    table.dataTable>thead>tr>th, table.dataTable>thead>tr>td {
            background: #0f626a;
    }
</style>

<div class="container-fluid py-4  ">
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="nav nav-pills gap-2">
                <a href="{{ route('user.fundrequest.index') }}" class="nav-link py-2 px-3 {{ request()->routeIs('user.fundrequest.index') ? 'active bg-primary ' : '' }}">
                    <i class="fas fa-hand-holding-usd me-2"></i> Add Fund Request
                </a>
                <a href="{{ route('user.fundrequest.history') }}" class="nav-link py-2 px-3 {{ request()->routeIs('user.fundrequest.history') ? 'active bg-primary ' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i> Fund Request History
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4  ">
        <div class="card-header  py-3">
            <h4 class="card-title mb-0 h5 d-flex align-items-center">
                <i class="fas fa-file-invoice-dollar me-3 text-warning"></i> Fund Requests History
            </h4>
        </div>
        
        <div class="card-body  p-4">
            <div class="table-responsive">
                <table id="fundRequestTable" class="table table-striped table-hover align-middle w-100">
                    <thead class="border-bottom">
                        <tr>
                            <th class="small fw-bold text-uppercase text-secondary">S No.</th>
                            <th class="small fw-bold text-uppercase text-secondary">Amount</th>
                            <th class="small fw-bold text-uppercase text-secondary">Crypto Type</th>
                            <th class="small fw-bold text-uppercase text-secondary">Payment Type</th>
                            <th class="small fw-bold text-uppercase text-secondary">UTR Number</th>
                            <th class="small fw-bold text-uppercase text-secondary">Payment Slip</th>
                            <th class="small fw-bold text-uppercase text-secondary">Status</th>
                            <th class="small fw-bold text-uppercase text-secondary">Reason</th>
                            <th class="small fw-bold text-uppercase text-secondary">Date</th>
                        </tr>
                    </thead>
                    <tbody class="small ">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $('#fundRequestTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("user.fundrequest.history.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center fw-bold' },
            { data: 'amount', name: 'amount', className: 'fw-bold' },
            { data: 'cripto_type', name: 'cripto_type' },
            { data: 'payment_type', name: 'payment_type' },
            { data: 'tx_hash', name: 'tx_hash' },
            { data: 'payment_slip', name: 'payment_slip', orderable: false, searchable: false },
            { 
                data: 'status', 
                name: 'status',
                className: 'text-center',
                render: function(data) {
                    let badgeClass = '';
                    switch(data.toLowerCase()) {
                        case 'pending':
                            badgeClass = 'bg-warning text-dark';
                            break;
                        case 'completed':
                            badgeClass = 'bg-success text-white';
                            break;
                        case 'rejected':
                            badgeClass = 'bg-danger text-white';
                            break;
                        case 'processing':
                            badgeClass = 'bg-info text-dark';
                            break;
                        default:
                            badgeClass = 'bg-secondary text-white';
                    }
                    return '<span class="badge py-2 px-3 text-uppercase rounded-pill shadow-sm ' + badgeClass + '">' + data + '</span>';
                }
            },
            { data: 'reason', name: 'reason', className: 'text-muted italic' },
            { data: 'added_on', name: 'added_on' }
        ],
        order: [[8, 'desc']],
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        }
    });
});
</script>
@endsection