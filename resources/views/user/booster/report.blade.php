@extends('user.layouts.app')
@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<div class="page-breadcrumb" style="margin-left: 260px; padding-top: 80px;">
    <!-- <div class="container-xxl flex-grow-1 container-p-y"> -->
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            Booster History
        </h4>
        <div class="row">
           <div class="col-12">
                <div class="card p-3 shadow">
                   <div class="card-datatable table-responsive p-2">
                        <table id="topupHistoryTable" class="table ">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Position</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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
$(document).ready(function () {
    $('#topupHistoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('user.booster.history.list') }}',
        columns: [
        { data: 'id', name: 'id', orderable: false, searchable: false },
        { data: 'side', name: 'side' },
        { data: 'amount', name: 'amount' },
        { data: 'date', name: 'date' },
        { data: 'status_button', name: 'status_button' }
        ],
        order: [[3, 'desc']]
    });
});
</script>
@endsection