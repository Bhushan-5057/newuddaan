@extends('admin.layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">


    <div class="container-fluid py-4">

        {{-- SUMMARY CARDS --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4>${{ number_format($totalOrderAmount, 2) }}</h4>
                        <span>Total Order Amount</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4>{{ $totalOrderCount }}</h4>
                        <span>Total Orders</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 id="filteredAmount">$0.00</h4>
                        <span>Filtered Amount</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 id="filteredCount">0</h4>
                        <span>Filtered Orders</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Username</label>
                <input type="text" id="filter_username" class="form-control" placeholder="Search username">
            </div>

            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" id="from_date" class="form-control datepicker" placeholder="YYYY-MM-DD">
            </div>

            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" id="to_date" class="form-control datepicker" placeholder="YYYY-MM-DD">
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button id="filter" class="btn btn-primary">Filter</button>
                <button id="reset" class="btn btn-secondary">Reset</button>
            </div>
        </div>


        {{-- TABLE --}}
        <div class="card p-3">
            <table id="topupHistoryTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>User</th>
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

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>


    <script>
        function getExportFileName() {
            let username = $('#filter_username').val() || 'all';
            let fromDate = $('#from_date').val() || 'all';
            let toDate = $('#to_date').val() || 'dates';

            let now = new Date();
            let time =
                String(now.getHours()).padStart(2, '0') +
                String(now.getMinutes()).padStart(2, '0') +
                String(now.getSeconds()).padStart(2, '0');

            return `Topup_History_${username}_${fromDate}_to_${toDate}_${time}`;
        }

        $(function() {

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            let table = $('#topupHistoryTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,

                lengthMenu: [10, 25, 50, 100],

                scrollY: '60vh',
                scrollCollapse: true,
                scrollX: true,

                fixedHeader: true,

                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"f>>' +
                    '<"row"<"col-12"B>>' +
                    '<"row"<"col-12"tr>>' +
                    '<"row mt-2"<"col-md-5"i><"col-md-7"p>>',

                buttons: [{
                        extend: 'csv',
                        text: 'Export CSV',
                        filename: function() {
                            return getExportFileName();
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Export Excel',
                        filename: function() {
                            return getExportFileName();
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Export PDF',
                        filename: function() {
                            return getExportFileName();
                        },
                        title: 'Topup History Report'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: function() {
                            return getExportFileName().replaceAll('_', ' ');
                        }
                    }
                ],


                ajax: {
                    url: "{{ route('admin.topup.history.data') }}",
                    data: function(d) {
                        d.username = $('#filter_username').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                    {
                        data: 'user_info'
                    },
                    {
                        data: 'tx_id'
                    },
                    {
                        data: 'order_amount'
                    },
                    {
                        data: 'status_label',
                        orderable: false
                    },
                    {
                        data: 'added_on'
                    }
                ],

                drawCallback: function(settings) {
                    if (settings.json) {
                        $('#filteredAmount').text('$' + settings.json.filteredOrderAmount);
                        $('#filteredCount').text(settings.json.filteredOrderCount);
                    }
                }
            });

            $('#filter').click(function() {
                table.draw();
            });

            $('#reset').click(function() {
                $('#filter_username').val('');
                $('#from_date').val('');
                $('#to_date').val('');
                table.draw();
            });


        });
    </script>
@endsection
