@extends('admin.layouts.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">

<style>
    .stat-card {
        border-radius: 10px;
        padding: 16px;
        color: #fff;
    }
    .stat-icon {
        font-size: 26px;
        opacity: .9;
    }
    .bg-amount { background: linear-gradient(45deg,#7367f0,#9c8cff); }
    .bg-count { background: linear-gradient(45deg,#28c76f,#48da89); }
    .bg-famount { background: linear-gradient(45deg,#ff9f43,#ffb976); }
    .bg-fcount { background: linear-gradient(45deg,#ea5455,#f08182); }

    table.dataTable thead th {
        white-space: nowrap;
    }
</style>

<div class="container-xxl container-p-y">

    {{-- PAGE TITLE --}}
    <h4 class="mb-3">{{ ucfirst($slug) }} Income</h4>

    {{-- FILTER CARD --}}
    <div class="card mb-4 p-3">
        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <label class="form-label">Username</label>
                <input type="text" id="filter-username" class="form-control" placeholder="Enter Username">
            </div>

            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" id="from_date" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" id="to_date" class="form-control">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary w-100" id="filterBtn">
                    <i class="bx bx-filter"></i> Filter
                </button>
                <button class="btn btn-outline-secondary w-100" id="resetBtn">
                    <i class="bx bx-reset"></i> Reset
                </button>
            </div>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stat-card bg-amount d-flex justify-content-between align-items-center">
                <div>
                    <small>Total Amount</small>
                    <h4 class="mb-0">₹{{ number_format($totalAmount,2) }}</h4>
                </div>
                <i class="bx bx-rupee stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-count d-flex justify-content-between align-items-center">
                <div>
                    <small>Total Count</small>
                    <h4 class="mb-0">{{ $totalCount }}</h4>
                </div>
                <i class="bx bx-list-ul stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-famount d-flex justify-content-between align-items-center">
                <div>
                    <small>Filtered Amount</small>
                    <h4 class="mb-0">₹<span id="filteredAmount">0</span></h4>
                </div>
                <i class="bx bx-filter-alt stat-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-fcount d-flex justify-content-between align-items-center">
                <div>
                    <small>Filtered Count</small>
                    <h4 class="mb-0"><span id="filteredCount">0</span></h4>
                </div>
                <i class="bx bx-data stat-icon"></i>
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card p-3">
        <div class="table-responsive">
            <table id="incomeTable" class="table table-bordered table-striped w-100">
                <thead class="table-light">
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
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
let table;

$(document).ready(function () {

    table = $('#incomeTable').DataTable({
        processing: true,
        serverSide: true,

        pageLength: 25,
        lengthMenu: [10,25,50,100],

        scrollY: '60vh',
        scrollCollapse: true,

        fixedHeader: true,

        dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"f>>' +
             '<"row"<"col-12"B>>' +
             '<"row"<"col-12"tr>>' +
             '<"row mt-2"<"col-md-5"i><"col-md-7"p>>',

        buttons: [
            { extend:'excel', title:'{{ ucfirst($slug) }} Income Report' },
            { extend:'pdf',   title:'{{ ucfirst($slug) }} Income Report' },
            { extend:'print', title:'{{ ucfirst($slug) }} Income Report' }
        ],

        ajax: {
            url: '{{ route('admin.income.history.data',$slug) }}',
            data: function (d) {
                d.username  = $('#filter-username').val();
                d.from_date = $('#from_date').val();
                d.to_date   = $('#to_date').val();
            }
        },

        columns: [
            { data:'username' },
            { data:'name' },
            { data:'amount' },
            { data:'remark' },
            { data:'date' }
        ],

        order: [[4,'desc']],

        drawCallback: function (settings) {
            if(settings.json){
                $('#filteredAmount').text(settings.json.filteredAmount);
                $('#filteredCount').text(settings.json.filteredCount);
            }
        }
    });

    $('#filterBtn').click(() => table.ajax.reload());

    $('#resetBtn').click(() => {
        $('#filter-username,#from_date,#to_date').val('');
        table.search('').draw();
    });

});
</script>
@endsection
