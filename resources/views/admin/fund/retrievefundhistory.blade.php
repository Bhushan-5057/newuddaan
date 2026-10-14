@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">


            <div class="card p-3">

                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="card-title mb-0">Retrieve Fund Transaction List</h5>

                    <button id="exportCsv" class="btn btn-sm btn-primary">
                        Export CSV
                    </button>
                </div>


                <div class="card-datatable table-responsive p-2">
                    <table id="transactionTable" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Wallet</th>
                                <th>Amount</th>
                                <th>Debit/Credit</th>
                                <th>Date</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@section('scripts')
   <script>
$(function() {

    var table = $('#transactionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.retrievefundtransactions.list') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'username' },
            { data: 'tx_type' },
            { data: 'wallet_type' },
            { data: 'amount' },
            { data: 'debit_credit' },
            { data: 'date' },
            { data: 'remark' }
        ]
    });

    // 🔹 Export CSV Button Click
    $('#exportCsv').on('click', function () {
        window.location.href = "{{ route('admin.retrievefundtransactions.export') }}";
    });

});
</script>

@endsection
