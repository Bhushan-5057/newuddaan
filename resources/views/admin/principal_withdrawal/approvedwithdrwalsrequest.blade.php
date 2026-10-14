@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">

@php $currency = company_info('currency','₹'); @endphp

<div class="container-xxl flex-grow-1 container-p-y">

{{-- SUMMARY --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h4 id="totalAmount">{{ $currency }} 0.00</h4>
                <small>Total Amount</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h4 id="totalPaidAmount">{{ $currency }} 0.00</h4>
                <small>Total Paid Amount</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h4 id="totalCount">0</h4>
                <small>Total Entries</small>
            </div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="card p-3 mb-3">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" id="from_date" class="form-control">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" id="to_date" class="form-control">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary w-100" id="filterBtn">Filter</button>
            <button class="btn btn-secondary w-100" id="resetBtn">Reset</button>
        </div>
    </div>
</div>

{{-- TABLE --}}
<div class="card p-2">
<table id="transactionTable" class="table table-striped w-100">
<thead class="table-light">
<tr>
    <th>#</th>
    <th>User</th>
    <th>Action</th>
    <th>Amount</th>
    <th>Tx Charge</th>
    <th>Payable</th>
    <th>Order ID</th>
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
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(function(){

let table = $('#transactionTable').DataTable({
    processing:true,
    serverSide:true,
    pageLength:25,
    lengthMenu:[10,25,50,100],
    scrollY:'60vh',
    scrollCollapse:true,
    scrollX:true,
    fixedHeader:true,

    dom:'<"row mb-2"<"col-md-6"l><"col-md-6 text-end"f>>' +
        '<"row"<"col-12"B>>' +
        '<"row"<"col-12"tr>>' +
        '<"row mt-2"<"col-md-5"i><"col-md-7"p>>',

    buttons:['excel','pdf','print'],

    ajax:{
        url:"{{ route('admin.approvedprincipalwithdrwalsrequest.list') }}",
        data:d=>{
            d.from_date = $('#from_date').val();
            d.to_date   = $('#to_date').val();
        }
    },

    columns:[
        {data:'DT_RowIndex',orderable:false},
        {data:'tx_user'},
        {data:'action',orderable:false},
        {data:'amount'},
        {data:'tx_charge'},
        {data:'payable_amount'},
        {data:'order_id'},
        {data:'status',orderable:false},
        {data:'date'}
    ],

    drawCallback:function(settings){
        if(settings.json){
            let c = settings.json.currency;
            $('#totalAmount').text(c+' '+settings.json.filteredAmount);
            $('#totalPaidAmount').text(c+' '+settings.json.filteredPaidAmount);
            $('#totalCount').text(settings.json.filteredCount);
        }
    }
});

$('#filterBtn').click(()=>table.ajax.reload());
$('#resetBtn').click(()=>{
    $('#from_date,#to_date').val('');
    table.ajax.reload();
});

});
</script>
@endsection
