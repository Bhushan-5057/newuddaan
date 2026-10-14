




@extends('admin.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />

<div class="page-breadcrumb">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card p-3">
            <h4>Booster Bonus</h4>

            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="username" class="form-control" placeholder="Search by Username">
                </div>
                <div class="col-md-3">
                    <input type="text" id="date_range" class="form-control" placeholder="Select Date Range">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" id="filterBtn">Filter</button>
                    <button class="btn btn-secondary" id="resetBtn">Reset</button>
                </div>
            </div>

            <div class="card-datatable table-responsive p-2">
                <table class="table" id="fundTransferTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Remark</th>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function () {
    $('#date_range').daterangepicker({
        autoUpdateInput: false,
        locale: { cancelLabel: 'Clear' }
    });

    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    var table = $('#fundTransferTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.direct.history.data') }}',
            data: function(d) {
                d.username = $('#username').val();
                d.date_range = $('#date_range').val();
            }
        },
        columns: [
            { data: 'username', name: 'username' },
            { data: 'name', name: 'name' },
            { data: 'amount', name: 'amount' },
            { data: 'remark', name: 'remark' },
            { data: 'date', name: 'date' }
        ],
        order: [[4, 'desc']]
    });

    $('#filterBtn').click(function () {
        table.ajax.reload();
    });

    $('#resetBtn').click(function () {
        $('#username').val('');
        $('#date_range').val('');
        table.ajax.reload();
    });
});
</script>
@endsection
