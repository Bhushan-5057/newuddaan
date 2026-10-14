@extends('user.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="page-breadcrumb">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card p-3">
            <h4>{{ $incomeType->name }} History</h4> <!-- Dynamic Title -->

            <div class="card-datatable table-responsive p-2">
                <table class="table" id="incomeTable">
                    <thead>
                        <tr>
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
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#incomeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.income.history', $incomeType->slug) }}", // ✅ Dynamic URL
        columns: [
            { data: 'amount', name: 'amount' },
            { data: 'remark', name: 'remark' },
            { data: 'date', name: 'date' },
        ],
        order: [[2, 'desc']], // sort by date column
        error: function(xhr, error, thrown) {
            console.error('DataTables error:', xhr.responseText);
            alert('Error loading data. Check console for details.');
        }
    });
});
</script>
@endsection
