@extends('superadmin.layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">


        <div class="card p-3">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="card-title mb-0">Fund Convert History</h5>
            </div>

           <div class="card-datatable table-responsive p-2">
                <table class="table" id="fundConvertTable">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Tx User</th>
                            <th>Txn Type</th>
                            <th>Credit/Debit</th>
                            <th>Wallet</th>
                            <th>Amount</th>
                            <th>Remark</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>



@endsection

@section('scripts')



<script>
     
$(function() {
    $('#fundConvertTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("superadmin.fund.convert.history.data") }}',
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'tx_user', name: 'tx_user' },
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