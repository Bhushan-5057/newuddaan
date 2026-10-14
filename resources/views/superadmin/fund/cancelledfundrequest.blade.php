@extends('superadmin.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">
        <div class="card p-3">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0">Cancelled Fund Request List</h5>
            </div>

            <div class="card-datatable table-responsive p-2">
                <table id="transactionTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>S No.</th>
                            <th>Tx User</th>
                
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Type</th>
                            <th>Utr Number</th>
                            <th>Payment Slip</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery (required by DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
      @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    @endif
    
$(function() {
    var table = $('#transactionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('superadmin.cancelledfundrequest.list') }}",
       columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'tx_user', name: 'tx_user' },
    { data: 'amount', name: 'amount' },
    { data: 'payment_type', name: 'payment_type' },
    { data: 'tx_type', name: 'tx_type' },
    { data: 'utr_number', name: 'utr_number' },
    { data: 'payment_slip', name: 'payment_slip', orderable: false, searchable: false },
    { data: 'status_button', name: 'status_button', orderable: false, searchable: false },
    { data: 'reason', name: 'reason', orderable: false, searchable: false },
    { data: 'date', name: 'date' }
]
    });

});
</script>
@endsection
