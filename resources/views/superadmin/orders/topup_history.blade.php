@extends('superadmin.layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">



<div class="container-fluid py-4">

    <div class="col-sm-3 col-12 mb-4">

        <div class="card">

            <div class="card-body text-center">

                <h2 class="mb-1">{{company_info('currency', '$') . number_format($totalamount, 2) }}</h2>

                <span class="text-muted">Total Amount</span>

                <div id="referralLineChart"></div>

            </div>

        </div>

    </div>  

    <div class="card p-4 shadow">

        <h5>Topup History</h5>

        <div class="table-responsive">

            <table id="topupHistoryTable" class="table ">

                <thead>

                    <tr>

                        <th>S No.</th>

                        <th>Action</th>

                        <th>Username</th>

                        <th>Order ID</th>

                        <th>Order Amount</th>

                        <th>Status</th>

                        <th>Date</th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection



@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>



<script>

$(document).ready(function () {

    $('#topupHistoryTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: '{{ route('superadmin.topup.history.data') }}',

        columns: [

            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },

            { data: 'action', name: 'action', orderable: false, searchable: false },

            { data: 'user_info', name: 'user_info' },

            { data: 'tx_id', name: 'tx_id' },

            { data: 'order_amount', name: 'order_amount' },

            { data: 'status_label', name: 'status', orderable: false, searchable: false },

            { data: 'added_on', name: 'added_on' }

        ]

    });

});

</script>

@endsection

