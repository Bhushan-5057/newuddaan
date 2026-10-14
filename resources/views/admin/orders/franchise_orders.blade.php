@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<div class="container-fluid py-4">

    {{-- SUMMARY --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4>{{ company_info('currency','$') }} {{ number_format($totalAmount,2) }}</h4>
                    <span>Total Order Amount</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4>{{ $totalCount }}</h4>
                    <span>Total Orders</span>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <label>Franchise Username</label>
            <input type="text" id="filter_username" class="form-control">
        </div>

        <div class="col-md-3">
            <label>Status</label>
            <select id="filter_status" class="form-control">
                <option value="">All</option>
                <option value="0">Pending</option>
                <option value="1">Approved</option>
                <option value="2">Rejected</option>
                <option value="3">Partial</option>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end gap-2">
            <button id="filter" class="btn btn-primary">Filter</button>
            <button id="reset" class="btn btn-secondary">Reset</button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card p-3">
        <table id="franchiseOrdersTable" class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>Franchise</th>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
        </table>
    </div>

</div>
@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function () {

    let table = $('#franchiseOrdersTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,

        ajax: {
            url: "{{ route('admin.franchise.orders.data') }}",
            data: function (d) {
                d.username = $('#filter_username').val();
                d.status   = $('#filter_status').val();
            }
        },

        columns: [
            { data: 'DT_RowIndex', orderable:false },
            { data: 'action', orderable:false, searchable:false },
            { data: 'franchise_info' },
            { data: 'id' },
            { data: 'order_amount' },
            { data: 'status_label', orderable:false },
            { data: 'created_at' },
        ],
    });

    $('#filter').click(() => table.draw());

    $('#reset').click(function () {
        $('#filter_username').val('');
        $('#filter_status').val('');
        table.draw();
    });

});
</script>
@endsection