@extends('user.layouts.app')



@section('content')

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
.table-danger {
    background-color: #ffe5e5 !important;
}
</style>

<div class="page-breadcrumb">

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card p-3">

            <h4 class="mb-3">{{ $incomeType->name }} History</h4>
            
            <div class="row mb-3">

    <div class="col-md-4">
        <div class="card text-center bg-success text-white p-2">
            <h5>Total Income</h5>
            <h4>{{ number_format($totalIncome,2) }}</h4>
        </div>
    </div>

    <!--<div class="col-md-4">-->
    <!--    <div class="card text-center bg-warning text-dark p-2">-->
    <!--        <h5>Total Hold</h5>-->
    <!--        <h4>{{ number_format($totalHold,2) }}</h4>-->
    <!--    </div>-->
    <!--</div>-->

    <!--<div class="col-md-4">-->
    <!--    <div class="card text-center bg-danger text-white p-2">-->
    <!--        <h5>Total Flush</h5>-->
    <!--        <h4>{{ number_format($totalFlush,2) }}</h4>-->
    <!--    </div>-->
    <!--</div>-->

</div>



            {{-- Filters --}}

            <div class="row mb-3">

                @if ($incomeType->slug === 'level' )

                    <div class="col-md-3">

                        <label class="form-label">Filter by Level</label>

                        <select id="levelFilter" class="form-select">

                            <option value="">All Levels</option>

                            @for ($i = 1; $i <= 3; $i++)

                                <option value="{{ $i }}">Level {{ $i }}</option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">Filter by From User</label>

                        <input type="text" id="txUserFilter" class="form-control" placeholder="Enter User Code or Name">

                    </div>


                @elseif ($incomeType->slug === 'direct')

                    <div class="col-md-3">

                        <label class="form-label">Filter by From Username</label>

                        <input type="text" id="directUserFilter" class="form-control" placeholder="Enter Username">

                    </div>

                @endif


<div class="col-md-3">
    <label class="form-label">Start Date</label>
    <input type="date" id="startDate" class="form-control">
</div>

<div class="col-md-3">
    <label class="form-label">End Date</label>
    <input type="date" id="endDate" class="form-control">
</div>


<!--                    <div class="col-md-3">-->
<!--    <label class="form-label">Filter by Status</label>-->
<!--    <select id="statusFilter" class="form-select">-->
<!--        <option value="">All</option>-->
<!--        <option value="0">Hold</option>-->
<!--        <option value="1">Released</option>-->
<!--        <option value="4">Flush</option>-->
<!--    </select>-->
<!--</div>-->

<div class="col-md-2 d-flex align-items-end mt-3">
    <button id="resetFilters" class="btn btn-danger w-100">
        Reset
    </button>
</div>

            </div>



            <div class="card-datatable table-responsive p-2">

                <table class="table" id="incomeTable">

                    <thead>

                        <tr>

                            @if ($incomeType->slug === 'level')

                                <th>Level</th>

                                <th>From User</th>

                            @elseif ($incomeType->slug === 'direct')

                                <th>From User</th>

                            @endif

                            <th>Amount</th>
                            <th>Status</th>

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

<script>

$(function () {

    const table = $('#incomeTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: {

            url: "{{ route('user.income.history', $incomeType->slug) }}",

            data: function (d) {

                @if ($incomeType->slug === 'level' || $incomeType->slug === 'direct')

                    d.level = $('#levelFilter').val();

                    d.tx_user = $('#txUserFilter').val();

                @elseif ($incomeType->slug === 'direct')

                    d.direct_user = $('#directUserFilter').val();

                @endif

                 d.status = $('#statusFilter').val();
                   d.start_date = $('#startDate').val();
        d.end_date = $('#endDate').val();

            }

        },

        columns: [

            @if ($incomeType->slug === 'level' || $incomeType->slug === 'direct')

                // { data: 'level', name: 'level' },

                { data: 'tx_user', name: 'tx_user' },

            @elseif ($incomeType->slug === 'direct')

                { data: 'tx_user', name: 'tx_user' },

            @endif

            { data: 'amount', name: 'amount' },
            { data: 'status', name: 'status' },

            { data: 'remark', name: 'remark' },

            { data: 'date', name: 'date' },

        ],

        order: [[{{ ($incomeType->slug === 'level' || $incomeType->slug === 'direct') ? 3 : 2 }}, 'desc']],

    });

    $('#statusFilter, #startDate, #endDate').on('change', function () {
    table.ajax.reload();
});


$('#statusFilter, #startDate, #endDate')
.on('keyup change blur', function () {
    table.ajax.reload();
});


    @if ($incomeType->slug === 'level' )

        $('#levelFilter, #txUserFilter').on('keyup change blur', () => table.ajax.reload());

    @elseif ($incomeType->slug === 'direct')

        $('#directUserFilter').on('keyup blur', () => table.ajax.reload());

    @endif



    $('#resetFilters').click(function () {

    $('#levelFilter').val('');
    $('#txUserFilter').val('');
    $('#statusFilter').val('');
    $('#startDate').val('');
    $('#endDate').val('');

    table.ajax.reload();

});



});



</script>

@endsection

