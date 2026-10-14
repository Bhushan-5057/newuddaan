@extends('admin.layouts.app')



@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row g-4 mb-4">

        <div class="card p-3">

            <div class="card-header d-flex justify-content-between align-items-center border-bottom">

                <h5 class="card-title mb-0">Approved Withdrawal Requests List</h5>

            </div>

<div class="mb-2">
    <button class="btn btn-primary btn-sm" onclick="exportApprovedCSV()">
        Export CSV
    </button>
</div>


            <div class="card-datatable table-responsive p-2">

                <table id="transactionTable" class="table table-striped">

                    <thead>

                        <tr>

                            <th>S No.</th>

                            <th>Tx User</th>

                            <th>Action</th>

                            <th>Amount</th>

                            <th>Tx Charge</th>

                            <th>Payable Amount</th>

                             <th>User Wallet Address</th>

                            <th>Status</th>

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

        ajax: "{{ route('admin.approvedwithdrwalsrequest.list') }}",

       columns: [

    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },

    { data: 'tx_user', name: 'tx_user' },

    { data: 'action', name: 'action', orderable: false, searchable: false },

    { data: 'amount', name: 'amount' },

    { data: 'tx_charge', name: 'tx_charge' },

    { data: 'payable_amount', name: 'payable_amount' },

        { data: 'user_wallet_address', name: 'user_wallet_address' },

    { data: 'status', name: 'status', orderable: false, searchable: false },

    { data: 'date', name: 'date' }

]

    });



});

</script>

<script>
function exportApprovedCSV() {
    window.location = "{{ route('admin.approvedwithdrwalsrequest.export') }}";
}
</script>


@endsection

