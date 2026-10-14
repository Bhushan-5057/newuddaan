@extends('user.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="page-breadcrumb" style="margin-left: 260px; padding-top: 80px;">

        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light"> Withdrawal/</span> Principal Withdrawal Report
        </h4>
       
            <div class="row">
                <!-- Summary Box -->
                <div class="col-md-4 mb-3">
                    <div class="card p-3 shadow">
                       
                        <p><strong>Total Withdrawal</strong><br>$ {{ number_format($totalWithdrawal, 2) }}</p>
                        <hr>
                        <p><strong>Paid Withdrawal</strong><br>$ {{ number_format($paidWithdrawal, 2) }}</p>
                        <hr>
                        <p><strong>Reject Withdrawal</strong><br>$ {{ number_format($rejectedWithdrawal, 2) }}</p>
                    </div>
                </div>

                <!-- Table -->
                <div class="col-md-8">
                    <div class="card p-3 shadow">
                        <div class="table-responsive">
                            <table id="withdrawalTable" class="table ">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Order Id</th>
                                        <th>Amount</th>
                                        <th>Withdrawal charge</th>
                                        <th>Payable Amount</th>
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
      
   
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(function() {
    $('#withdrawalTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.principal.withdraw.list') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'order_id', name: 'order_id' },
            { data: 'amount', name: 'amount' },
            { data: 'tx_charge', name: 'tx_charge' },
            { data: 'paidAmount', name: 'paidAmount' },
            { data: 'status_button', name: 'status_button' },
            { data: 'reason', name: 'reason' },
            { data: 'date', name: 'date' }
        ]
    });
});
</script>
@endsection
